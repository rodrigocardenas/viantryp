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
            $table->string('client_name')->nullable()->after('summary');
            $table->string('client_email')->nullable()->after('client_name');
            $table->unsignedBigInteger('agent_id')->nullable()->after('client_email');
            $table->foreign('agent_id')->references('id')->on('persons')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trips', function (Blueprint $table) {
            $table->dropForeign(['agent_id']);
            $table->dropColumn(['client_name', 'client_email', 'agent_id']);
        });
    }
};
