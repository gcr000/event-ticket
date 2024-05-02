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
        Schema::create('tenants', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('url_name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('paypal_client_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        DB::table('tenants')->insert([
            'id' => 1,
            'name' => 'RStore',
            'url_name' => 'rstore',
            'paypal_client_id' => 'Aa8OWqhWwkDo97GxF0BcLqR_iLxLXOPmsObJprF4Ow3LCerjtst4eP1EFG6UG-c5tMeV4ZmTJtx64Dhm'
        ]);

        DB::table('tenants')->insert([
            'id' => 2,
            'name' => 'C&C',
            'url_name' => 'c&c',
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tenants');
    }
};
