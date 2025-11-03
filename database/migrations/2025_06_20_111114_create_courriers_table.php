<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->string('reference_arrive', 50)->nullable()->index(); // Allow alphanumeric reference numbers
            $table->string('reference_bo',50)->nullable()->index();
            $table->unsignedBigInteger('reference_visa')->nullable()->index();
            $table->unsignedBigInteger('reference_dec')->nullable()->index();
            $table->unsignedBigInteger('reference_depart')->nullable()->index();

            // Infos principales
            $table->enum('type_courrier', ['arrive', 'depart', 'visa', 'decision','interne']);
            $table->text('objet');
            $table->date('date_reception')->nullable();
            $table->date('date_depart')->nullable();
            $table->string('fichier_scan')->nullable();
            $table->date('date_enregistrement')->nullable();// date de creation du courrier
            $table->integer('Nbr_piece')->default(1)->nullable();
            $table->enum('priorite', ['normale', 'urgent', 'confidentiel', 'A reponse obligatoire'])->nullable();

            $table->enum('statut', ['en_attente','en_cours','en_traitement','arriver','cloture', 'archiver'])->default('en_attente')->nullable();

             $table->date('delais')->nullable();

            
            // Clés étrangères
            $table->unsignedBigInteger('id_expediteur')->nullable()->index();
            $table->unsignedBigInteger('id_agent_en_charge')->nullable()->index();
            $table->unsignedBigInteger('entite_id')->nullable()->index();

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
