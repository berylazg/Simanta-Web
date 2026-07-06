<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reminders', function (Blueprint $table) {

            $table->id();

            $table->foreignId('tagihan_id')
                  ->constrained('tagihans')
                  ->cascadeOnDelete();

            $table->dateTime('waktu_kirim')->nullable();

            $table->enum('status_kirim',[
                'pending',
                'terkirim',
                'gagal'
            ])->default('pending');

            $table->string('email_tujuan')->nullable();

            $table->text('pesan')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reminders');
    }
};