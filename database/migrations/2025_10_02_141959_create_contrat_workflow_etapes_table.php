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
        Schema::create('contrat_workflow_etapes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contrat_id')->constrained()->onDelete('cascade');
            $table->foreignId('workflow_etape_id')->constrained()->onDelete('cascade');
            $table->foreignId('approver_id')->nullable()->constrained('users'); // utilisateur assigné à l’étape
            $table->string('statut')->default('en_attente'); // en_attente, approuvée, refusée
            $table->timestamps();
           
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contrat_workflow_etapes');
    }
};
