<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;

class Trip extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'code',
        'title',
        'start_date',
        'end_date',
        'travelers',
        'destination',
        'status',
        'summary',
        'price',
        'items_data',
        'days_dates',
        'cover_image_url',
        'share_token',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'items_data' => 'array',
        'days_dates' => 'array',
    ];

    /**
     * Get the user that owns the trip
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the persons associated with the trip
     */
    public function persons()
    {
        return $this->belongsToMany(Person::class);
    }

    // Status constants
    const STATUS_DRAFT = 'draft';
    const STATUS_SENT = 'sent';
    const STATUS_APPROVED = 'approved';
    const STATUS_COMPLETED = 'completed';

    /**
     * Get the status emoji for display
     */
    public function getStatusEmoji(): string
    {
        $emojiMap = [
            self::STATUS_DRAFT => '✏️',
            self::STATUS_SENT => '📤',
            self::STATUS_APPROVED => '✅',
            self::STATUS_COMPLETED => '🏁'
        ];

        return $emojiMap[$this->status] ?? '📋';
    }

    /**
     * Get formatted dates for display
     */
    public function getFormattedDates(): string
    {
        if (!$this->start_date || !$this->end_date) {
            return 'Sin fecha';
        }

        $start = $this->start_date->format('d/m/Y');
        $end = $this->end_date->format('d/m/Y');

        return "{$start} - {$end}";
    }

    /**
     * Get trip duration in days
     */
    public function getDuration(): string
    {
        if (!$this->start_date || !$this->end_date) {
            return 'Sin duración';
        }

        $days = $this->start_date->diffInDays($this->end_date) + 1;
        return $days === 1 ? '1 día' : "{$days} días";
    }

    /**
     * Get trip items grouped by day
     */
    public function getDaysAttribute()
    {
        if (!$this->items_data) {
            return collect();
        }

        $itemsByDay = [];

        foreach ($this->items_data as $item) {
            $day = $item['day'] ?? 1;
            if (!isset($itemsByDay[$day])) {
                $itemsByDay[$day] = [];
            }
            $itemsByDay[$day][] = new TripItem($item);
        }

        // Convert to Day objects
        $days = [];
        foreach ($itemsByDay as $dayNumber => $items) {
            $dayDate = null;
            if ($this->days_dates && isset($this->days_dates[$dayNumber])) {
                $dayDate = \Carbon\Carbon::parse($this->days_dates[$dayNumber]);
            } elseif ($this->start_date) {
                // Fallback to calculated date if no manual date set
                $dayDate = $this->start_date->copy()->addDays($dayNumber - 1);
            }

            $days[] = new TripDay($dayNumber, $dayDate, $items);
        }

        return collect($days)->sortBy('day');
    }

    /**
     * Get all trip items
     */
    public function getItemsAttribute()
    {
        if (!$this->items_data) {
            return collect();
        }

        return collect($this->items_data)->map(function ($item) {
            return new TripItem($item);
        });
    }

    /**
     * Scope for filtering by status
     */
    public function scopeByStatus($query, $status)
    {
        if ($status === 'all') {
            return $query;
        }

        return $query->where('status', $status);
    }

    /**
     * Scope for searching by title
     */
     public function scopeSearch($query, $search)
     {
         if (empty($search)) {
             return $query;
         }

         return $query->where('title', 'like', "%{$search}%");
     }

     /**
      * Generate a unique share token for the trip
      */
     public function generateShareToken(): string
     {
         do {
             $token = hash('sha256', $this->id . time() . rand());
         } while (self::where('share_token', $token)->exists());

         $this->share_token = $token;
         $this->save();

         return $token;
     }

     /**
      * Get the share URL for the trip
      */
     public function getShareUrl(): string
     {
         if (!$this->share_token) {
             $this->generateShareToken();
         }

         return route('trips.share', ['token' => $this->share_token]);
     }

     /**
      * Generate a unique random code for the trip
      */
     public function generateCode(): string
     {
         $maxAttempts = 10; // Prevent infinite loops
         $attempts = 0;

         do {
             $code = strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 8));
             $attempts++;

             try {
                 // Attempt to save the code - database will enforce uniqueness
                 $this->code = $code;
                 $this->save();

                 // If we get here, the save was successful
                 return $code;
             } catch (QueryException $e) {
                 // Check if it's a unique constraint violation
                 $errorCode = $e->getCode();
                 $errorMessage = $e->getMessage();

                 if ($errorCode == 23000 ||
                     str_contains($errorMessage, 'Duplicate entry') ||
                     str_contains($errorMessage, 'UNIQUE constraint') ||
                     str_contains($errorMessage, '1062')) { // MySQL duplicate entry error code
                     // Code collision occurred due to race condition, retry with a new code
                     continue;
                 }
                 // If it's a different database error, rethrow it
                 throw $e;
             }
         } while ($attempts < $maxAttempts);

         // If we've exhausted all attempts, throw an exception
         throw new \RuntimeException('Failed to generate a unique code after ' . $maxAttempts . ' attempts.');
     }

     /**
      * Find trip by share token
      */
     public static function findByShareToken(string $token): ?self
     {
         return self::where('share_token', $token)->first();
     }

     /**
      * Get the documents for the trip
      */
     public function documents()
     {
         return $this->hasMany(TripDocument::class);
     }

     /**
      * Get documents by type
      */
     public function getDocumentsByType(string $type)
     {
         return $this->documents()->where('type', $type)->get();
     }
}

/**
 * Trip Day class for organizing items by day
 */
class TripDay
{
    public $day;
    public $date;
    public $items;

    public function __construct($day, $date, $items)
    {
        $this->day = $day;
        $this->date = $date;
        $this->items = collect($items);
    }

    public function getFormattedDate(): string
    {
        if (!$this->date) {
            return 'Sin fecha';
        }

        return $this->date->format('D, d M');
    }

    public function getFullDate(): string
    {
        if (!$this->date) {
            return 'Sin fecha';
        }

        return $this->date->format('l, d \d\e F \d\e Y');
    }

    public function getDateInputValue(): string
    {
        if (!$this->date) {
            return '';
        }

        return $this->date->format('Y-m-d');
    }
}

/**
 * Trip Item class for individual trip elements
 */
class TripItem
{
    public $type;
    public $data;

    public function __construct($data)
    {
        $this->type = $data['type'] ?? 'note';
        $this->data = $data;
    }

    public function getIconClass(): string
    {
        $iconMap = [
            'flight' => 'icon-flight',
            'hotel' => 'icon-hotel',
            'activity' => 'icon-activity',
            'transport' => 'icon-transport',
            'note' => 'icon-note'
        ];

        return $iconMap[$this->type] ?? 'icon-note';
    }

    public function getIcon(): string
    {
        $iconMap = [
            'flight' => 'fas fa-plane',
            'hotel' => 'fas fa-bed',
            'activity' => 'fas fa-map-marker-alt',
            'transport' => 'fas fa-car',
            'note' => 'fas fa-sticky-note'
        ];

        return $iconMap[$this->type] ?? 'fas fa-sticky-note';
    }

    public function getTypeLabel(): string
    {
        $labelMap = [
            'flight' => 'Vuelo',
            'hotel' => 'Hotel',
            'activity' => 'Actividad',
            'transport' => 'Transporte',
            'note' => 'Nota'
        ];

        return $labelMap[$this->type] ?? 'Elemento';
    }

    public function getTitle(): string
    {
        switch ($this->type) {
            case 'flight':
                $airline = $this->data['airline'] ?? '';
                $flightNumber = $this->data['flight_number'] ?? '';
                return trim("{$airline} {$flightNumber}");
            case 'hotel':
                return $this->data['hotel_name'] ?? 'Hotel';
            case 'activity':
                return $this->data['activity_title'] ?? 'Actividad';
            case 'transport':
                return $this->data['transport_type'] ?? 'Transporte';
            case 'note':
                return $this->data['note_title'] ?? 'Nota';
            default:
                return 'Elemento';
        }
    }

    public function getSubtitle(): string
    {
        switch ($this->type) {
            case 'flight':
                $departure = $this->data['departure_airport'] ?? '';
                $arrival = $this->data['arrival_airport'] ?? '';
                return trim("{$departure} → {$arrival}");
            case 'hotel':
                $checkin = $this->data['check_in'] ?? '';
                $checkout = $this->data['check_out'] ?? '';
                return trim("{$checkin} - {$checkout}");
            case 'activity':
                return $this->data['location'] ?? '';
            case 'transport':
                $pickup = $this->data['pickup_location'] ?? '';
                $destination = $this->data['destination'] ?? '';
                return trim("{$pickup} → {$destination}");
            default:
                return '';
        }
    }

    public function getDetailsHtml(): string
    {
        switch ($this->type) {
            case 'flight':
                return $this->getFlightDetailsHtml();
            case 'hotel':
                return $this->getHotelDetailsHtml();
            case 'activity':
                return $this->getActivityDetailsHtml();
            case 'transport':
                return $this->getTransportDetailsHtml();
            case 'note':
                return $this->getNoteDetailsHtml();
            default:
                return '<p>Sin detalles disponibles</p>';
        }
    }

    private function getFlightDetailsHtml(): string
    {
        $html = '<div class="flight-details">';

        // Flight route
        $html .= '<div class="flight-route">';
        $html .= '<div class="flight-segment">';
        $html .= '<div class="flight-time">' . ($this->data['departure_time'] ?? '') . '</div>';
        $html .= '<div class="flight-airport">' . ($this->data['departure_airport'] ?? '') . '</div>';
        $html .= '</div>';
        $html .= '<div class="flight-path">';
        $html .= '<div class="flight-line"></div>';
        $html .= '<div class="flight-plane">✈️</div>';
        $html .= '<div class="flight-destination">📍</div>';
        $html .= '</div>';
        $html .= '<div class="flight-segment">';
        $html .= '<div class="flight-time">' . ($this->data['arrival_time'] ?? '') . '</div>';
        $html .= '<div class="flight-airport">' . ($this->data['arrival_airport'] ?? '') . '</div>';
        $html .= '</div>';
        $html .= '</div>';

        // Reservation details
        $html .= '<div class="flight-sections">';
        $html .= '<div class="flight-section">';
        $html .= '<h4 class="section-title">Detalles de la reserva</h4>';
        $html .= '<div class="reservation-details">';

        if (!empty($this->data['confirmation_number'])) {
            $html .= '<div class="reservation-item">';
            $html .= '<span class="reservation-label">Confirmación #:</span>';
            $html .= '<span class="reservation-value">' . $this->data['confirmation_number'] . '</span>';
            $html .= '</div>';
        }

        $html .= '<div class="reservation-item">';
        $html .= '<span class="reservation-label">Aerolínea:</span>';
        $html .= '<span class="reservation-value">';
        $html .= ($this->data['airline'] ?? '') .
                 (!empty($this->data['flight_number']) ? ' · ' . $this->data['flight_number'] : '');
        $html .= '</span>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';

        if (!empty($this->data['notes'])) {
            $html .= '<div class="flight-section">';
            $html .= '<h4 class="section-title">Detalles adicionales</h4>';
            $html .= '<div class="additional-details">';
            $html .= '<div class="document-link">';
            $html .= '<i class="fas fa-info-circle"></i>';
            $html .= '<span>' . $this->data['notes'] . '</span>';
            $html .= '</div>';
            $html .= '</div>';
            $html .= '</div>';
        }

        $html .= '</div>';
        $html .= '</div>';

        return $html;
    }

    private function getHotelDetailsHtml(): string
    {
        $html = '<div class="item-details">';

        // Hotel images gallery
        if (!empty($this->data['hotel_data']) && !empty($this->data['hotel_data']['images'])) {
            $images = $this->data['hotel_data']['images'];
            if (count($images) > 0) {
                $html .= '<div class="hotel-gallery">';
                $html .= '<div class="gallery-title">Galería de Fotos</div>';
                $html .= '<div class="gallery-grid">';

                // Show up to 6 images
                $displayImages = array_slice($images, 0, 6);
                foreach ($displayImages as $index => $imageUrl) {
                    $html .= '<div class="gallery-item" onclick="openHotelGallery(' . $this->data['hotel_id'] . ', ' . $index . ')">';
                    $html .= '<img src="' . htmlspecialchars($imageUrl) . '" alt="Hotel image" loading="lazy">';
                    $html .= '</div>';
                }

                if (count($images) > 6) {
                    $remaining = count($images) - 6;
                    $html .= '<div class="gallery-item more-images" onclick="openHotelGallery(' . $this->data['hotel_id'] . ', 0)">';
                    $html .= '<div class="more-overlay">';
                    $html .= '<span>+' . $remaining . ' más</span>';
                    $html .= '</div>';
                    $html .= '<img src="' . htmlspecialchars($images[5]) . '" alt="Hotel image" loading="lazy">';
                    $html .= '</div>';
                }

                $html .= '</div>';
                $html .= '</div>';
            }
        }

        // Hotel rating and reviews
        if (!empty($this->data['hotel_data'])) {
            $hotelData = $this->data['hotel_data'];
            $html .= '<div class="hotel-rating-section">';

            if (!empty($hotelData['rating'])) {
                $html .= '<div class="detail-row">';
                $html .= '<div class="detail-icon-small"><i class="fas fa-star"></i></div>';
                $html .= '<div class="detail-text-small">';
                $html .= '<div class="detail-label-small">Calificación</div>';
                $html .= '<div class="detail-value-small">';
                $html .= '<span class="rating-score">' . number_format($hotelData['rating'], 1) . '</span>';
                $html .= '<span class="rating-word"> ' . ($hotelData['reviewScoreWord'] ?? '') . '</span>';
                if (!empty($hotelData['reviewCount'])) {
                    $html .= '<span class="review-count"> (' . number_format($hotelData['reviewCount']) . ' reseñas)</span>';
                }
                $html .= '</div>';
                $html .= '</div>';
                $html .= '</div>';
            }

            if (!empty($hotelData['stars'])) {
                $html .= '<div class="detail-row">';
                $html .= '<div class="detail-icon-small"><i class="fas fa-building"></i></div>';
                $html .= '<div class="detail-text-small">';
                $html .= '<div class="detail-label-small">Categoría</div>';
                $html .= '<div class="detail-value-small">';
                for ($i = 0; $i < $hotelData['stars']; $i++) {
                    $html .= '<i class="fas fa-star star-filled"></i>';
                }
                $html .= '</div>';
                $html .= '</div>';
                $html .= '</div>';
            }

            $html .= '</div>';
        }

        if (!empty($this->data['check_in']) || !empty($this->data['check_out'])) {
            $html .= '<div class="detail-row">';
            $html .= '<div class="detail-icon-small"><i class="fas fa-clock"></i></div>';
            $html .= '<div class="detail-text-small">';
            $html .= '<div class="detail-label-small">Check-in/Check-out</div>';
            $html .= '<div class="detail-value-small">' .
                     ($this->data['check_in'] ?? '') . ' · ' .
                     ($this->data['check_out'] ?? '') . '</div>';
            $html .= '</div>';
            $html .= '</div>';
        }

        if (!empty($this->data['nights'])) {
            $html .= '<div class="detail-row">';
            $html .= '<div class="detail-icon-small"><i class="fas fa-moon"></i></div>';
            $html .= '<div class="detail-text-small">';
            $html .= '<div class="detail-label-small">Noches</div>';
            $html .= '<div class="detail-value-small">' . $this->data['nights'] .
                     ($this->data['nights'] == 1 ? ' noche' : ' noches') . '</div>';
            $html .= '</div>';
            $html .= '</div>';
        }

        if (!empty($this->data['room_type'])) {
            $html .= '<div class="detail-row">';
            $html .= '<div class="detail-icon-small"><i class="fas fa-bed"></i></div>';
            $html .= '<div class="detail-text-small">';
            $html .= '<div class="detail-label-small">Tipo de habitación</div>';
            $html .= '<div class="detail-value-small">' . $this->data['room_type'] . '</div>';
            $html .= '</div>';
            $html .= '</div>';
        }

        if (!empty($this->data['confirmation_number'])) {
            $html .= '<div class="detail-row">';
            $html .= '<div class="detail-icon-small"><i class="fas fa-receipt"></i></div>';
            $html .= '<div class="detail-text-small">';
            $html .= '<div class="detail-label-small">Confirmación</div>';
            $html .= '<div class="detail-value-small">' . $this->data['confirmation_number'] . '</div>';
            $html .= '</div>';
            $html .= '</div>';
        }

        $html .= '</div>';
        return $html;
    }

    private function getActivityDetailsHtml(): string
    {
        $html = '<div class="item-details">';

        if (!empty($this->data['start_time']) || !empty($this->data['end_time'])) {
            $html .= '<div class="detail-row">';
            $html .= '<div class="detail-icon-small"><i class="fas fa-clock"></i></div>';
            $html .= '<div class="detail-text-small">';
            $html .= '<div class="detail-label-small">Horario</div>';
            $html .= '<div class="detail-value-small">' .
                     ($this->data['start_time'] ?? '') .
                     (!empty($this->data['end_time']) ? ' - ' . $this->data['end_time'] : '') . '</div>';
            $html .= '</div>';
            $html .= '</div>';
        }

        if (!empty($this->data['location'])) {
            $html .= '<div class="detail-row">';
            $html .= '<div class="detail-icon-small"><i class="fas fa-map-marker-alt"></i></div>';
            $html .= '<div class="detail-text-small">';
            $html .= '<div class="detail-label-small">Ubicación</div>';
            $html .= '<div class="detail-value-small">' . $this->data['location'] . '</div>';
            $html .= '</div>';
            $html .= '</div>';
        }

        if (!empty($this->data['description'])) {
            $html .= '<div class="detail-row">';
            $html .= '<div class="detail-icon-small"><i class="fas fa-info-circle"></i></div>';
            $html .= '<div class="detail-text-small">';
            $html .= '<div class="detail-label-small">Descripción</div>';
            $html .= '<div class="detail-value-small">' . $this->data['description'] . '</div>';
            $html .= '</div>';
            $html .= '</div>';
        }

        $html .= '</div>';
        return $html;
    }

    private function getTransportDetailsHtml(): string
    {
        $html = '<div class="item-details">';

        if (!empty($this->data['pickup_time'])) {
            $html .= '<div class="detail-row">';
            $html .= '<div class="detail-icon-small"><i class="fas fa-clock"></i></div>';
            $html .= '<div class="detail-text-small">';
            $html .= '<div class="detail-label-small">Hora de recogida</div>';
            $html .= '<div class="detail-value-small">' . $this->data['pickup_time'] . '</div>';
            $html .= '</div>';
            $html .= '</div>';
        }

        if (!empty($this->data['pickup_location'])) {
            $html .= '<div class="detail-row">';
            $html .= '<div class="detail-icon-small"><i class="fas fa-map-marker-alt"></i></div>';
            $html .= '<div class="detail-text-small">';
            $html .= '<div class="detail-label-small">Punto de recogida</div>';
            $html .= '<div class="detail-value-small">' . $this->data['pickup_location'] . '</div>';
            $html .= '</div>';
            $html .= '</div>';
        }

        if (!empty($this->data['destination'])) {
            $html .= '<div class="detail-row">';
            $html .= '<div class="detail-icon-small"><i class="fas fa-flag-checkered"></i></div>';
            $html .= '<div class="detail-text-small">';
            $html .= '<div class="detail-label-small">Destino</div>';
            $html .= '<div class="detail-value-small">' . $this->data['destination'] . '</div>';
            $html .= '</div>';
            $html .= '</div>';
        }

        $html .= '</div>';
        return $html;
    }

    private function getNoteDetailsHtml(): string
    {
        $html = '<div class="item-details">';
        $html .= '<div class="detail-row">';
        $html .= '<div class="detail-icon-small"><i class="fas fa-sticky-note"></i></div>';
        $html .= '<div class="detail-text-small">';
        $html .= '<div class="detail-value-small">' .
                 ($this->data['note_content'] ?? $this->data['notes'] ?? 'Sin contenido') . '</div>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';
        return $html;
    }
}
