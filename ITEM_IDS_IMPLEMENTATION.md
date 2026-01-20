# Identificadores de Items en Viajes

## Resumen de Cambios

Se han agregado identificadores únicos (IDs) a los items dentro de `items_data` para permitir la asociación de documentos (archivos) a items específicos dentro de días específicos.

## Estructura de IDs

Los IDs generados tienen el formato descriptivo:
```
day_{day_number}_{type}_{count}
```

### Ejemplos:
- `day_1_flight_1` - Primer vuelo del día 1
- `day_1_flight_2` - Segundo vuelo del día 1
- `day_1_hotel_1` - Primer hotel del día 1
- `day_2_flight_1` - Primer vuelo del día 2
- `day_global_note_1` - Primera nota global (sin day)

## Cambios Implementados

### 1. Modelo `Trip` (`app/Models/Trip.php`)

#### Mutator `setItemsDataAttribute()`
- Intercepta la asignación de `items_data`
- Automáticamente agrega IDs a los items que no los tengan
- Preserve IDs existentes

```php
$trip->items_data = $itemsArray; // Los IDs se agregan automáticamente
```

#### Método `addItemIds(array $items)`
- Genera IDs descriptivos para items sin ID
- Mantiene IDs existentes
- Contador independiente por día y tipo

```php
$processedItems = $trip->addItemIds($rawItems);
```

#### Método `findItemById(string $itemId)`
- Busca un item específico por su ID en `items_data`
- Retorna el item o `null` si no existe

```php
$flight = $trip->findItemById('day_1_flight_1');
```

#### Método `getItemsByDay(int $day)`
- Retorna todos los items de un día específico

```php
$day1Items = $trip->getItemsByDay(1);
```

#### Método `getDocumentsByItemId(string $itemId)`
- Obtiene todos los documentos asociados a un item específico
- Usa la columna `item_id` de la tabla `trip_documents`

```php
$itemDocuments = $trip->getDocumentsByItemId('day_1_flight_1');
```

## Tabla `trip_documents`

Ya existe la columna `item_id` (nullable) en la migración:

```php
$table->string('item_id')->nullable(); // ID del item en items_data
```

Ahora esta columna puede ser utilizada para asociar documentos a items específicos:

```php
TripDocument::create([
    'trip_id' => $trip->id,
    'user_id' => $user->id,
    'type' => 'flight',
    'item_id' => 'day_1_flight_1', // ← Nuevo: ID del item específico
    'original_name' => 'booking.pdf',
    'filename' => 'booking_12345.pdf',
    'path' => 'documents/booking_12345.pdf',
    'mime_type' => 'application/pdf',
    'size' => 102400
]);
```

## Ejemplo de Uso Completo

```php
// Crear un viaje con items
$trip = Trip::create([
    'user_id' => $user->id,
    'title' => 'Mi Viaje',
    'items_data' => [
        [
            'day' => 1,
            'type' => 'flight',
            'title' => 'Vuelo a Miami',
            'flight_number' => 'UA123'
            // El ID 'day_1_flight_1' se genera automáticamente
        ],
        [
            'day' => 1,
            'type' => 'hotel',
            'title' => 'Hotel Miami Beach',
            'check_in' => '2024-01-15'
            // El ID 'day_1_hotel_1' se genera automáticamente
        ]
    ]
]);

// Obtener un item específico
$flight = $trip->findItemById('day_1_flight_1');
// $flight = ['day' => 1, 'type' => 'flight', 'title' => 'Vuelo a Miami', 'id' => 'day_1_flight_1', ...]

// Subir un documento para el item
$document = $trip->documents()->create([
    'user_id' => $user->id,
    'type' => 'flight',
    'item_id' => 'day_1_flight_1',
    'original_name' => 'boarding_pass.pdf',
    'filename' => 'boarding_pass_' . time() . '.pdf',
    'path' => 'trip_' . $trip->id . '/boarding_pass_' . time() . '.pdf',
    'mime_type' => 'application/pdf',
    'size' => 102400
]);

// Obtener todos los documentos del item
$itemDocuments = $trip->getDocumentsByItemId('day_1_flight_1');
```

## Pruebas

Se han incluido 5 tests unitarios en `tests/Unit/TripItemIdGenerationTest.php`:

✅ `item_ids_are_generated_with_correct_format` - Verifica el formato de IDs generados
✅ `global_items_get_correct_ids` - Verifica IDs para items sin día
✅ `existing_ids_are_not_overwritten` - Verifica que IDs existentes se preserven
✅ `find_item_by_id` - Prueba búsqueda de items por ID
✅ `get_items_by_day` - Prueba obtención de items por día

Ejecutar pruebas:
```bash
php artisan test tests/Unit/TripItemIdGenerationTest.php
```

## Notas Importantes

1. **Generación Automática**: Los IDs se generan automáticamente cuando se asignan `items_data`, no requiere código adicional en el controlador.

2. **Compatibilidad**: Los items existentes en viajes anterior no se ven afectados. Los IDs se generan solo para items nuevos.

3. **Preservación de IDs**: Si un item ya tiene un ID, no será sobrescrito. Esto permite personalización si es necesaria.

4. **Contadores Independientes**: Cada combinación de `day` y `type` tiene su propio contador, evitando conflictos en los números.

5. **Items Globales**: Los items sin `day` se marcan como `day_global`, diferenciándose de items específicos de días.
