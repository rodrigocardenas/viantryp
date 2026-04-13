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
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new \App\Notifications\CustomResetPassword($token));
    }

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
        'tutorials_seen',
        'plan',
    ];

    public const PLAN_BASICO = 'básico';
    public const PLAN_ESENCIAL = 'esencial';
    public const PLAN_AVANZADO = 'avanzado';
    public const PLAN_COLABORATIVO = 'colaborativo';
    public const PLAN_CORPORATIVO = 'corporativo';

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
            'tutorials_seen' => 'array',
        ];
    }

    public function trips()
    {
        return $this->hasMany(\App\Models\Trip::class);
    }

    public function getPlanLimits(): array
    {
        return match ($this->plan) {
            self::PLAN_BASICO => [
                'max_trips' => 1,
                'max_attachments' => 10,
                'max_editors' => 0,
            ],
            self::PLAN_ESENCIAL => [
                'max_trips' => 3,
                'max_attachments' => 50,
                'max_editors' => 0,
            ],
            self::PLAN_AVANZADO => [
                'max_trips' => 10,
                'max_attachments' => 1000000, // Unlimited
                'max_editors' => 2,
            ],
            self::PLAN_COLABORATIVO, self::PLAN_CORPORATIVO => [
                'max_trips' => 1000000, // Unlimited
                'max_attachments' => 1000000, // Unlimited
                'max_editors' => 1000000, // Unlimited
            ],
            default => [
                'max_trips' => 1,
                'max_attachments' => 10,
                'max_editors' => 0,
            ],
        };
    }

    public function hasReachedTripLimit(): bool
    {
        $limits = $this->getPlanLimits();
        if ($limits['max_trips'] >= 1000000) return false;

        $count = \App\Models\Trip::where('user_id', $this->id)->count();
        return $count >= $limits['max_trips'];
    }

    public function hasReachedAttachmentLimit($trip): bool
    {
        $limits = $this->getPlanLimits();
        if ($limits['max_attachments'] >= 1000000) return false;

        $tripId = is_numeric($trip) ? $trip : $trip->id;

        $count = \App\Models\TripDocument::where('trip_id', $tripId)
            ->where('type', 'pro_attachment')
            ->count();
        return $count >= $limits['max_attachments'];
    }

    public function hasReachedEditorLimit(): bool
    {
        $limits = $this->getPlanLimits();
        if (($limits['max_editors'] ?? 0) >= 1000000) return false;

        $count = \DB::table('trip_collaborators')
            ->join('trips', 'trip_collaborators.trip_id', '=', 'trips.id')
            ->where('trips.user_id', $this->id)
            ->where('trip_collaborators.role', 'editor')
            ->distinct('trip_collaborators.email')
            ->count();

        return $count >= ($limits['max_editors'] ?? 0);
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
