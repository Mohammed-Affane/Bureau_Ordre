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
        Schema::create('entites', function (Blueprint $table) {
            $table->id();
             $table->string('nom');
            $table->string('type');
            $table->text('code')->nullable();

            // Clés étrangères
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->unsignedBigInteger('responsable_id')->unique()->nullable();
            

         $table->foreign('parent_id')
                ->references('id')->on('entites')
                ->onDelete('set null')->onUpdate('cascade');

                $table->foreign('responsable_id')
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
        Schema::dropIfExists('entites');
    }

    
};
