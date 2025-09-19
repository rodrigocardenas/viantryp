<header class="header">
    <div class="header-content">
        <div class="logo-container">
            <a href="{{ route('trips.index') }}" class="viantryp-logo">
                <i class="fas fa-route"></i>
                Viantryp
            </a>
        </div>
        <div class="header-right">
            @if(isset($showActions) && $showActions)
                <div class="nav-actions">
                    @if(isset($backUrl))
                        <a href="{{ $backUrl }}" class="btn btn-back">
                            <i class="fas fa-arrow-left"></i>
                            Volver
                        </a>
                    @endif
                    @if(isset($actions))
                        @foreach($actions as $action)
                            @if(isset($action['onclick']))
                                <button type="button" class="btn {{ $action['class'] ?? 'btn-primary' }}" onclick="{{ $action['onclick'] }}">
                                    @if(isset($action['icon']))
                                        <i class="{{ $action['icon'] }}"></i>
                                    @endif
                                    {{ $action['text'] }}
                                </button>
                            @else
                                <a href="{{ $action['url'] }}" class="btn {{ $action['class'] ?? 'btn-primary' }}">
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
                    <div class="user-profile">
                        @if(Auth::user()->avatar)
                            <img src="{{ Auth::user()->avatar }}" alt="Avatar" class="user-avatar">
                        @else
                            <div class="user-avatar-placeholder">
                                <i class="fas fa-user"></i>
                            </div>
                        @endif
                        <span class="user-name">{{ Auth::user()->name }}</span>
                        <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                            @csrf
                            <button type="submit" class="btn btn-logout">
                                <i class="fas fa-sign-out-alt"></i>
                                Cerrar Sesión
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
        background: linear-gradient(135deg, var(--blue-700) 0%, var(--blue-600) 60%, var(--blue-300) 100%);
        color: white;
        padding: 1.25rem 2rem;
        box-shadow: var(--shadow-soft);
    }

    .header-content {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 2rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .viantryp-logo {
        color: white;
        text-decoration: none;
        font-size: 1.5rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .viantryp-logo i {
        font-size: 1.8rem;
    }

    .nav-actions {
        display: flex;
        gap: 1rem;
        align-items: center;
    }

    .btn {
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
        font-size: 0.9rem;
    }

    .btn-back {
        background: rgba(255, 255, 255, 0.2);
        color: white;
        border: 1px solid rgba(255, 255, 255, 0.3);
    }

    .btn-back:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: translateY(-1px);
    }

    .btn-save {
        background: #28a745;
        color: white;
    }

    .btn-save:hover {
        background: #218838;
        transform: translateY(-1px);
    }

    .btn-preview {
        background: #dc3545;
        color: white;
    }

    .btn-preview:hover {
        background: #c82333;
        transform: translateY(-1px);
    }

    .btn-pdf {
        background: #fd7e14;
        color: white;
    }

    .btn-pdf:hover {
        background: #e8650e;
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
        color: white;
        font-weight: 500;
        font-size: 0.9rem;
        max-width: 120px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .btn-logout {
        background: rgba(255, 255, 255, 0.2);
        color: white;
        border: 1px solid rgba(255, 255, 255, 0.3);
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
        background: rgba(255, 255, 255, 0.3);
        transform: translateY(-1px);
    }

    .btn-logout i {
        font-size: 0.8rem;
    }

    /* New Authentication Buttons */
    .auth-buttons {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .btn-login {
        background: rgba(255, 255, 255, 0.2);
        color: white;
        border: 1px solid rgba(255, 255, 255, 0.3);
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
        background: rgba(255, 255, 255, 0.3);
        transform: translateY(-1px);
    }

    .btn-register {
        background: #28a745;
        color: white;
        border: 1px solid #28a745;
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
        background: #218838;
        border-color: #218838;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(40, 167, 69, 0.3);
    }

    .auth-divider {
        color: rgba(255, 255, 255, 0.6);
        font-size: 0.8rem;
        font-weight: 300;
    }

    @media (max-width: 768px) {
        .header-content {
            padding: 0 1rem;
            flex-direction: column;
            gap: 1rem;
        }

        .nav-actions {
            flex-wrap: wrap;
            justify-content: center;
        }

        .btn {
            padding: 0.5rem 1rem;
            font-size: 0.8rem;
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

    .header-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
        max-width: 1200px;
        margin: 0 auto;
    }

    .logo-container {
        display: flex;
        align-items: center;
    }

    .viantryp-logo {
        display: flex;
        align-items: center;
        color: white;
        font-size: 1.8rem;
        font-weight: 700;
        text-decoration: none;
    }

    .viantryp-logo i { margin-right: 0.6rem; }

    .nav-actions {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .btn-back {
        background: var(--stone-100);
        color: var(--slate-600);
        border: 1px solid var(--stone-300);
    }

    .btn-back:hover {
        background: var(--blue-700);
        color: white;
        border-color: var(--blue-700);
    }

    @media (max-width: 768px) {
        .header-content {
            padding: 0 1rem;
            flex-direction: column;
            gap: 1rem;
        }

        .header-right {
            width: 100%;
            justify-content: space-between;
        }

        .logo-container {
            gap: 1rem;
        }

        .viantryp-logo {
            font-size: 1.4rem;
        }

        .nav-actions {
            flex-wrap: wrap;
            gap: 0.5rem;
        }

        .auth-section {
            order: -1;
        }

        .user-profile {
            padding: 0.4rem 0.8rem;
        }

        .user-name {
            display: none;
        }

        .btn-google-login {
            padding: 0.4rem 0.8rem;
            font-size: 0.8rem;
        }

        .btn-logout {
            padding: 0.3rem 0.6rem;
            font-size: 0.75rem;
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
</style>
