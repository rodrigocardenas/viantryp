<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Asignar un usuario por defecto a los trips existentes que no tengan user_id
        $defaultUserId = \App\Models\User::first()?->id ?? 1;

        if (Schema::hasColumn('trips', 'user_id')) {
            // La columna ya existe, solo actualizar valores NULL
            DB::table('trips')->whereNull('user_id')->update(['user_id' => $defaultUserId]);

            // Hacer la columna NOT NULL si no lo es
            Schema::table('trips', function (Blueprint $table) {
                $table->unsignedBigInteger('user_id')->nullable(false)->change();
            });
        } else {
            // La columna no existe, crearla
            Schema::table('trips', function (Blueprint $table) {
                $table->unsignedBigInteger('user_id')->after('id');
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            });

            // Asignar el usuario por defecto a todos los registros existentes
            DB::table('trips')->update(['user_id' => $defaultUserId]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trips', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
};
