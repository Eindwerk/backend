<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEmailVerificationTokenToUsers extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Willekeurige token (gehasht) om uit de frontend te verifiëren
            $table->string('email_verification_token')->nullable()->unique();
            // Expiratietijd (bijv. 60 minuten na genereren)
            $table->timestamp('email_verification_token_expires_at')->nullable();
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'email_verification_token',
                'email_verification_token_expires_at',
            ]);
        });
    }
}
