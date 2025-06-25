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
        Schema::create('courrier_references', function (Blueprint $table) {
            $table->id();

            // Clés étrangères
            $table->unsignedBigInteger('id_courrier_source')->nullable();
            $table->unsignedBigInteger('id_courrier_cible')->nullable();

             // Déclaration des clés étrangères manuellement (car on utilise unsignedBigInteger)
            $table->foreign('id_courrier_source')
                ->references('id')->on('courriers')
                ->onDelete('set null')->onUpdate('cascade');

                $table->foreign('id_courrier_cible')
                ->references('id')->on('courriers')
                ->onDelete('set null')->onUpdate('cascade');




            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courrier_references');
    }
    
};
