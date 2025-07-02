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
        Schema::create('courriers', function (Blueprint $table) {
            $table->id(); // BIGINT UNSIGNED PRIMARY KEY

            // Références (en integers si c’est bien ce que tu veux)
            $table->unsignedBigInteger('reference_arrive')->unique()->nullable();
            $table->unsignedBigInteger('reference_BO')->unique()->nullable();

            // Infos principales
            $table->enum('type_courrier', ['arrive', 'depart', 'interne']);
            $table->text('objet');
            $table->string('fichier_scan')->nullable();
            $table->date('date_reception')->nullable();
            $table->date('date_enregistrement');
            $table->integer('Nbr_piece')->default(1);
            $table->enum('priorite', ['normale', 'urgent', 'confidentiel', 'A reponse obligatoire'])->nullable();

            // Clés étrangères
            $table->unsignedBigInteger('id_expediteur')->nullable();
            
            $table->unsignedBigInteger('id_agent_en_charge')->nullable();
            $table->unsignedBigInteger('id_entite')->nullable();

            // Déclaration des clés étrangères manuellement (car on utilise unsignedBigInteger)
            $table->foreign('id_expediteur')
                ->references('id')->on('expediteurs')
                ->onDelete('set null')->onUpdate('cascade');

                $table->foreign('id_entite')
                ->references('id')->on('entites')
                ->onDelete('set null')->onUpdate('cascade');

            $table->foreign('id_agent_en_charge')
                ->references('id')->on('users')
                ->onDelete('set null')->onUpdate('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courriers');
    }

    
};
