@extends('layouts.app')

@section('title', 'Viantryp | Mi Perfil')

@push('styles')
@include('layouts.theme-styles')
<link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@700;800;900&family=Barlow:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/driver.js@1.0.1/dist/driver.css"/>
<style>
  :root {
    --accent-light: #e6f4f3;
    --accent-dark: #115e57;
    --bg: #f4f6f5;
    --card: #ffffff;
    --text: #1a2e2c;
    --muted: #7a9290;
    --border: #e0ecea;
    --avatar-bg: var(--accent);
  }

  @font-face {
    font-family: 'Dongra Script';
    src: url('/fonts/Dongra Script.ttf') format('truetype');
  }

  /* THEME OVERRIDES FOR LOCAL VARS */
  [data-theme="ocean"]  { --accent-light:#e6f0f7; --accent-dark:#0d3d5e; }
  [data-theme="sunset"] { --accent-light:#fdf0eb; --accent-dark:#8c3a1a; }
  [data-theme="gold"]   { --accent-light:#fdf8e6; --accent-dark:#7a5800; }
  [data-theme="blush"]     { --accent-light:#fdf0f4; --accent-dark:#b55677; }
  [data-theme="mint"]      { --accent-light:#e8f8f5; --accent-dark:#267a65; }
  [data-theme="lavender"]  { --accent-light:#f4eeff; --accent-dark:#6d4ea0; }
  [data-theme="silver"]    { --accent-light:#eef1f1; --accent-dark:#4a5859; }

  /* Adjustments for integration */
  body {
    background: var(--bg);
    color: var(--text);
  }

  .page-wrapper {
    max-width: 1060px;
    margin: 0 auto;
    padding: 40px 24px 80px;
    font-family: 'Barlow', sans-serif;
  }

  .page-title {
    font-family: 'Barlow Condensed', sans-serif;
    font-size: 32px;
    font-weight: 900;
    color: #000000;
    margin-bottom: 8px;
    text-transform: uppercase;
    letter-spacing: -0.5px;
    line-height: 1.1;
  }

  .page-subtitle {
    color: var(--muted);
    font-size: 15px;
    font-weight: 400;
    margin-bottom: 24px;
  }

  .grid {
    display: grid;
    grid-template-columns: 280px 1fr;
    gap: 24px;
    align-items: start;
  }

  .card {
    background: var(--card);
    border-radius: 16px;
    border: 1px solid var(--border);
    overflow: hidden;
    transition: border-color 0.3s;
  }

  .profile-card {
    text-align: center;
    padding: 32px 24px 24px;
  }

    .avatar-delete-btn:hover { opacity: 1; transform: scale(1.1); }
    .avatar-delete-btn svg { width: 12px; height: 12px; stroke: white; fill: none; stroke-width: 2.5; }

  .avatar-wrapper {
    position: relative;
    display: inline-block;
    margin-bottom: 16px;
  }

  .avatar-big {
    width: 88px;
    height: 88px;
    background: var(--avatar-bg);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-family: 'Barlow Condensed', sans-serif;
    font-size: 32px;
    font-weight: 800;
    margin: 0 auto;
    transition: background 0.3s;
    cursor: pointer;
    position: relative;
    overflow: hidden;
  }

  .avatar-big img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    position: absolute;
    top: 0; left: 0;
    border-radius: 50%;
  }

  .avatar-edit-btn {
    position: absolute;
    bottom: 2px;
    right: 2px;
    width: 26px;
    height: 26px;
    background: var(--accent);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    border: 2px solid white;
  }

  .avatar-edit-btn svg { width: 12px; height: 12px; stroke: white; fill: none; stroke-width: 2.5; }

  .avatar-delete-btn {
    position: absolute;
    bottom: 2px;
    left: 2px;
    width: 26px;
    height: 26px;
    background: #ef4444;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    border: 2px solid white;
    transition: all 0.2s;
    opacity: 0.8;
  }
  .avatar-delete-btn:hover { opacity: 1; transform: scale(1.1); }
  .avatar-delete-btn svg { width: 12px; height: 12px; stroke: white; fill: none; stroke-width: 2.5; }

  .profile-name {
    font-family: 'Barlow', sans-serif;
    font-size: 20px;
    font-weight: 700;
    color: var(--text);
    margin-bottom: 4px;
  }

  .profile-email {
    color: var(--muted);
    font-size: 13px;
    margin-bottom: 16px;
  }

  .plan-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: var(--accent-light);
    color: var(--accent);
    border-radius: 20px;
    padding: 5px 14px;
    font-size: 12px;
    font-weight: 600;
    margin-bottom: 20px;
    border: 1px solid var(--accent);
    transition: all 0.3s;
  }

  .plan-dot { width: 7px; height: 7px; background: var(--accent); border-radius: 50%; }


  .sidebar-nav { padding: 8px; }
  .nav-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 11px 14px;
    border-radius: 10px;
    font-size: 14px;
    font-weight: 500;
    color: var(--muted);
    cursor: pointer;
    transition: all 0.18s;
    text-decoration: none;
    border: none;
    background: transparent;
    width: 100%;
    text-align: left;
    font-family: 'DM Sans', sans-serif;
  }

  .nav-item:hover { background: var(--accent-light); color: var(--accent); }
  .nav-item.active { background: var(--accent-light); color: var(--accent); font-weight: 600; }
  .nav-item svg { width: 17px; height: 17px; stroke: currentColor; fill: none; stroke-width: 1.8; flex-shrink: 0; }

  .main-content { display: flex; flex-direction: column; gap: 20px; }
  .section-label {
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 1.2px;
    color: var(--muted);
    text-transform: uppercase;
    margin-bottom: 18px;
  }

  .card-body { padding: 28px; }
  .form-group { margin-bottom: 20px; }
  .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }

  label {
    display: block;
    font-size: 12px;
    font-weight: 600;
    color: var(--muted);
    margin-bottom: 7px;
    text-transform: uppercase;
    letter-spacing: 0.6px;
  }

  input[type="text"], input[type="email"], input[type="tel"], input[type="password"], select, textarea {
    width: 100%;
    background: var(--bg);
    border: 1.5px solid var(--border);
    border-radius: 10px;
    padding: 11px 14px;
    font-size: 14px;
    font-family: 'DM Sans', sans-serif;
    color: var(--text);
    outline: none;
    transition: border-color 0.2s, box-shadow 0.2s;
  }

  input:focus, select:focus, textarea:focus {
    border-color: var(--accent);
    box-shadow: 0 0 0 3px var(--accent-light);
  }

  textarea { resize: vertical; min-height: 80px; }

  .logo-upload-area {
    border: 2px dashed var(--border);
    border-radius: 12px;
    padding: 28px;
    text-align: center;
    cursor: pointer;
    transition: all 0.2s;
    background: var(--bg);
    position: relative;
  }

  .logo-upload-area:hover {
    border-color: var(--accent);
    background: var(--accent-light);
  }

  .logo-upload-area input { position: absolute; inset: 0; opacity: 0; cursor: pointer; width: 100%; height: 100%; }

  .logo-preview {
    width: 80px;
    height: 80px;
    object-fit: contain;
    margin: 0 auto 12px;
    display: block;
    border-radius: 8px;
  }

  .upload-icon {
    width: 40px;
    height: 40px;
    background: var(--accent-light);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 10px;
    transition: background 0.3s;
  }

  .upload-icon svg { width: 20px; height: 20px; stroke: var(--accent); fill: none; stroke-width: 1.8; }
  .upload-text { font-size: 13px; font-weight: 600; color: var(--text); margin-bottom: 4px; }
  .upload-hint { font-size: 12px; color: var(--muted); }

  .theme-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 14px;
  }

  .theme-group-label {
    font-size: 10px;
    font-weight: 700;
    letter-spacing: 1.2px;
    text-transform: uppercase;
    color: var(--muted);
    margin: 20px 0 6px;
    grid-column: 1 / -1;
  }

  .theme-option {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 8px;
    cursor: pointer;
  }

  .theme-swatch {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    border: 3px solid transparent;
    transition: all 0.2s;
    position: relative;
    overflow: hidden;
  }

  .theme-swatch::after {
    content: '✓';
    position: absolute;
    inset: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 18px;
    font-weight: 700;
    opacity: 0;
    transition: opacity 0.2s;
  }

  .theme-option.selected .theme-swatch {
    border-color: var(--text);
    box-shadow: 0 0 0 2px white, 0 0 0 4px var(--text);
  }

  .theme-option.selected .theme-swatch::after { opacity: 1; }
  .theme-name { font-size: 11px; font-weight: 600; color: var(--muted); }

  .itinerary-preview {
    background: var(--bg);
    border-radius: 12px;
    padding: 16px;
    margin-top: 20px;
    border: 1px solid var(--border);
  }

  .preview-label { font-size: 11px; font-weight: 700; color: var(--muted); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 12px; }

  .preview-header {
    background: var(--accent);
    border-radius: 8px 8px 0 0;
    padding: 10px 14px;
    color: white;
    font-size: 12px;
    font-weight: 700;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    transition: background 0.3s;
    min-height: 42px;
  }

  .preview-header img {
    max-height: 32px;
    width: auto;
    filter: brightness(0) invert(1);
    object-fit: contain;
  }

  .preview-header span {
    font-family: 'Dongra Script', cursive !important;
    font-size: 18px;
    color: white;
    font-weight: 400;
  }


  .preview-body {
    background: white;
    border-radius: 0 0 8px 8px;
    padding: 12px 14px;
    display: flex;
    flex-direction: column;
    gap: 8px;
  }

  .preview-day { display: flex; align-items: center; gap: 8px; }
  .preview-day-dot { width: 8px; height: 8px; border-radius: 50%; background: var(--accent); flex-shrink: 0; transition: background 0.3s; }
  .preview-day-line { height: 8px; border-radius: 4px; background: var(--border); flex: 1; }
  .preview-day-line.short { flex: 0.6; }

  .btn-save {
    background: var(--accent);
    color: white;
    border: none;
    border-radius: 12px;
    padding: 13px 28px;
    font-size: 14px;
    font-weight: 700;
    font-family: 'DM Sans', sans-serif;
    cursor: pointer;
    transition: all 0.2s;
    display: flex;
    align-items: center;
    gap: 8px;
  }

  .btn-save:hover { background: var(--accent-dark); transform: translateY(-1px); box-shadow: 0 4px 12px rgba(0,0,0,0.15); }

  .btn-secondary {
    background: transparent;
    color: var(--muted);
    border: 1.5px solid var(--border);
    border-radius: 12px;
    padding: 12px 24px;
    font-size: 14px;
    font-weight: 600;
    font-family: 'DM Sans', sans-serif;
    cursor: pointer;
    transition: all 0.2s;
  }

  .btn-secondary:hover { color: var(--text); border-color: var(--text); }
  .btn-row { display: flex; align-items: center; gap: 12px; }

  .toast-profile {
    position: fixed;
    bottom: 30px;
    right: 30px;
    background: var(--accent);
    color: white;
    padding: 14px 22px;
    border-radius: 12px;
    font-size: 14px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 10px;
    box-shadow: 0 8px 24px rgba(0,0,0,0.15);
    opacity: 0;
    transform: translateY(10px);
    transition: all 0.3s;
    pointer-events: none;
    z-index: 999;
  }

  .toast-profile.show { opacity: 1; transform: translateY(0); }
  .divider { height: 1px; background: var(--border); margin: 4px 0; }

  .danger-text { color: #c0392b; font-size: 13px; margin-bottom: 16px; }
  .btn-danger-profile {
    background: transparent;
    color: #c0392b;
    border: 1.5px solid #f5b8b8;
    border-radius: 10px;
    padding: 10px 20px;
    font-size: 13px;
    font-weight: 600;
    font-family: 'DM Sans', sans-serif;
    cursor: pointer;
    transition: all 0.2s;
  }
  .btn-danger-profile:hover { background: #fff0f0; border-color: #c0392b; }

  .tab-section { display: none; }
  .tab-section.active { display: block; }

  @media (max-width: 768px) {
    .grid { grid-template-columns: 1fr; }
    .form-row { grid-template-columns: 1fr; }
    .theme-grid { grid-template-columns: repeat(5, 1fr); }
  }
</style>
@endpush

@section('content')
<x-header tutorialOnclick="initProfileTutorial(true)" />

<div class="page-wrapper">
  <h1 class="page-title">Mi Perfil</h1>
  <p class="page-subtitle">Gestiona tu información personal y personaliza tu agencia</p>

  <div class="grid">
    <!-- SIDEBAR -->
    <div class="sidebar">
      <!-- Profile Card -->
      <div class="card profile-card">
        <div class="avatar-wrapper">
          <div class="avatar-big" id="avatarBig">
            <span id="avatarInitial" style="{{ $user->avatar ? 'display:none' : '' }}">{{ $user->display_initials }}</span>
            <img id="avatarImg" src="{{ $user->avatar ? asset('storage/' . $user->avatar) : '' }}" alt="" style="{{ $user->avatar ? '' : 'display:none' }}">
          </div>
          <div class="avatar-edit-btn" title="Subir foto">
            <svg viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
          </div>
          <div class="avatar-delete-btn" id="avatarDeleteBtn" title="Eliminar foto" style="{{ $user->avatar ? '' : 'display:none' }}">
            <svg viewBox="0 0 24 24"><path d="M3 6h18m-2 0v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
          </div>
          <input type="file" id="avatarUpload" accept="image/*" style="display:none">
        </div>
        <div class="profile-name" id="profileName">{{ $user->display_name }}</div>
        <div class="profile-email">{{ $user->email }}</div>
        <div class="plan-badge">
          <div class="plan-dot"></div>
          Plan Pro
        </div>
      </div>

      <!-- Sidebar Nav -->
      <div class="card sidebar-nav">
        <button class="nav-item active" data-section="info">
          <svg viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
          Información Personal
        </button>
        <button class="nav-item" data-section="agencia">
          <svg viewBox="0 0 24 24"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2"/></svg>
          Mi Agencia
        </button>
        <button class="nav-item" data-section="tema">
          <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M12 2a10 10 0 0 1 10 10H2A10 10 0 0 1 12 2z"/><path d="M2 12h20"/></svg>
          Tema e Identidad
        </button>
        <div class="divider"></div>
        <button class="nav-item" data-section="seguridad">
          <svg viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
          Seguridad
        </button>
      </div>
    </div>

    <!-- MAIN -->
    <div class="main-content">

      <!-- INFORMACIÓN PERSONAL -->
      <div class="card tab-section active" id="section-info">
        <div class="card-body">
          <div class="section-label">Información Personal</div>
          <div class="form-row">
            <div class="form-group">
              <label>Nombre</label>
              <input type="text" id="inputNombre" value="{{ $user->name }}">
            </div>
            <div class="form-group">
              <label>Apellido</label>
              <input type="text" id="inputApellido" value="{{ $user->last_name }}">
            </div>
          </div>
          <div class="form-group">
            <label>Correo Electrónico</label>
            <input type="email" value="{{ auth()->user()->email }}" disabled>
          </div>
          <div class="form-row">
            <div class="form-group">
              <label>Teléfono</label>
              <input type="tel" id="inputPhone" value="{{ $user->phone }}" placeholder="+57 300 000 0000">
            </div>
            <div class="form-group">
              <label>País</label>
              <select id="inputCountry">
                <option value="Colombia" {{ $user->country == 'Colombia' ? 'selected' : '' }}>Colombia</option>
                <option value="México" {{ $user->country == 'México' ? 'selected' : '' }}>México</option>
                <option value="Argentina" {{ $user->country == 'Argentina' ? 'selected' : '' }}>Argentina</option>
                <option value="España" {{ $user->country == 'España' ? 'selected' : '' }}>España</option>
                <option value="Chile" {{ $user->country == 'Chile' ? 'selected' : '' }}>Chile</option>
                <option value="Perú" {{ $user->country == 'Perú' ? 'selected' : '' }}>Perú</option>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label>Sobre mí</label>
            <textarea id="inputBio" placeholder="Cuéntale a tus clientes sobre tu experiencia como consultor de viajes...">{{ $user->bio }}</textarea>
          </div>
          
          <div class="form-group" id="personalNameOption" style="margin-top: 24px; padding: 16px; background: var(--accent-light); border-radius: 12px; border: 1px solid var(--accent-border);">
            <label style="display: flex; align-items: center; gap: 12px; cursor: pointer; text-transform: none; letter-spacing: normal; color: var(--accent-dark); margin-bottom: 0; font-family: 'Barlow', sans-serif;">
              <input type="radio" name="displayNameType" value="personal" {{ $user->display_name_type == 'personal' ? 'checked' : '' }} style="width: 18px; height: 18px; accent-color: var(--accent);">
              <span style="font-size: 14px;">Presentarme con mi <strong>nombre personal</strong> en mis propuestas y perfil.</span>
            </label>
          </div>

          <div class="btn-row">
            <button class="btn-save" id="savePersonalInfo">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
              Guardar Cambios
            </button>
            <button class="btn-secondary">Cancelar</button>
          </div>
        </div>
      </div>

      <!-- AGENCIA -->
      <div class="card tab-section" id="section-agencia">
        <div class="card-body">
          <div class="section-label">Datos de la Agencia</div>
          <div class="form-group">
            <label>Nombre de la Agencia</label>
            <input type="text" id="inputAgencia" value="{{ $user->agency_name }}">
          </div>
          <div class="form-row">
            <div class="form-group">
              <label>Sitio Web</label>
              <input type="text" id="inputWebsite" value="{{ $user->agency_website }}" placeholder="https://miagencia.com">
            </div>
            <div class="form-group">
              <label>WhatsApp</label>
              <input type="tel" id="inputWhatsapp" value="{{ $user->agency_whatsapp }}" placeholder="+57 300 000 0000">
            </div>
          </div>
          <div class="form-group">
            <label>Eslogan</label>
            <input type="text" id="inputSlogan" value="{{ $user->agency_slogan }}" placeholder="Tu agencia de confianza para viajar el mundo">
          </div>
          <div class="form-group">
            <label>Logo de la Agencia</label>
            <div class="logo-upload-area" id="logoDropArea">
              <input type="file" accept="image/*">
              <div id="logoPlaceholder">
                <div class="upload-icon">
                  <svg viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                </div>
                <div class="upload-text">Sube el logo de tu agencia</div>
                <div class="upload-hint">PNG, SVG o JPG · Máx. 2MB · Recomendado 200×200px</div>
              </div>
              <img id="logoPreview" class="logo-preview" src="" alt="" style="display:none">
            </div>
          </div>

          <div class="form-group" id="agencyNameOption" style="margin-top: 24px; padding: 16px; background: var(--accent-light); border-radius: 12px; border: 1px solid var(--accent-border);">
            <label style="display: flex; align-items: center; gap: 12px; cursor: pointer; text-transform: none; letter-spacing: normal; color: var(--accent-dark); margin-bottom: 0; font-family: 'Barlow', sans-serif;">
              <input type="radio" name="displayNameType" value="agency" {{ $user->display_name_type == 'agency' ? 'checked' : '' }} style="width: 18px; height: 18px; accent-color: var(--accent);">
              <span style="font-size: 14px;">Presentarme con el <strong>nombre de mi agencia</strong> en mis propuestas y perfil.</span>
            </label>
          </div>

          <div class="btn-row">
            <button class="btn-save" id="saveAgencyInfo">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
              Guardar Agencia
            </button>
          </div>
        </div>
      </div>

      <!-- TEMA -->
      <div class="card tab-section" id="section-tema">
        <div class="card-body">
          <div class="section-label">Tema e Identidad Visual</div>
          <p style="font-size:13px;color:var(--muted);margin-bottom:24px;">Elige el color principal que aparecerá en todos tus itinerarios y propuestas de viaje.</p>

          <div class="theme-grid" id="themeGrid" data-selected="{{ $user->theme_color ?? 'default' }}">
            <div class="theme-option {{ ($user->theme_color ?? 'default') == 'default' ? 'selected' : '' }}" data-theme="default">
              <div class="theme-swatch" style="background:#1a7f77"></div>
              <span class="theme-name">Teal</span>
            </div>
            <div class="theme-option {{ $user->theme_color == 'ocean' ? 'selected' : '' }}" data-theme="ocean">
              <div class="theme-swatch" style="background:#1a5f8f"></div>
              <span class="theme-name">Ocean</span>
            </div>
            <div class="theme-option {{ $user->theme_color == 'gold' ? 'selected' : '' }}" data-theme="gold">
              <div class="theme-swatch" style="background:#b08000"></div>
              <span class="theme-name">Gold</span>
            </div>
            <div class="theme-option {{ $user->theme_color == 'sunset' ? 'selected' : '' }}" data-theme="sunset">
              <div class="theme-swatch" style="background:#c0552a"></div>
              <span class="theme-name">Terracota</span>
            </div>
            <div class="theme-option {{ $user->theme_color == 'blush' ? 'selected' : '' }}" data-theme="blush">
              <div class="theme-swatch" style="background:linear-gradient(135deg,#e07b9a,#f4a5bd)"></div>
              <span class="theme-name">Blush</span>
            </div>
            <div class="theme-option {{ $user->theme_color == 'silver' ? 'selected' : '' }}" data-theme="silver">
              <div class="theme-swatch" style="background:linear-gradient(135deg,#6e7f80,#9aa8a9)"></div>
              <span class="theme-name">Silver</span>
            </div>
            <div class="theme-option {{ $user->theme_color == 'mint' ? 'selected' : '' }}" data-theme="mint">
              <div class="theme-swatch" style="background:linear-gradient(135deg,#3db898,#62d4b5)"></div>
              <span class="theme-name">Menta</span>
            </div>
            <div class="theme-option {{ $user->theme_color == 'lavender' ? 'selected' : '' }}" data-theme="lavender">
              <div class="theme-swatch" style="background:linear-gradient(135deg,#9b72cf,#b39ddb)"></div>
              <span class="theme-name">Lavanda</span>
            </div>
          </div>

          <!-- Preview mini -->
          <div class="itinerary-preview">
            <div class="preview-label">Vista previa del itinerario</div>
            <div class="preview-header" id="previewHeaderContent">
              <!-- Dynamic content -->
            </div>
            <div class="preview-body">
              <div class="preview-day">
                <div class="preview-day-dot"></div>
                <div class="preview-day-line"></div>
              </div>
              <div class="preview-day">
                <div class="preview-day-dot"></div>
                <div class="preview-day-line short"></div>
              </div>
              <div class="preview-day">
                <div class="preview-day-dot"></div>
                <div class="preview-day-line"></div>
              </div>
            </div>
          </div>

          <div class="btn-row" style="margin-top:24px">
            <button class="btn-save" id="saveTheme">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
              Aplicar Tema
            </button>
          </div>
        </div>
      </div>

      <!-- SEGURIDAD -->
      <div class="card tab-section" id="section-seguridad">
        <div class="card-body">
          <div class="section-label">Cambiar Contraseña</div>
          <div class="form-group">
            <label>Contraseña Actual</label>
            <input type="password" placeholder="••••••••">
          </div>
          <div class="form-row">
            <div class="form-group">
              <label>Nueva Contraseña</label>
              <input type="password" placeholder="••••••••">
            </div>
            <div class="form-group">
              <label>Confirmar Contraseña</label>
              <input type="password" placeholder="••••••••">
            </div>
          </div>
          <div class="btn-row" style="margin-bottom:32px">
            <button class="btn-save">Actualizar Contraseña</button>
          </div>

          <div class="divider" style="margin-bottom:28px"></div>

          <div class="section-label" style="color:#c0392b">Zona de Peligro</div>
          <p class="danger-text">Eliminar tu cuenta borrará permanentemente todos tus itinerarios, datos y configuración. Esta acción no se puede deshacer.</p>
          <button class="btn-danger-profile">Eliminar mi cuenta</button>
        </div>
      </div>

    </div>
  </div>
</div>

<!-- TOAST -->
<div class="toast-profile" id="toastProfile">
  <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
  Cambios guardados exitosamente
</div>
@endsection

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function() {
    // CSRF Setup for Fetch
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // NAV ITEMS — navegación entre secciones
    document.querySelectorAll('.nav-item[data-section]').forEach(function(btn) {
      btn.addEventListener('click', function() {
        var id = btn.getAttribute('data-section');
        document.querySelectorAll('.tab-section').forEach(function(s) { s.classList.remove('active'); });
        document.querySelectorAll('.nav-item').forEach(function(b) { b.classList.remove('active'); });
        document.getElementById('section-' + id).classList.add('active');
        btn.classList.add('active');
      });
    });

    // Toast helper
    function showToast(message) {
      var toast = document.getElementById('toastProfile');
      toast.innerHTML = `<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg> ${message}`;
      toast.classList.add('show');
      setTimeout(function() { toast.classList.remove('show'); }, 3000);
    }

    // SAVE PERSONAL INFO
    document.getElementById('savePersonalInfo').addEventListener('click', function() {
      const data = {
        name: document.getElementById('inputNombre').value,
        last_name: document.getElementById('inputApellido').value,
        phone: document.getElementById('inputPhone').value,
        country: document.getElementById('inputCountry').value,
        bio: document.getElementById('inputBio').value,
        display_name_type: document.querySelector('input[name="displayNameType"]:checked').value
      };

      fetch('{{ route('profile.update.personal') }}', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': csrfToken,
          'Accept': 'application/json'
        },
        body: JSON.stringify(data)
      })
      .then(res => res.json())
      .then(res => {
        if(res.success) showToast(res.message);
      });
    });

    // SAVE AGENCY INFO
    document.getElementById('saveAgencyInfo').addEventListener('click', function() {
      const data = {
        agency_name: document.getElementById('inputAgencia').value,
        agency_website: document.getElementById('inputWebsite').value,
        agency_whatsapp: document.getElementById('inputWhatsapp').value,
        agency_slogan: document.getElementById('inputSlogan').value,
        display_name_type: document.querySelector('input[name="displayNameType"]:checked').value
      };

      fetch('{{ route('profile.update.agency') }}', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': csrfToken,
          'Accept': 'application/json'
        },
        body: JSON.stringify(data)
      })
      .then(res => res.json())
      .then(res => {
        if(res.success) showToast(res.message);
      });
    });

    // SAVE THEME
    document.getElementById('saveTheme').addEventListener('click', function() {
      const selected = document.querySelector('.theme-option.selected');
      const theme = selected ? selected.getAttribute('data-theme') : 'default';

      fetch('{{ route('profile.update.theme') }}', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': csrfToken,
          'Accept': 'application/json'
        },
        body: JSON.stringify({ theme_color: theme })
      })
      .then(res => res.json())
      .then(res => {
        if(res.success) {
            showToast(res.message);
            // Dynamic change of UI colors if needed immediately
            location.reload(); // Simple way to apply global theme injection
        }
      });
    });

    // THEME OPTIONS - Preview only
    document.querySelectorAll('.theme-option[data-theme]').forEach(function(el) {
      el.addEventListener('click', function() {
        var theme = el.getAttribute('data-theme');
        document.body.setAttribute('data-theme', theme === 'default' ? '' : theme);
        document.querySelectorAll('.theme-option').forEach(function(o) { o.classList.remove('selected'); });
        el.classList.add('selected');
      });
    });

    // NOMBRE en tiempo real
    var inputNombre = document.getElementById('inputNombre');
    var inputApellido = document.getElementById('inputApellido');
    if (inputNombre) inputNombre.addEventListener('input', updateName);
    if (inputApellido) inputApellido.addEventListener('input', updateName);

    function updateName() {
      updateDisplayNames();
    }

    // AGENCIA en tiempo real
    var inputAgencia = document.getElementById('inputAgencia');
    if (inputAgencia) {
      inputAgencia.addEventListener('input', function() {
        updateDisplayNames();
      });
    }

    // DISPLAY NAME PREFERENCE in real-time
    document.querySelectorAll('input[name="displayNameType"]').forEach(function(radio) {
      radio.addEventListener('change', function() {
        var val = this.value;
        // Sync radios between sections
        document.querySelectorAll('input[name="displayNameType"]').forEach(r => {
            if (r.value === val) r.checked = true;
        });
        updateDisplayNames();
      });
    });

    function updateDisplayNames() {
      var typeEl = document.querySelector('input[name="displayNameType"]:checked');
      if (!typeEl) return;
      var type = typeEl.value;
      var nameVal = '';
      var initials = '';

      if (type === 'personal') {
        var n = inputNombre ? inputNombre.value : '';
        var a = inputApellido ? inputApellido.value : '';
        nameVal = (n + ' ' + a).trim() || 'Tu Nombre';
        initials = ((n[0] || '?') + (a[0] || '')).toUpperCase();
      } else {
        var aName = inputAgencia ? inputAgencia.value : '';
        nameVal = aName || 'Mi Agencia';
        var words = nameVal.split(' ').filter(w => w.length > 0);
        initials = (words[0] ? words[0][0] : 'V').toUpperCase() + (words[1] ? words[1][0] : '').toUpperCase();
      }

      document.getElementById('profileName').textContent = nameVal;
      document.getElementById('avatarInitial').textContent = initials;
      
      // Update preview header
      var previewHeader = document.getElementById('previewHeaderContent');
      if (previewHeader) {
          if (type === 'agency') {
              var logoUrl = document.getElementById('logoPreview') ? document.getElementById('logoPreview').getAttribute('src') : null;
              var agencyName = inputAgencia ? inputAgencia.value : '{{ $user->agency_name }}';
              
              if (logoUrl && logoUrl.trim() !== '') {
                  previewHeader.innerHTML = `<img src="${logoUrl}" alt="Logo">`;
              } else {
                  previewHeader.innerHTML = `<span>${agencyName || 'Mi Agencia'}</span>`;
              }
          } else {
              previewHeader.innerHTML = `<span>${nameVal}</span>`;
          }
      }
      
      // Update topbar names
      document.querySelectorAll('.uname').forEach(el => el.textContent = nameVal);
      
      // Update topbar initials (if no avatar)
      var avatarImg = document.getElementById('avatarImg');
      if (!avatarImg || !avatarImg.src || avatarImg.style.display === 'none') {
          document.querySelectorAll('.ubadge .avatar').forEach(avatar => {
              if (!avatar.querySelector('img')) {
                  avatar.textContent = initials;
              }
          });
      }
    }

    // LOGO UPLOAD
    var logoInput = document.querySelector('.logo-upload-area input[type="file"]');
    if (logoInput) {
      logoInput.addEventListener('change', function() {
        var file = this.files[0];
        if (!file) return;

        const formData = new FormData();
        formData.append('logo', file);

        fetch('{{ route('profile.upload.logo') }}', {
          method: 'POST',
          headers: { 'X-CSRF-TOKEN': csrfToken },
          body: formData
        })
        .then(res => res.json())
        .then(res => {
            if(res.success) {
                var preview = document.getElementById('logoPreview');
                preview.src = res.url;
                preview.style.display = 'block';
                document.getElementById('logoPlaceholder').style.display = 'none';
                
                updateDisplayNames();
                showToast('Logo actualizado');
            }
        });
      });
    }

    // AVATAR UPLOAD
    var avatarInput = document.getElementById('avatarUpload');
    if (avatarInput) {
      avatarInput.addEventListener('change', function() {
        var file = this.files[0];
        if (!file) return;

        const formData = new FormData();
        formData.append('avatar', file);

        fetch('{{ route('profile.upload.avatar') }}', {
          method: 'POST',
          headers: { 'X-CSRF-TOKEN': csrfToken },
          body: formData
        })
        .then(res => res.json())
        .then(res => {
            if(res.success) {
                var img = document.getElementById('avatarImg');
                img.src = res.url;
                img.style.display = 'block';
                document.getElementById('avatarInitial').style.display = 'none';
                document.getElementById('avatarDeleteBtn').style.display = 'flex';
                showToast('Avatar actualizado');
            }
        });
      });
    }

    // AVATAR EDIT BTN
    var avatarEditBtn = document.querySelector('.avatar-edit-btn');
    if (avatarEditBtn) {
      avatarEditBtn.addEventListener('click', function() {
        document.getElementById('avatarUpload').click();
      });
    }

    // AVATAR DELETE BTN
    var avatarDeleteBtn = document.getElementById('avatarDeleteBtn');
    if (avatarDeleteBtn) {
        avatarDeleteBtn.addEventListener('click', function() {
            if (confirm('¿Estás seguro de que quieres eliminar tu foto de perfil?')) {
                fetch('{{ route('profile.delete.avatar') }}', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': csrfToken },
                })
                .then(res => res.json())
                .then(res => {
                    if(res.success) {
                        document.getElementById('avatarImg').style.display = 'none';
                        document.getElementById('avatarInitial').style.display = 'block';
                        avatarDeleteBtn.style.display = 'none';
                        
                        // Actualizar avatares de navegación
                        var navAvatars = document.querySelectorAll('.avatar img');
                        navAvatars.forEach(img => img.parentElement.innerHTML = document.getElementById('avatarInitial').textContent);
                        
                        showToast('Foto de perfil eliminada');
                    }
                });
            }
        });
    }

    // Auto-start tutorial
    setTimeout(() => {
        if (typeof initProfileTutorial === 'function') {
            initProfileTutorial();
        }
    }, 1200);
  });

  function initProfileTutorial(force = false) {
    if (!window.driver) return;
    const driver = window.driver.js.driver;
    const tutorialsSeen = window.ViantrypTutorials || [];
    const hasSeenTutorial = tutorialsSeen.includes('profile');

    if (hasSeenTutorial && !force) return;

    const driverObj = driver({
        showProgress: true,
        animate: true,
        allowClose: true,
        nextBtnText: 'Siguiente',
        prevBtnText: 'Anterior',
        doneBtnText: 'Finalizar',
        steps: [
            { 
                element: '.page-title', 
                popover: { 
                    title: '¡Tu Perfil!', 
                    description: 'Aquí es donde sucede la magia de la personalización. Configura cómo te ven tus clientes y el estilo de tus propuestas.' 
                } 
            },
            { 
                element: '#personalNameOption', 
                popover: { 
                    title: 'Identidad Personal', 
                    description: 'Si prefieres que tus propuestas tengan un toque más cercano usando tu nombre y apellido, marca esta opción.',
                    position: 'top'
                },
                onHighlightStarted: () => {
                   document.querySelector('.nav-item[data-section="info"]').click();
                }
            },
            { 
                element: '#agencyNameOption', 
                popover: { 
                    title: 'Identidad de Agencia', 
                    description: '¿Trabajas bajo una marca? Sube el logo de tu agencia y selecciona esta opción para que todas tus propuestas salgan con tu imagen corporativa.',
                    position: 'top'
                },
                onHighlightStarted: () => {
                   document.querySelector('.nav-item[data-section="agencia"]').click();
                }
            },
            { 
                element: '#themeGrid', 
                popover: { 
                    title: 'Personalización Visual', 
                    description: '¡Dale color a tus viajes! Elige el tema que mejor represente tu estilo. Verás el cambio reflejado al instante en la vista previa de abajo.' 
                },
                onHighlightStarted: () => {
                   document.querySelector('.nav-item[data-section="tema"]').click();
                }
            },
            { 
                element: '.itinerary-preview', 
                popover: { 
                    title: 'Vista Previa en Vivo', 
                    description: 'Así es como se verá la cabecera de tus itinerarios. Asegúrate de que todo luzca perfecto antes de enviar una propuesta.' 
                } 
            }
        ],
        onDestroyed: () => {
            if (!hasSeenTutorial) {
                fetch('{{ route("profile.complete.tutorial") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ tutorial: 'profile' })
                });
                if (!window.ViantrypTutorials.includes('profile')) {
                    window.ViantrypTutorials.push('profile');
                }
            }
        }
    });

    driverObj.drive();
  }
</script>
<script src="https://cdn.jsdelivr.net/npm/driver.js@1.0.1/dist/driver.js.iife.js"></script>
@endpush
