<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('booking_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants');
            $table->foreignId('event_id')->constrained();
            $table->foreignId('booking_id')->constrained();
            $table->string('email');
            $table->string('name');
            $table->string('phone_number')->nullable();
            $table->string('booking_code')->nullable();
            $table->index('booking_code');
            $table->boolean('is_confirmed')->default(false);
            $table->dateTime('arrived_at')->nullable();
            $table->foreignId('user_puncher_id')->nullable()->references('id')->on('users');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_details');
    }
};
