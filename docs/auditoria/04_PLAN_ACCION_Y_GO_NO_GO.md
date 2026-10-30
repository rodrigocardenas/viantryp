# AUDITORÍA INTEGRAL VIANTRYP — ETAPA 4: PLAN DE ACCIÓN, QA Y CRITERIOS GO / NO-GO

**Fecha de Ejecución:** Octubre 2026  
**Entorno Auditado:** Código fuente completo de Viantryp (Laravel 12, Blade, JS, Capacitor Android, PWA)  
**Modalidad:** Solo Lectura (Consolidación de las 4 etapas de auditoría e integración estratégica)

---

## 1. RESUMEN EJECUTIVO Y DICTAMEN DE LANZAMIENTO

### Dictamen Oficial de Lanzamiento Comercial: **NO-GO (Lanzamiento Web Bloqueado)**

| Canal / Plataforma | Veredicto | Justificación Técnica & Comercial |
| :--- | :---: | :--- |
| **Plataforma Web (Desktop & Móvil)** | **NO-GO** | **Bloqueado por 3 fallos P0:** (1) Escalada gratuita de suscripción sin pago (`syncPaddlePlan`), (2) Fuga masiva de clientes entre agencias (`PersonController`), y (3) Plan Pro vitalicio gratuito al vencer el trial. |
| **PWA (Progressive Web App)** | **CONDICIONAL** | El manifiesto y service worker están activos, pero falta página offline fallback y resolución de los P0 del backend. |
| **Android (Google Play)** | **CONGELAR DESPLIEGUES** | La versión 1.0.10 está aprobada en Google Play, pero expone Paddle dentro del WebView (riesgo de expulsión por billing policy) y carece de eliminación de cuenta. **No desplegar cambios en la web que rompan la app**. |
| **Apple App Store (iOS)** | **NO PREPARADO (0%)** | Falta paquete nativo `@capacitor/ios`, falta *Sign in with Apple* (bloqueante por Guideline 4.8), falta eliminación de cuenta y falta soporte IAP. |

---

## 2. AUDITORÍA DE SEO TÉCNICO, RENDIMIENTO Y ANALÍTICA

### A. Diagnóstico SEO Técnico
- **Title Tag:** En `landing.blade.php`, `<title>Viantryp | Home</title>` es deficiente. No contiene palabras clave sobre "itinerarios de viaje", "agencias" ni la propuesta de valor.
- **Meta Description:** **Completamente ausente**. No existe `<meta name="description">` en la landing ni en las páginas públicas.
- **Canonical Tags:** **Ausentes**. No existe `<link rel="canonical">`, provocando canibalización potencial entre `viantryp.com`, `www.viantryp.com` y parámetros de campaña.
- **Open Graph & Twitter Cards:** **Totalmente ausentes**. Al compartir enlaces de Viantryp en WhatsApp, LinkedIn, X o Slack, no se genera imagen destacada, título ni descripción.
- **Robots.txt & Sitemap:**
  - `public/robots.txt` no bloquea rutas privadas (`/trips/`, `/profile/`, `/api/`).
  - No existe `sitemap.xml` en la raíz ni está indexado.

### B. Diagnóstico de Rendimiento y Core Web Vitals
- **Fuentes Render-Blocking:** En `landing.blade.php` se importan sincrónicamente 4 familias de fuentes de Google Fonts con 17 variantes de peso, sumadas a FontAwesome CDN. Esto retrasa el *First Contentful Paint (FCP)*.
- **Scripts No Minificados:** `public/js/trips/pro-editor.js` (248 KB) y `public/js/trips/pro-viewer.js` (189 KB) se cargan como scripts planos sin pasar por el empaquetador Vite, pesando ~440 KB de JavaScript crudo.

### C. Analítica y Embudo de Medición: **Cero Visibilidad**
- No existe Google Analytics 4 (GA4), ni Google Tag Manager (GTM), ni Meta Pixel, ni herramientas de mapas de calor (Clarity/Hotjar).
- Si se lanza tráfico publicitario hoy, el equipo no tendrá forma de saber cuántos visitantes llegaron, dónde rebotaron, si intentaron registrarse o en qué paso abandonaron el checkout.

---

## 3. MATRIZ DE PRUEBAS AUTOMATIZADAS (QA)

### Estado de las Pruebas Existentes en `tests/`:

| Test / Archivo | Tipo | Estado | Observación |
| :--- | :---: | :---: | :--- |
| `tests/Unit/TripItemIdGenerationTest.php` | Unit | **Válido** | Verifica formato de IDs para vuelos, hoteles y notas en itinerarios. |
| `tests/Feature/TripCreateTest.php` | Feature | **Roto / Obsoleto** | Llama a `route('trips.create')` que fue eliminada de `routes/web.php` en favor de `trips.create-pro`. |
| `tests/Feature/TripEditTest.php` | Feature | Requiere DB Testing | Depende de conexión MySQL a `viantryp_testing`. |
| `tests/Feature/TripPreviewTest.php` | Feature | Requiere DB Testing | Valida token público de previsualización. |
| `tests/Feature/TripCollaboratorDocumentTest.php` | Feature | Requiere DB Testing | Valida permisos de colaboradores sobre documentos. |
| **Pruebas de Registro & Login** | Feature | **Ausentes** | No hay tests para validaciones de rate limiting, login ni Google OAuth. |
| **Pruebas de Webhooks de Paddle** | Integration | **Ausentes** | No hay tests que simulen eventos `SubscriptionCreated` o `SubscriptionCanceled`. |
| **Pruebas de Aislamiento Multi-tenant** | Feature | **Ausentes** | No hay tests que verifiquen que un usuario no pueda ver clientes ajenos en `persons`. |

---

## 4. REGISTRO CONSOLIDADO DE HALLAZGOS (P0 A P3)

| ID | Prioridad | Módulo | Problema Identificado | Impacto Comercial / Técnico | Criterio de Aceptación para Resolución |
| :---: | :---: | :--- | :--- | :--- | :--- |
| **SEC-01** | **P0** | **Pagos** | Escalada gratuita en `ProfileController::syncPaddlePlan`. | Pérdida total de ingresos. Cualquier usuario puede auto-activarse planes ilimitados gratis. | Solo webhooks verificados de Paddle (`PADDLE_WEBHOOK_SECRET`) pueden cambiar el plan en DB. |
| **SEC-02** | **P0** | **Multi-tenant** | Fuga global de clientes en `PersonController::index()`. | Violación masiva de GDPR. Agencias pueden robar clientes de competidores. | Columna `user_id` añadida y todas las consultas filtradas por `Auth::id()`. |
| **BUS-01** | **P0** | **Monetización** | Plan Pro vitalicio por falta de degradación tras trial de 30 días. | Cero conversión a pago. Todos los registrados quedan en Plan Pro para siempre. | Usuario se registra en `plan = 'básico'` con `trial_ends_at = +30d`. Límites revierten a básico al día 31. |
| **LEG-01** | **P0** | **Políticas** | Inexistencia de endpoint para eliminar cuenta. | Incumplimiento de Apple App Store (Guideline 5.1.1 v), Google Play y RGPD Art. 17. | Endpoint `POST /profile/account/delete` con confirmación y borrado en cascada. |
| **LEG-02** | **P0** | **Google Play** | Paddle en WebView de Android para compras de suscripción. | Riesgo de suspensión y expulsión de Google Play por eludir Google Play Billing. | Ocultar modal de pago en app Android o implementar Google Play Billing nativo. |
| **SEC-03** | **P1** | **Archivos** | Adjuntos privados guardados en disco público de almacenamiento. | En servidores Nginx, cualquiera con el link accede a pasaportes/vouchers sin login. | Mover almacenamiento a `storage/app/private/` y servir vía streaming autenticado. |
| **SEC-04** | **P1** | **Seguridad** | Keystore y contraseñas de Google Play en texto plano en el repositorio. | Si el código se filtra, terceros pueden firmar apps maliciosas con la identidad de Viantryp. | Extraer contraseñas a variables de entorno y mover keystore fuera del git público. |
| **SEC-05** | **P1** | **SSRF** | `ImageProxyController` vulnerable a DNS Rebinding y DoS sin throttling. | Posible SSRF a metadatos de nube y saturación de disco en `img-cache/`. | Aplicar `throttle:60,1`, validar IP previa y restringir a dominios de imagen conocidos. |
| **UX-02** | **P1** | **Funnel** | Botón "Probar gratis" en Landing redirige a `/login` en vez de `/register`. | Abandono inmediato de visitantes interesados en la landing page. | Cambiar enlace a `route('register')`. |
| **IOS-01** | **P1** | **iOS Prep** | Ausencia de Sign in with Apple y paquete nativo `@capacitor/ios`. | Rechazo garantizado del 100% en Apple App Store. | Integrar `@capacitor-community/apple-sign-in` y compilar proyecto Xcode. |
| **MOB-01** | **P2** | **Android** | Botón atrás de Android cierra la app por falta de listener en Capacitor. | Mala experiencia de usuario en Android. | Implementar `App.addListener('backButton')` para retroceso en historial. |
| **INT-01** | **P2** | **Deep Links** | Incoherencia en `assetlinks.json` (`com.viantryp.www.twa` vs `com.viantryp.app`). | Enlaces de viajes compartidos no abren la app instalada en móviles Android. | Sincronizar package name en `routes/web.php` y `public/.well-known/assetlinks.json`. |
| **UX-01** | **P2** | **Auth** | Validación DNS en formulario de login (`email:rfc,dns`). | Bloqueo imprevisto de usuarios legítimos por latencias DNS. | Cambiar regla en `LoginController` a `'email' => 'required|string|email'`. |
| **BIL-01** | **P2** | **Billing** | Cancelación inmediata en Paddle sin agotar el periodo ya pagado. | Quejas y reembolsos forzados de clientes con planes activos. | Configurar Paddle con `effective_from: 'next_billing_period'`. |
| **SEO-01** | **P2** | **SEO** | Falta de Meta Description, Open Graph, Twitter Cards y Sitemap. | Cero presencia en redes sociales e indexación orgánica deficiente. | Añadir meta tags completos en `landing.blade.php` y generar `sitemap.xml`. |
| **ANL-01** | **P2** | **Analítica** | Ausencia total de herramientas de analítica y eventos de conversión. | Ceguera total sobre el rendimiento de campañas y embudo de registro. | Integrar Google Tag Manager / GA4 con eventos `sign_up`, `trial_start` y `checkout`. |
| **QA-01** | **P3** | **QA** | Pruebas unitarias obsoletas en `TripCreateTest`. | La suite de tests de Laravel falla en ejecución continua. | Actualizar llamadas a `route('trips.create-pro')`. |

---

## 5. PLAN DE ACCIÓN PRIORIZADO (BLOQUES DE TRABAJO A AL G)

### Bloque A: Imprescindible Antes del Lanzamiento Web (Bloqueante Launch)
- [ ] **A.1 (SEC-01):** Eliminar la lógica de auto-asignación en `ProfileController::syncPaddlePlan`. Asegurar que solo el webhook firmado de Paddle (`SyncPaddleSubscription`) actualice planes en base de datos.
- [ ] **A.2 (SEC-02):** Crear migración para agregar `user_id` a la tabla `persons`. Modificar `PersonController` para filtrar estrictamente por `Auth::id()`.
- [ ] **A.3 (BUS-01):** Modificar `ProfileController::chooseInitialPlan` para que registre al usuario con `plan = 'básico'` y `trial_ends_at = now()->addDays(30)`. Así, al día 31 la lógica de `User.php` restringe automáticamente los límites a menos que pague.
- [ ] **A.4 (UX-02):** Corregir en `resources/views/landing.blade.php` los enlaces de los botones de planes hacia `route('register')`.
- [ ] **A.5 (SEC-03):** Mover el almacenamiento de documentos adjuntos a un disco privado (`storage/app/private/`) y eliminar el riesgo de Nginx.
- [ ] **A.6 (UX-01):** Eliminar la regla `dns` en `LoginController.php`.

### Bloque B: Imprescindible para Experiencia y Cumplimiento
- [ ] **B.1 (LEG-01):** Implementar endpoint `POST /profile/account/delete` con borrado seguro de datos.
- [ ] **B.2 (SEC-05):** Aplicar `throttle:60,1` y validación estricta de dominios permitidos en `ImageProxyController`.
- [ ] **B.3 (BIL-01):** Configurar la cancelación en Paddle para que se aplique al fin del ciclo de facturación (`next_billing_period`).
- [ ] **B.4 (COM-01):** Crear plantilla y evento de email para confirmación de suscripción y aviso 3 días antes de vencimiento de prueba.

### Bloque C: Conversión, Crecimiento y Analítica
- [ ] **C.1 (SEO-01):** Redactar Meta Description atractiva, títulos optimizados, etiquetas Open Graph (imagen para WhatsApp/Facebook) y generar `public/sitemap.xml`.
- [ ] **C.2 (ANL-01):** Instalar Google Tag Manager / GA4 configurando los eventos clave: `lead_landing`, `sign_up_completed`, `trip_created`, `trial_activated` y `purchase_completed`.

### Bloque D: PWA y Experiencia Móvil
- [ ] **D.1 (PWA-01):** Crear página `public/offline.html` y precachearla en `sw.js` para que si se corta la conexión se muestre una interfaz institucional limpia.
- [ ] **D.2 (INT-01):** Corregir package name en `assetlinks.json` para activar Android App Links.

### Bloque E: Capacitor & Android (Versión de Google Play)
- [ ] **E.1 (LEG-02):** Condicionar el modal de upgrade: si la app corre en Android nativo (`is-viantryp-app`), ocultar el formulario de tarjeta de crédito Paddle y mostrar un mensaje indicando que la suscripción se gestiona desde la web para no violar la política de Google Play Billing.
- [ ] **E.2 (MOB-01):** Implementar listener `App.addListener('backButton')` para controlar el botón de retroceso sin cerrar la app.
- [ ] **E.3 (SEC-04):** Sacar el archivo keystore de producción de la raíz del proyecto y mover credenciales a variables de entorno.

### Bloque F: Preparación para Apple App Store (Futuro)
- [ ] **F.1:** Instalar `@capacitor/ios` y generar proyecto nativo en Xcode.
- [ ] **F.2:** Implementar autenticación nativa *Sign in with Apple* (`@capacitor-community/apple-sign-in`).
- [ ] **F.3:** Integrar StoreKit (In-App Purchases) para cobros en iOS.

### Bloque G: Mejoras Posteriores al Lanzamiento
- [ ] **G.1:** Empaquetar y minificar `pro-editor.js` y `pro-viewer.js` mediante Vite para reducir ~250 KB de peso.
- [ ] **G.2:** Almacenamiento offline de itinerarios en PWA mediante IndexedDB.
- [ ] **G.3:** Actualizar suite de pruebas de regresión en `tests/Feature/`.

