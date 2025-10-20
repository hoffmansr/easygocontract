
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1️⃣ Étendre temporairement l’ENUM pour inclure les nouvelles et anciennes valeurs
        DB::statement("ALTER TABLE societes MODIFY statut ENUM('active', 'suspendue', 'actif', 'suspendu') DEFAULT 'active'");

        // 2️⃣ Mettre à jour les anciennes valeurs vers les nouvelles
        DB::table('societes')->where('statut', 'active')->update(['statut' => 'actif']);
        DB::table('societes')->where('statut', 'suspendue')->update(['statut' => 'suspendu']);

        // 3️⃣ Réduire l’ENUM aux nouvelles valeurs seulement
        DB::statement("ALTER TABLE societes MODIFY statut ENUM('actif', 'suspendu') DEFAULT 'actif'");
    }

    public function down(): void
    {
        // Revenir à l’ancien ENUM si besoin
        DB::statement("ALTER TABLE societes MODIFY statut ENUM('active', 'suspendue', 'actif', 'suspendu') DEFAULT 'active'");

        DB::table('societes')->where('statut', 'actif')->update(['statut' => 'active']);
        DB::table('societes')->where('statut', 'suspendu')->update(['statut' => 'suspendue']);

        DB::statement("ALTER TABLE societes MODIFY statut ENUM('active', 'suspendue') DEFAULT 'active'");
    }
};
