<?php

namespace App\Services;

use App\Models\Trip;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TripAiService
{
    protected ?string $apiKey;
    protected string $model;
    protected string $apiUrl;

    public function __construct()
    {
        $this->apiKey = config('services.gemini.api_key') ?: env('GEMINI_API_KEY');
        $this->model = config('services.gemini.model', 'gemini-3.1-flash-lite');
        $this->apiUrl = "https://generativelanguage.googleapis.com/v1beta/models/{$this->model}:generateContent";
    }

    /**
     * Process a chat conversation with optional attachments for a trip.
     *
     * @param Trip $trip
     * @param string|null $message
     * @param array $uploadedFiles Array of file info: ['path' => ..., 'mime_type' => ..., 'original_name' => ...]
     * @return array
     */
    public function processConversation(Trip $trip, ?string $message, array $uploadedFiles = []): array
    {
        // If no API key is provided, return intelligent fallback for local development
        if (empty($this->apiKey)) {
            return $this->getMockResponse($trip, $message, $uploadedFiles);
        }

        try {
            $systemInstruction = $this->buildSystemInstruction($trip);
            $userParts = $this->buildUserParts($message, $uploadedFiles);

            $payload = [
                'systemInstruction' => [
                    'parts' => [
                        ['text' => $systemInstruction]
                    ]
                ],
                'contents' => [
                    [
                        'role' => 'user',
                        'parts' => $userParts
                    ]
                ],
                'generationConfig' => [
                    'responseMimeType' => 'application/json',
                    'temperature' => 0.2,
                    'maxOutputTokens' => 4096,
                ]
            ];

            $candidateModels = array_unique([
                'gemini-3.1-flash-lite',
                $this->model,
                'gemini-flash-lite-latest',
                'gemini-flash-latest',
            ]);

            $response = null;
            $lastException = null;

            foreach ($candidateModels as $candidateModel) {
                try {
                    $endpoint = "https://generativelanguage.googleapis.com/v1beta/models/{$candidateModel}:generateContent?key={$this->apiKey}";
                    $candidateResponse = Http::timeout(12)
                        ->withOptions([
                            'curl' => [
                                CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4,
                                CURLOPT_SSL_VERIFYPEER => false
                            ]
                        ])
                        ->withHeaders(['Content-Type' => 'application/json'])
                        ->post($endpoint, $payload);

                    if ($candidateResponse->successful()) {
                        $response = $candidateResponse;
                        break;
                    }
                } catch (\Throwable $e) {
                    $lastException = $e;
                    Log::error('Gemini Error:', [$e->getMessage()]);
                }
            }

            if (!$response || !$response->successful()) {
                if ($lastException) {
                    Log::error('Gemini Error:', [$lastException->getMessage()]);
                } else {
                    $errBody = $response ? $response->body() : 'No response received';
                    Log::error('Gemini Error:', [$errBody]);
                }

                return [
                    'success' => false,
                    'response_text' => 'Estoy reiniciando mi sistema. Por favor intenta tu mensaje de nuevo.',
                    'message' => 'Estoy reiniciando mi sistema. Por favor intenta tu mensaje de nuevo.',
                    'suggested_actions' => [],
                    'actions' => [],
                    'suggestions' => ['✈️ Vuelo', '🏨 Hotel', '📍 Actividad', '🍽️ Restaurante']
                ];
            }

            $result = $response->json();
            $rawText = $result['candidates'][0]['content']['parts'][0]['text'] ?? '{}';
            $parsedJson = json_decode($rawText, true);

            if (!is_array($parsedJson)) {
                Log::error('Gemini Error:', ['Invalid JSON received from Gemini: ' . substr($rawText, 0, 200)]);
                return [
                    'success' => false,
                    'response_text' => 'Estoy reiniciando mi sistema. Por favor intenta tu mensaje de nuevo.',
                    'message' => 'Estoy reiniciando mi sistema. Por favor intenta tu mensaje de nuevo.',
                    'suggested_actions' => [],
                    'actions' => [],
                    'suggestions' => []
                ];
            }

            $respMsg = $parsedJson['message'] ?? 'Analicé la información provista.';
            $rawItems = $parsedJson['items'] ?? ($parsedJson['actions'] ?? []);
            $normalizedItems = $this->normalizeActions($rawItems, $trip);

            return [
                'success' => true,
                'response_text' => $respMsg,
                'message' => $respMsg,
                'items' => $normalizedItems,
                'actions' => $normalizedItems,
                'suggested_actions' => $normalizedItems,
                'suggestions' => is_array($parsedJson['suggestions'] ?? null) ? $parsedJson['suggestions'] : ['📄 Cargar Archivo', '✍️ Pegar Texto']
            ];
        } catch (\Throwable $e) {
            Log::error('Gemini Error:', [$e->getMessage()]);

            return [
                'success' => false,
                'response_text' => 'Estoy reiniciando mi sistema. Por favor intenta tu mensaje de nuevo.',
                'message' => 'Estoy reiniciando mi sistema. Por favor intenta tu mensaje de nuevo.',
                'suggested_actions' => [],
                'actions' => [],
                'suggestions' => []
            ];
        }
    }

    /**
     * Build system prompt with trip context, strict ingestion boundaries, and schema rules.
     */
    protected function buildSystemInstruction(Trip $trip): string
    {
        $startDate = $trip->start_date ? $trip->start_date->format('Y-m-d') : 'No definida';
        $endDate = $trip->end_date ? $trip->end_date->format('Y-m-d') : 'No definida';
        $destination = $trip->destination ?: 'Destino por definir';
        $tripTitle = $trip->title ?: 'Mi Viaje';

        // Summary of current trip days and existing items with specific dates
        $daysSummary = [];
        $dayDatesInfo = [];
        if ($trip->pro_state && is_array($trip->pro_state)) {
            $days = $trip->pro_state['days'] ?? [];
            $dayDates = $trip->pro_state['dayDates'] ?? [];
            foreach ($days as $idx => $dayItems) {
                $count = is_array($dayItems) ? count($dayItems) : 0;
                $dateStr = $dayDates[$idx] ?? '';
                $dayLabel = "Día " . ($idx + 1) . ($dateStr ? " ({$dateStr})" : "");
                $daysSummary[] = "{$dayLabel}: {$count} elementos";
                if ($dateStr) {
                    $dayDatesInfo[] = "Día " . ($idx + 1) . " = {$dateStr}";
                }
            }
        }

        $daysContext = !empty($daysSummary) ? implode(', ', $daysSummary) : 'Sin días configurados aún';
        $datesMapping = !empty($dayDatesInfo) ? implode(', ', $dayDatesInfo) : 'Fecha inicial: ' . $startDate;

        return <<<PROMPT
Eres **Tryp AI**, el Asistente de Carga e Ingesta Automática de la plataforma Viantryp.

==================================================
1. RESTRICCIÓN DE ALCANCE ESTRICTA (STRICT INGESTION MODE)
==================================================
- **Propósito Único:** Estás diseñado EXCLUSIVAMENTE para extraer, estructurar y validar datos de reservas y planes desde archivos (PDFs, imágenes) o texto libre, para inyectarlos en el lienzo del viaje.
- **PROHIBICIÓN ABSOLUTA:** TIENES ESTRICTAMENTE PROHIBIDO dar recomendaciones turísticas, sugerir lugares, dar consejos de viaje, clima, visas o chatear de forma abierta.
- **Manejo de Consultas Ajenas o Solicitudes de Sugerencias:** Si el usuario te pide recomendaciones ("¿Qué restaurantes me sugieres?", "¿Qué puedo hacer en el destino?", etc.) o realiza preguntas ajenas a la carga de datos, responde ÚNICAMENTE con esta frase fija:
  *"Mi única función es ayudarte a ingresar y estructurar reservas en tu lienzo de viaje. Adjunta un documento o pega un texto con tus reservas para organizarlas."* y retorna la lista de `items` vacía `[]`.

==================================================
CONTEXTO DEL VIAJE ACTUAL
==================================================
- Título del viaje: {$tripTitle}
- Destino principal: {$destination}
- Fecha de inicio global del viaje: {$startDate}
- Fecha de finalización global del viaje: {$endDate}
- Fechas mapeadas por Día en el lienzo: {$datesMapping}
- Resumen de ocupación: {$daysContext}

==================================================
2. FUENTES DE ENTRADA ACEPTADAS
==================================================
1. Archivos (PDFs, PNG, JPG): Tiquetes de avión, confirmaciones de Booking/Airbnb, vouchers de alquiler, e-tickets de actividades, etc.
2. Texto Libre: Mensajes de WhatsApp, correos de confirmación copiados, notas de Notion/Word o itinerarios completos pegados en el chat.

==================================================
3. EXTRACCIÓN Y ORDENAMIENTO CRONOLÓGICO ESTRICTO
==================================================
- Analiza minuciosamente el archivo o texto provisto.
- Identifica cada uno de los eventos presentes (Vuelos, Hospedajes, Actividades, Traslados, Notas/Documentos).
- Asigna las fechas reales en formato ISO (`YYYY-MM-DD`). Si no se especifica el año, asume el año del viaje ({$startDate}).
- Si se conoce el día relativo (ej. "Día 1", "Día 2"), crúzalo con el mapa de fechas ({$datesMapping}) para derivar `start_date`.
- ORDENA TODOS LOS ELEMENTOS CRONOLÓGICAMENTE por `start_date` y luego por `start_time`.

==================================================
4. FORMATO DE RESPUESTA (JSON SCHEMA PURO OBLIGATORIO)
==================================================
Debes responder SIEMPRE con un único objeto JSON válido con esta estructura exacta (sin markdown extra ni bloques fuera de JSON):

{
  "message": "Analicé tus reservas y encontré N elementos para tu viaje.",
  "items": [
    {
      "type": "flight" | "hotel" | "activity" | "transport" | "note",
      "title": "Título descriptivo del evento",
      "start_date": "YYYY-MM-DD",
      "start_time": "HH:mm" | null,
      "end_date": "YYYY-MM-DD" | null,
      "end_time": "HH:mm" | null,
      "location_query": "Lugar o dirección para búsqueda de mapas",
      "notes": "Código de reserva, PNR o detalles importantes",
      "data": {
        // Para flight: departure_airport, arrival_airport, departure_time, arrival_time, airline, flight_number, confirmation_code
        // Para hotel: hotel_name, check_in, check_out, address, confirmation_code
        // Para activity: activity_title, time, location, description, confirmation_code
        // Para transport: transport_type, pickup_location, destination, departure_time, arrival_time, confirmation_code
        // Para note: note_title, content
      },
      "attach_file_index": 0
    }
  ],
  "suggestions": [
    "📄 Cargar Archivo", "✍️ Pegar Texto"
  ]
}
PROMPT;
    }

    /**
     * Build user parts including sanitized text and multimodal base64 files.
     */
    protected function buildUserParts(?string $message, array $uploadedFiles): array
    {
        $parts = [];

        if (!empty($message)) {
            $cleanMessage = strip_tags($message);
            $parts[] = ['text' => $cleanMessage];
        } else {
            $parts[] = ['text' => 'Por favor analiza los documentos adjuntos y extrae los datos para agregarlos al itinerario de viaje.'];
        }

        foreach ($uploadedFiles as $index => $fileInfo) {
            $path = $fileInfo['path'] ?? null;
            $mimeType = $fileInfo['mime_type'] ?? null;

            if ($path && file_exists($path)) {
                $fileBytes = file_get_contents($path);
                if ($fileBytes !== false) {
                    $base64 = base64_encode($fileBytes);
                    $parts[] = [
                        'inlineData' => [
                            'mimeType' => $mimeType ?: 'application/pdf',
                            'data' => $base64
                        ]
                    ];
                }
            }
        }

        return $parts;
    }

    /**
     * Normalize generated actions to match Viantryp format.
     */
    protected function normalizeActions(array $actions, Trip $trip): array
    {
        $normalized = [];

        foreach ($actions as $act) {
            if (!is_array($act)) {
                continue;
            }

            $actionType = strtoupper($act['action'] ?? 'CREATE_ITEM');

            // Handle FOCUS_DAY UI redirection action
            if ($actionType === 'FOCUS_DAY') {
                $dayIndex = intval($act['day_index'] ?? ($act['day'] ?? 1));
                if ($dayIndex < 1) $dayIndex = 1;

                $normalized[] = [
                    'action' => 'FOCUS_DAY',
                    'day_index' => $dayIndex,
                    'day' => $dayIndex,
                    'message' => $act['message'] ?? "Te abrí el Día {$dayIndex}. Haz clic en el lápiz ✏️ del elemento para editar sus detalles."
                ];
                continue;
            }

            if (!isset($act['type'])) {
                continue;
            }

            $rawType = strtolower(trim($act['type']));
            
            // Map synonyms
            $type = 'actividad';
            if (in_array($rawType, ['hotel', 'alojamiento', 'lodging', 'hospedaje'])) {
                $type = 'alojamiento';
            } elseif (in_array($rawType, ['flight', 'vuelo', 'avion', 'plane'])) {
                $type = 'flight';
            } elseif (in_array($rawType, ['activity', 'actividad', 'tour', 'visita'])) {
                $type = ($rawType === 'tour') ? 'tour' : 'actividad';
            } elseif (in_array($rawType, ['transport', 'transporte', 'transfer', 'traslado'])) {
                $type = 'transporte';
            } elseif (in_array($rawType, ['comida', 'restaurante', 'restaurant', 'cena', 'almuerzo'])) {
                $type = 'comida';
            } elseif (in_array($rawType, ['note', 'nota', 'caja', 'tip'])) {
                $type = 'caja';
            } elseif (in_array($rawType, ['ubicacion', 'location', 'lugar'])) {
                $type = 'ubicacion';
            }

            $day = isset($act['day']) ? intval($act['day']) : 1;
            if ($day < 1) $day = 1;

            $d = is_array($act['data'] ?? null) ? $act['data'] : [];

            // Field mapping
            if ($type === 'alojamiento') {
                $d['nombre'] = $d['nombre'] ?? ($d['hotel_name'] ?? ($d['title'] ?? ($act['title'] ?? '')));
                $d['direccion'] = $d['direccion'] ?? ($d['address'] ?? ($d['location'] ?? ''));
                $d['checkin'] = $d['checkin'] ?? ($d['check_in'] ?? '');
                $d['checkout'] = $d['checkout'] ?? ($d['check_out'] ?? '');
                $d['reserva'] = $d['reserva'] ?? ($d['confirmation_code'] ?? '');
                $d['precio'] = $d['precio'] ?? ($d['price'] ?? '');
                $d['tipo_alojamiento'] = $d['tipo_alojamiento'] ?? 'Hotel';
                
                // Validate mandatory field
                if (empty($d['nombre'])) continue;
            } elseif ($type === 'flight') {
                $d['origen'] = $d['origen'] ?? ($d['departure_airport'] ?? ($d['from'] ?? ''));
                $d['destino'] = $d['destino'] ?? ($d['arrival_airport'] ?? ($d['to'] ?? ''));
                $d['aerolinea'] = $d['aerolinea'] ?? ($d['airline'] ?? '');
                $d['vuelo'] = $d['vuelo'] ?? ($d['flight_number'] ?? '');
                $d['salida'] = $d['salida'] ?? ($d['departure_time'] ?? '');
                $d['llegada'] = $d['llegada'] ?? ($d['arrival_time'] ?? '');
                $d['reserva'] = $d['reserva'] ?? ($d['confirmation_code'] ?? '');
                
                // Validate mandatory fields
                if (empty($d['origen']) || empty($d['destino'])) continue;
            } elseif ($type === 'actividad') {
                $d['nombre'] = $d['nombre'] ?? ($d['activity_title'] ?? ($d['title'] ?? ($act['title'] ?? '')));
                $d['direccion'] = $d['direccion'] ?? ($d['location'] ?? ($d['address'] ?? ''));
                $d['fecha'] = $d['fecha'] ?? ($d['time'] ?? '');
                $d['duracion'] = $d['duracion'] ?? ($d['duration'] ?? '');
                $d['descripcion'] = $d['descripcion'] ?? ($d['description'] ?? '');
                $d['reserva'] = $d['reserva'] ?? ($d['confirmation_code'] ?? '');
                
                // Validate mandatory field
                if (empty($d['nombre'])) continue;
            } elseif ($type === 'transporte') {
                $d['tipo'] = $d['tipo'] ?? ($d['transport_type'] ?? 'Transporte');
                $d['proveedor'] = $d['proveedor'] ?? ($d['company'] ?? '');
                $d['origen'] = $d['origen'] ?? ($d['pickup_location'] ?? ($d['from'] ?? ''));
                $d['destino'] = $d['destino'] ?? ($d['destination'] ?? ($d['to'] ?? ''));
                $d['salida'] = $d['salida'] ?? ($d['departure_time'] ?? '');
                $d['llegada'] = $d['llegada'] ?? ($d['arrival_time'] ?? '');
                $d['reserva'] = $d['reserva'] ?? ($d['confirmation_code'] ?? '');

                if (empty($d['origen']) && empty($d['destino'])) continue;
            } elseif ($type === 'comida') {
                $d['restaurante'] = $d['restaurante'] ?? ($d['restaurant_name'] ?? ($d['name'] ?? ($d['title'] ?? ($act['title'] ?? ''))));
                $d['tipo'] = $d['tipo'] ?? 'Cena';
                $d['direccion'] = $d['direccion'] ?? ($d['address'] ?? ($d['location'] ?? ''));
                $d['fecha'] = $d['fecha'] ?? ($d['time'] ?? '');
                $d['reserva'] = $d['reserva'] ?? ($d['confirmation_code'] ?? '');

                if (empty($d['restaurante'])) continue;
            } elseif ($type === 'caja') {
                $d['titulo'] = $d['titulo'] ?? ($d['note_title'] ?? ($d['title'] ?? ($act['title'] ?? '')));
                $d['contenido'] = $d['contenido'] ?? ($d['content'] ?? ($d['description'] ?? ''));
                $d['icono'] = $d['icono'] ?? '💡';
                $d['color_fondo'] = $d['color_fondo'] ?? '#f59e0b';

                if (empty($d['titulo']) && empty($d['contenido'])) continue;
            }

            $startDateVal = $act['start_date'] ?? null;
            $startTimeVal = $act['start_time'] ?? null;
            $endDateVal = $act['end_date'] ?? null;
            $endTimeVal = $act['end_time'] ?? null;
            $locationQuery = $act['location_query'] ?? ($d['direccion'] ?? ($d['address'] ?? ($d['location'] ?? '')));
            $notesVal = $act['notes'] ?? ($d['reserva'] ?? ($d['confirmation_code'] ?? ($d['descripcion'] ?? '')));

            $titleVal = $act['title'] ?? ($d['nombre'] ?? ($d['restaurante'] ?? ($d['titulo'] ?? ($d['activity_title'] ?? ($d['aerolinea'] ?? 'Nuevo elemento')))));

            $normalized[] = [
                'action' => 'create_item',
                'type' => $type,
                'day' => $day,
                'title' => $titleVal,
                'start_date' => $startDateVal,
                'start_time' => $startTimeVal,
                'end_date' => $endDateVal,
                'end_time' => $endTimeVal,
                'location_query' => $locationQuery,
                'notes' => $notesVal,
                'data' => $d,
                'attach_file_index' => isset($act['attach_file_index']) ? intval($act['attach_file_index']) : null
            ];
        }

        return $normalized;
    }

    /**
     * Intelligent mock response for development when no API key is configured.
     */
    protected function getMockResponse(Trip $trip, ?string $message, array $uploadedFiles): array
    {
        $fileCount = count($uploadedFiles);

        if ($fileCount > 0) {
            $actions = [];
            foreach ($uploadedFiles as $i => $file) {
                $origName = $file['original_name'] ?? 'documento.pdf';
                $isFlight = stripos($origName, 'vuelo') !== false || stripos($origName, 'flight') !== false;
                $isHotel = stripos($origName, 'hotel') !== false || stripos($origName, 'booking') !== false || stripos($origName, 'airbnb') !== false;

                if ($isFlight) {
                    $actions[] = [
                        'action' => 'create_item',
                        'type' => 'flight',
                        'day' => 1,
                        'title' => 'Vuelo Detectado: BOG → ' . ($trip->destination ?: 'Destino'),
                        'data' => [
                            'airline' => 'Aerolínea',
                            'flight_number' => 'AV123',
                            'departure_airport' => 'BOG',
                            'arrival_airport' => substr(strtoupper($trip->destination ?: 'MAD'), 0, 3),
                            'departure_time' => '14:30',
                            'arrival_time' => '18:00',
                            'confirmation_code' => 'CONF-' . rand(1000, 9999)
                        ],
                        'attach_file_index' => $i
                    ];
                } elseif ($isHotel) {
                    $actions[] = [
                        'action' => 'create_item',
                        'type' => 'hotel',
                        'day' => 1,
                        'title' => 'Reserva de Hotel en ' . ($trip->destination ?: 'Destino'),
                        'data' => [
                            'hotel_name' => 'Hotel ' . ($trip->destination ?: 'Central'),
                            'check_in' => '15:00',
                            'check_out' => '11:00',
                            'address' => 'Zona turística central',
                            'confirmation_code' => 'HTL-' . rand(1000, 9999)
                        ],
                        'attach_file_index' => $i
                    ];
                } else {
                    $actions[] = [
                        'action' => 'create_item',
                        'type' => 'activity',
                        'day' => 2,
                        'title' => 'Actividad / Reserva: ' . pathinfo($origName, PATHINFO_FILENAME),
                        'data' => [
                            'activity_title' => pathinfo($origName, PATHINFO_FILENAME),
                            'time' => '10:00',
                            'location' => $trip->destination ?: 'Centro',
                            'description' => 'Documento procesado: ' . $origName
                        ],
                        'attach_file_index' => $i
                    ];
                }
            }

            return [
                'success' => true,
                'message' => "He recibido y analizado **{$fileCount} documento(s)**. He generado las tarjetas correspondientes con los datos extraídos para que puedas agregarlas al lienzo.",
                'actions' => $actions
            ];
        }

        // Text only response
        $reply = "¡Hola! Estoy listo para ayudarte a armar tu viaje a **" . ($trip->destination ?: 'tu destino') . "**. Puedes pedirme sugerencias de actividades, traslados o arrastrar tus vouchers en PDF e imágenes para agregarlos automáticamente al lienzo.";

        if (!empty($message)) {
            $msgLower = strtolower($message);
            if (str_contains($msgLower, 'hotel') || str_contains($msgLower, 'alojamiento')) {
                return [
                    'success' => true,
                    'message' => "He preparado una sugerencia de hotel para tu itinerario en **" . ($trip->destination ?: 'el destino') . "**:",
                    'actions' => [
                        [
                            'action' => 'create_item',
                            'type' => 'hotel',
                            'day' => 1,
                            'title' => 'Hotel en ' . ($trip->destination ?: 'Centro'),
                            'data' => [
                                'hotel_name' => 'Grand Hotel ' . ($trip->destination ?: 'Plaza'),
                                'check_in' => '15:00',
                                'check_out' => '11:00',
                                'address' => 'Avenida Principal 100'
                            ]
                        ]
                    ]
                ];
            } elseif (str_contains($msgLower, 'actividad') || str_contains($msgLower, 'tour') || str_contains($msgLower, 'cena')) {
                return [
                    'success' => true,
                    'message' => "¡Excelente plan! He creado esta actividad sugerida para tu día 2:",
                    'actions' => [
                        [
                            'action' => 'create_item',
                            'type' => 'activity',
                            'day' => 2,
                            'title' => 'Recorrido guiado por los principales atractivos',
                            'data' => [
                                'activity_title' => 'Tour icónico por la ciudad',
                                'time' => '10:00',
                                'location' => $trip->destination ?: 'Centro histórico',
                                'description' => 'Paseo guiado con degustación gastronómica y visita a monumentos.'
                            ]
                        ]
                    ]
                ];
            }
        }

        return [
            'success' => true,
            'message' => $reply,
            'actions' => []
        ];
    }
}
