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
        Schema::create('modeles_contrats', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('societe_id')->nullable();
            $table->foreign('societe_id')->references('id')->on('societes');
            $table->string('langue');
            $table->string('titre');
            $table->text('description');
            $table->unsignedBigInteger('type_contrat')->nullable();
            $table->foreign('type_contrat')->references('id')->on('types_contrats');
            $table->string('fichier_modele');
            $table->boolean('actif');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('modeles_contrats');
    }
};
