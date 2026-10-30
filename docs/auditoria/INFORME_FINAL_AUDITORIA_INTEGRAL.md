# INFORME FINAL DE AUDITORÍA INTEGRAL, CUSTOMER JOURNEY Y PREPARACIÓN PARA EL LANZAMIENTO DE VIANTRYP

**Fecha:** Octubre 2026  
**Auditor:** Equipo Multidisciplinario Sénior de Arquitectura, Ciberseguridad, SaaS, UX/UI, QA y Plataformas Móviles  
**Entorno de Ejecución:** Código fuente de producción local en `c:\laragon\www\viantryp`  
**Modalidad:** Inspección Técnica en Modo Solo Lectura (Sin modificaciones destructivas)

---

## 1. RESUMEN EJECUTIVO

Viantryp cuenta con un producto funcionalmente potente y visualmente sofisticado para el diseño y presentación de itinerarios de viaje interactivos. Su editor modular (`pro-editor.js`), la previsualización compartida (`pro-viewer.js`) y la integración con Unsplash y Google Places ofrecen una experiencia superior a la media de la industria turística.

Sin embargo, tras auditar exhaustivamente el código y sus configuraciones, **la recomendación inequívoca es NO-GO para el lanzamiento comercial de la plataforma web en su estado actual**.

### Principales Fortalezas:
1. **Editor de Itinerarios Pro:** Motor interactivo muy rico y completo para armar propuestas de viaje con vuelos, hoteles, notas, transporte y galerías.
2. **Presentación Pública de Itinerarios:** El visor accesible mediante token público (`/trips/share/{token}`) es el principal generador de valor ("Momento Aha!") y está muy bien resuelto para clientes finales en móvil y escritorio.
3. **App Android Aprobada en Google Play:** Build 22 / v1.0.10 publicada exitosamente con SDK 36 e inicio de sesión nativo de Google.
4. **Stack Tecnológico Moderno:** Laravel 12.0, Tailwind CSS v4, Vite 7 y PHP 8.2+.

### Principales Riesgos que Bloquean el Lanzamiento:
1. **Quiebre del Modelo de Negocio (Plan Pro Vitalicio Gratuito):** Todo usuario que se registra obtiene 1 mes gratis de Plan Pro, pero el sistema nunca degrada su cuenta al expirar los 30 días. Los usuarios disfrutan de límites Pro para siempre sin pagar.
2. **Escalada Gratuita de Planes (Paddle Bypass):** El endpoint `/profile/plan/paddle-sync` actualiza la base de datos a planes de pago sin verificar recibo, firma ni webhook de Paddle.
3. **Fuga Masiva de Clientes entre Agencias (IDOR Global):** La tabla `persons` no tiene aislamiento multi-tenant por usuario/agencia. Cualquier agencia puede ver, modificar o borrar los clientes y contactos de agencias competidoras.
4. **Violación de Políticas de Google Play:** La app Android cobra suscripciones con tarjeta de crédito mediante Paddle.js dentro del WebView, poniendo la app en riesgo de suspensión inmediata por infringir la directiva de Google Play Billing.
5. **Incumplimiento de Requisitos de Eliminación de Cuenta:** No existe opción para que el usuario borre su cuenta, violando las políticas de Google Play, Apple App Store y el RGPD.

### Cinco Acciones Más Importantes para Desbloquear el Lanzamiento Web:
1. Corregir el onboarding para que el usuario permanezca en `plan = 'básico'` con `trial_ends_at = +30d` (activando la restricción automática al día 31).
2. Eliminar la actualización de planes en `ProfileController::syncPaddlePlan` y condicionar el cambio de plan exclusivamente a la recepción del webhook firmado de Paddle.
3. Añadir `user_id` a la tabla `persons` y aislar todas las consultas de contactos por usuario autenticado.
4. Reparar los enlaces de los botones de planes en la landing page para que envíen a `/register` en vez de botar al usuario al `/login`.
5. Mover los documentos adjuntos de viajes al disco privado (`storage/app/private/`) para que ningún servidor web los sirva públicamente sin login.

---

## 2. ARQUITECTURA ACTUAL

Viantryp opera como un monolito híbrido en **Laravel 12.0** que provee las vistas web (Blade), las APIs AJAX internas, y sirve el contenido de forma remota a la aplicación Android generada con **Capacitor 8.5.2**.

- **Frontend:** Vistas Blade del lado del servidor, estilizadas con Tailwind CSS v4 y Vite 7. La lógica interactiva de itinerarios reside en dos módulos JavaScript vanilla de gran tamaño: `pro-editor.js` (248 KB) y `pro-viewer.js` (189 KB).
- **Backend & APIs:** Controladores estándar de Laravel en `app/Http/Controllers/`. Rutas definidas en `routes/web.php` (las rutas en `routes/api.php` están inactivas).
- **Base de Datos:** MySQL con almacenamiento estructurado y columnas JSON para el estado dinámico del itinerario (`trips.pro_state`, `trips.days_dates`, `users.custom_statuses`).
- **Capacitor Android:** No es una app con archivos locales empaquetados; `capacitor.config.json` define `"server": { "url": "https://viantryp.com" }`. La app móvil es un WebView que apunta al servidor web en producción.

---

## 3. INVENTARIO FUNCIONAL

- **Landing Page & Páginas Legales:** Completamente implementadas (Términos, Privacidad, RGPD, Seguridad, Contacto).
- **Autenticación (Email / Password / Google):** Implementada. Verificación de token criptográfico de Google operativa. Falta verificación de email en registro.
- **Editor y Visor de Itinerarios Pro:** Completamente implementado y verificado.
- **Gestión de Colaboradores de Viaje:** Implementada con tokens de invitación por correo y roles de editor/lector.
- **Asistente IA (Tryp IA):** Implementado con API de Google Gemini (`gemini-3.1-flash-lite`).
- **Suscripciones y Facturación:** Parcialmente implementado. Listener de webhooks existe en `AppServiceProvider`, pero el frontend está cableado a un endpoint inseguro (`paddle-sync`).
- **Gestión de Clientes / Personas:** Implementado pero gravemente roto a nivel de aislamiento multi-tenant.
- **Eliminación de Cuenta:** Inexistente.

---

## 4. CUSTOMER JOURNEY COMPLETO

1. **Visitante:** Aterriza en la landing page. Entiende la propuesta de valor y las funciones. Al pulsar "Probar 7 días gratis", es redirigido erróneamente al formulario de Login (`/login`) en vez del Registro (`/register`).
2. **Registro:** Formulario rápido y sin fricción. No exige validación de email. Inicia sesión automáticamente.
3. **Onboarding:** Modal de bienvenida con confeti. Elige "Personal" o "Agencia". Se le asigna 1 mes de Plan Pro gratis (que por error queda activo indefinidamente).
4. **Primer Viaje (Aha! Moment):** Crea un viaje en modal rápido. Abre el editor Pro. Diseña con fotos de Unsplash y mapas. Genera el enlace público `/trips/share/{token}`.
5. **Conversión a Pago:** Modal de upgrade con Paddle.js. Si intenta cancelar desde su perfil, se le cancela el servicio de inmediato sin respetar el mes ya abonado.
6. **Uso Recurrente:** Dashboard con buscador y filtros de estados personalizados.

---

## 5. AUDITORÍA DE FRONTEND Y BACKEND

- **Frontend:** Ausencia de empaquetado de los scripts `pro-editor.js` y `pro-viewer.js` mediante Vite. 4 fuentes externas bloqueantes en landing.
- **Backend:** `PersonController` carece de verificación de propiedad de recursos. `AirlineController` permite a cualquier usuario registrado alterar aerolíneas globales sin rol de administrador. `LoginController` ejecuta una validación DNS propensa a falsos positivos.

---

## 6. AUDITORÍA DE SEGURIDAD (OWASP)

- **Vulnerabilidades Confirmadas:**
  - `SEC-01 (P0):` Escalada gratuita de planes en `ProfileController::syncPaddlePlan`.
  - `SEC-02 (P0):` Fuga masiva de clientes entre agencias en `PersonController::all()`.
  - `SEC-03 (P1):` Documentos privados (pasaportes, billetes) servibles estáticamente en servidores Nginx por estar ubicados en `storage/app/public/documents/`.
  - `SEC-04 (P1):` Keystore de producción (`viantryp-release-official.keystore`) y credenciales en texto plano dentro del repositorio.
  - `SEC-05 (P1):` Proxy de imágenes (`ImageProxyController`) vulnerable a SSRF por DNS Rebinding y consumo abusivo de recursos sin Rate Limiting.

---

## 7. COMUNICACIONES Y AUTOMATIZACIONES

- **Implementadas:** Email de bienvenida (`WelcomeNotification`), recuperación de contraseña (`CustomResetPassword`), envío de itinerario (`SendTripLink`), invitación de colaboradores (`TripCollaborationInvite`) y formulario de contacto.
- **Ausentes Requeridas:** Email de confirmación de pago / recibo, aviso 3 días antes de expiración de prueba, aviso de cobro fallido por tarjeta rechazada y confirmación de cancelación de suscripción.

---

## 8. PAGOS Y SUSCRIPCIONES (PADDLE)

- Discrepancia entre la landing (7 días de prueba) y el onboarding (1 mes de prueba).
- Cancelación inmediata en Paddle (`effective_from: 'immediately'`) en lugar de prorrogar hasta fin del periodo facturado.
- Conflicto normativo severo en Android: cobro por tarjeta web dentro del WebView en Google Play.

---

## 9. PWA (PROGRESSIVE WEB APP)

- Manifiesto completo y funcional (`display: standalone`) con 8 iconos válidos en disco.
- Service Worker activo con estrategia Network-First.
- **Deficiencia:** Ausencia de página de contingencia offline (`offline.html`) y falta de persistencia offline de itinerarios en IndexedDB.

---

## 10. ANDROID Y CAPACITOR

- Target SDK 36 (Android 16), cumpliendo ampliamente las directivas de Google Play.
- **Problema de Usabilidad:** El botón atrás del móvil cierra la aplicación por falta del listener `App.addListener('backButton')`.
- **Deep Links Rotos:** `assetlinks.json` apunta a `com.viantryp.www.twa` mientras que la app oficial es `com.viantryp.app`.

---

## 11. PREPARACIÓN PARA APPLE APP STORE (iOS)

- **Estado:** 0% preparado. No existe `@capacitor/ios` ni directorio `/ios`.
- **Requisitos Obligatorios para evitar rechazo en revisión:**
  1. Integrar *Sign in with Apple* (Guideline 4.8).
  2. Implementar borrado de cuenta en la app (Guideline 5.1.1 v).
  3. Reemplazar Paddle en iOS por StoreKit (In-App Purchases) o inhabilitar compras en la app.
  4. Adaptar CSS a Safe Area Insets (`env(safe-area-inset-bottom)`) y `100dvh` para Safari WebKit.

---

## 12. SEO TÉCNICO, RENDIMIENTO Y ANALÍTICA

- **SEO:** Título de landing deficiente (`Viantryp | Home`), meta description inexistente, ausencia total de Open Graph / Twitter Cards y falta de `sitemap.xml`.
- **Rendimiento:** Fuentes externas bloqueantes y scripts principales no minificados.
- **Analítica:** 0% de herramientas de analítica instaladas (sin GA4, sin GTM, sin píxeles de conversión). Ceguera total de métricas.

---

## 13. MATRIZ DE PRUEBAS

- Tests de backend existentes cubren parcialmente generación de IDs y permisos de documentos, pero `TripCreateTest` está roto por apuntar a una ruta eliminada.
- Se requiere construir tests automatizados de integración para: registro, login, aislamiento de personas y recepción de webhooks de Paddle.

---

## 14. REGISTRO CONSOLIDADO DE HALLAZGOS

*(Ver tabla completa de hallazgos P0 a P3 detallada en la Sección 4 del archivo `04_PLAN_ACCION_Y_GO_NO_GO.md`).*

---

## 15. PLAN DE ACCIÓN ORDENADO POR BLOQUES

- **Bloque A:** Imprescindible antes del lanzamiento web (Corregir Paddle, aislar Personas, corregir trial vitalicio, reparar CTA landing, asegurar adjuntos).
- **Bloque B:** Experiencia y cumplimiento (Eliminación de cuenta, blindaje SSRF, cancelación ordenada en Paddle, emails transaccionales de pago).
- **Bloque C:** Conversión y analítica (Meta tags Open Graph, sitemap, instalación de GA4/GTM con embudo de eventos).
- **Bloque D:** PWA (Página offline fallback y corrección de assetlinks.json).
- **Bloque E:** Android (Ocultar checkout Paddle en Android WebView, listener de botón atrás, resguardar keystore fuera del repo).
- **Bloque F:** Preparación iOS (Instalación de Capacitor iOS, Sign in with Apple, StoreKit).
- **Bloque G:** Mejoras posteriores (Minificación Vite de scripts JS y caché offline IndexedDB).

---

## 16. DECISIÓN DE LANZAMIENTO (CRITERIOS GO / NO-GO)

| Canal | Estado | Condición para Pasar a GO |
| :--- | :---: | :--- |
| **Web** | **NO-GO** | Resolver en su totalidad el **Bloque A** (Seguridad de pagos, aislamiento multi-tenant y corrección de trial). |
| **PWA** | **CONDICIONAL** | Resolver Bloque A y añadir página offline amigable. |
| **Android** | **MANTENER / NO ACTUALIZAR** | La versión 1.0.10 actual sigue activa en la tienda. No desplegar cambios web que rompan el WebView y ocultar Paddle en la app antes de la próxima subida. |
| **iOS** | **NO-GO** | Ejecutar el Bloque F al completo con hardware Mac y cuenta de desarrollador de Apple. |

---

## 17. ARCHIVOS DE DOCUMENTACIÓN GENERADOS

Se han creado los siguientes documentos en la carpeta `docs/auditoria/` para registro permanente en el repositorio:
1. `docs/auditoria/01_ARQUITECTURA_E_INVENTARIO.md`
2. `docs/auditoria/02_CUSTOMER_JOURNEY_PAGOS_COMMS.md`
3. `docs/auditoria/03_SEGURIDAD_PWA_CAPACITOR_IOS.md`
4. `docs/auditoria/04_PLAN_ACCION_Y_GO_NO_GO.md`
5. `docs/auditoria/INFORME_FINAL_AUDITORIA_INTEGRAL.md` (Este informe maestro)
6. `docs/auditoria/informe-auditoria-etapa-1.html`
7. `docs/auditoria/informe-auditoria-etapa-2.html`
8. `docs/auditoria/informe-auditoria-etapa-3.html`
9. `docs/auditoria/informe-auditoria-etapa-4.html` (Tablero dinámico de QA, SEO y Go/No-Go)

