<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    protected $table = 'persons';
    protected $fillable = [
        'name',
        'email',
        'phone',
        'type',
    ];

    /**
     * Get the trips associated with the person
     */
    public function trips()
    {
        return $this->belongsToMany(Trip::class);
    }
}
