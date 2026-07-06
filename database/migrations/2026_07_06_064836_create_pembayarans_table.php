<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pembayarans', function (Blueprint $table) {

            $table->id();

            $table->foreignId('tagihan_id')
                  ->constrained('tagihans')
                  ->cascadeOnDelete();

            $table->foreignId('user_id')
                  ->nullable()
                  ->constrained()
                  ->nullOnDelete();

            $table->date('tanggal_bayar');

            $table->decimal('jumlah_bayar',15,2);

            $table->string('metode_bayar')->nullable();

            $table->string('nomor_referensi')->nullable();

            $table->string('file_bukti')->nullable();

            $table->text('catatan')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pembayarans');
    }
};