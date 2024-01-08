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
        Schema::create('arrivals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('client_id');
            $table->unsignedBigInteger('arrival_type_id'); //type
            $table->string('status');
            $table->string('dossier_tegic');
            $table->string('dossier_client')->nullable();
            $table->string('shipping_compagnie');
            $table->unsignedBigInteger('city_id'); //pod
            $table->date('eta');
            $table->date('ata')->nullable();
//            $table->integer('nombre');
            $table->string('lieu_de_chargement');
            $table->string('lieu_de_dechargement');
            $table->string('lieu_de_restitution')->nullable();
            $table->date('date_bae_Previsionnelle')->nullable();
            $table->date('date_magasinage')->nullable();
            $table->date('date_surestaries')->nullable();
            $table->date('date_remise')->nullable(); // "Créé" to “Main levee”.
            $table->date('date_taxation')->nullable(); // main levee to taxation
            $table->string('taxation_agent')->nullable(); // main levee to taxation
            $table->date('date_execution')->nullable(); // taxation to planify
            $table->unsignedBigInteger('driver_id')->nullable(); // taxation to planify
            $table->unsignedBigInteger('vehicle_id')->nullable(); // taxation to planify
            $table->unsignedBigInteger('created_by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('arrivals');
    }
};
