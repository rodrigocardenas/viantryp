<header class="header">
    <div class="header-content">
        <div class="logo-container">
            <a href="{{ route('trips.index') }}" class="viantryp-logo">
                <img src="/images/logo-viantryp.png" alt="Viantryp" style="height: 38px; width: auto;" />
            </a>
            @if(isset($subtitle))
                <span class="header-subtitle">
                    <span style="opacity: 0.6;">|</span>
                    {{ $subtitle }}
                </span>
            @endif
        </div>
        @auth
            {{-- Navigation removed as requested --}}
            {{-- <nav class="main-nav">
                <a href="{{ route('trips.index') }}" class="nav-link {{ request()->routeIs('trips.*') ? 'active' : '' }}">
                    <i class="fas fa-suitcase"></i>
                    Viajes
                </a>
                <a href="{{ route('persons.index') }}" class="nav-link {{ request()->routeIs('persons.*') ? 'active' : '' }}">
                    <i class="fas fa-users"></i>
                    Personas
                </a>
            </nav> --}}
        @endauth
        <div class="header-right">
            @if(isset($showActions) && $showActions)
                <div class="nav-actions">
                    @if(isset($backUrl))
                        @if(isset($backOnclick))
                            <button class="btn btn-back" data-action="back">
                                <i class="fas fa-arrow-left"></i>
                                Volver
                            </button>
                        @else
                            <a href="{{ $backUrl }}" class="btn btn-back">
                                <i class="fas fa-arrow-left"></i>
                                Volver
                            </a>
                        @endif
                    @endif
                    @if(isset($actions))
                        @foreach($actions as $action)
                            @if(isset($action['onclick']))
                                <button type="button" class="btn {{ $action['class'] ?? 'btn-primary' }}" data-action="{{ $action['data-action'] ?? $action['onclick'] }}">
                                    @if(isset($action['icon']))
                                        <i class="{{ $action['icon'] }}"></i>
                                    @endif
                                    {{ $action['text'] }}
                                </button>
                            @else
                                {{-- Render link actions but include data-action when provided so JS delegation works --}}
                                <a href="{{ $action['url'] }}" class="btn {{ $action['class'] ?? 'btn-primary' }}" @if(isset($action['data-action'])) data-action="{{ $action['data-action'] }}" @endif @if(isset($action['target'])) target="{{ $action['target'] }}" @endif>
                                    @if(isset($action['icon']))
                                        <i class="{{ $action['icon'] }}"></i>
                                    @endif
                                    {{ $action['text'] }}
                                </a>
                            @endif
                        @endforeach
                    @endif
                </div>
            @endif

            <!-- Authentication Section -->
            <div class="auth-section">
                @auth
                    <!-- User is logged in -->
                    <div style="display: flex; align-items: center; gap: 1rem;">
                        <div style="display: flex; align-items: center; gap: 0.5rem;" title="{{ auth()->user()->name }}">
                            <span style="font-size: 0.95rem; font-weight: 600; color: #ffffff;">
                                {{ auth()->user()->name }}
                            </span>
                            <div style="width: 36px; height: 36px; border-radius: 50%; background-color: rgba(255, 255, 255, 0.2); color: #ffffff; display: flex; align-items: center; justify-content: center; font-family: 'Syne', sans-serif; font-weight: 700; font-size: 1rem; border: 1.5px solid rgba(255, 255, 255, 0.4); box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                        </div>
                        <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                            @csrf
                            <button type="submit" class="logout-btn">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" style="width: 15px; height: 15px;">
                                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4M16 17l5-5-5-5M21 12H9"/>
                                </svg>
                                Salir
                            </button>
                        </form>
                    </div>
                @else
                    <!-- User is not logged in -->
                    <div class="auth-buttons">
                        <a href="{{ route('login') }}" class="btn btn-login">
                            <i class="fas fa-sign-in-alt"></i>
                            Iniciar Sesión
                        </a>
                        <a href="{{ route('register') }}" class="btn btn-register">
                            <i class="fas fa-user-plus"></i>
                            Registrarse
                        </a>
                        <div class="auth-divider">o</div>
                        <a href="{{ route('auth.google') }}" class="btn btn-google-login">
                            <i class="fab fa-google"></i>
                            Google
                        </a>
                    </div>
                @endauth
            </div>
        </div>
    </div>
</header>

<style>
    .header {
        background: #0f2a3a; /* Match landing footer background */
        color: #ffffff;
        padding: 0 2rem;
        border-bottom: none;
        height: 64px;
        display: flex;
        align-items: center;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        z-index: 1000;
    }

    .header-content {
        max-width: 1400px; /* Made it wider to match the editor spread */
        margin: 0 auto;
        padding: 0;
        display: flex;
        justify-content: space-between;
        align-items: center;
        width: 100%;
        height: 100%;
    }

    .logo-container {
        display: flex;
        align-items: center;
    }

    .viantryp-logo {
        color: #ffffff;
        text-decoration: none;
        font-size: 1.5rem;
        display: flex;
        align-items: baseline;
    }

    .logo-v {
        font-weight: 900;
        font-family: 'Barlow Condensed', sans-serif;
    }

    .logo-dot {
        font-weight: 900;
        color: #4cd5c2; /* Brighter teal dot for dark bg */
    }

    .logo-text {
        font-weight: 800;
        font-family: 'Barlow Condensed', sans-serif;
    }

    .header-subtitle {
        color: rgba(255, 255, 255, 0.9);
        font-size: 0.95rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-left: 1.5rem;
    }

    .header-subtitle i {
        color: rgba(255, 255, 255, 0.6);
        font-size: 0.75rem;
        font-weight: 400;
    }

    .nav-actions {
        display: flex;
        gap: 1rem;
        align-items: center;
    }

    .btn {
        padding: 0.5rem 1rem;
        border-radius: 999px; /* Pill shape like image */
        text-decoration: none;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.3s ease;
        border: 1px solid transparent;
        cursor: pointer;
        font-size: 0.85rem;
    }

    .btn-back {
        background: white;
        color: #6b7a8d;
        border: 1px solid #e2e8ef;
    }

    .btn-back:hover {
        background: #f5f7f9;
        color: #0d2b3e;
        transform: translateY(-1px);
    }

    .btn-save {
        background: #1a9a8a;
        color: white;
    }

    .btn-save:hover {
        background: #147A6D;
        transform: translateY(-1px);
    }

    .btn-preview, .btn-export {
        background: white;
        color: #0d2b3e;
        border: 1px solid #e2e8ef;
    }

    .btn-preview:hover, .btn-export:hover {
        background: #f5f7f9;
        transform: translateY(-1px);
    }

    /* Authentication Styles */
    .header-right {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .auth-section {
        display: flex;
        align-items: center;
    }

    .btn-google-login {
        background: #4285f4;
        color: white;
        border: 1px solid #4285f4;
        padding: 0.5rem 1rem;
        border-radius: 999px;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.3s ease;
        text-decoration: none;
        font-size: 0.85rem;
    }

    .btn-google-login:hover {
        background: #3367d6;
        border-color: #3367d6;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(66, 133, 244, 0.3);
    }

    .btn-google-login i {
        font-size: 0.9rem;
    }

    .user-profile {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        background: rgba(255, 255, 255, 0.1);
        padding: 0.5rem 1rem;
        border-radius: 999px;
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .user-avatar {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid rgba(255, 255, 255, 0.3);
    }

    .user-avatar-placeholder {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 0.9rem;
        border: 2px solid rgba(255, 255, 255, 0.3);
    }

    .user-name {
        color: #0d2b3e;
        font-weight: 500;
        font-size: 0.9rem;
        max-width: 120px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .btn-logout {
        background: white;
        color: #6b7a8d;
        border: 1px solid #e2e8ef;
        padding: 0.4rem 0.8rem;
        border-radius: 6px;
        font-size: 0.8rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 0.3rem;
    }

    .btn-logout:hover {
        background: #f5f7f9;
        color: #0d2b3e;
        transform: translateY(-1px);
    }

    .logout-btn {
        background: white;
        color: #0d2b3e;
        border: 1.5px solid #e2e8ef;
        padding: 8px 18px;
        border-radius: 50px;
        font-weight: 600;
        font-size: 15px;
        cursor: pointer;
        transition: border-color 0.18s, color 0.18s, background 0.18s;
        display: flex;
        align-items: center;
        gap: 6px;
        text-decoration: none;
    }

    .logout-btn:hover {
        border-color: #1a7a8a;
        color: #1a7a8a;
        background: #f0faf9;
    }

    /* New Authentication Buttons */
    .auth-buttons {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .btn-login {
        background: white;
        color: #0d2b3e;
        border: 1px solid #e2e8ef;
        padding: 0.5rem 1rem;
        border-radius: 999px;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.3s ease;
        text-decoration: none;
        font-size: 0.85rem;
    }

    .btn-login:hover {
        background: #f5f7f9;
        transform: translateY(-1px);
    }

    .btn-register {
        background: #1a9a8a;
        color: white;
        border: 1px solid #1a9a8a;
        padding: 0.5rem 1rem;
        border-radius: 999px;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.3s ease;
        text-decoration: none;
        font-size: 0.85rem;
    }

    .btn-register:hover {
        background: #147A6D;
        border-color: #147A6D;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(26, 154, 138, 0.2);
    }

    .auth-divider {
        color: #6b7a8d;
        font-size: 0.8rem;
        font-weight: 400;
    }

    @media (max-width: 768px) {
        .header-content {
            padding: 0 1rem;
            flex-direction: row;
            justify-content: space-between;
            align-items: center;
        }

        .header-right {
            width: auto;
            justify-content: flex-end;
        }

        .nav-actions {
            display: none !important;
        }

        .header-subtitle {
            display: none !important;
        }

        .user-name {
            display: none;
        }

        .logout-btn {
            font-size: 12px !important;
            padding: 0.4rem 0.6rem !important;
            gap: 4px;
            white-space: nowrap;
        }
        
        .viantryp-logo img {
            height: 32px !important;
        }

        .auth-buttons {
            gap: 0.5rem;
        }

        .btn-login,
        .btn-register {
            padding: 0.4rem 0.8rem;
            font-size: 0.75rem;
        }

        .auth-divider {
            font-size: 0.7rem;
        }
    }

    .main-nav {
        display: flex;
        gap: 2rem;
        align-items: center;
    }

    .nav-link {
        color: white;
        text-decoration: none;
        font-weight: 500;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .nav-link:hover {
        background: rgba(255, 255, 255, 0.1);
    }

    .nav-link.active {
        background: rgba(255, 255, 255, 0.2);
        font-weight: 600;
    }

    .nav-link i {
        font-size: 1rem;
    }

</style>
