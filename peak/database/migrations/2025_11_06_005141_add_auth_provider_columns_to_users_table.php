<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->after('username', function (Blueprint $table) {
                $table->string('auth_provider_id')->nullable();
                $table->string('auth_provider')->nullable();
                $table->string('auth_provider_avatar')->nullable();
                $table->string('token')->nullable();
                $table->string('refresh_token')->nullable();
                $table->timestamp('token_expires_at')->nullable();
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'auth_provider_id',
                'auth_provider',
                'auth_provider_avatar',
                'token',
                'refresh_token',
                'token_expires_at'
            ]);
        });
    }
};
