<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    protected $table = 'persons';
    protected $fillable = [
        'user_id',
        'name',
        'email',
        'phone',
        'type',
    ];

    /**
     * Get the user/agency that owns this person record
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the trips associated with the person
     */
    public function trips()
    {
        return $this->belongsToMany(Trip::class);
    }
}
