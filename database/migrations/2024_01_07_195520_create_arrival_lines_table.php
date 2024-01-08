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
        Schema::create('arrival_lines', function (Blueprint $table) {
            $table->id();
            $table->string('numero');
            $table->unsignedBigInteger('arrival_id');
            $table->unsignedBigInteger('arrival_line_type_id');
            $table->string('nb_de_pieces');
            $table->string('poids')->nullable();
            $table->string('volume')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('arrival_lines');
    }
};
