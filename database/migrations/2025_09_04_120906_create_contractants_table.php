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
        Schema::create('contractants', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('societe_id')->nullable();
            $table->foreign('societe_id')->references('id')->on('societes');
            $table->enum('categorie', ['personne physique', 'personne morale']);
            $table->string('nom');
            $table->string('prenom');
            $table->string('raison_sociale');
            $table->string('ice');
            $table->unsignedBigInteger('type_contractant')->nullable();
            $table->foreign('type_contractant')->references('id')->on('types_contractants');
            $table->string('email')->unique();
            $table->string('ville');
            $table->string('adresse');
            $table->string('telephone');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contractants');
    }
};
