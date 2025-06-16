<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdatePostsTableAddFields extends Migration
{
    public function up()
    {
        Schema::table('posts', function (Blueprint $table) {
            if (!Schema::hasColumn('posts', 'stadium_id')) {
                $table->unsignedInteger('stadium_id')->after('game_id');
                $table->foreign('stadium_id')->references('id')->on('stadia');
            }

            if (!Schema::hasColumn('posts', 'image_path')) {
                $table->string('image_path')->nullable()->after('content');
            }
        });
    }


    public function down()
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropForeign(['stadium_id']);
            $table->dropColumn('stadium_id');
            $table->dropColumn('image_path');

            // Indien toegevoegd, verwijder foreign keys
            // $table->dropForeign(['game_id']);
            // $table->dropForeign(['user_id']);
        });
    }
}
