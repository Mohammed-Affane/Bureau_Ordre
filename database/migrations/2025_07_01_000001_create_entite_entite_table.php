<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('entite_entite', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('courrier_id');
            $table->unsignedBigInteger('entite_source_id');
            $table->unsignedBigInteger('entite_dest_id');
            $table->timestamps();

            $table->foreign('entite_source_id')
                ->references('id')->on('entites')
                ->onDelete('cascade')->onUpdate('cascade');

            $table->foreign('entite_dest_id')
                ->references('id')->on('entites')
                ->onDelete('cascade')->onUpdate('cascade');

            $table->foreign('courrier_id')
                ->references('id')->on('courriers')
                ->onDelete('cascade')->onUpdate('cascade');

            $table->unique(['courrier_id', 'entite_source_id', 'entite_dest_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('entite_entite');
    }
};