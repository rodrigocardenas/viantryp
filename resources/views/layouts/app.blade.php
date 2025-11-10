<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="google-places-api-key" content="{{ config('services.google.places_api_key') }}">
    <title>@yield('title', 'Viantryp - Gestión de Viajes')</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    {{-- Google Maps API --}}
    @if(config('services.google.places_api_key'))
    <script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google.places_api_key') }}&libraries=places&v=weekly" async defer></script>
    @endif
    <link rel="stylesheet" href="{{ asset('css/components/sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components/trip-header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components/timeline.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components/modals.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components/editor-layout.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components/timeline-items.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components/autocomplete.css') }}">
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
    @vite(['resources/js/app.js'])
    @stack('styles')
    <style>
        :root {
            --ink: #1f2a44;
            --blue-700: #0ea5e9;
            --blue-600: #38bdf8;
            --blue-300: #93c5fd;
            --blue-100: #e0f2fe;
            --sky-50: #f0f9ff;
            --stone-100: #f5f7fa;
            --stone-300: #e2e8f0;
            --stone-400: #cbd5e1;
            --slate-600: #475569;
            --slate-500: #64748b;
            --success: #10b981;
            --danger: #ef4444;
            --shadow-soft: 0 10px 30px rgba(0,0,0,0.06);
            --shadow-hover: 0 14px 40px rgba(0,0,0,0.08);
            --radius: 16px;
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
            font-family: 'Poppins', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(180deg, #e6f3fb 0%, #f7fbff 60%);
            color: var(--ink);
            letter-spacing: 0.1px;
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

        .notification.show {
            transform: translateX(0);
        }

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
</head>
<body>
    @yield('content')

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
</body>
</html>

