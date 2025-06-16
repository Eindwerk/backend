<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->unsignedBigInteger('stadium_id')->nullable()->after('game_id');

            // Optioneel: voeg foreign key constraint toe als je wilt:
            $table->foreign('stadium_id')->references('id')->on('stadia')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropForeign(['stadium_id']);
            $table->dropColumn('stadium_id');
        });
    }
};
