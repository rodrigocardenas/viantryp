<header class="topbar">
  <div class="topbar-bg-decorators"></div>
  <div class="topbar-left">
    <a href="{{ route('home') }}" class="logo">
      <img src="/images/logo-viantryp.png" alt="Viantryp" style="height: 28px; width: auto;">
    </a>
  </div>

  <div class="topbar-right">
    @if(isset($showActions) && $showActions)
        <div class="nav-actions" style="display: flex; gap: 16px; align-items: center; margin-right: 16px;">
            @if(isset($backUrl))
                @if(isset($backOnclick))
                    <button class="nav-link" style="border:none; cursor:pointer;" data-action="back" onclick="{{ $backOnclick }}">
                        <i class="fas fa-arrow-left" style="margin-right:4px;"></i> Volver
                    </button>
                @else
                    <a href="{{ $backUrl }}" class="nav-link">
                        <i class="fas fa-arrow-left" style="margin-right:4px;"></i> Volver
                    </a>
                @endif
            @endif
            
            @if(isset($actions))
                <div style="display: flex; gap: 16px; align-items: center;">
                @foreach($actions as $action)
                    @php
                        $btnStyle = 'cursor:pointer; background:white; color:var(--dark); border: 1px solid var(--border); padding:6px 18px; border-radius:50px; font-size:13px; font-weight:600; display:inline-flex; align-items:center; text-decoration:none; transition:all 0.15s; font-family:\'Barlow\',sans-serif; height: 32px;';
                    @endphp
                    @if(isset($action['onclick']))
                        <button type="button" class="{{ $action['class'] ?? '' }}" style="{{ $btnStyle }}" data-action="{{ $action['data-action'] ?? $action['onclick'] }}" onmouseover="this.style.border-color='var(--teal)'; this.style.color='var(--teal)';" onmouseout="this.style.border-color='var(--border)'; this.style.color='var(--dark)';">
                            @if(isset($action['icon'])) <i class="{{ $action['icon'] }}" style="margin-right:6px;"></i> @endif
                            {{ $action['text'] }}
                        </button>
                    @else
                        <a href="{{ $action['url'] }}" class="{{ $action['class'] ?? '' }}" style="{{ $btnStyle }}" @if(isset($action['data-action'])) data-action="{{ $action['data-action'] }}" @endif @if(isset($action['target'])) target="{{ $action['target'] }}" @endif onmouseover="this.style.border-color='var(--teal)'; this.style.color='var(--teal)';" onmouseout="this.style.border-color='var(--border)'; this.style.color='var(--dark)';">
                            @if(isset($action['icon'])) <i class="{{ $action['icon'] }}" style="margin-right:6px;"></i> @endif
                            {{ $action['text'] }}
                        </a>
                    @endif
                @endforeach
                </div>
            @endif
        </div>
    @endif

    @if(isset($secondaryAction))
    <a href="{{ $secondaryAction['url'] }}" class="secondary-nav-link" title="{{ $secondaryAction['text'] }}" style="margin-right: 8px;">
        @if(isset($secondaryAction['icon'])) <i class="{{ $secondaryAction['icon'] }}" style="margin-right:6px;"></i> @endif
        {{ $secondaryAction['text'] }}
    </a>
    @endif

    @if(isset($tutorialOnclick))
    <a href="javascript:void(0)" onclick="{{ $tutorialOnclick }}" class="btn-help" title="Ayuda / Tutorial" style="margin-right: 8px;">
        <i class="fas fa-question-circle"></i>
    </a>
    @endif

    @auth
    <!-- Notification Bell -->
    <div class="notifications-dropdown" style="position: relative; margin-right: 8px;">
        <button id="notiTrigger" class="btn-help" title="Notificaciones" style="position: relative;">
            <i class="fas fa-bell"></i>
            <span id="notiBadge" style="display: none; position: absolute; top: -5px; right: -5px; background: #c0392b; color: white; border-radius: 50%; width: 16px; height: 16px; font-size: 10px; font-weight: 700; align-items: center; justify-content: center;">0</span>
        </button>
        <div id="notiMenu" class="dropdown-menu-content" style="display: none; position: absolute; top: calc(100% + 10px); right: 0; background: white; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); width: 280px; overflow: hidden; z-index: 1000; border: 1px solid #e2e8ef;">
            <div style="padding: 12px 16px; border-bottom: 1px solid #e2e8ef; display: flex; justify-content: space-between; align-items: center;">
                <span style="font-size: 13px; font-weight: 700; color: var(--dark); text-transform: uppercase; letter-spacing: 0.5px;">Notificaciones</span>
                <button onclick="markNotificationsAsRead()" style="border: none; background: transparent; color: var(--teal); font-size: 11px; font-weight: 600; cursor: pointer;">Marcar como leídas</button>
            </div>
            <div id="notiList" style="max-height: 320px; overflow-y: auto;">
                <!-- Notifications will be loaded here -->
                <div style="padding: 20px; text-align: center; color: var(--gray2); font-size: 12px;">Cargando...</div>
            </div>
        </div>
    </div>


    <div class="user-profile-dropdown" style="position: relative;">
        <div class="ubadge" id="profileTrigger" style="cursor: pointer; transition: all 0.2s; display: flex; align-items: center; gap: 8px;">
          <div class="avatar" id="navAvatar" style="overflow: hidden;">
            @if(auth()->user()->avatar)
                <img src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="" style="width: 100%; height: 100%; object-fit: cover;">
            @else
                {{ collect(explode(' ', auth()->user()->name))->map(function($word) { return strtoupper(substr($word, 0, 1)); })->take(2)->join('') }}
            @endif
          </div>
          <span class="uname">{{ auth()->user()->name }}</span>
          <i class="fas fa-chevron-down" style="font-size: 10px; color: #fbfbfb; margin-left: 4px;"></i>
        </div>
        
        <div id="profileMenu" class="dropdown-menu-content" style="display: none; position: absolute; top: calc(100% + 10px); right: 0; background: white; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); width: 180px; overflow: hidden; z-index: 1000; border: 1px solid #e2e8ef;">
            <a href="{{ route('trips.index') }}" class="dropdown-item" style="display: flex; align-items: center; gap: 10px; padding: 12px 16px; color: var(--dark); text-decoration: none; font-size: 13px; font-weight: 500; transition: background 0.2s;">
                <i class="fas fa-suitcase-rolling" style="color: #64748b; font-size: 15px;"></i>
                Mis viajes
            </a>
            <div style="height: 1px; background: #e2e8ef;"></div>
            <a href="{{ route('profile.index') }}" class="dropdown-item" style="display: flex; align-items: center; gap: 10px; padding: 12px 16px; color: var(--dark); text-decoration: none; font-size: 13px; font-weight: 500; transition: background 0.2s;">
                <i class="fas fa-user-circle" style="color: #64748b; font-size: 15px;"></i>
                Mi perfil
            </a>
            <div style="height: 1px; background: #e2e8ef;"></div>
            <form method="POST" action="{{ route('logout') }}" id="logout-form" style="margin: 0;">
                @csrf
                <button type="submit" class="dropdown-item" style="width: 100%; border: none; background: transparent; display: flex; align-items: center; gap: 10px; padding: 12px 16px; color: #c0392b; cursor: pointer; text-align: left; font-size: 13px; font-weight: 500; transition: background 0.2s; font-family: 'Barlow', sans-serif;">
                    <i class="fas fa-sign-out-alt" style="font-size: 15px;"></i>
                    Cerrar sesión
                </button>
            </form>
        </div>
    </div>

    <script>
        (function() {
            const initMenu = () => {
                const trigger = document.getElementById('profileTrigger');
                const menu = document.getElementById('profileMenu');
                
                if (trigger && menu) {
                    trigger.addEventListener('click', function(e) {
                        e.stopPropagation();
                        const isVisible = menu.style.display === 'block';
                        menu.style.display = isVisible ? 'none' : 'block';
                        trigger.style.background = isVisible ? 'transparent' : 'rgba(255,255,255,0.08)';
                    });
                    
                    document.addEventListener('click', function(e) {
                        if (!trigger.contains(e.target) && !menu.contains(e.target)) {
                            menu.style.display = 'none';
                            trigger.style.background = 'transparent';
                        }
                    });

                    const items = menu.querySelectorAll('.dropdown-item');
                    items.forEach(item => {
                        item.addEventListener('mouseover', () => item.style.background = '#f8fafc');
                        item.addEventListener('mouseout', () => item.style.background = 'transparent');
                    });
                }
            };

            const initNotis = () => {
                const trigger = document.getElementById('notiTrigger');
                const menu = document.getElementById('notiMenu');
                const badge = document.getElementById('notiBadge');
                const list = document.getElementById('notiList');

                if (!trigger || !menu) return;

                const fetchNotis = () => {
                    fetch('{{ route("notifications.get") }}')
                        .then(r => r.json())
                        .then(d => {
                            if (d.unread_count > 0) {
                                badge.textContent = d.unread_count;
                                badge.style.display = 'flex';
                            } else {
                                badge.style.display = 'none';
                            }

                            if (d.notifications.length === 0) {
                                list.innerHTML = '<div style="padding: 20px; text-align: center; color: var(--gray2); font-size: 12px;">No tienes notificaciones nuevas</div>';
                            } else {
                                list.innerHTML = d.notifications.map(n => `
                                    <div style="padding: 12px 16px; border-bottom: 1px solid #f8fafc; cursor: pointer; transition: background 0.2s; ${n.read_at ? '' : 'background: #f0f9f8;'}" onclick="handleNotiClick('${n.id}', '${n.data.invite_url}')">
                                        <div style="font-size: 13px; color: var(--dark); font-weight: ${n.read_at ? '400' : '600'}; margin-bottom: 4px;">${n.data.message}</div>
                                        <div style="font-size: 11px; color: var(--gray2);">${new Date(n.created_at).toLocaleString()}</div>
                                    </div>
                                `).join('');
                            }
                        });
                };

                window.handleNotiClick = (id, url) => {
                    fetch(`/notifications/mark-read/${id}`, {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
                    }).finally(() => {
                        window.location.href = url;
                    });
                };

                trigger.addEventListener('click', function(e) {
                    e.stopPropagation();
                    const isVisible = menu.style.display === 'block';
                    menu.style.display = isVisible ? 'none' : 'block';
                    if (!isVisible) fetchNotis();
                });

                document.addEventListener('click', function(e) {
                    if (!trigger.contains(e.target) && !menu.contains(e.target)) {
                        menu.style.display = 'none';
                    }
                });

                // Initial fetch for badge
                fetchNotis();
                // Refresh every 2 minutes
                setInterval(fetchNotis, 120000);
            };

            window.markNotificationsAsRead = () => {
                fetch('{{ route("notifications.mark-read") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                }).then(() => {
                    document.getElementById('notiBadge').style.display = 'none';
                    document.getElementById('notiMenu').style.display = 'none';
                });
            };

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', () => {
                    initMenu();
                    initNotis();
                });
            } else {
                initMenu();
                initNotis();
            }
        })();
    </script>
    @endauth
  </div>
</header>

<style>
    /* â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•
       TOPBAR GLOBALES
    â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â• */
    .topbar {
      position: sticky; top: 0; z-index: 200;
      background: var(--white);
      height: 64px;
      display: flex; align-items: center; justify-content: space-between;
      padding: 0 40px;
      flex-shrink: 0;
      font-family: 'Barlow', sans-serif;
      border-bottom: 1px solid var(--border);
    }
    .topbar-bg-decorators { display: none; }

    .nav-link {
      font-size: 14px; font-weight: 500; color: var(--dark); text-decoration: none;
      padding: 7px 14px; border-radius: 8px; transition: background 0.18s, color 0.18s;
      font-family: 'Barlow', sans-serif;
      background: transparent;
      line-height: 1;
      display: flex;
      align-items: center;
    }
    .nav-link:hover { background: var(--light); color: var(--teal); }
    .nav-link.active { color: var(--teal); background: rgba(26,154,138,0.05); }

    .topbar-right { display: flex; align-items: center; gap: 10px; position: relative; z-index: 1; }

    .secondary-nav-link {
        cursor: pointer;
        background: rgba(255, 255, 255, 0.1) !important;
        color: white !important;
        border: 1px solid rgba(255, 255, 255, 0.2) !important;
        padding: 6px 18px;
        border-radius: 50px;
        font-size: 13px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        text-decoration: none;
        transition: all 0.15s;
        font-family: 'Barlow', sans-serif;
        height: 32px;
    }
    .secondary-nav-link:hover {
        background: rgba(255, 255, 255, 0.2) !important;
        border-color: white !important;
        color: white !important;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    .ubadge {
      display: flex; align-items: center; gap: 8px; padding: 4px 14px 4px 4px; border-left: 1px solid var(--border); margin-left: 8px;
    }
    .avatar {
      width: 32px; height: 32px; border-radius: 50%;
      background: linear-gradient(135deg, #1a9a8a, #0c4a5b);
      display: flex; align-items: center; justify-content: center;
      font-size: 11px; font-weight: 700; color: white; letter-spacing: 0.5px;
    }
    .uname { font-size: 14px; font-weight: 600; color: #ffffffd9; white-space: nowrap; }
    .btn-out {
      display: flex; align-items: center; gap: 6px;
      border: 1px solid rgba(255,255,255,0.16);
      border-radius: 24px; padding: 7px 16px;
      background: transparent; color: rgba(255,255,255,0.6);
      font-size: 12px; font-weight: 500; font-family: 'DM Sans', sans-serif;
      cursor: pointer; transition: all 0.18s;
    }
    .btn-out:hover { background: rgba(255,255,255,0.09); color: white; }
    .btn-out svg { width: 13px; height: 13px; }

    .btn-help {
        width: 32px; height: 32px; border-radius: 50%;
        border: 1px solid rgba(255,255,255,0.2); background: rgba(255,255,255,0.1);
        display: flex; align-items: center; justify-content: center;
        color: rgba(255,255,255,0.8); cursor: pointer; transition: all 0.2s;
        text-decoration: none; font-size: 14px;
    }
    .btn-help:hover {
        background: rgba(255,255,255,0.2); color: white; border-color: white;
        transform: translateY(-1px);
    }

    /* Driver.js Custom Styles (Consistent with Dashboard) */
    .driver-popover {
        background-color: #ffffff !important;
        border-radius: 16px !important;
        padding: 20px !important;
        box-shadow: 0 10px 30px rgba(0,0,0,0.15) !important;
        border: 1px solid #e2e8ef !important;
        font-family: 'Barlow', sans-serif !important;
    }
    .driver-popover-title {
        font-family: 'Barlow Condensed', sans-serif !important;
        font-weight: 800 !important;
        font-size: 20px !important;
        color: #1a2e2c !important;
        text-transform: uppercase !important;
        letter-spacing: 0.5px !important;
    }
    .driver-popover-description {
        font-size: 14px !important;
        color: #64748b !important;
        line-height: 1.5 !important;
        margin-top: 8px !important;
    }
    .driver-popover-btn {
        background: var(--accent, #1a9a8a) !important;
        color: white !important;
        text-shadow: none !important;
        border: none !important;
        padding: 6px 14px !important;
        border-radius: 8px !important;
        font-weight: 600 !important;
        font-size: 12px !important;
        transition: all 0.2s !important;
    }
    .driver-popover-btn:hover { background: var(--accent-dark, #115e57) !important; }
    .driver-popover-close-btn { color: #8f9db0 !important; }
    .driver-popover-arrow { border-color: #ffffff !important; }

    @media (max-width: 768px) {
        .topbar { padding: 0 15px; }
        .nav-actions { display: none !important; }
        .uname { display: none; }
        .btn-out { font-size: 11px; padding: 6px 12px; gap: 4px; }
        .btn-out svg { width: 12px; height: 12px; margin: 0; }
        .ubadge { padding-right: 0; border: none; margin-left: 0; }
        .topbar-right { flex-direction: row-reverse; gap: 15px; }
        .btn-help, .secondary-nav-link { display: none !important; }
    }
</style>
