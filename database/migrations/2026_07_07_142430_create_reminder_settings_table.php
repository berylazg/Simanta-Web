<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('reminder_settings', function (Blueprint $table) {
            $table->id();

            $table->boolean('status')->default(true);

            $table->boolean('h30')->default(false);

            $table->boolean('h14')->default(false);

            $table->boolean('h7')->default(true);

            $table->boolean('h3')->default(true);

            $table->boolean('h1')->default(true);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reminder_settings');
    }
};
