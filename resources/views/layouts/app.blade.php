<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="google-places-api-key" content="{{ config('services.google.places_api_key') }}">
    <title>@yield('title', 'Viantryp - Gestión de Viajes')</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">

    {{-- PWA: Manifest y soporte móvil --}}
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <meta name="theme-color" content="#0d2b3e">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="Viantryp">
    <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('icons/icon-152x152.png') }}">
    <link rel="apple-touch-icon" sizes="192x192" href="{{ asset('icons/icon-192x192.png') }}">
    <link rel="apple-touch-icon" sizes="512x512" href="{{ asset('icons/icon-512x512.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@700;800;900&family=Barlow:wght@400;500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    {{-- Google Maps API --}}
    @if(config('services.google.places_api_key'))
    <script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google.places_api_key') }}&libraries=places&v=weekly" async defer></script>
    @endif
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
    @vite(['resources/js/app.js'])
    @stack('styles')
    <style>
        :root {
            --dark: #0d2b3e;
            --gray: #6b7a8d;
            --border: #e2e8ef;
            --white: #ffffff;
            --light: #f5f7f9;
            --radius: 12px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html, body {
            margin: 0;
            padding: 0;
            height: 100%;
        }

        body {
            font-family: 'Barlow', sans-serif;
            background: var(--light);
            color: var(--dark);
        }

        .btn {
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 999px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
            font-size: 0.9rem;
            white-space: nowrap;
            box-shadow: var(--shadow-soft);
        }

        .btn:hover { transform: translateY(-1px); box-shadow: var(--shadow-hover); }

        .btn-primary { background: var(--blue-700); color: white; }
        .btn-secondary { background: var(--stone-100); color: var(--slate-600); border: 1px solid var(--stone-300); }
        .btn-success { background: var(--success); color: white; }
        .btn-danger { background: var(--danger); color: white; }

        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            background: white;
            color: #333;
            padding: 0;
            border-radius: var(--radius);
            box-shadow: var(--shadow-hover);
            z-index: 10000;
            transform: translateX(100%);
            transition: transform 0.3s ease;
            max-width: 350px;
            display: none;
        }

        .notification.show { transform: translateX(0); }

        .notification-content {
            padding: 1rem;
        }

        .notification-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.5rem;
        }

        .notification-title {
            font-weight: 600;
            font-size: 1rem;
        }

        .notification-close {
            background: none;
            border: none;
            font-size: 1.2rem;
            cursor: pointer;
            color: #666;
            padding: 0.2rem;
            border-radius: 4px;
            transition: all 0.3s ease;
        }

        .notification-close:hover {
            background: #f1f5f9;
            color: #333;
        }

        .notification-message {
            font-size: 0.9rem;
            color: #666;
            line-height: 1.4;
        }

        @media (max-width: 768px) {
            .notification {
                right: 1rem;
                left: 1rem;
                max-width: none;
                transform: translateY(100%);
            }

            .notification.show {
                transform: translateY(0);
            }
        }
    </style>

    @auth
        @include('layouts.theme-styles')
    @endauth
</head>
<body>
    @yield('content')

    {{-- PWA: Banner de instalacion --}}
    <div id="pwa-install-banner" style="
        display: none;
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        z-index: 99999;
        background: linear-gradient(135deg, #0d2b3e 0%, #1a4a6e 100%);
        color: white;
        padding: 1rem 1.25rem;
        align-items: center;
        gap: 1rem;
        box-shadow: 0 -4px 24px rgba(0,0,0,0.3);
        border-top: 1px solid rgba(255,255,255,0.1);
        backdrop-filter: blur(10px);
        font-family: 'Barlow', sans-serif;
    ">
        <img src="{{ asset('icons/icon-72x72.png') }}" alt="Viantryp" style="width: 48px; height: 48px; border-radius: 12px; flex-shrink: 0;">
        <div style="flex: 1; min-width: 0;">
            <div style="font-weight: 700; font-size: 0.95rem; margin-bottom: 0.15rem;">Instalar Viantryp</div>
            <div style="font-size: 0.8rem; opacity: 0.8; line-height: 1.3;">Añade la app a tu pantalla de inicio para acceder más rápido</div>
        </div>
        <div style="display: flex; gap: 0.5rem; flex-shrink: 0;">
            <button id="pwa-install-dismiss" style="
                background: rgba(255,255,255,0.15);
                border: none;
                color: white;
                padding: 0.5rem 0.75rem;
                border-radius: 8px;
                font-size: 0.82rem;
                cursor: pointer;
                font-family: inherit;
            ">Ahora no</button>
            <button id="pwa-install-btn" style="
                background: white;
                color: #0d2b3e;
                border: none;
                padding: 0.5rem 1rem;
                border-radius: 8px;
                font-weight: 700;
                font-size: 0.82rem;
                cursor: pointer;
                font-family: inherit;
            ">Instalar</button>
        </div>
    </div>

    <!-- Notification System -->
    <div id="notification" class="notification">
        <div class="notification-content">
            <div class="notification-header">
                <span class="notification-title"></span>
                <button class="notification-close" onclick="hideNotification()">×</button>
            </div>
            <div class="notification-message" id="notificationMessage"></div>
        </div>
    </div>

    @stack('scripts')
    <script>
        window.ViantrypTutorials = @json(auth()->user() ? auth()->user()->tutorials_seen ?? [] : []);
    </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        // Notification functions
        function showNotification(title, message) {
            const notification = document.getElementById('notification');
            const notificationTitle = notification.querySelector('.notification-title');
            const notificationMessage = document.getElementById('notificationMessage');
            notificationTitle.textContent = title;
            notificationMessage.textContent = message;
            notification.style.display = 'block';
            notification.offsetHeight; // Force reflow
            notification.classList.add('show');
            setTimeout(() => {
                hideNotification();
            }, 4000);
        }

        function hideNotification() {
            const notification = document.getElementById('notification');
            notification.classList.remove('show');
            setTimeout(() => {
                notification.style.display = 'none';
            }, 300);
        }
    </script>

    {{-- PWA: Registro del Service Worker --}}
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js')
                    .then(reg => console.log('[Viantryp PWA] Service Worker registrado:', reg.scope))
                    .catch(err => console.warn('[Viantryp PWA] Error al registrar SW:', err));
            });
        }

        // Banner de instalacion PWA
        let deferredPrompt;
        const installBanner = document.getElementById('pwa-install-banner');
        const installBtn = document.getElementById('pwa-install-btn');
        const installDismiss = document.getElementById('pwa-install-dismiss');

        window.addEventListener('beforeinstallprompt', (e) => {
            e.preventDefault();
            deferredPrompt = e;
            if (installBanner) {
                installBanner.style.display = 'flex';
            }
        });

        if (installBtn) {
            installBtn.addEventListener('click', async () => {
                if (!deferredPrompt) return;
                deferredPrompt.prompt();
                const { outcome } = await deferredPrompt.userChoice;
                console.log('[Viantryp PWA] Resultado instalacion:', outcome);
                deferredPrompt = null;
                installBanner.style.display = 'none';
            });
        }

        if (installDismiss) {
            installDismiss.addEventListener('click', () => {
                installBanner.style.display = 'none';
                // No mostrar por 7 dias
                localStorage.setItem('pwa-install-dismissed', Date.now());
            });
        }

        // No mostrar si ya fue descartado en los ultimos 7 dias
        window.addEventListener('load', () => {
            const dismissed = localStorage.getItem('pwa-install-dismissed');
            if (dismissed && (Date.now() - parseInt(dismissed)) < 7 * 24 * 60 * 60 * 1000) {
                if (installBanner) installBanner.style.display = 'none';
            }
        });
    </script>
</body>
</html>

