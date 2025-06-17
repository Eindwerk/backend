<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            // Drop bestaande constraints (veiligheidshalve)
            $table->dropForeign(['user_id']);
            $table->dropForeign(['game_id']);
            $table->dropForeign(['stadium_id']); // deze is nodig vóór dropColumn

            // Drop ongewenste kolom
            $table->dropColumn('stadium_id');

            // Hernoem kolommen
            $table->renameColumn('content', 'comments');
            $table->renameColumn('image_path', 'image');

            // Herstel foreign keys correct en met unieke naamgeving (optioneel)
            $table->foreign('user_id', 'fk_posts_user')
                ->references('id')->on('users')->onDelete('cascade');

            $table->foreign('game_id', 'fk_posts_game')
                ->references('id')->on('games')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            // Rollback alles
            $table->renameColumn('comments', 'content');
            $table->renameColumn('image', 'image_path');

            $table->unsignedBigInteger('stadium_id')->nullable();

            $table->dropForeign(['user_id']);
            $table->dropForeign(['game_id']);
        });
    }
};
