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
        Schema::create('ot_lines', function (Blueprint $table) {
            $table->id('id_ot_line');
            $table->unsignedBigInteger('id_ot_header');
            $table->string('num_bl');
            $table->string('name_delivery');
            $table->integer('qty_line');
            $table->integer('volume_line');
            $table->integer('line_order');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ot_lines');
    }
};
