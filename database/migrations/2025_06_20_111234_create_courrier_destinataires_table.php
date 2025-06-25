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
        Schema::create('courrier_destinataires', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('type_source');
            $table->text('adresse')->nullable();

            // Clés étrangères
            $table->unsignedBigInteger('id_courrier')->nullable();

             // Déclaration des clés étrangères manuellement (car on utilise unsignedBigInteger)
            $table->foreign('id_courrier')
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
        Schema::dropIfExists('courrier_destinataires');
    }
     
};
