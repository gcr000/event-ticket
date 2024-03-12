<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('tenant_id')->after('id')->default('1')->constrained('tenants');
        });

        Schema::table('locations', function (Blueprint $table) {
            $table->foreignId('tenant_id')->after('id')->default('1')->constrained('tenants');
        });

        Schema::table('events', function (Blueprint $table) {
            $table->foreignId('tenant_id')->after('id')->default('1')->constrained('tenants');
        });

        \App\Models\User::find(1)->update([
            'tenant_id' => 1,
            'role_id' => 1,
        ]);

        \App\Models\User::find(2)->update([
            'tenant_id' => 1,
            'role_id' => 2,
        ]);

        \App\Models\User::find(3)->update([
            'tenant_id' => 2,
            'role_id' => 4,
        ]);



        DB::table('locations')->insert([
            'tenant_id' => '1',
            'name' => 'RStore',
            'address' => 'Via Notarbartolo, 26 Palermo',
            'phone_number' => '091 611 4366',
            'email' => 'notarbartolo@example.com',
            'ref_user_name' => 'Giancarlo Uzzo',
            'ref_user_email' => 'giancarlo.uzzo@rstore.it',
            'ref_user_phone_number' => '393 0085352',
            'ref_user_id' => '1',
            'latitude' => '38.13266834817932',
            'longitude' => '13.345886170864107',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        DB::table('locations')->insert([
            'tenant_id' => '1',
            'name' => 'Stadio Renzo Barbera',
            'address' => 'Viale del Fante, 11 Palermo',
            'phone_number' => '091 611 4366',
            'email' => 'mediaworld@example.com',
            'ref_user_name' => 'Samuele Meli',
            'ref_user_email' => 'samuele.meli@rstore.it',
            'ref_user_phone_number' => '4444444444',
            'ref_user_id' => '2',
            'latitude' => '38.15234361548895',
            'longitude' => '13.340846300125124',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        DB::table('events')->insert([
            'tenant_id' => '1',
            'name' => 'Inaugurazione Store',
            'description' => 'Apertura nuovo punto vendita in via Notarbartolo',
            'location_id' => '1',
            'datetime_from' => date('Y-m-d 10:30:00', strtotime('+1 day')),
            'datetime_to' => date('Y-m-d 13:00:00', strtotime('+1 day')),
            'is_single_day' => '1',
            'max_prenotabili' => '100',
            'duration' => '2',
            'user_id' => '1',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        DB::table('events')->insert([
            'tenant_id' => '1',
            'name' => 'Apertura nuovo punto vendita',
            'description' => 'Apertura nuovo punto vendita in viale Regione Siciliana',
            'location_id' => '2',
            'datetime_from' => date('Y-m-d 09:00:00', strtotime('+3 day')),
            'datetime_to' => date('Y-m-d 13:00:00', strtotime('+6 day')),
            'is_single_day' => '0',
            'max_prenotabili' => '10000000',
            'duration' => '4',
            'user_id' => '2',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        DB::table('events')->insert([
            'tenant_id' => '1',
            'name' => 'Ristrutturazione',
            'description' => 'Cantiere per ristrutturazione del punto vendita',
            'location_id' => '2',
            'datetime_from' => date('Y-m-d 09:00:00', strtotime('+5 day')),
            'datetime_to' => date('Y-m-d 13:00:00', strtotime('+5 day')),
            'is_single_day' => '1',
            'max_prenotabili' => '10000000',
            'duration' => '5',
            'user_id' => '1',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        DB::table('events')->insert([
            'tenant_id' => '1',
            'name' => 'Festa di Compleanno',
            'description' => 'Festa di compleanno per i 10 anni di attività',
            'location_id' => '1',
            'datetime_from' => date('Y-m-d 09:00:00', strtotime('+10 day')),
            'datetime_to' => date('Y-m-d 18:00:00', strtotime('+10 day')),
            'is_single_day' => '1',
            'max_prenotabili' => '70',
            'duration' => '8',
            'user_id' => '2',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
