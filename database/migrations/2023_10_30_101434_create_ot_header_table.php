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
        Schema::create('ot_header', function (Blueprint $table) {
            $table->id('id_ot_header');
            $table->unsignedBigInteger('id_client');
            $table->string('numero_ot')->nullable();
            $table->unsignedBigInteger('id_type_ot');
            $table->string('reference_ot')->nullable();
			$table->string('reference_company')->nullable();			
            $table->double('volume')->nullable();
            $table->integer('qty')->nullable();
            $table->integer('nbr_delivery_points');
            $table->integer('nbr_expected_days');
			$table->date('requested_execution_date')->nullable();
			$table->date('execution_date')->nullable();
			$table->date('ot_closing_date')->nullable();
			$table->string('pod')->nullable();
			$table->double('estimated_days_count')->nullable();			
            $table->string('comments')->nullable();		
            $table->float('distance')->nullable();
            $table->unsignedBigInteger('id_city');
            $table->string('is_mutual');
            $table->unsignedBigInteger('id_truck_category')->nullable();
            $table->date('date_order')->nullable();
            $table->date('date_execution')->nullable();
            $table->unsignedBigInteger('id_driver')->nullable();
            $table->unsignedBigInteger('id_vehicule')->nullable();
            $table->date('date_delivery')->nullable();
            $table->unsignedBigInteger('ot_status');
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
        Schema::dropIfExists('ot_header');
    }
};
