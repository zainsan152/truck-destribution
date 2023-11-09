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
        Schema::create('vehicle_fleet', function (Blueprint $table) {
            $table->id('id_vehicle');
            $table->string('marque_vehicle')->nullable(); //branch
            $table->string('modele_vehicle')->nullable(); //model
            $table->string('immatriculation')->nullable(); //registration
            $table->string('num_cartegrise')->nullable();
            $table->date('date_acquisition')->nullable();
            $table->unsignedBigInteger('id_truck_category');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicle_fleet');
    }
};
