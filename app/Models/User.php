<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'google_id',
        'avatar',
        'last_name',
        'phone',
        'country',
        'bio',
        'agency_name',
        'agency_website',
        'agency_whatsapp',
        'agency_slogan',
        'agency_logo',
        'theme_color',
        'display_name_type',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function getDisplayNameAttribute()
    {
        return ($this->display_name_type === 'agency' && !empty($this->agency_name)) 
            ? $this->agency_name 
            : $this->name;
    }

    public function getDisplayInitialsAttribute()
    {
        $name = $this->display_name;
        return collect(explode(' ', $name))
            ->map(function($word) {
                return mb_strtoupper(mb_substr($word, 0, 1));
            })
            ->take(2)
            ->join('');
    }
}
