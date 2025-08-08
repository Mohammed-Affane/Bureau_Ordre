<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.->onDelete('cascade')->onUpdate('cascade');
     */
    public function up(): void
    {
        Schema::create('traitements', function (Blueprint $table) {
            $table->id();
            $table->text('action')->nullable();
            $table->date('date_traitement')->nullable();
            $table->enum('statut', ['brouillon', 'valide', 'envoyé_au_SG'])->default('brouillon');
            

            // Clés étrangères
            $table->unsignedBigInteger('id_affectation')->nullable();

             // Déclaration des clés étrangères manuellement (car on utilise unsignedBigInteger)
            $table->foreign('id_affectation')
                ->references('id')->on('affectations')
                ->onDelete('set null')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('traitements');
    }
   
};
