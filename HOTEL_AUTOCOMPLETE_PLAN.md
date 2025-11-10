# Plan de Implementación: Autocomplete Predictivo con Google Places API para Hoteles

## Análisis Actual del Sistema

### Estructura del Componente Hotel
- **Archivo**: `resources/views/components/sidebar/items/hotel-item.blade.php`
- **Función**: Elemento arrastrable para agregar hospedajes
- **Props**: `disabled` (opcional)
- **Base**: Extiende `x-sidebar.items.base` con tipo "hotel", icono "fas fa-bed", título "Agregar Hospedaje"

### Modal de Elementos
- **Archivo**: `resources/views/components/element-modal.blade.php`
- **Función**: Modal dinámico para crear/editar elementos del viaje
- **Formulario Hotel**: Input simple de texto para "Nombre del Hotel" (`id="hotel-name"`)

### Configuración Google
- **Archivo**: `config/services.php`
- **Configuración**: Cliente OAuth de Google ya configurado para autenticación
- **Campos**: `client_id`, `client_secret`, `redirect`

### Estado Actual
- No existen archivos `autocomplete.js` ni `autocomplete.css`
- El formulario de hotel es básico (solo input de texto)
- No hay integración con Google Places API

## Plan de Implementación Paso a Paso

### Fase 1: Configuración de Google Places API
1. **Obtener API Key de Google Places**
   - Acceder a Google Cloud Console
   - Habilitar Places API
   - Generar API Key restringida para Places API
   - Agregar restricción de dominio/IP si es necesario

2. **Configurar API Key en Laravel**
   - Agregar `GOOGLE_PLACES_API_KEY` al archivo `.env`
   - Actualizar `config/services.php` para incluir Places API:
     ```php
     'google' => [
         'client_id' => env('GOOGLE_CLIENT_ID'),
         'client_secret' => env('GOOGLE_CLIENT_SECRET'),
         'redirect' => env('GOOGLE_REDIRECT_URI', '/auth/google/callback'),
         'places_api_key' => env('GOOGLE_PLACES_API_KEY'),
     ],
     ```

### Fase 2: Crear Módulo JavaScript de Autocomplete
3. **Crear archivo `resources/js/modules/autocomplete.js`**
   - Implementar clase `GooglePlacesAutocomplete`
   - Métodos principales:
     - `init(inputElement, options)`: Inicializar autocomplete en input
     - `loadGoogleMapsAPI()`: Cargar Google Maps JavaScript API
     - `setupAutocomplete()`: Configurar Places Autocomplete
     - `handlePlaceSelect()`: Manejar selección de lugar
     - `extractPlaceData()`: Extraer datos relevantes del lugar

4. **Crear archivo `resources/css/components/autocomplete.css`**
   - Estilos para el dropdown de sugerencias
   - Estilos para el input con autocomplete
   - Animaciones y estados (loading, error, etc.)

### Fase 3: Actualizar Formulario de Hotel
5. **Modificar `resources/js/modules/modal.js`**
   - Actualizar `elementFormsData.hotel` para incluir autocomplete
   - Agregar data attributes para configuración
   - Integrar con el módulo autocomplete

6. **Actualizar lógica de guardado**
   - Modificar `collectFormData()` para incluir datos de Google Places
   - Almacenar `place_id`, coordenadas, dirección completa, etc.
   - Actualizar `selectedHotelData` en ModalManager

### Fase 4: Backend - Almacenamiento de Datos
7. **Crear/Actualizar Modelo de Hotel (si es necesario)**
   - Evaluar si crear modelo `Hotel` separado o usar campos en `Trip` data
   - Campos sugeridos: `place_id`, `name`, `address`, `latitude`, `longitude`, `rating`, `types`

8. **Actualizar Controladores**
   - Modificar `TripController` para manejar datos de hotel con Places
   - Crear endpoint para búsqueda adicional si es necesario

### Fase 5: Integración y Testing
9. **Actualizar archivos de compilación**
   - Agregar autocomplete.js a `resources/js/app.js` o crear bundle separado
   - Compilar assets con `npm run dev` o `npm run build`

10. **Testing funcional**
    - Verificar carga de Google Maps API
    - Probar autocomplete predictivo
    - Validar almacenamiento de datos
    - Probar edición de elementos existentes

### Fase 6: Mejoras y Optimizaciones
11. **Mejoras de UX**
    - Agregar indicadores de carga
    - Manejo de errores de API
    - Cache de resultados
    - Fallback cuando API no está disponible

12. **Optimizaciones de rendimiento**
    - Lazy loading de Google Maps API
    - Debouncing de búsquedas
    - Limitar requests a Places API

## Estructura de Datos Esperada

### Datos de Google Places a Almacenar
```javascript
{
  place_id: "ChIJ...",
  name: "Hotel Example",
  formatted_address: "Calle Example 123, Ciudad",
  geometry: {
    location: {
      lat: -34.603722,
      lng: -58.381592
    }
  },
  rating: 4.5,
  types: ["lodging", "point_of_interest", "establishment"],
  photos: [...],
  website: "https://...",
  international_phone_number: "+54 11 1234-5678"
}
```

### Campos del Formulario Actualizados
- Input de nombre con autocomplete
- Campos adicionales poblados automáticamente:
  - Dirección completa
  - Rating/estrellas
  - Website
  - Teléfono
  - Tipo de alojamiento

## Consideraciones Técnicas

### Limitaciones de Google Places API
- Requiere API Key válida
- Límites de quota (hasta 1000 requests/día gratis)
- Requiere Google Maps JavaScript API
- Costos por uso excesivo

### Compatibilidad
- Asegurar compatibilidad con Select2 (ya usado en otros selects)
- Mantener consistencia con otros elementos del modal
- Responsive design

### Seguridad
- API Key restringida por dominio
- Validación de datos en backend
- Sanitización de inputs

## Próximos Pasos Recomendados

1. Obtener y configurar Google Places API Key
2. Implementar módulo autocomplete básico
3. Integrar con formulario de hotel
4. Testing exhaustivo
5. Documentar uso y mantenimiento

---

**Estado**: Planificado
**Prioridad**: Alta
**Complejidad**: Media-Alta
**Tiempo Estimado**: 2-3 días de desarrollo
