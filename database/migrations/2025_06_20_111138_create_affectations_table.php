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

        Schema::create('affectations', function (Blueprint $table) {
            $table->id();
            $table->enum('statut_affectation',['a_cab','a_sg','a_div'])->nullable();
            $table->date('date_affectation')->nullable();
            $table->text('Instruction')->nullable();

             // Clés étrangères
            $table->unsignedBigInteger('id_courrier')->nullable();
            $table->unsignedBigInteger('id_affecte_a_utilisateur')->nullable();
             $table->unsignedBigInteger('id_affecte_par_utilisateur')->nullable();


             // Déclaration des clés étrangères manuellement (car on utilise unsignedBigInteger)
            $table->foreign('id_courrier')
                ->references('id')->on('courriers')
                ->onDelete('cascade')->onUpdate('cascade');

            $table->foreign('id_affecte_a_utilisateur')
                ->references('id')->on('users')
                ->onDelete('cascade')->onUpdate('cascade');

            $table->foreign('id_affecte_par_utilisateur')
                ->references('id')->on('users')
                ->onDelete('cascade')->onUpdate('cascade');

        
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('affectations');
    }

    
};
