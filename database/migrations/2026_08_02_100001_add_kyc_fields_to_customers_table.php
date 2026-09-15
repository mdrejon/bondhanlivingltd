<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            // Personal
            $table->string('father_name')->nullable()->after('name');
            $table->string('mother_name')->nullable()->after('father_name');
            $table->enum('gender', ['male', 'female', 'other'])->nullable()->after('mother_name');
            $table->date('date_of_birth')->nullable()->after('gender');
            $table->string('occupation')->nullable()->after('nationality');
            $table->string('emergency_contact')->nullable()->after('phone');

            // Address — present_address/permanent_address replace the flat `address`
            // column's role going forward; `address` itself is left in place for
            // existing rows rather than migrated/dropped (see docs/hgrm-saas).
            $table->text('present_address')->nullable()->after('address');
            $table->text('permanent_address')->nullable()->after('present_address');
            $table->foreignId('district_id')->nullable()->after('permanent_address')->constrained('districts')->nullOnDelete();
            $table->foreignId('upazila_id')->nullable()->after('district_id')->constrained('upazilas')->nullOnDelete();
            $table->foreignId('police_station_id')->nullable()->after('upazila_id')->constrained('police_stations')->nullOnDelete();
            $table->string('post_code', 20)->nullable()->after('police_station_id');

            // Identity
            $table->string('birth_certificate_number')->nullable()->after('nid_number');
            $table->string('driving_license_number')->nullable()->after('passport_number');

            // Foreign guest
            $table->boolean('is_foreign_guest')->default(false)->after('nationality');
            $table->string('visa_number')->nullable()->after('is_foreign_guest');
            $table->date('arrival_date_bd')->nullable()->after('visa_number');

            // Marriage / companion (MVP scope — see docs/hgrm-saas/DATABASE-SCHEMA.md upgrade path)
            $table->boolean('is_couple')->default(false)->after('arrival_date_bd');
            $table->string('spouse_name')->nullable()->after('is_couple');
            $table->date('marriage_date')->nullable()->after('spouse_name');

            // Police-only "suspicious person" flag (PRD § 5.4) — visibility/editability
            // enforced in the controller via User::canManagePoliceFlag(), not just hidden
            // in the UI.
            $table->boolean('is_flagged')->default(false)->after('marriage_date');
            $table->text('flagged_note')->nullable()->after('is_flagged');
        });
    }

    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropConstrainedForeignId('district_id');
            $table->dropConstrainedForeignId('upazila_id');
            $table->dropConstrainedForeignId('police_station_id');

            $table->dropColumn([
                'father_name', 'mother_name', 'gender', 'date_of_birth', 'occupation',
                'emergency_contact', 'present_address', 'permanent_address', 'post_code',
                'birth_certificate_number', 'driving_license_number',
                'is_foreign_guest', 'visa_number', 'arrival_date_bd',
                'is_couple', 'spouse_name', 'marriage_date',
                'is_flagged', 'flagged_note',
            ]);
        });
    }
};
