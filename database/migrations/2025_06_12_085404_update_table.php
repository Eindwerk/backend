<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('stadia', function (Blueprint $table) {
            // Verwijder ongewenste kolommen
            $columnsToDrop = ['city', 'image', 'capacity'];
            foreach ($columnsToDrop as $column) {
                if (Schema::hasColumn('stadia', $column)) {
                    $table->dropColumn($column);
                }
            }

            // Voeg ontbrekende/gewenste kolommen toe indien nodig
            if (!Schema::hasColumn('stadia', 'team_id')) {
                $table->foreignId('team_id')->nullable()->constrained()->nullOnDelete();
            }

            if (!Schema::hasColumn('stadia', 'location')) {
                $table->json('location')->nullable();
            }

            if (!Schema::hasColumn('stadia', 'banner_image')) {
                $table->string('banner_image')->nullable();
            }

            if (!Schema::hasColumn('stadia', 'profile_image')) {
                $table->string('profile_image')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('stadia', function (Blueprint $table) {
            $table->dropForeign(['team_id']);
            $table->dropColumn([
                'team_id',
                'location',
                'banner_image',
                'profile_image',
            ]);

            // Eventueel oude kolommen herstellen
            $table->string('city')->nullable();
            $table->string('image')->nullable();
            $table->integer('capacity')->nullable();
        });
    }
};
