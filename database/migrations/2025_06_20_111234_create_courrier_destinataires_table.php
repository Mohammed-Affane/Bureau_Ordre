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
            $table->string('nom')->nullable();
            $table->string('type_source')->nullable();
            $table->string('CIN')->nullable();
            $table->text('adresse')->nullable();
            $table->string('telephone')->nullable();

            $table->enum('type_courrier', ['interne', 'externe']);

            $table->unsignedBigInteger('entite_id')->nullable();


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
        Schema::dropIfExists('courrier_destinataires');
    }
     
};
