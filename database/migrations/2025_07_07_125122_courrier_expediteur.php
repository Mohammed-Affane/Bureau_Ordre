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
         Schema::create('courrier_expediteur', function (Blueprint $table) {
            $table->id();

            // Clés étrangères
            $table->unsignedBigInteger('courrier_id')->nullable();
            $table->unsignedBigInteger('entite_id')->nullable();

             // Déclaration des clés étrangères manuellement (car on utilise unsignedBigInteger)
            $table->foreign('courrier_id')
                ->references('id')->on('courriers')
                ->onDelete('set null')->onUpdate('cascade');

            $table->foreign('entite_id')
                ->references('id')->on('entites')
                ->onDelete('set null')->onUpdate('cascade');
                

            

           
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courrier_expediteur');
    }
};
