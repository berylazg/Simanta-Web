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
        Schema::create('notifikasis', function (Blueprint $table) {

    $table->id();

    $table->foreignId('tagihan_id')
          ->nullable()
          ->constrained('tagihans')
          ->nullOnDelete();

    $table->enum('tipe',[
        'email',
        'system'
    ]);

    $table->text('pesan');

    $table->boolean('is_read')->default(false);

    $table->timestamps();

});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifikasis');
    }
};
