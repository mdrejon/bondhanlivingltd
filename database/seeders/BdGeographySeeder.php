<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Seeds Bangladesh's administrative reference data: divisions, districts, and
 * upazilas are sourced verbatim (ids preserved) from the nuhil/bangladesh-geocode
 * dataset (github.com/nuhil/bangladesh-geocode), which itself cites bangladesh.gov.bd
 * and Wikipedia — 8 divisions, 64 districts, 494 upazilas.
 *
 * police_stations has no equivalent verified nationwide open dataset. Rural thanas
 * are approximated 1:1 from upazilas (name mirrors the upazila — true for the large
 * majority of the country). Metropolitan police stations (Dhaka Metropolitan Police,
 * Chattogram/Rajshahi/Khulna/Sylhet/Barishal/Rangpur/Gazipur/Narayanganj Metropolitan
 * Police, none of which map to an upazila) are NOT included here — see
 * docs/hgrm-saas/WORKFLOW-ROADMAP.md Phase 1 for the follow-up needed once an official
 * list is sourced.
 */
class BdGeographySeeder extends Seeder
{
    public function run(): void
    {
        $base = database_path('seeders/data/bd-geocode');

        $this->seedTable('divisions', $base . '/divisions.json', fn (array $row) => [
            'id'      => $row['id'],
            'name'    => $row['name'],
            'name_bn' => $row['name_bn'],
        ]);

        $this->seedTable('districts', $base . '/districts.json', fn (array $row) => [
            'id'          => $row['id'],
            'division_id' => $row['division_id'],
            'name'        => $row['name'],
            'name_bn'     => $row['name_bn'],
        ]);

        $this->seedTable('upazilas', $base . '/upazilas.json', fn (array $row) => [
            'id'          => $row['id'],
            'district_id' => $row['district_id'],
            'name'        => $row['name'],
            'name_bn'     => $row['name_bn'],
        ]);

        $this->seedTable('police_stations', $base . '/police_stations.json', fn (array $row) => [
            'id'          => $row['id'],
            'district_id' => $row['district_id'],
            'upazila_id'  => $row['upazila_id'],
            'name'        => $row['name'],
            'name_bn'     => $row['name_bn'],
        ]);

        $this->command->info('Bangladesh geography reference data seeded (divisions, districts, upazilas, police_stations).');
    }

    private function seedTable(string $table, string $jsonPath, \Closure $mapRow): void
    {
        if (DB::table($table)->exists()) {
            $this->command->info("Skipping {$table} — already seeded.");
            return;
        }

        $rows = json_decode(file_get_contents($jsonPath), true);
        $now  = now();

        $chunks = array_chunk($rows, 200);
        foreach ($chunks as $chunk) {
            $insert = array_map(function (array $row) use ($mapRow, $now) {
                $mapped               = $mapRow($row);
                $mapped['created_at'] = $now;
                $mapped['updated_at'] = $now;
                return $mapped;
            }, $chunk);

            DB::table($table)->insert($insert);
        }
    }
}
