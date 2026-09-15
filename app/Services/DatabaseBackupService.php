<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use PDO;
use RuntimeException;

class DatabaseBackupService
{
    /**
     * Tables never written to a backup file. The backups table is the log of
     * the backups themselves — including it would make a restore wipe the log.
     */
    private const DUMP_EXCLUDED_TABLES = ['backups'];

    /**
     * Table sections skipped when restoring a dump (covers older dump files
     * that still contain them). Skipping `sessions` keeps everyone logged in
     * when the session driver is set to database.
     */
    private const RESTORE_EXCLUDED_TABLES = ['backups', 'sessions'];

    /**
     * Dump the MySQL database to the given absolute file path.
     *
     * Pure PHP/PDO implementation so it also works on shared hosts where
     * exec() and the mysqldump binary are unavailable.
     */
    public function dump(string $absolutePath): void
    {
        $pdo      = DB::connection()->getPdo();
        $database = DB::connection()->getDatabaseName();

        $fh = fopen($absolutePath, 'wb');
        if ($fh === false) {
            throw new RuntimeException("Cannot open backup file for writing: {$absolutePath}");
        }

        try {
            fwrite($fh, "-- Database backup of `{$database}`\n");
            fwrite($fh, '-- Generated at ' . now()->toDateTimeString() . " by Hotel Beach Way admin\n\n");
            fwrite($fh, "SET NAMES utf8mb4;\n");
            fwrite($fh, "SET FOREIGN_KEY_CHECKS=0;\n\n");

            $tables = $pdo->query("SHOW FULL TABLES WHERE Table_type = 'BASE TABLE'")
                          ->fetchAll(PDO::FETCH_NUM);

            foreach ($tables as $row) {
                $table = $row[0];
                if (in_array($table, self::DUMP_EXCLUDED_TABLES, true)) {
                    continue;
                }

                $create = $pdo->query("SHOW CREATE TABLE `{$table}`")->fetch(PDO::FETCH_NUM)[1];

                fwrite($fh, "--\n-- Table: `{$table}`\n--\n");
                fwrite($fh, "DROP TABLE IF EXISTS `{$table}`;\n");
                fwrite($fh, $create . ";\n\n");

                $this->dumpTableRows($pdo, $table, $fh);
            }

            fwrite($fh, "SET FOREIGN_KEY_CHECKS=1;\n");
        } finally {
            fclose($fh);
        }
    }

    /**
     * Restore the database from a dump file previously produced by dump().
     *
     * The file is split back into the per-table sections dump() wrote, the
     * excluded tables' sections are dropped, and each section is executed
     * separately (keeps individual queries small and errors attributable).
     */
    public function restore(string $absolutePath): void
    {
        $sql = file_get_contents($absolutePath);
        if ($sql === false) {
            throw new RuntimeException("Cannot read backup file: {$absolutePath}");
        }

        // Normalise line endings so section markers always match
        $sql = str_replace("\r\n", "\n", $sql);

        $sections = preg_split('/(?=^--\n-- Table: `)/m', $sql);
        if ($sections === false) {
            throw new RuntimeException('Could not parse the backup file.');
        }

        $pdo = $this->newMultiStatementConnection();

        foreach ($sections as $section) {
            if (trim($section) === '') {
                continue;
            }

            if (preg_match('/^--\n-- Table: `([^`]+)`/', $section, $m)
                && in_array($m[1], self::RESTORE_EXCLUDED_TABLES, true)) {
                continue;
            }

            $this->executeMultiStatement($pdo, $section);
        }
    }

    /**
     * Check that a dump file matches the current database structure.
     *
     * Returns a list of human-readable problems; an empty array means the
     * file is safe to restore. Tables in RESTORE_EXCLUDED_TABLES are ignored
     * on both sides since restore never touches them.
     */
    public function validateStructure(string $sql): array
    {
        $sql = str_replace("\r\n", "\n", $sql);

        if (!str_starts_with($sql, '-- Database backup of `')) {
            return ["This file was not created by this application's backup tool, so it cannot be verified."];
        }

        // Tables + columns as recorded in the dump's CREATE TABLE statements
        $dumpTables = [];
        if (preg_match_all('/^CREATE TABLE `([^`]+)` \((.*?)^\)/ms', $sql, $matches, PREG_SET_ORDER)) {
            foreach ($matches as $m) {
                $cols = [];
                foreach (explode("\n", $m[2]) as $line) {
                    if (preg_match('/^\s+`([^`]+)`/', $line, $cm)) {
                        $cols[] = strtolower($cm[1]);
                    }
                }
                $dumpTables[$m[1]] = $cols;
            }
        }

        if (!$dumpTables) {
            return ['No table definitions could be found in the uploaded file.'];
        }

        $skip = self::RESTORE_EXCLUDED_TABLES;

        $currentTables = [];
        foreach (DB::select("SHOW FULL TABLES WHERE Table_type = 'BASE TABLE'") as $row) {
            $vals = array_values((array) $row);
            $currentTables[] = $vals[0];
        }

        $dumpNames    = array_diff(array_keys($dumpTables), $skip);
        $currentNames = array_diff($currentTables, $skip);

        $problems = [];

        foreach (array_diff($dumpNames, $currentNames) as $t) {
            $problems[] = "Table `{$t}` exists in the file but not in the current database.";
        }
        foreach (array_diff($currentNames, $dumpNames) as $t) {
            $problems[] = "Current table `{$t}` is missing from the file.";
        }

        $database = DB::connection()->getDatabaseName();
        foreach (array_intersect($dumpNames, $currentNames) as $t) {
            $currentCols = array_map(
                fn ($r) => strtolower(((array) $r)['COLUMN_NAME'] ?? array_values((array) $r)[0]),
                DB::select(
                    'SELECT COLUMN_NAME FROM information_schema.columns WHERE table_schema = ? AND table_name = ?',
                    [$database, $t]
                )
            );

            $missing = array_diff($currentCols, $dumpTables[$t]);
            $extra   = array_diff($dumpTables[$t], $currentCols);

            if ($missing) {
                $problems[] = "Table `{$t}`: the file is missing column(s) " . implode(', ', $missing) . '.';
            }
            if ($extra) {
                $problems[] = "Table `{$t}`: the file has unknown column(s) " . implode(', ', $extra) . '.';
            }
        }

        return $problems;
    }

    /**
     * Fresh PDO connection that allows multi-statement execution — the shared
     * Laravel connection is created without that option and it cannot be
     * enabled after connecting.
     */
    private function newMultiStatementConnection(): PDO
    {
        $c = config('database.connections.mysql');

        return new PDO(
            "mysql:host={$c['host']};port={$c['port']};dbname={$c['database']};charset=utf8mb4",
            $c['username'],
            $c['password'],
            [
                PDO::ATTR_ERRMODE                => PDO::ERRMODE_EXCEPTION,
                PDO::MYSQL_ATTR_MULTI_STATEMENTS => true,
            ]
        );
    }

    private function executeMultiStatement(PDO $pdo, string $sql): void
    {
        $stmt = $pdo->query($sql);
        // Drain every result set so errors in later statements surface too
        // (ERRMODE_EXCEPTION turns them into PDOExceptions).
        do {
            // nothing to fetch from DDL/INSERT statements
        } while ($stmt->nextRowset());
        $stmt->closeCursor();
    }

    /**
     * Write the table's rows as batched INSERT statements.
     *
     * @param resource $fh
     */
    private function dumpTableRows(PDO $pdo, string $table, $fh): void
    {
        $stmt    = $pdo->query("SELECT * FROM `{$table}`");
        $columns = null;
        $batch   = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            if ($columns === null) {
                $columns = '`' . implode('`, `', array_keys($row)) . '`';
            }

            $values = array_map(function ($value) use ($pdo) {
                if ($value === null) {
                    return 'NULL';
                }
                if (is_int($value) || is_float($value)) {
                    return (string) $value;
                }
                return $pdo->quote((string) $value);
            }, array_values($row));

            $batch[] = '(' . implode(', ', $values) . ')';

            if (count($batch) >= 250) {
                fwrite($fh, "INSERT INTO `{$table}` ({$columns}) VALUES\n" . implode(",\n", $batch) . ";\n");
                $batch = [];
            }
        }

        if ($batch) {
            fwrite($fh, "INSERT INTO `{$table}` ({$columns}) VALUES\n" . implode(",\n", $batch) . ";\n");
        }

        fwrite($fh, "\n");
    }
}
