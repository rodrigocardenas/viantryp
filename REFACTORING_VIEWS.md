# Documentación de la Refactorización de Vistas del Editor

Este documento describe la nueva arquitectura de componentes para las vistas del editor de viajes, implementada para mejorar la modularidad, reutilización y mantenibilidad del código.

## 1. Separación de Vistas: `Create` vs. `Edit`

La lógica del editor se ha dividido en dos vistas principales, eliminando la necesidad de la vista genérica y monolítica `editor.blade.php`.

### `resources/views/trips/create.blade.php`

- **Propósito**: Gestionar la creación de un nuevo viaje.
- **Lógica**:
    - No carga datos de ningún viaje (`window.existingTripData = null`).
    - Se identifica con la variable global `window.editorMode = 'create'`.
    - Muestra automáticamente un modal (`<x-new-trip-modal />`) para que el usuario ingrese los detalles iniciales del viaje.
    - La interfaz del editor principal está oculta hasta que se completa el modal.

### `resources/views/trips/edit.blade.php`

- **Propósito**: Gestionar la edición de un viaje existente.
- **Lógica**:
    - Recibe el objeto `$trip` desde el controlador.
    - Carga los datos del viaje en la variable global `window.existingTripData`.
    - Se identifica con `window.editorMode = 'edit'`.
    - La lógica de JavaScript específica para la edición (cargar elementos, autoguardado, etc.) se ha externalizado al siguiente módulo.

## 2. Externalización de JavaScript

Para mantener las vistas Blade limpias, la lógica de JavaScript se ha movido a módulos dedicados.

### `resources/js/modules/editor-edit-mode.js`

- **Propósito**: Contiene todo el código JavaScript que antes estaba incrustado en `edit.blade.php`.
- **Funcionalidad**:
    - `loadExistingElements()`: Renderiza los elementos del viaje en el timeline.
    - `initializeDragAndDrop()`: Configura las zonas para arrastrar y soltar.
    - `initializeAutoSave()`: Implementa la funcionalidad de guardado automático.
- **Uso**: Este módulo es importado directamente en `edit.blade.php` a través de Vite.

## 3. Componentes de Sidebar Reutilizables

El sidebar del editor se ha refactorizado para usar componentes Blade individuales, haciendo el código más declarativo y fácil de gestionar.

### `resources/views/components/editor/sidebar.blade.php`

- **Propósito**: Actúa como el contenedor principal del sidebar.
- **Implementación**: En lugar de contener el HTML de cada elemento, ahora invoca a los componentes correspondientes.

```blade
<div class="element-categories">
    <x-sidebar.items.flight-item />
    <x-sidebar.items.hotel-item />
    <x-sidebar.items.activity-item />
    <x-sidebar.items.transport-item />
    <x-sidebar.items.note-item />
    <x-sidebar.items.summary-item />
    <x-sidebar.items.total-item />
</div>
```

### Componentes de Items (`resources/views/components/sidebar/items/`)

- Se ha creado un directorio para albergar cada elemento del sidebar.
- Todos los componentes de item (ej. `flight-item.blade.php`, `hotel-item.blade.php`) extienden de un componente base.

#### `base.blade.php`

- **Ubicación**: `resources/views/components/sidebar/items/base.blade.php`
- **Propósito**: Define la estructura HTML y el comportamiento común de todos los elementos del sidebar (drag-and-drop, estilos, etc.).
- **Props**: `type`, `icon`, `title`, `description`, `disabled`.

#### Ejemplo de Uso (`flight-item.blade.php`)

```blade
<x-sidebar.items.base
    type="flight"
    icon="fas fa-plane"
    title="Vuelo"
    description="Aerolínea y horarios"
    :disabled="$disabled"
/>
```

## Conclusión

Esta nueva estructura desacopla la lógica de las vistas, promueve la reutilización de componentes y facilita la mantenibilidad a largo plazo del editor de viajes.
