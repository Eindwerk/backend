<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('teams', function (Blueprint $table) {
            $table->renameColumn('logo_url', 'profile_image');
        });
    }

    public function down()
    {
        Schema::table('teams', function (Blueprint $table) {
            $table->renameColumn('profile_image', 'logo_url');
        });
    }
};
