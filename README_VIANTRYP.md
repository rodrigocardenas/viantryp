# Viantryp - Sistema de Gestión de Viajes

## Descripción

Viantryp es un sistema de gestión de viajes desarrollado en Laravel que permite crear, editar y visualizar itinerarios de viaje de manera intuitiva. El sistema ha sido convertido desde archivos HTML estáticos a una aplicación Laravel completa con componentes Blade reutilizables.

## Estado del Proyecto - Beta Mínima

🚀 **ENFOQUE ACTUAL: Publicación de Beta con Funcionalidades Críticas**

El proyecto está enfocado en lanzar una **beta mínima viable** que incluya las funcionalidades esenciales para la gestión básica de itinerarios y su visualización pública por parte de los clientes.

### ✅ Funcionalidades Implementadas (Beta Lista)

#### **Funcionalidades Críticas para Beta**
- **✅ Creación y Edición de Itinerarios**: Editor visual completo con drag & drop para agregar vuelos, hoteles, actividades, transporte y notas
- **✅ Vista Pública de Itinerarios**: Página de preview accesible sin autenticación para que los clientes puedan ver sus viajes
- **✅ Gestión Básica de Viajes**: Crear, editar, eliminar y duplicar viajes con estados (Draft, Enviado, Aprobado, Completado)
- **✅ Responsive Design**: Interfaz completamente adaptada para dispositivos móviles
- **✅ Compartir Itinerarios**: Generación de enlaces públicos para compartir viajes con clientes
- **✅ Exportación PDF**: Descarga de itinerarios en formato PDF para impresión

#### **Funcionalidades Adicionales Implementadas**
- **Autenticación de Usuarios**: Sistema completo con login/registro y OAuth Google
- **Gestión de Personas**: Clientes y agentes asociados a viajes
- **Documentos Adjuntos**: Sistema para subir y gestionar documentos por elemento del itinerario
- **Búsqueda y Filtros**: Filtrar viajes por estado y buscar por título
- **Acciones en Lote**: Seleccionar múltiples viajes para operaciones masivas
- **Códigos Únicos**: Identificadores únicos para cada viaje
- **Imágenes de Portada**: Subida de imágenes representativas para cada viaje
- **Integración Google Places**: Autocompletado y detalles enriquecidos para hoteles

### 🚧 Funcionalidades Pendientes (Post-Beta)

#### **No Prioritarias para Beta**
- [ ] Autenticación de usuarios (ya implementada)
- [ ] Compartir viajes (ya implementada)
- [ ] Exportación a PDF real (ya implementada)
- [ ] Integración con APIs de vuelos/hoteles (parcialmente implementada)
- [ ] Sistema de colaboración
- [ ] Notificaciones por email (básico implementado)
- [ ] App móvil nativa
- [ ] Integración con calendarios
- [ ] Notificaciones push (NO IMPLEMENTADO - No prioritario)

## Características Técnicas

### Arquitectura
- **Backend**: Laravel 12 con PHP 8.2+
- **Frontend**: Blade Templates, CSS3, JavaScript ES6+
- **Base de Datos**: MySQL con Eloquent ORM
- **Autenticación**: Laravel Sanctum + OAuth Google
- **APIs**: Google Places, Google OAuth

### Responsive Design
- Diseño completamente adaptativo para móviles
- Grid system flexible
- Componentes que se ajustan al tamaño de pantalla
- Optimización touch para dispositivos móviles

### Funcionalidades JavaScript
- Editor visual con drag & drop
- Filtrado y búsqueda en tiempo real
- Selección múltiple con acciones en lote
- Modales para agregar elementos
- Validación de formularios
- Gestión de estado persistente

## Estructura del Proyecto

### Vistas Blade Principales
- `resources/views/trips/index.blade.php` - Lista de viajes con filtros y búsqueda
- `resources/views/trips/edit.blade.php` - Editor completo de itinerarios
- `resources/views/trips/preview.blade.php` - Vista pública del itinerario
- `resources/views/trips/create.blade.php` - Creación de nuevos viajes

### Componentes Reutilizables
- `resources/views/components/header.blade.php` - Header con acciones dinámicas
- `resources/views/components/sidebar.blade.php` - Panel lateral con elementos disponibles
- `resources/views/components/timeline.blade.php` - Timeline del itinerario
- `resources/views/components/trip-header.blade.php` - Información del viaje

### Modelos y Controladores
- `app/Models/Trip.php` - Modelo principal con relaciones y métodos auxiliares
- `app/Http/Controllers/TripController.php` - Controlador completo con todas las operaciones CRUD
- `app/Models/Person.php` - Gestión de clientes y agentes
- `app/Models/TripDocument.php` - Documentos adjuntos

### Base de Datos
- `database/migrations/2024_01_01_000000_create_trips_table.php` - Tabla principal de viajes
- `database/migrations/2025_11_11_190000_add_cover_image_url_to_trips_table.php` - Imágenes de portada
- `database/migrations/2025_10_16_142706_add_share_token_to_trips_table.php` - Compartir viajes
- `database/seeders/TripSeeder.php` - Datos de ejemplo

## Instalación y Configuración

### Requisitos del Sistema
- PHP 8.2 o superior
- Composer
- MySQL 8.0+
- Node.js 18+ y npm
- Laravel 12

### Pasos de Instalación

1. **Clonar el proyecto**:
   ```bash
   git clone <repository-url>
   cd viantryp
   ```

2. **Instalar dependencias PHP**:
   ```bash
   composer install
   ```

3. **Instalar dependencias JavaScript**:
   ```bash
   npm install
   ```

4. **Configurar entorno**:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Configurar base de datos**:
   - Crear base de datos MySQL
   - Configurar variables en `.env`:
     ```
     DB_CONNECTION=mysql
     DB_HOST=127.0.0.1
     DB_PORT=3306
     DB_DATABASE=viantryp
     DB_USERNAME=your_username
     DB_PASSWORD=your_password
     ```

6. **Configurar Google APIs** (opcional para funcionalidades avanzadas):
   ```
   GOOGLE_PLACES_API_KEY=your_google_places_api_key
   GOOGLE_CLIENT_ID=your_google_oauth_client_id
   GOOGLE_CLIENT_SECRET=your_google_oauth_client_secret
   ```

7. **Ejecutar migraciones**:
   ```bash
   php artisan migrate
   ```

8. **Cargar datos de ejemplo**:
   ```bash
   php artisan db:seed --class=TripSeeder
   ```

9. **Compilar assets**:
   ```bash
   npm run build
   ```

10. **Iniciar servidor**:
    ```bash
    php artisan serve
    ```

### Comandos de Desarrollo
```bash
# Iniciar servidor de desarrollo con hot reload
composer run dev

# Ejecutar tests
php artisan test

# Limpiar cache
php artisan optimize:clear
```

## Uso y Funcionalidades

### Rutas Principales

#### **Para Agentes (Requiere Autenticación)**
- `/` - Dashboard principal → Lista de viajes
- `/trips` - Gestión completa de viajes
- `/trips/create` - Crear nuevo itinerario
- `/trips/{id}/edit` - Editor visual de itinerarios

#### **Para Clientes (Acceso Público)**
- `/trips/{id}/preview` - Vista previa pública del itinerario
- `/trips/share/{token}` - Acceso compartido con token

### Flujo de Trabajo Principal

#### 1. **Creación de Itinerarios**
- Crear viaje con información básica (título, fechas, destino, viajeros)
- Usar editor visual drag & drop para agregar elementos:
  - ✈️ **Vuelos**: Aerolíneas, números de vuelo, horarios, aeropuertos
  - 🏨 **Hoteles**: Nombre, check-in/out, habitaciones, confirmación
  - 🎯 **Actividades**: Título, ubicación, horarios, descripciones
  - 🚗 **Transporte**: Tipo, horarios, puntos de recogida/destino
  - 📝 **Notas**: Información adicional organizada por días

#### 2. **Vista Previa y Compartir**
- Visualización elegante con timeline por días
- Detalles expandibles para cada elemento
- **Compartir con clientes**: Generar enlace público único
- **Exportar PDF**: Descarga profesional para impresión

#### 3. **Gestión de Estados**
- **En Diseño**: Viaje en creación/edición
- **Enviado**: Compartido con cliente para revisión
- **Aprobado**: Cliente ha aprobado el itinerario
- **Completado**: Viaje finalizado

### Funcionalidades Avanzadas

#### **Gestión de Documentos**
- Adjuntar documentos a cualquier elemento del itinerario
- PDFs, imágenes, confirmaciones de reserva
- Acceso directo desde la vista previa

#### **Integración Google Places** (Opcional)
- Autocompletado inteligente para hoteles
- Información detallada: calificaciones, fotos, dirección
- Mejora la experiencia de creación de itinerarios

#### **Responsive Design**
- Optimizado completamente para móviles
- Touch-friendly interface
- Adaptable a cualquier tamaño de pantalla

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

## Tecnologías y Arquitectura

### Stack Tecnológico
- **Backend**: Laravel 12 (PHP 8.2+)
- **Frontend**: Blade Templates + JavaScript ES6+
- **Base de Datos**: MySQL 8.0+ con Eloquent ORM
- **Autenticación**: Laravel Sanctum + OAuth 2.0 (Google)
- **APIs Externas**: Google Places API, Google OAuth
- **Assets**: Vite para compilación y optimización
- **Estilos**: CSS3 con variables CSS personalizadas
- **UI/UX**: Font Awesome icons, Google Fonts (Poppins)

### Arquitectura de Componentes

#### **Componentes Blade Reutilizables**
- `<x-header>` - Header dinámico con acciones contextuales
- `<x-navigation>` - Navegación por pestañas con filtros
- `<x-sidebar>` - Panel lateral con elementos arrastrables
- `<x-timeline>` - Timeline visual del itinerario
- `<x-trip-header>` - Información del viaje editable
- `<x-element-modal>` - Modal para crear/editar elementos

#### **JavaScript Modular**
- **trip-manager.js**: Gestión del estado de viajes
- **day-manager.js**: Lógica de días y timeline
- **element-manager.js**: CRUD de elementos del itinerario
- **drag-drop.js**: Sistema de arrastrar y soltar
- **persistence.js**: Guardado automático y recuperación

#### **Modelo de Datos**
```sql
-- Viajes con itinerarios JSON
trips: id, user_id, code, title, start_date, end_date,
       travelers, destination, status, summary,
       items_data (JSON), cover_image_url, share_token

-- Personas (clientes/agentes)
persons: id, name, email, phone, type

-- Documentos adjuntos
trip_documents: id, trip_id, item_type, item_id,
                original_name, file_path, mime_type
```

### Características Técnicas Avanzadas

#### **Sistema de Estados**
- Estados bien definidos con transiciones lógicas
- Validaciones de permisos por estado
- Compartir solo viajes en estados apropiados

#### **Responsive Design Completo**
- Mobile-first approach
- Breakpoints optimizados para todos los dispositivos
- Touch gestures y navegación móvil
- Optimización de performance en móviles

#### **Seguridad**
- Autenticación robusta con Laravel
- Autorización granular por viaje
- Sanitización de datos JSON
- Protección CSRF en todas las formas

## Roadmap y Próximos Pasos

### 🎯 **Beta Mínima - LISTA PARA PUBLICACIÓN**

El proyecto tiene **todas las funcionalidades críticas implementadas** para lanzar una beta funcional:

#### ✅ **Funcionalidades Beta Completadas**
- ✅ Creación y edición visual de itinerarios
- ✅ Vista pública para clientes
- ✅ Gestión completa de viajes
- ✅ Responsive design móvil
- ✅ Compartir y exportar PDF
- ✅ Autenticación y seguridad

### 🚀 **Próximas Etapas (Post-Beta)**

#### **Fase 1: Mejoras de UX/UI (1-2 semanas)**
- [ ] Refinamiento de la interfaz de usuario
- [ ] Optimización de performance en móviles
- [ ] Mejoras en la experiencia de drag & drop
- [ ] Sistema de notificaciones toast mejorado

#### **Fase 2: Funcionalidades Avanzadas (2-4 semanas)**
- [ ] Sistema de colaboración multi-usuario
- [ ] Integración completa con APIs de reservas
- [ ] Plantillas de viajes reutilizables
- [ ] Sistema de versiones de itinerarios
- [ ] Análisis y estadísticas de viajes

#### **Fase 3: Expansión y Escalabilidad (1-3 meses)**
- [ ] App móvil nativa (React Native)
- [ ] API REST completa para integraciones
- [ ] Integración con calendarios externos
- [ ] Sistema de pagos integrado
- [ ] Multi-idioma (i18n)

#### **Fase 4: Enterprise Features (3-6 meses)**
- [ ] Dashboard administrativo avanzado
- [ ] Sistema de reportes y analytics
- [ ] Integración con CRMs externos
- [ ] Automatización de procesos
- [ ] Compliance y auditoría

### 🛠️ **Optimizaciones Técnicas Pendientes**
- [ ] Implementación de Redis para cache
- [ ] Optimización de imágenes con CDN
- [ ] Sistema de logs centralizado
- [ ] Tests automatizados completos (90%+ coverage)
- [ ] CI/CD pipeline con GitHub Actions
- [ ] Documentación técnica completa
- [ ] Monitoreo y alerting (Sentry, DataDog)

### 📊 **Métricas de Éxito para Beta**
- [ ] Tiempo de carga < 3 segundos
- [ ] Funcionamiento perfecto en móviles
- [ ] Creación de itinerario completo en < 10 minutos
- [ ] Exportación PDF funcional
- [ ] Compartir con clientes sin fricciones

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
