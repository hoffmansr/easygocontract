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
        Schema::table('workflow_etapes', function (Blueprint $table) {
             $table->integer('niveau')->after('id');
            //
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('workflow_etapes', function (Blueprint $table) {
                $table->dropColumn('niveau');
            //
        });
    }
};
