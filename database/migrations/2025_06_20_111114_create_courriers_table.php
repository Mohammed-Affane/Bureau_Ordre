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
            $table->unsignedBigInteger('reference_arrive')->nullable();
            $table->unsignedBigInteger('reference_bo')->nullable();
            $table->unsignedBigInteger('reference_visa')->nullable();
            $table->unsignedBigInteger('reference_dec')->nullable();
            $table->unsignedBigInteger('reference_depart')->nullable();

            // Infos principales
            $table->enum('type_courrier', ['arrive', 'depart', 'visa', 'decision','interne']);
            $table->text('objet');
            $table->date('date_reception')->nullable();
            $table->date('date_depart')->nullable();
            $table->string('fichier_scan')->nullable();
            $table->date('date_enregistrement')->nullable();
            $table->integer('Nbr_piece')->default(1)->nullable();
            $table->enum('priorite', ['normale', 'urgent', 'confidentiel', 'A reponse obligatoire'])->nullable();

            $table->enum('statut', ['en_attente','en_cours', 'arriver','cloture', 'archiver'])->default('en_attente')->nullable();

            // Clés étrangères
            $table->unsignedBigInteger('id_expediteur')->nullable();
            $table->unsignedBigInteger('id_agent_en_charge')->nullable();
            $table->unsignedBigInteger('entite_id')->nullable();

            // Déclaration des clés étrangères manuellement (car on utilise unsignedBigInteger)
            $table->foreign('id_expediteur')
                ->references('id')->on('expediteurs')
                ->onDelete('set null')->onUpdate('cascade');

                $table->foreign('entite_id')
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
