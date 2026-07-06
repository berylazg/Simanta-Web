<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tagihans', function (Blueprint $table) {

            $table->id();

            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();

            $table->foreignId('vendor_id')->nullable()->constrained('vendors')->nullOnDelete();

            $table->foreignId('kategori_id')->nullable()->constrained('kategori_tagihans')->nullOnDelete();

            $table->string('nomor_invoice')->nullable();

            $table->string('nama_tagihan');

            $table->string('nomor_kontrak')->nullable();

            $table->decimal('nominal',15,2)->default(0);

            $table->date('tanggal_invoice')->nullable();

            $table->date('tanggal_jatuh_tempo')->nullable();

            $table->date('tanggal_reminder')->nullable();

            $table->enum('status',[
                'draft',
                'upcoming',
                'overdue',
                'paid'
            ])->default('draft');

            $table->text('deskripsi')->nullable();

            $table->string('file_invoice')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tagihans');
    }
};