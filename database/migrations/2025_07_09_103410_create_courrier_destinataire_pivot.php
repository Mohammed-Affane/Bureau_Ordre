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
        Schema::create('courrier_destinataire_pivot', function (Blueprint $table) {
            $table->id();

              $table->unsignedBigInteger('id_courrier')->nullable();
              $table->unsignedBigInteger('id_destinataire_courrier')->nullable();

         $table->foreign('id_courrier')
                ->references('id')->on('courriers')
                ->onDelete('set null')->onUpdate('cascade');

                $table->foreign('id_destinataire_courrier')
                ->references('id')->on('courrier_destinataires')
                ->onDelete('set null')->onUpdate('cascade');
            

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courrier_destinataire_pivot');
    }
};
