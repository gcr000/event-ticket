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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->string('name');
            $table->string('description');
            $table->dateTime('datetime_from');
            $table->dateTime('datetime_to')->nullable();
            $table->string('duration')->default('1');
            $table->string('image')->nullable();
            $table->boolean('is_single_day')->default(true);
            $table->string('attachment_path')->nullable();
            $table->integer('max_prenotabili')->default(0);
            $table->integer('ticket_for_person')->default(1);
            $table->integer('prenotati')->default(0);
            $table->integer('partecipanti')->default(0);
            $table->boolean('show_referente')->default(true);
            $table->string('is_payment_required')->default('no');
            $table->string('price')->default('0')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // add index to datetime_from and datetime_to
            $table->index(['datetime_from', 'datetime_to']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
