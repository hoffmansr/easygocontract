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
        Schema::table('contrats', function (Blueprint $table) {
            $table->date('date_debut')->nullable()->change();
            $table->date('date_fin')->nullable()->change();
            $table->string('type_renouvelement')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('contrats', function (Blueprint $table) {
            $table->date('date_debut')->nullable(false)->change();
            $table->date('date_fin')->nullable(false)->change();
            $table->string('type_renouvelement')->nullable(false)->change();
        });
    }
};
