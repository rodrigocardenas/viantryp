<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TripDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'trip_id',
        'user_id',
        'type',
        'item_id',
        'original_name',
        'filename',
        'path',
        'mime_type',
        'size'
    ];

    protected $casts = [
        'size' => 'integer',
    ];

    /**
     * Get the trip that owns the document
     */
    public function trip()
    {
        return $this->belongsTo(Trip::class);
    }

    /**
     * Get the user that owns the document
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the full URL for the document
     */
    public function getUrlAttribute(): string
    {
        return asset('storage/documents/' . $this->path);
    }

    /**
     * Get formatted file size
     */
    public function getFormattedSizeAttribute(): string
    {
        $bytes = $this->size;
        $units = ['B', 'KB', 'MB', 'GB'];

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 2) . ' ' . $units[$i];
    }

    /**
     * Get file extension
     */
    public function getExtensionAttribute(): string
    {
        return pathinfo($this->original_name, PATHINFO_EXTENSION);
    }

    /**
     * Check if file is an image
     */
    public function isImage(): bool
    {
        return str_starts_with($this->mime_type, 'image/');
    }

    /**
     * Check if file is a PDF
     */
    public function isPdf(): bool
    {
        return $this->mime_type === 'application/pdf';
    }
}
