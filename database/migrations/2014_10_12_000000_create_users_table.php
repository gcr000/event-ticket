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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('phone_number')->nullable();
            $table->string('is_disabled')->default('0');
            $table->rememberToken();
            $table->timestamps();

            // add index to email
            $table->index('email');
        });

        DB::table('users')->insert([
            'name' => 'Giancarlo Uzzo',
            'email' => 'giancarlo.uzzo@rstore.it',
            'phone_number' => '3930085352',
            'password' => '$2y$10$Ivlbt6YXnt9/Iwm7vQSvmOoYlm9W.XvtsGEYoMFPzhPxMQThZ4l2W',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        DB::table('users')->insert([
            'name' => 'Samuele Meli',
            'email' => 'samuele.meli@rstore.it',
            'phone_number' => '3514573214',
            'password' => '$2y$10$Ivlbt6YXnt9/Iwm7vQSvmOoYlm9W.XvtsGEYoMFPzhPxMQThZ4l2W',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        DB::table('users')->insert([
            'name' => 'Paolo Purpura',
            'email' => 'paolo.purpura@rstore.it',
            'phone_number' => '3442354154',
            'password' => '$2y$10$Ivlbt6YXnt9/Iwm7vQSvmOoYlm9W.XvtsGEYoMFPzhPxMQThZ4l2W',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
