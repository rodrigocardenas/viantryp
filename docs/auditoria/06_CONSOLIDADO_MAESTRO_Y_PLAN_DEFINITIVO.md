# 06. CONSOLIDADO MAESTRO DEFINITIVO, BARRIDO DE 10 ÁREAS CRÍTICAS Y PLAN DE ACCIÓN — VIANTRYP

**Fecha:** 30 de octubre de 2026  
**Estado:** Documento Maestro Definitivo (Etapas 1 a 6 + Barrido Final de 10 Áreas + Diseño Web/App Aprobado)  
**Modo de Ejecución:** 100 % Solo Lectura verificada sobre el código fuente real (`Laravel 12`, `PHP 8.2+`, `Cashier Paddle v2.8`, `PWA`, `Capacitor 8 Android`)

---

## 1. DISEÑO APROBADO: SEPARACIÓN DE FACTURACIÓN WEB vs. APP ANDROID/iOS (MODO CONSUMO EXCLUSIVO)

Para proteger la cuenta de Google Play (`com.viantryp.app`) y asegurar la futura aprobación en Apple App Store sin la complejidad técnica ni la comisión del 15 %–30 % de las compras integradas nativas, se establece el **Modelo de Aplicación de Consumo Exclusivo (*Reader / Multiplatform Service*)**:

### 1.1 Matriz de Comportamiento por Canal

| Funcionalidad | Viantryp en Navegador Web (`viantryp.com`) | Viantryp en App Android / iOS (`?app=1` / Cookie `viantryp_app_mode` / Capacitor) |
| :--- | :--- | :--- |
| **Script `paddle.js` y Checkout** | **Habilitado:** Se carga `https://cdn.paddle.com/paddle/v2/paddle.js` y se permite `Paddle.Checkout.open()`. | **Deshabilitado:** No se carga `paddle.js` ni se define `openPaddleCheckout()` en el DOM. |
| **Contratar o Cambiar de Plan** | **Habilitado:** Botones de *Subir a Viajero Pro / Negocios*, selector mensual/anual y canje de código promocional activos. | **Oculto:** Se ocultan precios, selectores de ciclo, campo de códigos promocionales y botones de compra. |
| **Gestión de Métodos de Pago y Facturas** | **Habilitado:** Acceso a actualización de tarjeta, consulta de facturas y cancelación/renovación de suscripción. | **Oculto:** No se muestran botones de modificación de pago ni enlaces externos clickeables (`<a href>`). |
| **Uso del Producto y Consulta de Plan** | **Habilitado:** Acceso completo según el plan activo del usuario. | **Habilitado:** El usuario inicia sesión, consulta el nombre de su plan actual y sus límites de uso, y utiliza todas las funciones contratadas. |

### 1.2 Mensaje Oficial Aprobado para Mostrar en la App Móvil
En `resources/views/profile/index.blade.php` (pestaña *Suscripción*) y en `resources/views/components/upgrade-modal.blade.php`, cuando la petición provenga de la aplicación (`request()->boolean('app') || request()->cookie('viantryp_app_mode') === '1'`), se ocultarán las tarjetas de precios y botones de Paddle y se mostrará este aviso informativo en texto plano (sin enlaces ni botones que abran el navegador externo):

> **Aviso en App Móvil:**  
> *"La gestión de suscripciones y facturación no está disponible desde la aplicación. Para consultar y gestionar tu cuenta de facturación, accede a Viantryp desde un navegador web."*

---

## 2. BARRIDO FINAL EXHAUSTIVO DE LAS 10 ÁREAS CRÍTICAS

### Área 1: Autorización (Prioridad: Crítica)
Se auditaron los **12 controladores** y las **58 rutas** definidas en `routes/web.php` para detectar vulnerabilidades IDOR (*Insecure Direct Object Reference*) y accesos cruzados entre agencias:

| Controlador / Endpoint | Estado de Autorización | Hallazgo Exacto en Código |
| :--- | :--- | :--- |
| **`PersonController` (`routes/web.php:113`)** | **VULNERABLE (P0 — `SEC-02`)** | `index()`, `show()`, `edit()`, `update()`, `destroy()` y `getAgents()` no filtran por usuario. Cualquier agencia ve, edita o borra todos los clientes y agentes de la plataforma. |
| **`TripController` (`inlineUpdate` L398, `updateClient` L473, `storePro` L230)** | **VULNERABLE (P0 — `SEC-02`)** | `Trip` y `Person` se relacionan mediante la tabla pivote `person_trip` (`$trip->persons()`). Al buscar `Person::where('email', $email)->first()` sin `user_id`, vincula el registro `Person` de otra agencia y, al editar el nombre (`$client->update(['name' => ...])`), sobrescribe el nombre del cliente en los viajes de la otra agencia. |
| **`AirlineController` (`routes/web.php:117`)** | **VULNERABLE (P0/P1 — `N-16` Nuevo)** | `Route::resource('airlines', AirlineController::class)` está dentro de `middleware('auth')` sin restricción de rol administrador. **Cualquier usuario registrado puede entrar a `/airlines` y editar o eliminar aerolíneas del catálogo global compartido por toda la plataforma**, o reemplazar sus logos en `storage/app/public/logos`. |
| **`GooglePlacesController` (`routes/web.php:185`)** | **VULNERABLE (P1 — `N-17` Nuevo)** | `Route::match(['get', 'post'], 'api/places/details', ...)` está **fuera del grupo `middleware('auth')`**. Usuarios no autenticados (o autenticados que omitan `trip_id`) evaden `hasReachedGooglePlacesLimit()` y consumen cuota pagada de Google Places Details + descarga de fotos. |
| **`TripController::inviteCollaborator` (L1078)** | **PARCIAL (P1 — `UX-02`)** | Verifica `$trip->user_id != Auth::id()`, pero **solo comprueba límite si `role === 'editor'`** (`L1089`), permitiendo invitar infinitos colaboradores con rol `viewer` o desajustando el límite cuando el trial venció. |
| **`TripController` (Resto de métodos: `edit`, `destroy`, `updateStatus`, `updateCode`, `uploadCover`, `duplicate`, `bulkDelete`, `bulkDuplicate`, `saveProState`, `transferOwnership`, `removeCollaborator`)** | **SEGURO** | Todos verifican explícitamente `$trip->user_id === Auth::id()` o `$trip->canEdit(Auth::id())` / `$trip->canView(Auth::id())`. En `bulkDelete` y `bulkDuplicate` se valida que `count($userTrips) === count($validated['trip_ids'])`. |
| **`TripDocumentController` (`destroy` L18, `download` L46)** | **SEGURO EN LÓGICA / EXPUESTO EN DISCO** | La lógica PHP verifica `$document->user_id === Auth::id()`, `$document->trip->canView(Auth::id())` o el `share_token` del viaje; sin embargo, el archivo físico se guarda en el disco público (ver Área 5). |

---

### Área 2: Credenciales y Secretos (Prioridad: Crítica)
Se inspeccionó el árbol de trabajo y el historial completo de commits en Git:
1. **`viantryp-release-official.keystore` y `android/app/build.gradle` (`SEC-04`):**
   - El archivo binario del keystore sigue rastreado por Git (`git ls-files`) y la contraseña `viantryp2026` está en texto plano en `android/app/build.gradle:38-41`.
   - Como en `public/.well-known/assetlinks.json` existen certificados de *Google Play App Signing*, este keystore actúa como **Upload Key** (llave de subida).
   - **Acción:** Ejecutar `git rm --cached viantryp-release-official.keystore`, externalizar las contraseñas a `android/keystore.properties` (ignorado en Git) y solicitar en *Google Play Console → Configuración → Integridad de la app → Solicitar actualización de clave de subida* si el repositorio ha sido accesible por terceros.
2. **Archivo `voyager_key.pem` en la raíz (`N-15`):**
   - Existe una clave privada PEM en la raíz del proyecto y `.gitignore` no bloquea `*.pem` ni `*.key`. Debe retirarse del directorio web inmediatamente.
3. **Códigos Promocionales y Token de Carto en el historial Git (`N-03`, `N-04`):**
   - Antes del commit `f302a7f` (27 de octubre de 2026), los 4 códigos promocionales y el JWT de Carto estuvieron en texto plano en `config/plans.php` y `TripController.php`.
   - **Acción:** Cambiar los valores de `PROMO_CODE_*` en el `.env` de producción antes del lanzamiento y configurar *Allowed Referrers* (`https://viantryp.com/*`) en el panel de Carto.

---

### Área 3: Autenticación y Sesiones (Prioridad: Alta)
1. **Recuperación de contraseña (`ForgotPasswordController.php`):**
   - `POST /forgot-password` tiene `throttle:5,1` y devuelve un mensaje genérico anti-enumeración (`L36`).
   - **Laguna detectada (`N-22`):** `POST /reset-password` (`routes/web.php:28`) **no tiene middleware `throttle`**, y `sendResetLink` valida `'email' => 'required|email:rfc,dns'` (`L29`), lo cual puede fallar con lentitud si el resolver DNS del servidor tiene microcortes.
2. **Verificación de correo y Pre-Secuestro de Cuenta (`N-02`):**
   - `MustVerifyEmail` está deshabilitado en `app/Models/User.php:16`.
   - Si un tercero registra el correo de una agencia antes de que esta entre con Google, cuando la agencia inicia sesión con Google (`GoogleAuthController.php:78, 255`), el sistema une el `google_id` pero **no invalida la contraseña creada por el tercero**.
3. **CSRF en Google Identity Services (`N-14`):**
   - `bootstrap/app.php:22` excluye `auth/google/*` de la verificación CSRF de Laravel. En `handleGsiCallback` se verifica la firma JWT del `id_token` con `Google\Client`, pero falta comprobar que la cookie `g_csrf_token` coincida con el campo `g_csrf_token` del POST para evitar *Login CSRF*.
4. **Configuración de Sesiones (`config/session.php`):**
   - `'driver' => env('SESSION_DRIVER', 'database')`, `'http_only' => true`, `'same_site' => 'lax'`.
   - **Recomendación para Producción:** Definir explícitamente en `.env` de producción `SESSION_SECURE_COOKIE=true` y `SESSION_ENCRYPT=true`.

---

### Área 4: Infraestructura, Backups y Rollback (Prioridad: Alta)
1. **Estado Actual en el Repositorio:**
   - No existe paquete de respaldos (`spatie/laravel-backup`) ni script automatizado de `mysqldump` o sincronización de `storage/` en `routes/console.php`.
2. **Plan de Backups y Rollback Obligatorio antes de Desplegar Bloques A–C:**
   - **Paso 1 (Snapshot Pre-Despliegue):** Antes de correr la migración del Bloque A (`persons.user_id`), ejecutar `mysqldump --single-transaction -u [user] -p [db] > backup_pre_launch_$(date +%F_%H%M).sql` y comprimir `storage/app/public` y `public/trip-documents`.
   - **Paso 2 (Backup Diario Programado):** Configurar un cron diario a las 03:00 AM en el servidor que respalde MySQL y la carpeta `storage/app/private/documents` hacia almacenamiento externo (AWS S3 o bucket compatible) con retención de 14 días.
   - **Paso 3 (Prueba de Restauración):** Importar el dump en una base de datos temporal de staging para comprobar que las relaciones `trips`, `person_trip` y `trip_documents` restauran sin errores de clave foránea.

---

### Área 5: Gestión y Seguridad de Archivos (Prioridad: Alta)
El barrido línea por línea reveló **tres hallazgos críticos adicionales** sobre cómo se guardan y sirven los archivos:
1. **`public/.htaccess` NO protege los documentos subidos en el Editor Pro (`SEC-03` Confirmado al 100 %):**
   - En `TripController::uploadAttachment` (`L824-827`), el archivo se guarda con `Storage::disk('public')->put('documents/' . $filename, ...)`.
   - El disco `public` escribe en `storage/app/public/documents/{uuid}.ext`, cuya URL pública a través del enlace simbólico es **`https://viantryp.com/storage/documents/{uuid}.ext`**.
   - Sin embargo, `public/.htaccess` (`L16`) tiene la regla `RewriteRule ^documents/ - [F,L]`, que solo bloquea `/documents/...`, **NO `/storage/documents/...`**! Por lo tanto, hoy los archivos subidos en el editor y en `public/trip-documents/` (Tryp IA) son accesibles directamente si se conoce su URL, incluso en Apache.
2. **Cabecera `Content-Disposition: inline` sin `X-Content-Type-Options` específico en descargas (`TripDocumentController.php:77-79`):**
   - `download()` devuelve `response()->file($filePath, ['Content-Disposition' => 'inline; filename="' . $document->original_name . '"'])`.
   - Si el nombre original contiene comillas (`"`) puede romper la cabecera HTTP, y solo los PDFs e imágenes seguras (`image/jpeg`, `image/png`, `image/webp`, `application/pdf`) deben servirse como `inline`; documentos `.doc`, `.docx` o `.txt` deben servirse con `response()->download(...)` (`attachment`).
3. **Extensión confiada al cliente (`N-09`):**
   - `TripController.php:823, 954`, `TripAiController.php:89` y `ProfileController.php:174, 212` usan `$file->getClientOriginalExtension()` en lugar de `$file->extension()` (derivado del MIME real detectado por `finfo`).

---

### Área 6: Webhooks de Paddle y Ciclo de Vida de Suscripciones (Prioridad: Alta)
Se inspeccionó `app/Providers/AppServiceProvider.php:33-40` y `app/Listeners/SyncPaddleSubscription.php:1-49`:
1. **Nombre Real del Listener:** El listener registrado es `App\Listeners\SyncPaddleSubscription` (no `PaddleEventListener`).
2. **Desconexión con Cashier (`N-01`):** `SyncPaddleSubscription` escucha `SubscriptionCreated`, `SubscriptionUpdated` y `SubscriptionCanceled` de `Laravel\Paddle\Events\*`. Esos eventos solo se disparan si `WebhookController` de Cashier encuentra un registro en la tabla `customers` (`$this->findBillable($data['customer_id'])`) y crea la fila en `subscriptions`. Como las migraciones de Cashier no están publicadas en `database/migrations/`, **ninguno de estos tres eventos llega a ejecutarse hoy**.
3. **Lagunas en `SyncPaddleSubscription.php` ante Eventos Reales de Paddle (`N-23` Nuevo):**
   - **Cancelación en Periodo de Gracia (`onGracePeriod`):** En la línea 23, `if ($event instanceof SubscriptionCanceled || !$subscription->active())` degrada al usuario a `User::PLAN_BASICO` **de inmediato** en cuanto solicita cancelar, aunque ya haya pagado el mes o el año completo y todavía esté dentro de su periodo pagado (`$subscription->onGracePeriod()`).En Cashier Paddle, `active()` devuelve `true` durante el periodo de gracia, pero como el `if` tiene `$event instanceof SubscriptionCanceled` primero, le quita el plan instantáneamente aunque le queden 29 días pagados.
   - **Pagos Fallidos (`past_due`) y Pausas:** Si una renovación falla, Paddle envía `subscription.past_due` (`SubscriptionUpdated`). Si se respeta `$subscription->active()` sin cortar en el primer intento fallido (o dando aviso), se evita perder clientes por fallos temporales de tarjeta.
   - **Idempotencia y Eventos Desordenados:** Al publicar las tablas de Cashier y verificar por API el estado actual de la suscripción cuando llega un webhook, se garantiza que un evento antiguo retrasado no sobrescriba uno más reciente.

---

### Área 7: Configuración de Producción y Superficie de Exposición (Prioridad: Alta)
1. **Fuga de mensajes de excepción internos en respuestas JSON (`N-24` Nuevo):**
   - En `TripController.php` líneas `937`, `982` y `1061`, los bloques `catch` devuelven `'exception' => $e->getMessage()` o `'error' => $response->body()` directamente al navegador en respuestas HTTP 500, lo que puede exponer rutas internas del servidor o detalles de SQL/API en producción.
2. **HTTPS, Proxies y Cabeceras (`AppServiceProvider.php:26`, `bootstrap/app.php:14, 26`):**
   - `URL::forceScheme('https')` está correctamente activo en producción.
   - `SecurityHeaders.php` aplica HSTS, `X-Frame-Options: SAMEORIGIN`, `X-Content-Type-Options: nosniff`, `Referrer-Policy` y `Permissions-Policy`.
   - No hay herramientas de depuración expuestas (Telescope, Horizon o Log Viewer no están instalados en `composer.json`).

---

### Área 8: Operación, Colas, Tareas Programadas y Correo (Prioridad: Media-Alta)
1. **Colas en Base de Datos (`config/queue.php` + `SyncUserRegistration.php` — `N-19` Nuevo):**
   - `QUEUE_CONNECTION` por defecto es `database`. El observer `UserObserver::created` despacha `SyncUserRegistration` a la cola (`ShouldQueue`) para notificar a Discord y añadir el usuario a Google Sheets.
   - Si en el servidor de producción no hay un proceso `Supervisor` ejecutando `php artisan queue:work --tries=3`, los trabajos quedan represados en la tabla `jobs`.
2. **Correos Síncronos que Bloquean la Petición HTTP:**
   - `RegisterController` (`WelcomeNotification`), `TripController::sendEmail`, `TripController::inviteCollaborator` y `ContactController::submit` envían correos por SMTP **de forma síncrona** durante la petición del usuario, añadiendo 1–3 segundos de espera o provocando errores si Hostinger Mail tarda en responder.
3. **Ausencia de Tareas Programadas (`routes/console.php`):**
   - `routes/console.php` solo tiene el comando de ejemplo `inspire`. Se debe programar un comando diario (`viantryp:maintenance`) que limpie archivos antiguos de `public/img-cache/` (>30 días) y degrade cuentas cuyo `trial_ends_at` haya vencido.

---

### Área 9: Privacidad, RGPD y Sub-encargados de Datos (Prioridad: Alta)
Al contrastar la página legal `resources/views/pages/privacy.blade.php` con el código real del proyecto, se detectó una **omisión legal importante (`N-18` Nuevo)**:
- En `resources/views/pages/privacy.blade.php` (líneas 76–85, sección *"Con quién compartimos tus datos"*), únicamente se declaran **AWS (Irlanda)**, **Paddle.com** y **Hostinger Mail**.
- Sin embargo, el código envía datos personales a otros tres terceros que **deben incluirse en la Política de Privacidad**:
  1. **Google Cloud / Google Gemini API y Google Places (`TripAiService`, `GooglePlacesController`):** Recibe documentos PDF/imágenes y textos de reservas subidos al asistente Tryp IA para estructurar el itinerario.
  2. **Google Workspace / Google Sheets (`GoogleSheetService` vía `SyncUserRegistration`):** Recibe nombre, correo, teléfono, país y plan de los usuarios registrados.
  3. **Discord (`DiscordService` vía `SyncUserRegistration`):** Recibe alertas internas de registro de nuevos usuarios para el equipo de soporte/operaciones.
- Además, la sección *"Tus derechos"* (`L94-95`) promete la eliminación permanente de la cuenta y la descarga de una copia del contenido (portabilidad), lo cual requiere implementar el botón de eliminación en `/profile` (**Bloque E**).

---

### Área 10: Calidad y Consistencia Funcional Web / PWA / Android (Prioridad: Alta)
El barrido cruzado entre la interfaz de planes (`upgrade-modal.blade.php`) y los controladores reveló una **contradicción funcional directa (`N-21` Nuevo)**:
- En `resources/views/components/upgrade-modal.blade.php:42` y en `app/Models/Trip.php:104, 144`, el **Plan Básico** ofrece **"Fotos Unsplash & GIFs ilimitados"** (`hasReachedUnsplashLimit()` y `hasReachedGiphyLimit()` devuelven `false`).
- Sin embargo, en `app/Http/Controllers/TripController.php` líneas **722 a 738** (`saveProState`), quedó una validación antigua hardcoded que **bloquea el guardado del itinerario con error 403 (`LIMIT_REACHED`) si un usuario del Plan Básico incluye más de 10 fotos de Unsplash o más de 10 GIFs**.
- **Acción:** Eliminar las líneas 722–739 en `TripController::saveProState` para que el editor cumpla lo que promete la página de precios y el modelo `Trip`.

---

## 3. RESUMEN CONSOLIDADO DE LOS 37 HALLAZGOS Y SU PLAN DE ACCIÓN EN 10 BLOQUES (A → J)

Con los **6 nuevos hallazgos del barrido final (`N-16` a `N-24`)**, el inventario queda 100 % cerrado en **37 puntos exactos**, agrupados en los mismos **10 Bloques de Ejecución (A → J)**:

| Bloque | Objetivo y Hallazgos que Resuelve | Archivos Exactos a Intervenir | Tiempo Estimado |
| :--- | :--- | :--- | :--- |
| **BLOQUE A** | **Aislamiento Multi-Tenant de Clientes y Catálogos (`SEC-02`, `N-16`, `N-17`)**<br>• Añadir `user_id` a `persons` y migrar desde la tabla pivote `person_trip` (clonando filas compartidas entre dos agencias).<br>• Filtrar `PersonController` y `TripController` (`L230, 398, 473`) por `user_id`.<br>• Eliminar/restringir `Route::resource('airlines')` (dejando solo `GET api/airlines`) y meter `api/places/details` dentro de `middleware('auth')`. | • Nueva migración `add_user_id_to_persons_table`<br>• `app/Models/Person.php`<br>• `app/Http/Controllers/PersonController.php`<br>• `app/Http/Controllers/TripController.php`<br>• `routes/web.php` | **Día 1 (4–6 h)** |
| **BLOQUE B** | **Blindaje de Pagos Paddle, Webhooks, Trials y Promo Codes (`N-01`, `SEC-01`, `BUS-01`, `N-03`, `N-23`)**<br>• **B1 (Primero):** Publicar migraciones de Cashier Paddle; verificar `transaction_id` vía API de Paddle en `syncPaddlePlan`; en `SyncPaddleSubscription`, no quitar el plan si `$subscription->onGracePeriod()` es `true`.<br>• **B2 (Segundo):** Bloquear cualquier llamada a `syncPaddlePlan` sin `transaction_id` válido.<br>• **B3 (Tercero):** Expirar trials vencidos en `User::getPlanLimits()`, unificar días de prueba y rotar `PROMO_CODE_*` con expiración. | • Migraciones Cashier (`customers`, `subscriptions`, `transactions`)<br>• `app/Http/Controllers/ProfileController.php`<br>• `app/Listeners/SyncPaddleSubscription.php`<br>• `app/Models/User.php`<br>• `resources/views/profile/index.blade.php`<br>• `resources/views/components/upgrade-modal.blade.php` | **Día 2 (5–6 h)** |
| **BLOQUE C** | **Seguridad de Archivos, SSRF, XSS, TLS IA y Fugas de Error (`SEC-03`, `SEC-05`, `N-05`, `N-07`, `N-09`, `N-12`, `N-20`, `N-24`)**<br>• Guardar documentos del editor y de Tryp IA en `Storage::disk('local')` (`storage/app/private/documents`) con lectura retrocompatible en `TripDocumentController::download`.<br>• Sanitizar nombre de archivo en `Content-Disposition` y forzar `attachment` salvo PDF/JPG/PNG/WEBP.<br>• Validar IP resuelta por DNS y `allow_redirects => false` en `ImageProxyController`.<br>• Escapar HTML con `esc()` y `safeHref()` en `public/js/trips/pro-viewer.js`.<br>• Quitar `CURLOPT_SSL_VERIFYPEER => false` en `TripAiService.php:79` y ocultar `$e->getMessage()` en JSON. | • `app/Http/Controllers/TripController.php`<br>• `app/Http/Controllers/TripDocumentController.php`<br>• `app/Http/Controllers/TripAiController.php`<br>• `app/Http/Controllers/ImageProxyController.php`<br>• `public/js/trips/pro-viewer.js`<br>• `app/Services/TripAiService.php` | **Día 3 (5–6 h)** |
| **BLOQUE D** | **Autenticación, Anti-Hijacking, CSRF y Rate Limiting (`N-02`, `N-08`, `N-13`, `N-14`, `N-22`)**<br>• Al vincular `google_id` a un usuario con `email_verified_at === null`, regenerar `password` aleatoria y marcar `email_verified_at = now()`.<br>• Validar cookie `g_csrf_token` en `handleGsiCallback`.<br>• Añadir `throttle` a `POST /reset-password`, `/api/image-proxy`, `/profile/plan/verify-code`, `/profile/plan/paddle-sync`, etc. | • `app/Http/Controllers/Auth/GoogleAuthController.php`<br>• `routes/web.php`<br>• `bootstrap/app.php` | **Día 4 (3–4 h)** |
| **BLOQUE E** | **Eliminación de Cuenta y Actualización de Política de Privacidad (`LEG-01`, `N-18`)**<br>• Implementar `DELETE /profile` con cancelación en Paddle, borrado de archivos y sesión.<br>• Actualizar `resources/views/pages/privacy.blade.php` para declarar el uso de Google Gemini API (Tryp IA) y Google Workspace / Discord en la gestión operativa. | • `app/Http/Controllers/ProfileController.php`<br>• `resources/views/profile/index.blade.php`<br>• `resources/views/pages/privacy.blade.php`<br>• `routes/web.php` | **Día 4 (3 h)** |
| **BLOQUE F** | **Separación Web vs. App Android/iOS — Modo Consumo Exclusivo (`LEG-02`, `CAP-01`)**<br>• Cuando `$isAppMode` sea verdadero (`request()->boolean('app') || request()->cookie('viantryp_app_mode') === '1'`), no cargar `paddle.js`, ocultar precios/botones de compra y mostrar el mensaje oficial aprobado:<br>*"La gestión de suscripciones y facturación no está disponible desde la aplicación. Para consultar y gestionar tu cuenta de facturación, accede a Viantryp desde un navegador web."* | • `resources/views/profile/index.blade.php`<br>• `resources/views/components/upgrade-modal.blade.php`<br>• `android/app/src/main/AndroidManifest.xml` | **Día 5 (2–3 h)** |
| **BLOQUE G** | **Service Worker v3 y Caché PWA (`PWA-01`)**<br>• Subir a `viantryp-v3` en `public/sw.js`, no cachear respuestas HTML de navegación autenticada y limpiar caché al cerrar sesión. | • `public/sw.js`<br>• `resources/views/layouts/app.blade.php` | **Día 5 (1.5 h)** |
| **BLOQUE H** | **Conversión Landing, Límites Reales de Unsplash/GIFs y Cuotas (`UX-01`, `UX-02`, `N-06`, `N-21`, `DATA-02`)**<br>• Redirigir invitados en `/planes-redirect` hacia `/register`.<br>• Quitar el bloqueo erróneo de `>10` fotos Unsplash y GIFs en `TripController::saveProState` (`L722-739`).<br>• Corregir doble incremento de IA y añadir TTL de 6 h en `CachedFlight` para vuelos futuros. | • `routes/web.php`<br>• `app/Http/Controllers/TripController.php`<br>• `app/Http/Controllers/TripAiController.php`<br>• `app/Http/Controllers/FlightLookupController.php` | **Día 5 (2–3 h)** |
| **BLOQUE I** | **Higiene de Git, Credenciales y Mantenimiento (`SEC-04`, `N-04`, `N-15`, `N-19`)**<br>• Desindexar `viantryp-release-official.keystore` y retirar `voyager_key.pem`.<br>• Añadir comando programado en `routes/console.php` para limpieza de caché y vencimiento de trials. | • `.gitignore`<br>• `android/app/build.gradle`<br>• `routes/console.php` | **Día 6 (2 h)** |
| **BLOQUE J** | **Batería de Pruebas Automatizadas y Verificación Multi-Canal (`QA-01`)**<br>• Reparar `ExampleTest` e implementar los tests de regresión para validar Web, PWA y modo App (`?app=1`). | • `tests/Feature/*` | **Día 6 (3 h)** |
