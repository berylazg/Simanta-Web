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
        Schema::create('reminder_logs', function (Blueprint $table) {

            $table->id();

            $table->foreignId('tagihan_id')
                ->constrained('tagihans')
                ->cascadeOnDelete();

            $table->string('vendor');

            $table->string('email');

            $table->integer('hari_reminder');

            $table->enum('status',[
                'Berhasil',
                'Gagal'
            ]);

            $table->timestamp('waktu_kirim');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reminder_logs');
    }
};
