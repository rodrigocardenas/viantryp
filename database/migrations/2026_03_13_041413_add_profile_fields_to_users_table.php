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
        Schema::table('users', function (Blueprint $table) {
            $table->string('last_name')->nullable();
            $table->string('phone')->nullable();
            $table->string('country')->nullable();
            $table->text('bio')->nullable();
            $table->string('agency_name')->nullable();
            $table->string('agency_website')->nullable();
            $table->string('agency_whatsapp')->nullable();
            $table->string('agency_slogan')->nullable();
            $table->string('agency_logo')->nullable();
            $table->string('theme_color')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'last_name', 'phone', 'country', 'bio',
                'agency_name', 'agency_website', 'agency_whatsapp',
                'agency_slogan', 'agency_logo', 'theme_color'
            ]);
        });
    }
};
