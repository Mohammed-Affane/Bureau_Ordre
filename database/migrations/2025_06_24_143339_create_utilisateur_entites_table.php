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
        Schema::create('utilisateur_entites', function (Blueprint $table) {
            $table->id();

              $table->unsignedBigInteger('id_utilisateur')->nullable();
              $table->unsignedBigInteger('id_entite')->nullable();
           
            

         $table->foreign('id_utilisateur')
                ->references('id')->on('users')
                ->onDelete('set null')->onUpdate('cascade');

                $table->foreign('id_entite')
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
        Schema::dropIfExists('utilisateur_entites');
    }
};
