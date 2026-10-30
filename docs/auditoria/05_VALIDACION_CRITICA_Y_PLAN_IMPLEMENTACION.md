# VALIDACIÓN CRÍTICA DE LA AUDITORÍA Y PLAN DE IMPLEMENTACIÓN SEGURO

**Fecha:** 2026-10-30
**Alcance:** revisión crítica de los informes 01–04 e INFORME_FINAL. Modo estrictamente de solo lectura.
**No se modificó** código, migraciones, configuración, dependencias, base de datos ni credenciales. Este es el único archivo creado.

## 0. Cómo leer este informe: niveles de evidencia

| Etiqueta | Significado |
| :--- | :--- |
| **INSPECCIONADO** | Leí el código o la configuración y sigo el flujo hasta el recurso. |
| **EJECUTADO** | Lo ejecuté. **En esta fase no ejecuté ninguna prueba funcional** (ver §0.1). |
| **PENDIENTE** | Requiere entorno, dato de producción o cuenta que no tengo. |
| **FUENTE EXTERNA** | Afirmación sobre políticas de tiendas, obtenida de búsqueda web (fuentes secundarias). Debe confirmarse en el texto oficial. |

Estados de hallazgo: **Confirmado**, **Parcialmente confirmado**, **No reproducido**, **Incorrecto**, **Pendiente de evidencia**.

### 0.1 Limitaciones del entorno
- **MySQL local no disponible** (conexión rechazada en `127.0.0.1:3306`). No pude ejecutar la suite PHPUnit ni consultar el esquema real. Todo lo que dependa del esquema real de la base queda **PENDIENTE**.
- `voyager_key.pem`: el sistema operativo **denegó el acceso**. No lo eludí. No sé qué contiene ni si está versionado. Git no lo muestra como rastreado.
- No tengo acceso a producción. `.env` es de desarrollo (`APP_ENV=local`, `APP_DEBUG=true`, `QUEUE_CONNECTION=sync`). Nada de lo que veo allí describe producción.
- No tengo acceso a Google Play Console, Paddle, el panel del hosting ni GitHub (visibilidad del repositorio).
- Las políticas de tiendas provienen de búsqueda web y fuentes secundarias.

---

## 1. RESUMEN EJECUTIVO

**Conclusión principal:** la auditoría previa acertó en la mayoría de los riesgos de fondo, pero contiene **errores de diagnóstico y recomendaciones que, aplicadas literalmente, podrían romper el cobro o dejar datos expuestos.** Hallé además **hallazgos nuevos de severidad alta que la auditoría no vio**.

### 1.1 Correcciones a la auditoría original (lo más importante)

1. **La recomendación A.1 («eliminar `syncPaddlePlan` y usar solo el webhook») es peligrosa tal como estaba escrita.** El código no vincula el checkout de Paddle con ningún registro `Customer` de Cashier. Por eso el webhook probablemente **no activa nunca el plan**, y el endpoint del navegador es hoy el único mecanismo de activación (ver N-01). Si se elimina sin arreglar antes la vinculación, **ningún cliente que pague obtendría su plan**.
2. **«Cabeceras de seguridad ausentes» es incorrecto.** Existe el middleware `SecurityHeaders` con HSTS, CSP, `X-Frame-Options`, `nosniff` y otras. Su debilidad real es que la CSP permite `'unsafe-inline'` y `'unsafe-eval'`.
3. **INT-01 («assetlinks apunta al paquete equivocado») es incorrecto.** El archivo estático `public/.well-known/assetlinks.json` contiene `com.viantryp.app` con 5 huellas, incluidas las de Play App Signing. La ruta de `web.php` es solo un respaldo. El problema real es otro: el `AndroidManifest.xml` publicado **no declara ningún `intent-filter` de App Links**.
4. **MOB-01 («el botón atrás cierra la app») está sobreestimado.** Por defecto Capacitor navega hacia atrás en el historial del WebView y solo sale en la raíz. Lo que sí falta es cerrar modales con el botón atrás. Debe verificarse en un dispositivo.
5. **UX-02 («la landing manda a /login») tiene el mecanismo equivocado.** `Authenticate::redirectTo` redirige a `route('home')`, no a `/login`. El visitante vuelve a la landing sin pasar por el registro. Solo afecta a 2 de 9 CTAs.
6. **BIL-01 («cancelación inmediata») solo aplica a la ruta de degradación a Básico.** No existe una opción de cancelar al final del periodo.
7. **LEG-02, Sign in with Apple e IAP estaban formulados como «rechazo inevitable».** Las reglas tienen excepciones y matices (§7).
8. **Telescope y Horizon se citaron como fortaleza de monitoreo.** Son también superficie de ataque (N-06).

### 1.2 Estado de los hallazgos P0/P1 originales

| ID | Estado | Prioridad validada |
| :--- | :--- | :---: |
| SEC-01 Plan modificable desde el navegador | **Confirmado** (rama de upgrade) | **P0** |
| SEC-02 Fuga/contaminación de clientes | **Confirmado y ampliado** (peor que lo descrito) | **P0** |
| BUS-01 Pro vitalicio tras la prueba | **Confirmado por código** (no ejecutado) | **P0 comercial** |
| LEG-01 Sin eliminación de cuenta | **Confirmado** | **P1** (P0 para iOS) |
| LEG-02 Cobro Paddle en Android | **Posiblemente aplicable, pendiente** | **P1** |
| SEC-03 Documentos en disco público | **Parcialmente confirmado** | **P2** (P1 si Nginx) |
| SEC-04 Keystore y claves | **Confirmado y peor** (en historial de Git) | **P1** (P0 si el repo es público) |
| SEC-05 SSRF del proxy de imágenes | **Parcialmente confirmado** (por lectura de código) | **P1** |

### 1.3 Hallazgos nuevos de mayor impacto
- **N-01:** el pipeline de pagos Paddle→BD probablemente no funciona; las migraciones de Cashier no están en el repo.
- **N-02:** secuestro previo de cuentas (*pre-hijacking*) por registro sin verificación de email más vinculación con Google por email.
- **N-03:** códigos promocionales estáticos, filtrados en el historial de Git, **idénticos a los valores del `.env` local**, y endpoint sin límite de intentos.
- **N-05:** posible XSS almacenado en el itinerario compartido (indicio fuerte, requiere validación).

### 1.4 Recomendación
Sigue vigente **NO-GO** para el lanzamiento comercial web. El orden de trabajo cambia respecto al plan original: **primero entorno de pruebas y respaldo, después datos/permisos/pagos juntos, y los cambios de planes y prueba gratuita solo después de dejar funcionando la vinculación con Paddle.**

---

## 2. ARQUITECTURA REAL VERIFICADA (Fase 1)

| # | Pregunta | Hallazgo (INSPECCIONADO) |
| :-: | :--- | :--- |
| 1 | Relación usuarios/agencias/clientes/viajes/colaboradores | **No existe entidad agencia ni workspace.** «Agencia» es un atributo del usuario (`account_type`, `agency_name`). `trips.user_id` es el dueño. `trip_collaborators` da acceso por viaje (roles `editor`/`viewer`, con `accepted_at`). `persons` **no tiene dueño** y se relaciona con viajes por la tabla `person_trip`. |
| 2 | Propiedad de recursos | Viajes: `canEdit`/`canView` en `Trip` (dueño o colaborador aceptado). Documentos: `user_id` y viaje. **Personas: ninguna.** |
| 3 | Web / AJAX / API | Todo está en `routes/web.php` (CSRF activo). `routes/api.php` no tiene rutas activas. Hay endpoints AJAX tipo `api/*` dentro de `web.php`. |
| 4 | Autenticación por ruta | Grupo `auth` para el panel. Públicas: `trips/share/{token}`, `api/places/*`, `api/image-proxy`, `auth/google/native`, descarga de documentos (autoriza dentro del método). |
| 5 | Planes, prueba, suscripciones | Campos `plan` y `trial_ends_at` en `users`. `User::getPlanLimits()` y `getEffectivePlanAttribute()` elevan a «avanzado» solo si `plan === básico` **y** la prueba está vigente. |
| 6 | Sincronización Paddle→BD | Listener `SyncPaddleSubscription` conectado a eventos de Cashier. **Pero ver N-01.** El front llama además a `/profile/plan/paddle-sync`. |
| 7 | Documentos | Subidas al disco `public` (`documents/{uuid}.ext` y `trip-documents/{random40}.ext`). Descarga autorizada por `TripDocumentController::download`. |
| 8 | Proxy de imágenes | `ImageProxyController` descarga URLs externas, las guarda en `public/img-cache/` y redirige al archivo estático. |
| 9 | Caché del service worker | Solo cachea estáticos (`/css`, `/js`, `/images`, `/icons`, `/fonts`, `/build`). **No cachea HTML ni datos privados** (positivo). Network-first. `skipWaiting` y `clients.claim`. |
| 10 | Capacitor↔web | `server.url = https://viantryp.com`: WebView remoto. `MainActivity` solo registra `GoogleAuth`. |
| 11 | Nativo vs web remota | Nativo: paquete `com.viantryp.app`, versionCode 22, plugins, splash, manifest. Web remota: toda la UI, login por cookies de sesión, pagos, lógica. |
| 12 | Pruebas | 7 archivos. **Al menos 3 usan rutas que no existen** (`trips.create`, `trips.preview`). **No pude ejecutar ninguna** (BD no disponible). |
| 13 | Respaldos, logs, recuperación | **No hay nada documentado ni configurado en el repo** (sin paquete de backup, sin Sentry/Flare). Logs `stack`. Telescope instalado. **Pendiente: infraestructura real.** |

### 2.1 Dependencias críticas entre componentes
- **Cashier Paddle 2.8.1:** el repo **no contiene** las migraciones `customers`, `subscriptions`, `subscription_items` y `transactions` (no están publicadas en `database/migrations`). Ver N-01.
- `vendor/` no está versionado y el middleware `VerifyWebhookSignature` del vendor contiene **comentarios añadidos a mano**. Alguien tocó el vendor. La lógica visible parece estándar, pero un `composer install` limpio no reproducirá esos comentarios.
- La web remota es compartida por navegador, PWA y Android. **Cualquier despliegue web afecta a Android al instante.**

---

## 3. VERIFICACIÓN HALLAZGO POR HALLAZGO (Fase 2)

### SEC-01 — Plan modificable desde el navegador
- **Estado:** **Confirmado** (ruta de ascenso). Parcialmente mitigado el 27-oct (commit `f302a7f`).
- **Archivo:** `ProfileController::syncPaddlePlan` y `updatePlan`.
- **Evidencia:** `syncPaddlePlan` toma `price_id` del cuerpo de la petición. Si coincide con un precio configurado en `config/plans.php` (identificadores que además están en el HTML de la página), hace `$user->update(['plan' => $matchedPlan])`. No consulta a Paddle ni verifica una transacción. El commit `f302a7f` bloqueó la asignación por nombre de plan (ahora devuelve 403/422), pero **no** el camino por `price_id`.
- **Escenario:** un usuario autenticado hace `POST /profile/plan/paddle-sync` con `{"price_id":"<id de Negocios>"}` y un token CSRF válido (visible en la página). Resultado: plan de pago sin pagar.
- **Impacto:** pérdida de ingresos; permisos de pago concedidos sin pago.
- **Prioridad:** P0.
- **Corrección:** ver bloque B y D. **No eliminar el endpoint sin antes arreglar la vinculación (N-01).** Opción puente: verificar server-side con la API de Paddle el `transaction_id` (estado `completed`, `custom_data.user_id`, `price_id`) antes de activar.
- **Regresión:** alta (es la única vía de activación hoy).
- **Pruebas:** petición manipulada → rechazada; checkout sandbox real → plan activo; evento duplicado → sin cambios.
- **Pendiente:** ¿`f302a7f` está desplegado en producción?

### SEC-02 — Clientes entre agencias
- **Estado:** **Confirmado y ampliado.** El diagnóstico original se quedó corto.
- **Archivos:** `PersonController` (todas las acciones), `TripController::storePro`, `inlineUpdate`, `updateClient`, `Person`, migración `create_persons_table`.
- **Evidencia:**
  - `persons.email` es **único global** y la tabla no tiene `user_id`.
  - `PersonController` hace `Person::all()`, `findOrFail($id)`, `update($request->all())` y `delete()` sin comprobar propiedad. Las rutas `persons` y `persons-agents` están en el grupo `auth`, **accesibles a cualquier usuario autenticado** aunque la interfaz no enlace a ellas.
  - **Ruta real de impacto (la más grave):** `updateClient` e `inlineUpdate` buscan `Person::where('email', $email)->first()`, **adjuntan a mi viaje a una persona que pertenece a otro usuario** y **le modifican el nombre**. `storePro` usa `Person::updateOrCreate(['email'=>…])` con el mismo efecto. Un usuario que escribe el correo de un cliente de otra agencia **lee y sobrescribe su registro compartido**.
  - `Person::create($request->all())` en `PersonController`: asignación masiva sobre `$fillable` (`name,email,phone,type`); el riesgo es acotado por `$fillable`.
- **Escenario real:** la Agencia B crea un viaje con el email `cliente@x.com` que ya es cliente de la Agencia A. Se asocia a la persona de A, y B puede renombrarla; eso cambia el nombre en los viajes de A.
- **Impacto:** violación de confidencialidad B2B y RGPD; corrupción de datos entre cuentas.
- **Prioridad:** P0.
- **Corrección:** modelo **contactos por propietario** (`user_id` en `persons`, índice único compuesto `(user_id, email)`). **No usar `agency_id`: no existe esa entidad.** Migración con **conservación de datos**: una persona vinculada a viajes de varios dueños debe **duplicarse por dueño** y re-vincular `person_trip`. Ver decisión D-2.
- **Regresión:** alta. Afecta a creación de viajes, edición inline y modales del dashboard.
- **Pruebas:** B no puede leer, editar, borrar ni adjuntar contactos de A; mismo email en dos cuentas coexiste; migración conserva todos los vínculos.

### BUS-01 — Plan Pro tras vencer la prueba
- **Estado:** **Confirmado por código; no ejecutado.**
- **Archivo:** `ProfileController::chooseInitialPlan`, `User`.
- **Evidencia:** `chooseInitialPlan` guarda `plan = 'avanzado'` con `trial_ends_at = now()+30d`. En todo `app/` y `routes/` **ninguna** pieza degrada el plan al vencer (el scheduler `routes/console.php` solo tiene `inspire`). `getPlanLimits()` solo eleva por prueba cuando `plan === básico`, de modo que con `plan = avanzado` el límite Pro es permanente. La propia vista de perfil dice «Se requiere activar suscripción al finalizar el mes», lo que **contradice** el comportamiento.
- **Matiz que la auditoría omitió:** el diseño correcto ya existe a medias (el modelo eleva por prueba si el plan es básico). La corrección más pequeña es guardar `plan = básico` + `trial_ends_at`. **Pero** hay lecturas directas de `$user->plan` en varias vistas (modal de upgrade, perfil, Discord, Sheets) que habría que revisar.
- **Datos existentes:** los usuarios ya registrados tienen `plan = avanzado` **sin distinguir** prueba, pago real o código promocional. **No se puede degradar en bloque** sin reconstruir quién pagó (D-3).
- **Incoherencia comercial:** la landing dice **7 días**, el código otorga **30**.
- **Prioridad:** P0 comercial.

### LEG-01 — Eliminación de cuenta
- **Estado:** **Confirmado.** No hay ruta, método ni interfaz de eliminación. Solo existe `deleteAvatar`.
- **Complejidad que el informe previo omitió:** el dueño de viajes con colaboradores (transferencia o borrado), suscripción activa en Paddle, copias de datos personales en Discord y Google Sheets (`SyncUserRegistration`), documentos en disco, y obligaciones fiscales (los datos de facturación los retiene Paddle como Merchant of Record).
- **Prioridad:** P1 (P0 para iOS). **Pendiente:** qué declaró en Play Console sobre eliminación de cuenta.

### LEG-02 — Android y cobro
- **Estado:** **Posiblemente aplicable, pendiente de verificar.**
- **Evidencia:** el modal de upgrade abre `Paddle.Checkout.open` dentro del WebView de Capacitor. **FUENTE EXTERNA:** la política de Pagos de Google Play exige Google Play Billing para funciones digitales y suscripciones dentro de la app. Hay excepciones y programas regionales (opciones de facturación alternativa/enlace externo en ciertos países). La app **ya fue aprobada**, lo que no garantiza cumplimiento futuro.
- **Matiz:** el producto es SaaS B2B/B2C de planificación; decidir si el modelo encaja en una excepción o si se opta por «solo consumo de cuenta» es **una decisión de negocio y legal**, no solo técnica.
- **Prioridad:** P1. Se escala si Google lo señala.

### SEC-03 — Documentos privados
- **Estado:** **Parcialmente confirmado.**
- **Evidencia:** las subidas van al disco `public`. Los nombres son **no adivinables** (UUID y 40 caracteres aleatorios). La respuesta de subida entrega la ruta **autorizada** `documents.download`, no la ruta directa. `.htaccess` bloquea solo `storage/documents/` y **solo en Apache**. **Las subidas del chat con IA van a `storage/trip-documents/`, que no está bloqueado en ningún servidor.**
- **Escenario:** quien conozca la URL directa (por ejemplo, tras revocar a un colaborador) accede sin autenticación. No es enumerable.
- **Prioridad:** P2; sube a P1 si producción usa Nginx **y** las URLs directas se filtran. **Pendiente:** servidor web de producción.
- **Corrección:** disco privado + descarga por controlador, **con migración de los archivos existentes** (y las rutas guardadas en BD).

### SEC-04 — Keystore y credenciales
- **Estado:** **Confirmado y peor de lo descrito.**
- **Evidencia (Git):** `viantryp-release-official.keystore` está **rastreado** en Git (`git ls-files`) a pesar de que `.gitignore` contiene `*.keystore`: se versionó antes de ignorarlo. `android/app/build.gradle` está rastreado y contenía las contraseñas de firma desde el commit `ee0bb05` (v11). El remoto es `github.com/rodrigocardenas/viantryp`. Quien tenga el repositorio tiene el keystore **y** sus contraseñas.
- **Matiz que cambia la gravedad:** `assetlinks.json` incluye huellas de **Play App Signing**. Si la app está inscrita en Play App Signing, ese keystore sería la **clave de subida**, recuperable mediante soporte de Google, y no la clave de firma definitiva. **Pendiente de confirmar en Play Console.**
- **Prioridad:** P1; **P0 si el repositorio es o fue público.**
- **Corrección:** confirmar visibilidad del repo; rotar contraseñas y solicitar reinicio de la clave de subida si procede; mover secretos a `keystore.properties` fuera de Git. **Reescribir el historial de Git es una decisión del propietario** (D-5).

### SEC-05 — Proxy de imágenes (SSRF)
- **Estado:** **Parcialmente confirmado (por lectura de código; no explotado).**
- **Evidencia:** el filtro solo bloquea IPs **literales** privadas y algunos sufijos de nombre. Un dominio controlado por el atacante que resuelva a una IP interna (o redirija a ella) lo elude: el cliente HTTP de Laravel sigue redirecciones por defecto. El cuerpo descargado se guarda (si pesa más de 500 bytes) en `public/img-cache/*.jpg` y se sirve de vuelta, lo que **permite leer la respuesta**. La ruta **no tiene throttle** y no limita el tamaño descargado.
- **Escenario:** consulta a servicios internos o metadatos de nube desde el servidor, y llenado de disco. La explotabilidad real depende de la red del hosting (**pendiente**).
- **Prioridad:** P1.

### UX-01 — Validación DNS en login
- **Estado:** **Confirmado.** `LoginController`, `ForgotPasswordController` (envío y reinicio) usan `email:rfc,dns`; el login además exige `password min:8`, por lo que **cuentas con contraseñas más cortas no pueden entrar**.
- **Prioridad:** P2.

### UX-02 — Enlaces de la landing
- **Estado:** **Parcialmente confirmado; mecanismo corregido.** Líneas 6491 y 6516: «Probar 7 días gratis» apunta a `plans.redirect` (protegida). El visitante vuelve a la landing. Otros 5 CTA sí llevan a registro.
- **Prioridad:** P2.

### BIL-01 — Cancelación
- **Estado:** **Parcialmente confirmado.**
- **Evidencia:** solo la ruta «degradar a Básico» cancela en Paddle con `effective_from: immediately`. No hay opción de cancelar al final del periodo. **Riesgo adicional:** localiza suscripciones con `search => $user->email`; no consta que la búsqueda de Paddle sea coincidencia exacta, así que **podría cancelar suscripciones de otros clientes con correos parecidos** (N-10, pendiente de contrastar con la documentación de Paddle en sandbox).
- **Prioridad:** P2 (P1 si se confirma el riesgo adicional).

### MOB-01 — Botón atrás
- **Estado:** **Parcialmente confirmado / sobreestimado.** No hay listener propio. Capacitor retrocede por historial por defecto. Falta cerrar modales y paneles con el botón atrás. **Pendiente: prueba en dispositivo.**
- **Prioridad:** P3. **Requiere** nueva versión de Android solo si se implementa con `@capacitor/app` desde el binario. El plugin ya está instalado y el código puede ir en la web remota (**cambio web que afecta a Android al instante**).

### INT-01 — App Links
- **Estado:** **Incorrecto como se formuló.** Ver §1.1. Hallazgo reformulado: la app no declara `intent-filter` con `autoVerify`; los enlaces `https://viantryp.com/...` abren el navegador. Implementarlo exige **nueva compilación y publicación de Android**.
- **Prioridad:** P3.

### PWA-01 — Página offline
- **Estado:** **Confirmado** (no hay `offline.html`; el `fetch` devuelve `undefined` si falla la red y no hay caché). **Positivo:** no se cachea contenido privado.
- **Prioridad:** P3.

### SEO-01 — SEO
- **Estado:** **Confirmado para la landing.** `<title>Viantryp | Home</title>`; sin `meta description`, Open Graph, Twitter ni canonical. Las páginas legales (`pages/_layout`) **sí** tienen meta description. `robots.txt` permite todo y no hay `sitemap.xml`. La landing se renderiza en servidor (positivo).
- **Prioridad:** P2. **No medí Core Web Vitals**; no ejecuté Lighthouse.

### ANL-01 — Analítica
- **Estado:** **Confirmado en el código** (ningún script en las vistas). **Pendiente:** si Cloudflare Web Analytics se inyecta en el borde (la CSP lo permite).
- **Prioridad:** P2.

### QA-01 — Pruebas
- **Estado:** **Confirmado y ampliado.** `TripCreateTest` usa `trips.create`; otros tests usan `trips.preview`. Ninguna de esas rutas existe en `web.php`. **No hay pruebas** de autenticación, webhooks, aislamiento de personas ni planes. **No ejecuté la suite.**
- **Prioridad:** P3 como defecto; **P1 como bloqueo del plan**, porque sin red de seguridad no se debe refactorizar permisos y pagos.

---

## 4. HALLAZGOS NUEVOS (Fase 3)

| ID | Hallazgo | Estado | Prio | Evidencia y escenario |
| :-: | :--- | :---: | :-: | :--- |
| **N-01** | **Vinculación Paddle↔usuario y migraciones de Cashier ausentes.** | Parcialmente confirmado (BD **pendiente**) | **P0/P1** | `grep` no halla `createAsCustomer`, ni `billable_id`, ni creación de `Customer` en `app/`. El checkout se abre desde el navegador con `customer.email` y `customData.user_id`. El handler de Cashier `handleSubscriptionCreated` hace `findBillable($data['customer_id'])` y **sale en silencio si no hay fila en `customers`**. Las migraciones de Cashier (`customers`, `subscriptions`, `subscription_items`, `transactions`) no están en el repo. **Inferencia (no ejecutada):** el webhook no activa planes; `SyncPaddleSubscription` no se dispara; el perfil `try/catch` oculta el error. Esto explica por qué existe el endpoint del navegador. Verificar en sandbox con BD aislada. |
| **N-02** | **Pre-hijacking de cuenta.** | Confirmado por código | **P1** | El registro **no verifica el email** y entra directo. `GoogleAuthController` (web y nativo) vincula por email: `User::where('email')` y le asigna `google_id`, **sin comprobar que la cuenta existente estaba verificada**. Un atacante registra `victima@x.com` con su contraseña; cuando la víctima entra con Google, queda dentro de **la cuenta del atacante**, que conoce la contraseña. También: `acceptInvite` valida la invitación contra el email **no verificado** (suplantación de invitaciones). |
| **N-03** | **Códigos promocionales.** | Confirmado | **P1** | `verifyPlanCode` otorga **plan permanente** con `trial_ends_at = null`, sin throttle. Los códigos estuvieron **fijos en `config/plans.php` en el historial de Git** (commits previos a `f302a7f`). Comprobé (sin imprimir valores) que **los valores del `.env` local coinciden con los códigos filtrados** — dato de desarrollo; **producción pendiente**. Si producción usa los mismos, **están públicos para quien tenga el repo**. Rotar y limitar intentos. |
| **N-04** | **Secretos históricos en Git.** | Confirmado | **P1** | La clave de Carto estuvo codificada como valor por defecto (commits históricos). Contraseñas de keystore (ver SEC-04). Rotar; el cambio actual solo limpia el HEAD. |
| **N-05** | **Posible XSS almacenado en el itinerario compartido.** | **Indicio, requiere validación** | **P1 (provisional)** | `pro-viewer.js`: **0 llamadas** a funciones de escape y al menos 25 interpolaciones directas de campos de texto (título, nombre, descripción, ubicación) en plantillas HTML (conteo por patrón, no auditoría exhaustiva). La página pública inyecta `proState` con `@json` (seguro como dato), pero se renderiza como HTML. La CSP permite scripts en línea. **Falta confirmar** que el texto llega sin sanear y se asigna con `innerHTML`. Vectores: colaborador editor contra dueño/cliente, dueño contra sus clientes, y **contenido generado por IA a partir de documentos subidos** (inyección indirecta). |
| **N-06** | **Telescope/Horizon en producción.** | Pendiente | P2 | Acceso por **lista de correos** (gate). `TELESCOPE_ENABLED` vale `true` por defecto. Con registro sin verificar (N-02), un correo de administrador **no registrado** podría ser registrado por un tercero. Telescope puede almacenar datos personales. Verificar variables en producción. |
| **N-07** | **Firma del webhook con «fallo abierto».** | Confirmado en el vendor | P1 | El middleware se aplica **solo si `PADDLE_WEBHOOK_SECRET` tiene valor**. Si en producción queda vacío, el webhook acepta eventos sin firma. Debe haber un control que falle si falta el secreto. |
| **N-08** | **Places público.** | Confirmado | P2 | `api/places/details` está fuera de `auth` (throttle 30/min/IP) y gasta la clave de Google del servidor: abuso de costo. |
| **N-09** | **Paddle en producción desde desarrollo.** | Confirmado (local) | P2 | `.env` local tiene `PADDLE_SANDBOX=false`: el entorno de desarrollo apunta a **Paddle real**. Riesgo de cargos reales en pruebas. Contradice «no pagos reales» y obliga a usar claves sandbox en el entorno de pruebas. |
| **N-10** | **Cancelación por búsqueda difusa.** | Pendiente | P2 | Ver BIL-01. |
| **N-11** | **Enlaces públicos sin revocación ni caducidad.** | Confirmado | P2 | Solo existe `generate-share-token` (reutiliza el token existente). No hay ruta para revocar o rotar. El `share_token` usa `sha256(id.time.rand())`: no adivinable en la práctica, pero con entropía menor que un token aleatorio criptográfico. El `short_token` de 8 caracteres usa `Str::random`. |
| **N-12** | **`searchUnsplash` sin autorización de viaje.** | Confirmado | P3 | `Trip::find($tripId)` sin `canEdit`: revela si un viaje ajeno alcanzó su límite. |
| **N-13** | **CSP débil y CSRF exento.** | Confirmado | P2 | `'unsafe-inline'` y `'unsafe-eval'` en `script-src`; `img-src https:`. `auth/google/*` está **exento de CSRF**: el callback de Google Identity Services (POST) debe validar `g_csrf_token`; no lo comprobé (pendiente). |
| **N-14** | **Límites de plan evaluados con el plan del actor.** | Confirmado | P3 | `saveProState` aplica límites con el plan del usuario que guarda, no el del dueño del viaje. Un colaborador con otro plan obtiene límites distintos. |
| **N-15** | **Sin respaldos documentados y datos personales en terceros.** | Pendiente | P1 | Sin evidencia de backup, restauración o monitoreo de errores. Discord y Google Sheets reciben nombre, email, país (`SyncUserRegistration`); debe constar en la política de privacidad y en el flujo de eliminación. |

### 4.1 Verificado y correcto (para no rehacer trabajo)
- **Viajes:** `destroy`, `updateStatus`, `inlineUpdate`, `updateCode`, `generateShareToken`, `sendEmail`, `bulkDelete`, `bulkDuplicate`, invitaciones y colaboradores comprueban propiedad o permiso correctamente. `saveProState`, `uploadAttachment`, `uploadCover` y `getProData` usan `canEdit`/`canView`.
- **Documentos:** `download` y `destroy` autorizan; un token público solo da acceso a documentos de **su** viaje.
- **Google nativo:** verifica el `id_token` con la biblioteca de Google.
- **Sesión:** `http_only`, `same_site=lax`, regeneración al iniciar sesión, `invalidate` y regenerar token al salir. Límite de intentos de login (5/15 min) y registro.
- **SQL:** consultas por Eloquent; no hallé inyección.
- **Restablecimiento de contraseña:** token con caducidad de 60 minutos y límite de peticiones.

---

## 5. CUSTOMER JOURNEY VALIDADO (Fase 4)

| Etapa | Individual | Agencia | Colaborador | Destinatario |
| :--- | :--- | :--- | :--- | :--- |
| Visitante→registro | 5 CTA a registro; 2 CTA de plan vuelven a la landing | igual | Invitación exige login previo | Enlace público directo |
| Verificación de correo | **No existe** (N-02) | **No existe** | `acceptInvite` depende del email no verificado | n/a |
| Onboarding | Modal personal/agencia → Pro 30 días | igual | No pasa por onboarding de plan | n/a |
| Primer viaje | Modal rápido → editor | Cliente por email → **contamina `persons` (SEC-02)** | Edita según rol | n/a |
| Compartir | Enlace permanente, **sin revocar** | igual | n/a | Ve el viaje; **posible XSS (N-05)** |
| Prueba→pago | **Pro vitalicio (BUS-01)**; pago depende del navegador (SEC-01) | igual | límites con el plan del actor (N-14) | n/a |
| Cancelación | Sin cancelar al final del periodo | igual | puede abandonar el viaje | n/a |
| Eliminar cuenta | **No existe** | **No existe** (y hay colaboradores y clientes) | no puede eliminarse sin afectar viajes | n/a |

Estados sin salida o confusos: cuenta registrada que nunca elige plan inicial; plan «avanzado» que no se puede distinguir de un plan pagado; mensaje de 7 días frente a 30; contraseñas cortas antiguas que no pueden iniciar sesión.

---

## 6. PWA Y CAPACITOR (Fase 5)

| Tema | Hallazgo |
| :--- | :--- |
| Manifest/SW | Válidos. 8 iconos presentes. `scope /`, `start_url /?app=1`. SW v6 con limpieza de cachés antiguas. |
| Privacidad en caché | **Correcto:** HTML y datos autenticados no se cachean. |
| Offline | Sin página de respaldo ni datos offline. Decisión de producto (D-9): los itinerarios suelen consultarse **en viaje**, sin red. |
| Actualización | `skipWaiting`+`claim` sin aviso de recarga: puede mezclar JS nuevo con HTML antiguo durante una sesión. Riesgo bajo; probar. |
| Sesión en Android | Cookie de sesión + `remember` + cookie `viantryp_app_mode`. Login nativo por `id_token`. |
| Pagos en Android | Paddle.js en el WebView (LEG-02). |
| Deep links | No implementados en el binario. |
| Paquete/permisos | `com.viantryp.app`; solo permiso `INTERNET`; `allowBackup=false`; `FileProvider` no exportado. |

### 6.1 Cambios web frente a cambios que exigen nueva versión de Android

| Requiere solo despliegue web (**afecta a Android de inmediato**) | Requiere compilar y publicar Android |
| :--- | :--- |
| Seguridad de planes, personas, archivos | `intent-filter` de App Links |
| Prueba gratuita, eliminación de cuenta | Google Play Billing nativo |
| Ocultar el checkout en Android (`window.Capacitor`) | Cualquier plugin nuevo |
| Cierre de modales con botón atrás (plugin `@capacitor/app` ya incluido) | Cambios de manifest o permisos |
| SEO, analítica, PWA | Sign in with Apple (solo iOS) |

**Regla:** probar cada cambio web en el WebView de Android **antes** de desplegar. Nadie debe cambiar `capacitor.config.json`, el paquete ni las claves de firma en esta fase.

---

## 7. POLÍTICAS DE LAS TIENDAS (Fase 6)

*Clasificación basada en búsquedas web con fuentes secundarias. Confirmar con los textos oficiales antes de decidir.*

| Requisito | Tienda | Clasificación | Notas |
| :--- | :---: | :--- | :--- |
| Google Play Billing para suscripciones digitales | Google | **Posiblemente aplicable, pendiente** | Un checkout web dentro de un WebView figura como riesgo. Hay excepciones y programas regionales; el modelo exacto debe analizarse. La aprobación previa no garantiza cumplimiento. |
| Eliminación de cuenta (in-app y URL web) | Google | **Confirmado y aplicable** (hay creación de cuenta) | Pendiente: lo declarado en Data Safety. |
| Eliminación de cuenta desde la app | Apple 5.1.1(v) | **Confirmado y aplicable** (si hay iOS) | Debe iniciarse dentro de la app; desactivar no basta. |
| Inicio de sesión equivalente en privacidad | Apple 4.8 | **Aplicable si hay Google login** | Se exige una opción equivalente en privacidad; **Sign in with Apple es la vía habitual, no la única formal.** No es «rechazo inevitable»: no he evaluado la app. |
| IAP para contenido digital | Apple 3.1.1 | **Posiblemente aplicable** | Hay cambios recientes en EE. UU. que permiten enlaces externos sin autorización; **no se aplican igual en otras regiones.** |
| Ocultar el checkout web resuelve el cumplimiento | Ambas | **No presuponer** | Las reglas sobre mencionar precios y enlazar a la web también pueden aplicar. |
| iOS en Capacitor | Apple | **Mejora/trabajo futuro** | Mantener separado del mantenimiento de Android. |

**iOS:** no existe `@capacitor/ios`, carpeta `ios`, ni cuenta de Apple verificada. Requiere Mac, Xcode y dispositivo real. **Nada de esto se evaluó ni se puede evaluar aquí.**

---

## 8. MATRIZ DE RIESGOS ACTUALIZADA

| Prio | IDs | Tema |
| :-: | :--- | :--- |
| **P0** | SEC-01, SEC-02, BUS-01, N-01 | Pagos, aislamiento de contactos, monetización, pipeline de Paddle |
| **P1** | SEC-04/N-04, SEC-05, N-02, N-03, N-05*, N-07, N-15, LEG-01, LEG-02 | Secretos, SSRF, cuentas, códigos, XSS*, webhook, respaldos, tiendas |
| **P2** | SEC-03, UX-01, UX-02, BIL-01, SEO-01, ANL-01, N-06, N-08, N-09, N-11, N-13 | |
| **P3** | MOB-01, INT-01, PWA-01, QA-01, N-12, N-14 | |

*Provisional hasta validar. **Corregidos respecto al informe original:** INT-01 (incorrecto), MOB-01 (rebajado), LEG-02 (rebajado a P1 pendiente), cabeceras (incorrecto), SEC-03 (rebajado a P2).

---

## 9. PLAN DE CORRECCIÓN (Fase 7)

### Principios de ejecución
1. **Nada se despliega sin un entorno de pruebas aislado y respaldo verificado.**
2. **Cada migración es reversible y se ensaya con una copia de la BD real.**
3. **Cada despliegue web se prueba en el WebView de Android** (el cambio afecta a la app publicada).
4. **B y D comparten código (`ProfileController`, `User`, `upgrade-modal`) y no se ejecutan en paralelo.**

### Bloque A — Preparación, copias y entorno de pruebas
- **Objetivo:** poder cambiar con seguridad y volver atrás.
- **Tareas:** respaldo completo (BD + `storage`) **con restauración ensayada**; entorno de staging con BD aislada y **claves de Paddle sandbox**; `.env` de desarrollo con `PADDLE_SANDBOX=true`; poner en verde la suite (rutas obsoletas, factories); registrar la versión desplegada en producción y qué commits contiene (¿`f302a7f`?); configurar un registro de errores; **confirmar visibilidad del repositorio**.
- **Archivos:** `tests/*`, `phpunit.xml`, `.env.example`, infraestructura.
- **Dependencias:** ninguna.
- **Riesgos:** bajo.
- **Aceptación:** restauración de respaldo ensayada y documentada; `php artisan test` en verde en BD aislada; staging con checkout sandbox operativo.
- **Reversión:** n/a.

### Bloque B — Datos, permisos y pagos críticos (B1 → B2 → B3 en este orden)
- **B1 (vinculación Paddle, N-01 + N-07):** verificar en sandbox si el webhook activa planes. Publicar migraciones de Cashier (**revisar primero el esquema real de producción**); crear/vincular `Customer` por usuario; abrir el checkout con datos de vinculación (`custom_data`) o desde el servidor; fallar si falta `PADDLE_WEBHOOK_SECRET`; idempotencia y eventos desordenados.
- **B2 (SEC-01):** activar el plan **solo** por webhook verificado o por verificación server-side de la transacción. Cierre del endpoint del navegador **después** de que B1 funcione.
- **B3 (SEC-02):** `persons.user_id`, índice `(user_id,email)`, política de acceso, y duplicación de personas compartidas conservando `person_trip`.
- **Archivos:** `ProfileController`, `AppServiceProvider`, `SyncPaddleSubscription`, `upgrade-modal.blade.php`, `config/cashier.php`, `PersonController`, `TripController` (storePro/inlineUpdate/updateClient), `Person`, nuevas migraciones.
- **Dependencias:** A. **No ejecutar B2 antes que B1. No ejecutar B3 en paralelo con migraciones de Cashier.**
- **Riesgos:** **alto** (pagos y datos de clientes).
- **Aceptación:** pruebas de la §10 en verde, incluidas manipulación de petición, webhook inválido/duplicado y aislamiento entre dos agencias, más un pago sandbox completo hasta activar el plan.
- **Reversión:** migraciones con `down()` y respaldo previo; el endpoint antiguo se desactiva con interruptor, no se borra de inmediato.

### Bloque C — Archivos, autenticación y servidor
- **C1:** verificación de email (con tolerancia para cuentas existentes) y **bloqueo de vinculación de Google a cuentas no verificadas** (N-02).
- **C2:** rotar y limitar intentos de códigos promocionales; rotar claves expuestas (Carto, códigos, contraseñas de keystore) (N-03, N-04).
- **C3:** SSRF del proxy: resolución previa de IP, sin redirecciones, límites de tamaño y tipo, throttle (SEC-05).
- **C4:** documentos a almacenamiento privado, **migrando archivos existentes y rutas en BD**; cubrir `trip-documents` (SEC-03).
- **C5:** validar y corregir XSS (escape en `pro-viewer.js`/editor, endurecer CSP) (N-05, N-13).
- **C6:** `LoginController`/`ForgotPassword` sin DNS (UX-01); Places y Telescope/Horizon (N-06, N-08).
- **Dependencias:** A. C1 y C2 son independientes entre sí. **C4 no debe correr a la vez que despliegues de B3.**
- **Aceptación:** pruebas de §10 y revisión manual de XSS con cargas de prueba en staging.
- **Reversión:** feature flags; la migración de archivos conserva el original hasta validar.

### Bloque D — Planes, prueba gratuita y suscripciones (**después de B**)
- Definir reglas (D-3, D-4); corregir `chooseInitialPlan`; **plan de migración para usuarios ya existentes**; lecturas directas de `$user->plan` en vistas; cancelación al final del periodo; comunicaciones de pago y fin de prueba.
- **Aceptación:** prueba termina según la regla definida (con reloj simulado); plan se mantiene tras pago sandbox; cancelación respeta el periodo.
- **Riesgo:** alto para usuarios existentes.
- **Reversión:** conservar columnas previas, ejecutar en modo reporte antes de cambiar datos.

### Bloque E — Eliminación de cuenta y privacidad
- Flujo con confirmación, cancelación en Paddle, tratamiento de viajes con colaboradores (transferir o eliminar), borrado de archivos, **datos en Discord/Sheets**, retención legal. Texto de política de privacidad actualizado.
- **Dependencias:** B1 (Paddle), B3 (contactos), C4 (archivos).
- **Aceptación:** cuenta eliminada no puede entrar; sin datos personales huérfanos; viajes con colaboradores se resuelven según la decisión D-6.

### Bloque F — Estabilidad funcional y customer journey
- CTA de la landing; coherencia 7/30 días; avisos; estados vacíos; contraseñas cortas en login; revocación de enlaces compartidos; límites según plan del dueño (N-14).

### Bloque G — PWA y Capacitor Android
- **Solo web (sin tocar Android):** página offline, aviso de actualización, cierre de modales con botón atrás, ocultar/ajustar el checkout dentro de Android **según la decisión D-7**.
- **Requiere nueva versión de Android:** App Links, Billing nativo, cambios de manifest.
- **Aceptación:** matriz de dispositivos y pruebas manuales en el WebView; la versión publicada sigue funcionando tras cada despliegue web.

### Bloque H — SEO, analítica y rendimiento
- Meta, Open Graph, canonical, sitemap, `robots.txt` (bloquear rutas privadas); analítica con consentimiento (si aplica); empaquetado y minificado de scripts; fuentes.
- **Dependencias:** ninguna crítica. Puede ir en paralelo con G (solo web) pero **no con despliegues de B/C**.

### Bloque I — iOS (independiente)
- `@capacitor/ios`, proyecto Xcode, login equivalente, eliminación de cuenta en app, decisión de compras, Safe Areas, pruebas en dispositivo real. **No empezar hasta cerrar A–E.**

### Bloque J — Regresión completa y publicación controlada
- Suite completa, pruebas manuales por canal (web, PWA, Android), despliegue gradual con respaldo, monitoreo reforzado las primeras 72 horas y plan de reversión.

### Tareas que **no** deben ejecutarse simultáneamente
| Conflicto | Motivo |
| :--- | :--- |
| B2 antes de B1 | Se apagaría la única vía de activación |
| B3 y migraciones de Cashier a la vez | Dos migraciones sobre BD de producción y riesgo de orden |
| B y D en paralelo | Ambos tocan `ProfileController`, `User` y `upgrade-modal` |
| C4 (mover archivos) y despliegues de B | Rutas de archivo y pruebas inconsistentes |
| Despliegues web sin probar en WebView de Android | Afectan a la app publicada |
| Reescribir el historial de Git durante cualquier otro bloque | Invalida ramas y despliegues |

---

## 10. PRUEBAS DE REGRESIÓN (Fase 8)

*Estado de todas: **NO EJECUTADO** (diseño).*

| ID | Tipo | Verifica | Prio |
| :-: | :--- | :--- | :-: |
| T-01 | Autorización | Agencia B no lista, ve, edita ni borra contactos de A | P0 |
| T-02 | Autorización | Escribir el email del cliente de A en un viaje de B **no** adjunta ni modifica a la persona de A | P0 |
| T-03 | Integración | Migración de `persons` conserva todos los vínculos `person_trip` | P0 |
| T-04 | Autorización | `POST /profile/plan/paddle-sync` con `price_id` ajeno **no** cambia el plan | P0 |
| T-05 | Integración | Webhook con firma inválida → 403; sin secreto configurado → error de arranque | P0 |
| T-06 | Integración | Webhook válido activa el plan correcto y vincula usuario y cliente | P0 |
| T-07 | Integración | Evento duplicado y desordenado → mismo estado final | P0 |
| T-08 | Unitaria | Prueba gratuita termina a los N días con reloj simulado | P0 |
| T-09 | Autorización | Documento sin sesión ni token → 403; token de otro viaje → 403 | P1 |
| T-10 | Autorización | Archivo directo en `/storage/...` no accesible (Apache y Nginx) | P1 |
| T-11 | Autorización | Colaborador `viewer` no puede editar; `editor` sí; revocado pierde acceso | P1 |
| T-12 | Integración | Enlace público no expone email del dueño ni datos privados | P1 |
| T-13 | Seguridad | Proxy rechaza hosts que resuelven a IP privada y redirecciones internas | P1 |
| T-14 | Seguridad | Texto con HTML en título/descripción se muestra escapado en el viewer | P1 |
| T-15 | Autenticación | Registro con email ajeno no permite que Google se vincule a cuenta no verificada | P1 |
| T-16 | Autenticación | Login, logout, reinicio de contraseña (con y sin DNS) | P1 |
| T-17 | Integración | Códigos promocionales: límite de intentos y rotación | P1 |
| T-18 | Funcional | Crear/editar/duplicar/eliminar viaje y guardar `pro_state` | P1 |
| T-19 | Integración | Eliminación de cuenta: viajes con colaboradores, Paddle, archivos, sesiones | P1 |
| T-20 | Sandbox | Pago, renovación, fallo, cancelación al final del periodo | P1 |
| T-21 | Manual | Web, PWA instalada y WebView de Android tras cada despliegue | P1 |
| T-22 | Manual | Botón atrás: cierra modal, retrocede, sale en raíz | P3 |
| T-23 | Manual | PWA sin conexión muestra página de respaldo | P3 |

---

## 11. CRITERIOS GO / NO-GO (Fase 9)

**Regla:** un P0/P1 confirmado sobre datos privados, autorización o pagos **permanece abierto** hasta que exista **evidencia de pruebas** (T-xx en verde en staging) o una mitigación documentada y validada. No se aprueba por promedios ni por «parece solucionado».

| Criterio | Tipo | Condición de aprobación (evidencia) |
| :--- | :---: | :--- |
| Respaldo y restauración ensayados | Técnico | Acta de restauración reciente |
| Aislamiento de contactos | Legal/Técnico | T-01, T-02, T-03 verdes |
| Planes solo por pago verificado | Técnico/Comercial | T-04…T-07 verdes y pago sandbox completo |
| Prueba gratuita acotada | Comercial | T-08 verde; plan de migración de usuarios existentes ejecutado en modo reporte |
| Cuentas no secuestrables | Técnico | T-15 verde |
| Secretos rotados | Técnico | Códigos, claves de API y firma rotados; repo privado confirmado |
| XSS descartado o corregido | Técnico | T-14 verde |
| Archivos privados | Técnico | T-09, T-10 verdes |
| SSRF mitigado | Técnico | T-13 verde |
| Eliminación de cuenta | Legal | T-19 verde y política actualizada |
| Páginas legales coherentes con el producto | Legal | Revisión del propietario/asesor |
| **Web** | | Todos los anteriores + T-16, T-18, T-20 |
| **PWA** | | Web + T-21 (PWA) |
| **Android** | | Web + T-21 (WebView) + decisión D-7 documentada. La aprobación en Play es un hito, **no** evidencia técnica. |
| **iOS** | | Bloque I completo y revisión en dispositivo real; sin garantía de aprobación |

---

## 12. DECISIONES QUE REQUIEREN AL PROPIETARIO

| # | Decisión | Por qué importa |
| :-: | :--- | :--- |
| **D-1** | ¿Producción ya incluye el commit `f302a7f`? ¿Qué rama/commit está desplegado? | Cambia el estado real de SEC-01 |
| **D-2** | ¿Modelo de contactos por usuario (recomendado) o introducir `agency/workspace` real con miembros? | Define el esquema; la segunda opción es un cambio de producto grande |
| **D-3** | Usuarios actuales con plan `avanzado`: ¿quién pagó? ¿cómo distinguir prueba, pago y código? | Sin esto no se puede degradar sin dañar a clientes que pagaron |
| **D-4** | Duración de la prueba: ¿7 o 30 días? ¿Con o sin tarjeta? ¿Qué pasa al vencer (Básico, bloqueo de edición)? ¿Qué pasa con los viajes que excedan el límite? | Define reglas y mensajes |
| **D-5** | ¿El repositorio de GitHub es o fue público? ¿Se reescribe el historial? ¿La app usa Play App Signing? | Define la gravedad de SEC-04 y de la rotación |
| **D-6** | Al eliminar una cuenta con colaboradores: ¿transferir viajes, eliminarlos o exigir resolución previa? | Define el flujo de eliminación |
| **D-7** | Android: ¿Google Play Billing, programa de facturación alternativa, o solo consumo (comprar en la web)? Requiere validación legal. | Define el esfuerzo y el riesgo de cumplimiento |
| **D-8** | ¿Servidor web de producción (Apache/Nginx), proveedor, respaldo actual, Cloudflare? | Determina SEC-03, SEC-05 y la restauración |
| **D-9** | ¿Los itinerarios deben consultarse sin conexión (en viaje)? | Define el alcance de la PWA |
| **D-10** | ¿Se exigirá verificación de email a cuentas existentes? | Impacto en usuarios actuales |
| **D-11** | ¿Retención de datos y qué obligaciones fiscales o legales aplican? | Flujo de eliminación |
| **D-12** | ¿Quién tiene acceso a Telescope/Horizon y están activos en producción? | N-06 |
| **D-13** | ¿Se desea analítica? ¿Con banner de consentimiento? ¿Qué herramienta (ya incluida en Cloudflare o nueva)? | Privacidad y costo |
| **D-14** | ¿Se publicará iOS y cuándo? | Prioridad del Bloque I |
| **D-15** | Acceso para validar: cuenta sandbox de Paddle, copia anonimizada de la BD, dispositivo Android de prueba | Permite ejecutar T-xx |

---

## 13. QUÉ NO PUDE COMPROBAR (resumen)
- Esquema y datos reales de BD (incluidas tablas de Cashier); suite PHPUnit; Lighthouse y Core Web Vitals.
- Producción: variables, servidor web, respaldos, Telescope, versión desplegada.
- Contenido y seguimiento de `voyager_key.pem` (acceso denegado).
- Comportamiento en dispositivo Android y en iOS.
- Texto oficial de las políticas de Google y Apple.
- Explotación real de SEC-05 y N-05 (solo inspección).

