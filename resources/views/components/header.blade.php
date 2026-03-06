<header class="topbar">
  <div class="topbar-left">
    <a href="{{ route('trips.index') }}" class="logo">
      <img src="/images/logo-viantryp.png" alt="Viantryp" style="height: 28px; width: auto; filter: brightness(0) invert(1);">
    </a>
    @if(isset($subtitle))
        <span class="header-subtitle" style="color: rgba(255, 255, 255, 0.9); font-size: 13px; font-weight: 600; display: flex; align-items: center; gap: 0.75rem;">
            <span style="opacity: 0.6;">|</span>
            {{ $subtitle }}
        </span>
    @endif
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
                        $btnStyle = 'cursor:pointer; background:white; color:#0d2b3e; border: 1px solid #e2e8ef; padding:6px 18px; border-radius:999px; font-size:13px; font-weight:600; display:inline-flex; align-items:center; text-decoration:none; transition:all 0.15s; font-family:\'DM Sans\',sans-serif; height: 32px;';
                    @endphp
                    @if(isset($action['onclick']))
                        <button type="button" class="{{ $action['class'] ?? '' }}" style="{{ $btnStyle }}" data-action="{{ $action['data-action'] ?? $action['onclick'] }}" onmouseover="this.style.background='#f5f7f9'" onmouseout="this.style.background='white'">
                            @if(isset($action['icon'])) <i class="{{ $action['icon'] }}" style="margin-right:6px;"></i> @endif
                            {{ $action['text'] }}
                        </button>
                    @else
                        <a href="{{ $action['url'] }}" class="{{ $action['class'] ?? '' }}" style="{{ $btnStyle }}" @if(isset($action['data-action'])) data-action="{{ $action['data-action'] }}" @endif @if(isset($action['target'])) target="{{ $action['target'] }}" @endif onmouseover="this.style.background='#f5f7f9'" onmouseout="this.style.background='white'">
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
    <div class="ubadge">
      <div class="avatar">{{ collect(explode(' ', auth()->user()->name))->map(function($word) { return strtoupper(substr($word, 0, 1)); })->take(2)->join('') }}</div>
      <span class="uname">{{ auth()->user()->name }}</span>
    </div>
    <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
        @csrf
        <button type="submit" class="btn-out">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
            Cerrar sesión
        </button>
    </form>
    @endauth
  </div>
</header>

<style>
    /* â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•
       TOPBAR GLOBALES
    â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â• */
    .topbar {
      position: sticky; top: 0; z-index: 200;
      background: #0f2a3a;
      height: 75px;
      display: flex; align-items: center; justify-content: space-between;
      padding: 0 4rem;
      overflow: hidden;
      flex-shrink: 0;
      font-family: 'DM Sans', sans-serif;
    }
    .topbar::before {
      content: ''; position: absolute; top: 0; right: 120px;
      width: 160px; height: 300%; background: #1a9a8a;
      transform: skewX(-16deg); opacity: 0.07; pointer-events: none;
    }
    .topbar::after {
      content: ''; position: absolute; top: 0; right: 60px;
      width: 60px; height: 300%; background: #1a9a8a;
      transform: skewX(-16deg); opacity: 0.04; pointer-events: none;
    }
    .topbar-left { display: flex; align-items: center; gap: 28px; position: relative; z-index: 1; }
    
    .logo {
      display: flex; align-items: center; text-decoration: none;
    }

    .nav-links { display: flex; gap: 4px; }
    .nav-link {
      font-size: 12px; font-weight: 500; color: rgba(255,255,255,0.7); text-decoration: none;
      padding: 6px 12px; border-radius: 7px; transition: background 0.18s, color 0.18s;
      font-family: 'DM Sans', sans-serif;
      background: transparent;
      line-height: 1;
      display: flex;
      align-items: center;
    }
    .nav-link:hover { background: rgba(255,255,255,0.08); color: rgba(255,255,255,0.95); }
    .nav-link.active { color: white; background: rgba(255,255,255,0.1); }

    .topbar-right { display: flex; align-items: center; gap: 10px; position: relative; z-index: 1; }
    .ubadge {
      display: flex; align-items: center; gap: 8px; padding: 4px 14px 4px 4px; border-left: 1px solid rgba(255,255,255,0.1); margin-left: 8px;
    }
    .avatar {
      width: 30px; height: 30px; border-radius: 50%;
      background: linear-gradient(135deg, #1a9a8a, #0c4a5b);
      display: flex; align-items: center; justify-content: center;
      font-size: 11px; font-weight: 700; color: white; letter-spacing: 0.5px;
    }
    .uname { font-size: 12px; font-weight: 600; color: rgba(255,255,255,0.85); white-space: nowrap; }
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
        .header-subtitle { display: none !important; }
        .uname { display: none; }
        .btn-out { font-size: 11px; padding: 6px 12px; gap: 4px; }
        .btn-out svg { width: 12px; height: 12px; margin: 0; }
        .ubadge { padding-right: 0; border: none; margin-left: 0; }
        .topbar-right { flex-direction: row-reverse; gap: 15px; }
    }
</style>
