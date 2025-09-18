<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Trip;

class TripSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $trips = [
            [
                'title' => 'Viaje a París',
                'start_date' => '2024-03-15',
                'end_date' => '2024-03-20',
                'travelers' => 2,
                'destination' => 'París, Francia',
                'status' => Trip::STATUS_APPROVED,
                'summary' => 'Un viaje romántico a la Ciudad de la Luz, visitando los principales monumentos y disfrutando de la gastronomía francesa.',
                'items_data' => [
                    [
                        'type' => 'flight',
                        'day' => 1,
                        'airline' => 'Air France',
                        'flight_number' => 'AF1234',
                        'departure_time' => '08:00',
                        'arrival_time' => '10:30',
                        'departure_airport' => 'Madrid Barajas',
                        'arrival_airport' => 'París Charles de Gaulle',
                        'confirmation_number' => 'AF123456'
                    ],
                    [
                        'type' => 'hotel',
                        'day' => 1,
                        'hotel_name' => 'Hotel Plaza Athénée',
                        'check_in' => '15:00',
                        'check_out' => '12:00',
                        'room_type' => 'Habitación Deluxe',
                        'nights' => 5,
                        'confirmation_number' => 'HOTEL789'
                    ],
                    [
                        'type' => 'activity',
                        'day' => 2,
                        'activity_title' => 'Visita a la Torre Eiffel',
                        'start_time' => '09:00',
                        'end_time' => '12:00',
                        'location' => 'Torre Eiffel, París',
                        'description' => 'Subida a la Torre Eiffel con acceso prioritario'
                    ],
                    [
                        'type' => 'activity',
                        'day' => 2,
                        'activity_title' => 'Paseo por los Campos Elíseos',
                        'start_time' => '14:00',
                        'end_time' => '17:00',
                        'location' => 'Avenida de los Campos Elíseos',
                        'description' => 'Recorrido por la famosa avenida y compras'
                    ],
                    [
                        'type' => 'activity',
                        'day' => 3,
                        'activity_title' => 'Visita al Louvre',
                        'start_time' => '10:00',
                        'end_time' => '16:00',
                        'location' => 'Museo del Louvre',
                        'description' => 'Visita completa al museo más famoso del mundo'
                    ],
                    [
                        'type' => 'transport',
                        'day' => 4,
                        'transport_type' => 'Metro',
                        'pickup_time' => '09:00',
                        'pickup_location' => 'Hotel Plaza Athénée',
                        'destination' => 'Versalles',
                        'duration' => '45 minutos'
                    ],
                    [
                        'type' => 'activity',
                        'day' => 4,
                        'activity_title' => 'Palacio de Versalles',
                        'start_time' => '10:00',
                        'end_time' => '18:00',
                        'location' => 'Palacio de Versalles',
                        'description' => 'Visita completa al palacio y jardines'
                    ],
                    [
                        'type' => 'flight',
                        'day' => 6,
                        'airline' => 'Air France',
                        'flight_number' => 'AF5678',
                        'departure_time' => '14:00',
                        'arrival_time' => '16:30',
                        'departure_airport' => 'París Charles de Gaulle',
                        'arrival_airport' => 'Madrid Barajas',
                        'confirmation_number' => 'AF567890'
                    ]
                ]
            ],
            [
                'title' => 'Escapada a Barcelona',
                'start_date' => '2024-04-10',
                'end_date' => '2024-04-13',
                'travelers' => 1,
                'destination' => 'Barcelona, España',
                'status' => Trip::STATUS_DRAFT,
                'summary' => 'Una escapada de fin de semana para disfrutar de la arquitectura modernista y la gastronomía catalana.',
                'items_data' => [
                    [
                        'type' => 'flight',
                        'day' => 1,
                        'airline' => 'Vueling',
                        'flight_number' => 'VY1234',
                        'departure_time' => '07:30',
                        'arrival_time' => '08:45',
                        'departure_airport' => 'Madrid Barajas',
                        'arrival_airport' => 'Barcelona El Prat',
                        'confirmation_number' => 'VY123456'
                    ],
                    [
                        'type' => 'hotel',
                        'day' => 1,
                        'hotel_name' => 'Hotel Casa Fuster',
                        'check_in' => '15:00',
                        'check_out' => '12:00',
                        'room_type' => 'Habitación Superior',
                        'nights' => 3,
                        'confirmation_number' => 'CASA789'
                    ],
                    [
                        'type' => 'activity',
                        'day' => 2,
                        'activity_title' => 'Visita a la Sagrada Familia',
                        'start_time' => '09:00',
                        'end_time' => '12:00',
                        'location' => 'Sagrada Familia, Barcelona',
                        'description' => 'Visita guiada a la obra maestra de Gaudí'
                    ],
                    [
                        'type' => 'activity',
                        'day' => 2,
                        'activity_title' => 'Paseo por el Barrio Gótico',
                        'start_time' => '14:00',
                        'end_time' => '18:00',
                        'location' => 'Barrio Gótico, Barcelona',
                        'description' => 'Recorrido por el casco histórico de Barcelona'
                    ]
                ]
            ],
            [
                'title' => 'Viaje de Negocios a Londres',
                'start_date' => '2024-05-20',
                'end_date' => '2024-05-22',
                'travelers' => 1,
                'destination' => 'Londres, Reino Unido',
                'status' => Trip::STATUS_SENT,
                'summary' => 'Viaje de negocios para reuniones con clientes y partners en Londres.',
                'items_data' => [
                    [
                        'type' => 'flight',
                        'day' => 1,
                        'airline' => 'British Airways',
                        'flight_number' => 'BA1234',
                        'departure_time' => '06:00',
                        'arrival_time' => '07:30',
                        'departure_airport' => 'Madrid Barajas',
                        'arrival_airport' => 'Londres Heathrow',
                        'confirmation_number' => 'BA123456'
                    ],
                    [
                        'type' => 'hotel',
                        'day' => 1,
                        'hotel_name' => 'The Shard Hotel',
                        'check_in' => '15:00',
                        'check_out' => '12:00',
                        'room_type' => 'Habitación de Negocios',
                        'nights' => 2,
                        'confirmation_number' => 'SHARD789'
                    ],
                    [
                        'type' => 'activity',
                        'day' => 2,
                        'activity_title' => 'Reunión con Cliente',
                        'start_time' => '10:00',
                        'end_time' => '12:00',
                        'location' => 'Canary Wharf, Londres',
                        'description' => 'Reunión de presentación de propuesta comercial'
                    ],
                    [
                        'type' => 'activity',
                        'day' => 2,
                        'activity_title' => 'Almuerzo de Negocios',
                        'start_time' => '13:00',
                        'end_time' => '15:00',
                        'location' => 'Restaurante The Ivy',
                        'description' => 'Almuerzo con el equipo de ventas'
                    ]
                ]
            ]
        ];

        foreach ($trips as $tripData) {
            Trip::create($tripData);
        }
    }
}
