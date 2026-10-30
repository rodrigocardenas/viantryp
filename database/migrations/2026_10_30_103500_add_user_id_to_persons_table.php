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
        if (!Schema::hasColumn('persons', 'user_id')) {
            Schema::table('persons', function (Blueprint $table) {
                $table->foreignId('user_id')
                    ->nullable()
                    ->after('id')
                    ->constrained('users')
                    ->nullOnDelete();
            });
        }

        // Replace global unique index on email with composite unique (user_id, email) BEFORE cloning shared records
        try {
            Schema::table('persons', function (Blueprint $table) {
                $table->dropUnique('persons_email_unique');
            });
        } catch (\Throwable $e) {
            // Index may already have been dropped
        }

        try {
            Schema::table('persons', function (Blueprint $table) {
                $table->unique(['user_id', 'email'], 'persons_user_id_email_unique');
            });
        } catch (\Throwable $e) {
            // Composite index may already exist
        }

        // Backfill user_id from person_trip -> trips.user_id and deduplicate shared person records across agencies
        $links = DB::table('person_trip')
            ->join('trips', 'person_trip.trip_id', '=', 'trips.id')
            ->whereNotNull('trips.user_id')
            ->select('person_trip.id as pivot_id', 'person_trip.person_id', 'person_trip.trip_id', 'trips.user_id')
            ->orderBy('person_trip.id')
            ->get();

        $byPerson = $links->groupBy('person_id');

        foreach ($byPerson as $personId => $personLinks) {
            $person = DB::table('persons')->where('id', $personId)->first();
            if (!$person) {
                continue;
            }

            $byOwner = $personLinks->groupBy('user_id');
            $firstOwnerId = $byOwner->keys()->first();

            // Assign original person record to the first owner if not yet assigned
            if ($person->user_id === null) {
                DB::table('persons')
                    ->where('id', $personId)
                    ->update(['user_id' => $firstOwnerId]);
            }

            // If other agencies/users also linked to this same person record, clone a dedicated Person record per agency
            foreach ($byOwner as $ownerId => $ownerPivotRows) {
                if ((int) $ownerId === (int) ($person->user_id ?? $firstOwnerId)) {
                    continue;
                }

                // Check if this owner already has a person row with the same email
                $existingForOwner = null;
                if (!empty($person->email)) {
                    $existingForOwner = DB::table('persons')
                        ->where('user_id', $ownerId)
                        ->where('email', $person->email)
                        ->first();
                }

                if ($existingForOwner) {
                    $newPersonId = $existingForOwner->id;
                } else {
                    $newPersonId = DB::table('persons')->insertGetId([
                        'user_id' => $ownerId,
                        'name' => $person->name,
                        'email' => $person->email,
                        'phone' => $person->phone,
                        'type' => $person->type,
                        'created_at' => $person->created_at ?? now(),
                        'updated_at' => now(),
                    ]);
                }

                $pivotIds = $ownerPivotRows->pluck('pivot_id')->all();
                DB::table('person_trip')
                    ->whereIn('id', $pivotIds)
                    ->update(['person_id' => $newPersonId]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('persons', function (Blueprint $table) {
            try {
                $table->dropUnique('persons_user_id_email_unique');
            } catch (\Throwable $e) {
            }

            if (Schema::hasColumn('persons', 'user_id')) {
                $table->dropConstrainedForeignId('user_id');
            }
        });
    }
};

