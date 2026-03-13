<header class="topbar">
  <div class="topbar-bg-decorators"></div>
  <div class="topbar-left">
    <a href="{{ route('trips.index') }}" class="logo">
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

    @auth
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
          <i class="fas fa-chevron-down" style="font-size: 10px; color: var(--gray); margin-left: 4px;"></i>
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

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', initMenu);
            } else {
                initMenu();
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

    @media (max-width: 768px) {
        .topbar { padding: 0 15px; }
        .nav-actions { display: none !important; }
        .uname { display: none; }
        .btn-out { font-size: 11px; padding: 6px 12px; gap: 4px; }
        .btn-out svg { width: 12px; height: 12px; margin: 0; }
        .ubadge { padding-right: 0; border: none; margin-left: 0; }
        .topbar-right { flex-direction: row-reverse; gap: 15px; }
    }
</style>
