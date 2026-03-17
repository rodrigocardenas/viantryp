<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TripCollaborator extends Model
{
    protected $table = 'trip_collaborators';

    protected $fillable = [
        'trip_id',
        'user_id',
        'email',
        'role',
        'token',
        'accepted_at',
    ];

    protected $casts = [
        'accepted_at' => 'datetime',
    ];

    public function trip()
    {
        return $this->belongsTo(Trip::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
