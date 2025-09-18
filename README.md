# ✈️ Viantryp - Sistema de Gestión de Viajes

## 📋 Descripción
Viantryp es un sistema de gestión de viajes desarrollado en Laravel que permite crear, editar y visualizar itinerarios de viaje de manera intuitiva. El sistema ha sido convertido desde archivos HTML estáticos a una aplicación Laravel completa con componentes Blade reutilizables.

## 🚀 Características Principales

### 🏠 **Lista de Viajes**
- **Dashboard** con lista de viajes existentes
- **Filtros** por estado (En Diseño, Enviados, Aprobados, Completados)
- **Búsqueda** de viajes por título
- **Botón "Nuevo Viaje"** que redirige al editor
- **Vista previa** y edición de viajes existentes
- **Selección múltiple** y acciones en lote
- **Cambio de estado** individual de viajes

### ✏️ **Editor de Itinerarios**
- **Sistema de días** para organizar el itinerario
- **Modales** para agregar elementos (Vuelos, Hoteles, Actividades, Transporte, Notas)
- **Formularios específicos** para cada tipo de elemento
- **Gestión de fechas** y duración del viaje
- **Vista previa** del itinerario
- **Guardado** de viajes en base de datos

## 📁 Estructura del Proyecto

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

## 🎯 Funcionalidades del Sistema

### ✈️ **Elementos de Viaje**
- **Vuelos**: Número de vuelo, aerolínea, horarios, aeropuertos
- **Hoteles**: Check-in/out, tipo de habitación, noches, confirmación
- **Actividades**: Horarios, descripción, ubicación
- **Transporte**: Tipo de transporte, horarios, puntos de recogida
- **Notas**: Información adicional personalizada

### 📅 **Sistema de Días**
- **Agregar días** al itinerario
- **Organización** de elementos por día
- **Fechas automáticas** basadas en fechas del viaje
- **Timeline visual** del itinerario

### 💾 **Persistencia de Datos**
- **Base de datos MySQL** para almacenar viajes
- **JSON** para elementos del itinerario
- **Estados** de viaje (Draft, Enviado, Aprobado, Completado)
- **Datos de ejemplo** incluidos

## 🎨 Características de Diseño

### 📱 **Responsive Design**
- **Desktop**: Interfaz completa con sidebar
- **Móvil**: Adaptación automática de elementos
- **Notificaciones** adaptadas a pantallas pequeñas

### 🎯 **Experiencia de Usuario**
- **Animaciones suaves** en transiciones
- **Feedback visual** en interacciones
- **Notificaciones** informativas
- **Navegación intuitiva** entre páginas

## 🚀 Instalación y Uso

### 1. **Instalación**
```bash
# Clonar el proyecto
git clone <repository-url>
cd viantryp

# Instalar dependencias
composer install

# Configurar base de datos en .env
# Ejecutar migraciones
php artisan migrate

# Cargar datos de ejemplo
php artisan db:seed --class=TripSeeder

# Iniciar servidor
php artisan serve
```

### 2. **Acceso a la Plataforma**
- Abrir `http://localhost:8000` en el navegador
- Se mostrará la lista de viajes con datos de ejemplo

### 3. **Crear Nuevo Viaje**
- Hacer clic en **"Nuevo Viaje"**
- Se abrirá el editor con formulario básico

### 4. **Editar Itinerario**
- **Agregar días** al itinerario
- **Hacer clic en "Agregar"** en cada día para agregar elementos
- **Seleccionar tipo** de elemento en el modal
- **Completar formulario** específico para cada elemento
- **Guardar** el viaje

### 5. **Vista Previa**
- Hacer clic en **"Vista Previa"** para ver el itinerario
- **Timeline visual** con todos los elementos organizados por día

## 🔧 Tecnologías Utilizadas

- **Backend**: Laravel 11
- **Frontend**: Blade Templates, CSS3, JavaScript
- **Base de Datos**: MySQL
- **Estilos**: CSS personalizado con variables CSS
- **Iconos**: Font Awesome
- **Fuentes**: Google Fonts (Poppins)

## 📋 Estado del Proyecto

### ✅ **Completado**
- [x] Conversión completa de HTML a Laravel Blade
- [x] Sistema de gestión de viajes (CRUD)
- [x] Editor de itinerarios con sistema de días
- [x] Vista previa con timeline visual
- [x] Filtros y búsqueda de viajes
- [x] Selección múltiple y acciones en lote
- [x] Base de datos MySQL con migraciones
- [x] Componentes Blade reutilizables
- [x] Diseño responsive
- [x] Datos de ejemplo incluidos

### 🔄 **En Desarrollo**
- [ ] Autenticación de usuarios
- [ ] Exportación a PDF real
- [ ] Sistema de colaboración
- [ ] Notificaciones por email

## 🎯 Próximas Mejoras

1. **Autenticación** - Sistema de usuarios y permisos
2. **Exportación PDF** - Generación real de PDFs
3. **Colaboración** - Compartir itinerarios entre usuarios
4. **Integración con APIs** - Información de vuelos y hoteles en tiempo real
5. **App móvil** - Versión móvil nativa
6. **Notificaciones** - Recordatorios de fechas importantes

---

**Desarrollado con ❤️ para facilitar la planificación de viajes**





