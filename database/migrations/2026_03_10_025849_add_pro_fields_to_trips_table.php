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
        Schema::table('trips', function (Blueprint $table) {
            $table->boolean('is_pro')->default(false)->after('id');
            $table->json('pro_state')->nullable()->after('items_data');
            $table->string('short_token', 20)->nullable()->unique()->index()->after('share_token');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trips', function (Blueprint $table) {
            $table->dropColumn(['is_pro', 'pro_state', 'short_token']);
        });
    }
};
