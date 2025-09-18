# Viantryp - Sistema de Gestión de Viajes

## Descripción

Viantryp es un sistema de gestión de viajes desarrollado en Laravel que permite crear, editar y visualizar itinerarios de viaje de manera intuitiva. El sistema ha sido convertido desde archivos HTML estáticos a una aplicación Laravel completa con componentes Blade reutilizables.

## Características

- **Gestión de Viajes**: Crear, editar, eliminar y duplicar viajes
- **Editor de Itinerarios**: Interfaz visual para agregar vuelos, hoteles, actividades, transporte y notas
- **Vista Previa**: Visualización elegante de los itinerarios con timeline
- **Filtros y Búsqueda**: Filtrar viajes por estado y buscar por título
- **Acciones en Lote**: Seleccionar múltiples viajes para operaciones masivas
- **Estados de Viaje**: Draft, Enviado, Aprobado, Completado
- **Responsive Design**: Interfaz adaptada para dispositivos móviles

## Estructura del Proyecto

### Vistas Blade

- `resources/views/layouts/app.blade.php` - Layout principal
- `resources/views/trips/index.blade.php` - Lista de viajes
- `resources/views/trips/editor.blade.php` - Editor de itinerarios
- `resources/views/trips/preview.blade.php` - Vista previa del viaje

### Componentes

- `resources/views/components/header.blade.php` - Header reutilizable
- `resources/views/components/navigation.blade.php` - Navegación por pestañas
- `resources/views/components/trip-item.blade.php` - Elemento de viaje
- `resources/views/components/preview-item.blade.php` - Elemento de vista previa

### Modelos y Controladores

- `app/Models/Trip.php` - Modelo principal con clases auxiliares
- `app/Http/Controllers/TripController.php` - Controlador principal

### Base de Datos

- `database/migrations/2024_01_01_000000_create_trips_table.php` - Migración de la tabla trips
- `database/seeders/TripSeeder.php` - Datos de ejemplo

## Instalación

1. **Clonar el proyecto**:
   ```bash
   git clone <repository-url>
   cd viantryp
   ```

2. **Instalar dependencias**:
   ```bash
   composer install
   ```

3. **Configurar base de datos**:
   - Crear base de datos MySQL
   - Configurar `.env` con los datos de conexión

4. **Ejecutar migraciones**:
   ```bash
   php artisan migrate
   ```

5. **Cargar datos de ejemplo**:
   ```bash
   php artisan db:seed --class=TripSeeder
   ```

6. **Iniciar servidor**:
   ```bash
   php artisan serve
   ```

## Uso

### Rutas Disponibles

- `/` - Redirige a la lista de viajes
- `/trips` - Lista de viajes
- `/trips/create` - Crear nuevo viaje
- `/trips/{id}/edit` - Editar viaje
- `/trips/{id}/preview` - Vista previa del viaje

### Funcionalidades

#### Lista de Viajes
- Filtrar por estado (Todos, En Diseño, Enviados, Aprobados, Completados)
- Buscar por título
- Seleccionar múltiples viajes
- Acciones en lote (duplicar, eliminar)
- Cambiar estado individual

#### Editor de Itinerarios
- Formulario de información básica del viaje
- Agregar elementos: vuelos, hoteles, actividades, transporte, notas
- Vista previa en tiempo real
- Guardar y editar viajes

#### Vista Previa
- Timeline visual del itinerario
- Detalles expandibles de cada elemento
- Información organizada por días
- Opciones de impresión y descarga PDF

## Estructura de Datos

### Tabla `trips`
- `id` - Identificador único
- `title` - Título del viaje
- `start_date` - Fecha de inicio
- `end_date` - Fecha de fin
- `travelers` - Número de viajeros
- `destination` - Destino
- `status` - Estado del viaje
- `summary` - Resumen del viaje
- `items_data` - JSON con los elementos del itinerario
- `created_at`, `updated_at` - Timestamps

### Estructura de `items_data`
```json
[
  {
    "type": "flight|hotel|activity|transport|note",
    "day": 1,
    "title": "Título del elemento",
    "airline": "Aerolínea",
    "flight_number": "Número de vuelo",
    "departure_time": "08:00",
    "arrival_time": "10:30",
    "departure_airport": "Aeropuerto origen",
    "arrival_airport": "Aeropuerto destino",
    "confirmation_number": "Número de confirmación",
    "notes": "Notas adicionales"
  }
]
```

## Tecnologías Utilizadas

- **Backend**: Laravel 11
- **Frontend**: Blade Templates, CSS3, JavaScript
- **Base de Datos**: MySQL
- **Estilos**: CSS personalizado con variables CSS
- **Iconos**: Font Awesome
- **Fuentes**: Google Fonts (Poppins)

## Características Técnicas

### Componentes Reutilizables
- Header con acciones dinámicas
- Navegación por pestañas
- Elementos de viaje modulares
- Sistema de notificaciones

### Responsive Design
- Diseño adaptativo para móviles
- Grid system flexible
- Componentes que se ajustan al tamaño de pantalla

### Funcionalidades JavaScript
- Filtrado y búsqueda en tiempo real
- Selección múltiple con acciones en lote
- Modales para agregar elementos
- Notificaciones toast
- Validación de formularios

## Mejoras Futuras

- [ ] Autenticación de usuarios
- [ ] Compartir viajes
- [ ] Exportación a PDF real
- [ ] Integración con APIs de vuelos/hoteles
- [ ] Sistema de colaboración
- [ ] Notificaciones por email
- [ ] App móvil
- [ ] Integración con calendarios

## Contribución

1. Fork el proyecto
2. Crear rama para feature (`git checkout -b feature/nueva-funcionalidad`)
3. Commit cambios (`git commit -am 'Agregar nueva funcionalidad'`)
4. Push a la rama (`git push origin feature/nueva-funcionalidad`)
5. Crear Pull Request

## Licencia

Este proyecto está bajo la Licencia MIT. Ver el archivo `LICENSE` para más detalles.

## Contacto

Para preguntas o sugerencias, contactar a través de los issues del repositorio.
