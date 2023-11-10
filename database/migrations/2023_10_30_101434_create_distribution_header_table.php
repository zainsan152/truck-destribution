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
        Schema::create('distribution_header', function (Blueprint $table) {
            $table->id('id_distribution_header');
            $table->unsignedBigInteger('id_client');
            $table->string('code_distribution')->nullable();
            $table->unsignedBigInteger('id_type_distribution');
            $table->string('axe_distribution')->nullable();
            $table->double('volume')->nullable();
            $table->integer('qty')->nullable();
            $table->integer('nbr_delivery_points');
            $table->integer('nbr_expected_days');
            $table->string('comments')->nullable();
            $table->float('distance')->nullable();
            $table->unsignedBigInteger('id_city');
            $table->string('is_mutual');
            $table->unsignedBigInteger('id_truck_category');
            $table->string('date_order')->nullable();
            $table->date('date_execution')->nullable();
            $table->unsignedBigInteger('id_driver')->nullable();
            $table->unsignedBigInteger('id_vehicule')->nullable();
            $table->date('date_delivery')->nullable();
            $table->unsignedBigInteger('id_status_distribution');
            $table->unsignedBigInteger('createdby');
            $table->integer('modifiedby');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('distribution_header');
    }
};
