@extends('layouts.app')

@section('title', 'Mis Viajes - Viantryp')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;0,900;1,700&family=DM+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet"/>
<style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    :root {
      --ink:   #0f2a3a;
      --teal:  #1a9a8a;
      --teal2: #0c4a5b;
      --tealL: rgba(26,154,138,0.10);
      --cream: #f4f6f8;
      --sand:  #e2e8ef;
      --bdr:   rgba(15,42,58,0.09);
      --gray:  #6b7a8d;
      --gray2: #8f9db0;
      --white: #ffffff;
    }

    html, body {
      height: 100%;
      font-family: 'DM Sans', sans-serif;
      color: var(--ink);
      background: var(--cream);
    }

    body { display: flex; flex-direction: column; min-height: 100vh; }

    /* ════════════════════════════════════════
       TOPBAR
    ════════════════════════════════════════ */
    .topbar {
      position: sticky; top: 0; z-index: 200;
      background: var(--ink);
      height: 75px;
      display: flex; align-items: center; justify-content: space-between;
      padding: 0 40px;
      overflow: hidden;
      flex-shrink: 0;
    }
    .topbar::before {
      content: ''; position: absolute; top: 0; right: 120px;
      width: 160px; height: 300%; background: var(--teal);
      transform: skewX(-16deg); opacity: 0.07; pointer-events: none;
    }
    .topbar::after {
      content: ''; position: absolute; top: 0; right: 60px;
      width: 60px; height: 300%; background: var(--teal);
      transform: skewX(-16deg); opacity: 0.04; pointer-events: none;
    }
    .topbar-left { display: flex; align-items: center; gap: 28px; position: relative; z-index: 1; }
    
    .logo {
      display: flex; align-items: center; text-decoration: none;
    }
    .logo img {
      height: 28px; width: auto; filter: brightness(0) invert(1);
    }

    .nav-links { display: flex; gap: 4px; }
    .nav-link {
      font-size: 13px; font-weight: 500; color: rgba(255,255,255,0.5); text-decoration: none;
      padding: 6px 12px; border-radius: 7px; transition: background 0.18s, color 0.18s;
    }
    .nav-link:hover { background: rgba(255,255,255,0.08); color: rgba(255,255,255,0.85); }
    .nav-link.active { color: white; background: rgba(255,255,255,0.1); }

    .topbar-right { display: flex; align-items: center; gap: 10px; position: relative; z-index: 1; }
    .ubadge {
      display: flex; align-items: center; gap: 8px; padding: 4px 14px 4px 4px;
    }
    .avatar {
      width: 30px; height: 30px; border-radius: 50%;
      background: linear-gradient(135deg, var(--teal), var(--teal2));
      display: flex; align-items: center; justify-content: center;
      font-size: 11px; font-weight: 700; color: white; letter-spacing: 0.5px;
    }
    .uname { font-size: 12px; font-weight: 600; color: rgba(255,255,255,0.85); }
    .btn-out {
      display: flex; align-items: center; gap: 6px;
      border: 1px solid rgba(255,255,255,0.16);
      border-radius: 24px; padding: 7px 16px;
      background: transparent; color: rgba(255,255,255,0.6);
      font-size: 13px; font-weight: 500; font-family: 'DM Sans', sans-serif;
      cursor: pointer; transition: all 0.18s;
    }
    .btn-out:hover { background: rgba(255,255,255,0.09); color: white; }
    .btn-out svg { width: 13px; height: 13px; }

    /* ════════════════════════════════════════
       HERO BAND
    ════════════════════════════════════════ */
    .hero {
      background: #f8f9fa; padding: 40px 40px 0;
      position: relative; overflow: hidden;
    }
    .hero-rings { position: absolute; top: -140px; right: -140px; pointer-events: none; }
    .hero-rings span {
      display: block; border-radius: 50%; border: 1px solid rgba(255,255,255,0.045);
      position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);
    }
    .hero-rings span:nth-child(1) { width: 560px; height: 560px; }
    .hero-rings span:nth-child(2) { width: 400px; height: 400px; }
    .hero-rings span:nth-child(3) { width: 260px; height: 260px; }
    .hero-rings span:nth-child(4) { width: 140px; height: 140px; }
    .hero-dot { position: absolute; border-radius: 50%; background: var(--teal); pointer-events: none; }
    .hero-dot:nth-child(1) { width:8px; height:8px; top:38px; right:240px; opacity:0.5; }
    .hero-dot:nth-child(2) { width:5px; height:5px; top:90px; right:180px; opacity:0.35; }
    .hero-dot:nth-child(3) { width:4px; height:4px; top:58px; right:300px; opacity:0.3; }
    .hero-dot:nth-child(4) { width:10px; height:10px; top:120px; right:130px; opacity:0.2; }
    .hero-watermark { position: absolute; right: 60px; top: 50%; transform: translateY(-50%); pointer-events: none; opacity: 0.04; }
    .hero-watermark svg { width: 220px; height: 220px; fill: white; }
    
    .hero-inner { display: flex; align-items: flex-end; justify-content: space-between; gap: 40px; position: relative; z-index: 1; }
    
    .hero-tag {
      display: inline-flex; align-items: center; gap: 6px;
      background: #f2f8d8; border: 1px solid rgba(26,154,138,0.32);
      border-radius: 4px; padding: 4px 10px; font-size: 10px; font-weight: 700;
      letter-spacing: 1.2px; text-transform: uppercase; color: #8ab820; margin-bottom: 14px;
    }
    .htag-dot { width: 6px; height: 6px; border-radius: 50%; background: #8ab820; animation: blink 2s infinite; }
    @keyframes blink { 0%,100%{opacity:1} 50%{opacity:0.3} }
    
    .hero-title {
      font-family: 'Playfair Display', serif; font-weight: 900; font-size: 56px; line-height: 1.0;
      color: white; letter-spacing: -2px; margin-bottom: 8px;
    }
    .hero-title em { color: var(--teal); font-style: italic; }
    .hero-sub { font-size: 15px; font-weight: 400; color: #1f2a44; margin-bottom: 32px; }

    /* STAT CHIPS */
    .stat-chips { display: flex; gap: 8px; padding-bottom: 0; }
    .schip {
      background: #1a7a8a; border: 1px solid rgba(255,255,255,0.09); border-bottom: none;
      border-radius: 10px 10px 0 0; padding: 12px 20px; min-width: 100px;
      display: flex; flex-direction: column; align-items: center; cursor: pointer;
      transition: background 0.18s, border-color 0.18s, transform 0.12s; position: relative;
    }
    .schip::after {
      content: ''; position: absolute; bottom: 0; left: 0; right: 0; height: 2px;
      background: var(--teal); transform: scaleX(0); transform-origin: left; transition: transform 0.22s ease;
    }
    .schip.on { background: #1f2a44; border-color: rgba(26,154,138,0.35); }
    .schip.on::after { transform: scaleX(1); }
    .schip:active { transform: scale(0.96) translateY(1px); }
    .chip-num { font-family: 'Playfair Display', serif; font-weight: 700; font-size: 26px; line-height: 1; color: white; }
    .chip-lbl { font-size: 10.5px; font-weight: 600; color: #ffffffb8; margin-top: 4px; white-space: nowrap; letter-spacing: 0.3px; }
    .schip.on .chip-lbl { color: rgba(255,255,255,0.75); }

    /* ACTION BUTTON */
    .hero-right { display: flex; flex-direction: column; align-items: flex-end; gap: 12px; padding-bottom: 32px; flex-shrink: 0; }
    .btn-create {
      display: flex; align-items: center; gap: 9px; height: 46px; padding: 0 24px; border-radius: 12px;
      background: linear-gradient(135deg, var(--teal), var(--teal2)); color: white; border: none;
      font-size: 14px; font-weight: 700; font-family: 'DM Sans', sans-serif; cursor: pointer; letter-spacing: 0.2px; text-decoration: none;
      box-shadow: 0 8px 28px rgba(26,154,138,0.4); transition: transform 0.14s, box-shadow 0.18s, opacity 0.18s; position: relative; overflow: hidden;
    }
    .btn-create::before {
      content: ''; position: absolute; top: -15px; left: 10%; width: 40%; height: 70%;
      background: rgba(255,255,255,0.15); border-radius: 50%; filter: blur(8px);
    }
    .btn-create:hover { transform: translateY(-2px); box-shadow: 0 12px 32px rgba(26,154,138,0.5); color: white; }
    .btn-create:active { transform: translateY(0) scale(0.97); }
    .btn-create svg { width: 16px; height: 16px; position: relative; z-index: 1; }
    .btn-create span { position: relative; z-index: 1; }

    .wave { display: block; background: var(--ink); line-height: 0; }
    .wave svg { width: 100%; height: 32px; display: block; }

    /* ════════════════════════════════════════
       MAIN CONTENT
    ════════════════════════════════════════ */
    .content { flex: 1; padding: 28px 10px 56px; max-width: 1200px; width: 100%; margin: 0 auto; }

    .toolbar { display: flex; align-items: center; gap: 10px; margin-bottom: 20px; }
    .sbox { flex: 1; position: relative; max-width: 420px; }
    .sbox input {
      width: 100%; height: 44px; background: var(--white); border: 1.5px solid var(--bdr);
      border-radius: 12px; padding: 0 14px 0 42px; font-size: 14px; font-family: 'DM Sans', sans-serif;
      color: var(--ink); outline: none; box-shadow: 0 2px 10px rgba(10,22,40,0.05); transition: border-color 0.18s, box-shadow 0.18s;
    }
    .sbox input::placeholder { color: #b8c0cc; }
    .sbox input:focus { border-color: var(--teal); box-shadow: 0 0 0 3px rgba(26,154,138,0.10); }
    .sico { position: absolute; left: 13px; top: 50%; transform: translateY(-50%); color: #b8c0cc; pointer-events: none; display: flex; }
    
    /* BULK ACTIONS */
    .bulk-actions {
        display: none; align-items: center; gap: 0.5rem; justify-content: space-between; margin-bottom: 20px;
        padding: 12px 20px; background: var(--white); border: 1px solid var(--bdr); border-radius: 12px; box-shadow: 0 4px 24px rgba(10,22,40,0.06);
    }
    .bulk-actions.show { display: flex; }
    .bulk-actions-info { font-size: 14px; font-weight: 600; color: var(--ink); }
    .bulk-actions-info i { color: var(--teal); margin-right: 6px; }
    .bulk-action-btn {
        padding: 8px 16px; border: none; border-radius: 8px; font-size: 13px; font-weight: 600;
        cursor: pointer; transition: all 0.2s; display: flex; align-items: center; gap: 6px;
    }
    .bulk-duplicate-btn { background: var(--teal); color: white; }
    .bulk-duplicate-btn:hover { background: var(--teal2); }
    .bulk-delete-btn { background: #d94040; color: white; }
    .bulk-delete-btn:hover { background: #b43030; }
    .bulk-clear-btn { background: var(--sand); color: var(--ink); }
    .bulk-clear-btn:hover { background: #cbd5e1; }

    /* TABLE */
    .tbl-wrap { background: var(--white); border: 1px solid var(--bdr); border-radius: 18px; overflow: hidden; box-shadow: 0 4px 24px rgba(10,22,40,0.06); }
    table { width: 100%; border-collapse: collapse; }
    thead tr { border-bottom: 1px solid var(--bdr); background: #f8f7f3; }
    thead th { padding: 13px 20px; text-align: left; font-size: 10.5px; font-weight: 700; letter-spacing: 0.8px; text-transform: uppercase; color: var(--gray2); white-space: nowrap; }
    thead th:first-child { width: 46px; padding-left: 22px; }
    thead th.right { text-align: center; padding-right: 22px; }
    
    tbody tr { border-bottom: 1px solid var(--bdr); transition: transform 0.22s, opacity 0.22s, background 0.14s; }
    tbody tr:last-child { border-bottom: none; }
    tbody tr:hover { background: #f9f8f5; }
    tbody td { padding: 20px 20px; vertical-align: middle; font-size: 14px; }
    tbody td:first-child { padding-left: 22px; }
    input[type=checkbox] { width: 15px; height: 15px; accent-color: var(--teal); cursor: pointer; }

    .id-chip { font-size: 10.5px; font-weight: 700; font-family: monospace; letter-spacing: 0.5px; color: var(--teal); background: var(--tealL); border: 1px solid rgba(26,154,138,0.2); padding: 3px 8px; border-radius: 6px; cursor: pointer; transition: background 0.2s; }
    .id-chip:hover { background: white; border-color: var(--teal); }
    .code-input { width: 80px; padding: 2px 4px; border: 1px solid var(--bdr); border-radius: 4px; font-family: monospace; font-size: 10.5px; text-transform: uppercase; }

    .trip-name { font-size: 14px; font-weight: 700; color: var(--ink); line-height: 1.3; }
    .trip-dest { display: flex; align-items: center; gap: 4px; font-size: 12px; color: var(--gray2); margin-top: 3px; }
    .trip-dest svg { width: 11px; height: 11px; color: var(--teal); flex-shrink: 0; }
    .trip-date { font-size: 13px; color: var(--gray); font-weight: 500; }
    .trip-range { font-size: 11.5px; color: var(--gray2); margin-top: 2px; }
    .client-name { font-size: 13px; font-weight: 600; color: var(--ink); }
    .client-email { font-size: 11.5px; color: var(--teal); margin-top: 2px; text-decoration: none; transition: color 0.15s; display: block; }
    .client-email:hover { color: var(--teal2); text-decoration: underline; }

    /* STATUS SELECTOR */
    .status-select {
        padding: 6px 12px;
        border: 1px solid var(--sand);
        border-radius: 999px;
        font-size: 11px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s ease;
        min-width: 110px;
        appearance: none;
        background-color: white;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%2338bdf8' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
        background-position: right 8px center;
        background-repeat: no-repeat;
        background-size: 12px;
        padding-right: 28px;
        color: #0e3242;
    }
    .status-select:focus { outline: none; border-color: var(--teal); box-shadow: 0 0 0 2px rgba(26,154,138,0.12); }
    .status-select:hover { border-color: var(--teal); }
    
    .status-completed { background-color: #eef2f6 !important; color: #0f766e !important; border-color: #cbd5e1 !important; }
    .status-reserved { background-color: #dcfce7 !important; color: #15803d !important; border-color: #bbf7d0 !important; }
    .status-draft    { background-color: #e0f2fe !important; color: #1d5fa8 !important; border-color: #bae6fd !important; }
    .status-sent     { background-color: #e8f8ff !important; color: #0284c7 !important; border-color: #bae6fd !important; }
    .status-discarded { background-color: #fee2e2 !important; color: #b43030 !important; border-color: #fecaca !important; }

    .acts-cell { text-align: right; }
    .acts { display: flex; align-items: center; gap: 5px; justify-content: flex-end; }
    .abt {
      width: 32px; height: 32px; border-radius: 8px; border: 1.5px solid var(--bdr); background: transparent;
      display: flex; align-items: center; justify-content: center; color: var(--gray); cursor: pointer;
      position: relative; transition: all 0.16s;
    }
    .abt svg { width: 13px; height: 13px; }
    .abt:hover { transform: translateY(-1px); }
    .abt.view:hover  { border-color:var(--teal);  color:var(--teal);  background:rgba(26,154,138,0.07);  box-shadow:0 3px 10px rgba(26,154,138,0.18); }
    .abt.edit:hover  { border-color:#7c3aed; color:#7c3aed; background:rgba(124,58,237,0.07); box-shadow:0 3px 10px rgba(124,58,237,0.18); }
    .abt.share:hover { border-color:#2878d4; color:#2878d4; background:rgba(40,120,212,0.07); box-shadow:0 3px 10px rgba(40,120,212,0.18); }
    .abt.del:hover   { border-color:#d94040; color:#d94040; background:rgba(217,64,64,0.07);  box-shadow:0 3px 10px rgba(217,64,64,0.18); }
    
    .abt::after {
      content: attr(data-tip); position: absolute; bottom: calc(100% + 6px); left: 50%;
      transform: translateX(-50%); background: var(--ink); color: white; font-size: 10px;
      font-weight: 600; padding: 3px 8px; border-radius: 6px; white-space: nowrap; pointer-events: none; opacity: 0; transition: opacity 0.15s;
    }
    .abt:hover::after { opacity: 1; z-index: 1000; }

    .empty { display: none; text-align: center; padding: 72px 24px; }
    .e-ring {
      width: 72px; height: 72px; border-radius: 50%; background: var(--tealL);
      border: 1.5px dashed rgba(26,154,138,0.3); display: flex; align-items: center; justify-content: center; margin: 0 auto 18px;
    }
    .e-ring svg { width: 30px; height: 30px; color: var(--teal); }
    .empty h3 { font-family: Inter, sans-serif; font-weight: 600; font-size: 20px; color: var(--ink); margin-bottom: 7px; }
    .empty p  { font-size: 13px; color: var(--gray); }

    .bar-cell { width: 4px; padding: 0 !important; }
    .bar-inner { width: 4px; height: 100%; border-radius: 2px; }

    @keyframes rowIn{from{opacity:0;transform:translateY(8px)}to{opacity:1;transform:translateY(0)}}
</style>
@endpush

@section('content')

@php
    function getStatusBand($status) {
        $colors = [
            'draft' => 'linear-gradient(180deg,#2878d4,#60a5fa)',
            'sent' => 'linear-gradient(180deg,#0ea5e9,#7dd3fc)',
            'reserved' => 'linear-gradient(180deg,#16a34a,#4ade80)',
            'completed' => 'linear-gradient(180deg,#0d9488,#2dd4bf)',
            'discarded' => 'linear-gradient(180deg,#d94040,#f87171)',
        ];
        return $colors[$status] ?? 'linear-gradient(180deg,#a8b2bc,#cbd5e1)';
    }
    function getStatusLabel($status) {
        $labels = [
            'draft' => 'En Diseño',
            'sent' => 'Enviado',
            'reserved' => 'Reservado',
            'completed' => 'Completado',
            'discarded' => 'Descartado',
        ];
        return $labels[$status] ?? ucfirst($status);
    }
@endphp

<!-- ══ TOPBAR ══ -->
<header class="topbar">
  <div class="topbar-left">
    <a href="{{ route('home') }}" class="logo">
      <img src="/images/logo-viantryp.png" alt="Viantryp">
    </a>
  </div>
  <div class="topbar-right">
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
  </div>
</header>

<!-- ══ HERO ══ -->
<section class="hero">
  </div>
</section>

<!-- ══ CONTENT ══ -->
<div class="content">

  <div class="toolbar">
    <div class="sbox">
      <span class="sico">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg>
      </span>
      <input type="text" placeholder="Buscar por ID, nombre, cliente..." id="searchInput" oninput="searchTripsRows(this.value)"/>
    </div>
    <a href="{{ route('trips.create') }}" class="btn btn-primary" style="font-family: 'DM Sans', sans-serif; background-color: #1a7a8a; border-color: #1a7a8a; margin-left: auto;">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="width: 16px; height: 16px; margin-right: 4px;"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
      <span>Crear nuevo viaje</span>
    </a>
  </div>

  <div class="bulk-actions" id="bulk-actions">
      <div class="bulk-actions-info">
          <span>
              <i class="fas fa-check-circle"></i>
              <span id="selected-count">0</span> viaje(s) seleccionado(s)
          </span>
      </div>
      <div style="display: flex; gap: 8px;">
          <button class="bulk-action-btn bulk-duplicate-btn" onclick="duplicateSelectedTrips()">Duplicar</button>
          <button class="bulk-action-btn bulk-delete-btn" onclick="deleteSelectedTrips()">Eliminar</button>
          <button class="bulk-action-btn bulk-clear-btn" onclick="clearSelection()">Limpiar</button>
      </div>
  </div>

  <!-- Table -->
  <div class="tbl-wrap">
    <table id="mainTable">
      <thead>
        <tr>
          <th><input type="checkbox" id="checkAll" onchange="toggleSelectAll(this)"/></th>
          <th style="width:4px;padding:0"></th>
          <th>ID</th>
          <th>Nombre del Viaje</th>
          <th>Fecha</th>
          <th>Cliente</th>
          <th>Estado</th>
          <th class="right">Acciones</th>
        </tr>
      </thead>
      <tbody id="tbody">
          @if(count($trips) > 0)
              @foreach($trips as $index => $trip)
                <tr class="trip-row" style="animation-delay: {{ $index * 0.04 }}s; animation: rowIn 0.28s ease both;">
                    <td><input type="checkbox" class="rchk trip-checkbox" data-trip-id="{{ $trip->id }}" onchange="updateSelectAllState()"/></td>
                    <td class="bar-cell"><div class="bar-inner" style="background: {{ getStatusBand($trip->status) }}"></div></td>
                    <td>
                        <span class="id-chip code-display" onclick="event.stopPropagation(); editTripCode({{ $trip->id }}, '{{ $trip->code }}')">{{ $trip->code ?? 'N/A' }}</span>
                        <input type="text" class="code-input" id="code-input-{{ $trip->id }}" style="display: none;" onblur="saveTripCode({{ $trip->id }})" onkeypress="handleCodeKeyPress(event, {{ $trip->id }})" maxlength="20">
                    </td>
                    <td>
                      <div class="trip-name">{{ $trip->title }}</div>
                      @if($trip->destinations && count($trip->destinations) > 0)
                         <div class="trip-dest"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg> {{ rtrim($trip->destinations->pluck('name')->join(' · '), ' · ') ?: 'Sin destino' }}</div>
                      @endif
                    </td>
                    <td>
                      <div class="trip-date">{{ $trip->start_date ? \Carbon\Carbon::parse($trip->start_date)->translatedFormat('j M Y') : 'Sin fecha' }}</div>
                    </td>
                    @php
                        $client = collect($trip->persons)->firstWhere('type', 'client') ?? collect($trip->persons)->first();
                    @endphp
                    <td>
                      <div class="client-name">{{ $client ? $client->name : 'Sin cliente' }}</div>
                      @if($client && $client->email)
                        <a class="client-email" href="mailto:{{ $client->email }}">{{ $client->email }}</a>
                      @else
                        <div class="client-email" style="color:var(--gray2);text-decoration:none;">N/A</div>
                      @endif
                    </td>
                    <td>
                      <select class="status-select status-{{ $trip->status }}" data-status="{{ $trip->status }}" onchange="changeTripStatus({{ $trip->id }}, this.value)">
                          <option value="draft" {{ $trip->status === 'draft' ? 'selected' : '' }}>En diseño</option>
                          <option value="sent" {{ $trip->status === 'sent' ? 'selected' : '' }}>Enviado</option>
                          <option value="reserved" {{ $trip->status === 'reserved' ? 'selected' : '' }}>Reservado</option>
                          <option value="completed" {{ $trip->status === 'completed' ? 'selected' : '' }}>Completado</option>
                          <option value="discarded" {{ $trip->status === 'discarded' ? 'selected' : '' }}>Descartado</option>
                      </select>
                    </td>
                    <td class="acts-cell">
                      <div class="acts">
                        <button class="abt view" data-tip="Ver propuesta" onclick="previewTrip({{ $trip->id }})">
                          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                        </button>
                        <button class="abt edit" data-tip="Editar" onclick="editTrip({{ $trip->id }})">
                          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                        </button>
                        <button class="abt share" data-tip="Enviar al cliente" onclick="shareTripIndex({{ $trip->id }}, '{{ $trip->share_token }}')">
                          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
                        </button>
                        <button class="abt del" data-tip="Eliminar" onclick="delRow({{ $trip->id }})">
                          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/></svg>
                        </button>
                      </div>
                    </td>
                </tr>
              @endforeach
          @else
                <tr id="emptyRow">
                  <td colspan="8">
                    <div class="empty" style="display:block;">
                      <div class="e-ring"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16v-2l-8-5V3.5a1.5 1.5 0 0 0-3 0V9l-8 5v2l8-2.5V19l-2 1.5V22l3.5-1 3.5 1v-1.5L13 19v-5.5l8 2.5z"/></svg></div>
                      <h3>No hay viajes en tu lista.</h3>
                      <p> Haz clic en ‘Crear viaje’ y empieza a explorar.</p>
                    </div>
                  </td>
                </tr>
          @endif
      </tbody>
    </table>
  </div>
</div>
@endsection

@push('scripts')
<script>
    function filterTrips(filter) {
        window.location.href = `{{ route('trips.index') }}?filter=${filter}`;
    }

    function searchTripsRows(query) {
        query = query.toLowerCase();
        const rows = document.querySelectorAll('.trip-row');
        rows.forEach(row => {
            const text = row.innerText.toLowerCase();
            row.style.display = text.includes(query) ? '' : 'none';
        });
    }

    function previewTrip(tripId) { window.open(`{{ url('trips') }}/${tripId}/preview`, '_blank'); }
    function editTrip(tripId) { window.location.href = `{{ url('trips') }}/${tripId}/edit`; }
    
    // Status Logic
    function changeTripStatus(tripId, newStatus) {
        fetch(`{{ url('trips') }}/${tripId}/status`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ status: newStatus })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('Estado Actualizado', 'El estado del viaje ha sido actualizado.');
                // Update the select element's data-status attribute
                const selectElement = document.querySelector(`select[onchange*="${tripId}"]`);
                if (selectElement) {
                    selectElement.setAttribute('data-status', newStatus);
                    selectElement.className = `status-select status-${newStatus}`;
                }
                setTimeout(()=>location.reload(), 800);
            } else {
                showNotification('Error', data.message || 'No se pudo actualizar el estado del viaje.', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Error', 'No se pudo actualizar el estado del viaje.', 'error');
        });
    }

    function delRow(tripId) {
        if (!confirm('¿Seguro de que quieres eliminar este viaje?')) return;
        fetch(`{{ url('trips') }}/${tripId}`, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') }
        })
        .then(r => r.json())
        .then(d => {
            if (d.success) location.reload();
        });
    }
    
    // Bulk Selection
    function toggleSelectAll(cb) {
        document.querySelectorAll('.trip-checkbox').forEach(c => c.checked = cb.checked);
        updateSelectAllState();
    }
    
    function updateSelectAllState() {
        const checked = document.querySelectorAll('.trip-checkbox:checked');
        const bulk = document.getElementById('bulk-actions');
        document.getElementById('selected-count').textContent = checked.length;
        if(checked.length > 0) {
            bulk.classList.add('show');
        } else {
            bulk.classList.remove('show');
        }
    }
    
    function clearSelection() {
        document.getElementById('checkAll').checked = false;
        toggleSelectAll(document.getElementById('checkAll'));
    }
    
    function getSelectedTrips() {
        return Array.from(document.querySelectorAll('.trip-checkbox:checked')).map(c => parseInt(c.dataset.tripId));
    }
    
    function deleteSelectedTrips() {
        const ids = getSelectedTrips();
        if(ids.length === 0) return;
        if(confirm(`¿Eliminar ${ids.length} viaje(s)?`)) {
            fetch(`{{ url('trips/bulk-delete') }}`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') },
                body: JSON.stringify({ trip_ids: ids })
            }).then(r => r.json()).then(d => { if(d.success) location.reload(); });
        }
    }
    
    function duplicateSelectedTrips() {
        const ids = getSelectedTrips();
        if(ids.length === 0) return;
        fetch(`{{ url('trips/bulk-duplicate') }}`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') },
            body: JSON.stringify({ trip_ids: ids })
        }).then(r => r.json()).then(d => { if(d.success) location.reload(); });
    }

    function editTripCode(tripId, currentCode) {
        const displaySpan = document.querySelector(`.code-display[onclick*="(${tripId}"]`);
        const inputField = document.getElementById(`code-input-${tripId}`);

        if (displaySpan && inputField) {
            displaySpan.style.display = 'none';
            inputField.style.display = 'inline-block';
            inputField.value = currentCode;
            inputField.focus();
            inputField.select();
        }
    }

    function saveTripCode(tripId) {
        const inputField = document.getElementById(`code-input-${tripId}`);
        const displaySpan = document.querySelector(`.code-display[onclick*="(${tripId}"]`);
        const newCode = inputField.value.trim().toUpperCase();
        
        if (newCode === displaySpan.textContent.trim()) {
            inputField.style.display = 'none';
            displaySpan.style.display = 'inline-block';
            return;
        }

        fetch(`{{ url('trips') }}/${tripId}/code`, {
            method: 'POST',
            headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')},
            body: JSON.stringify({ code: newCode })
        }).then(r => r.json()).then(d => {
            if (d.success) {
                displaySpan.textContent = newCode;
                displaySpan.setAttribute('onclick', `event.stopPropagation(); editTripCode(${tripId}, '${newCode}')`);
                inputField.style.display = 'none'; displaySpan.style.display = 'inline-block';
            }
        });
    }

    function handleCodeKeyPress(event, tripId) {
        if (event.key === 'Enter') saveTripCode(tripId);
        else if (event.key === 'Escape') {
            const inputField = document.getElementById(`code-input-${tripId}`);
            const displaySpan = document.querySelector(`.code-display[onclick*="(${tripId}"]`);
            inputField.style.display = 'none';
            displaySpan.style.display = 'inline-block';
        }
    }

    // Share Modal
    function shareTripIndex(tripId, token) {
        if(token) return showShareModalIndex(`${window.location.origin}/share/${token}`);
        fetch(`{{ url('trips') }}/${tripId}/generate-share-token`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') }
        }).then(r => r.json()).then(d => { if(d.success) showShareModalIndex(d.share_url); });
    }

    function showShareModalIndex(url) {
        const b = document.createElement('div');
        b.innerHTML = `
            <div id="shareModal" style="position:fixed;top:0;left:0;right:0;bottom:0;background:rgba(0,0,0,0.5);display:flex;align-items:center;justify-content:center;z-index:10000;font-family:'Inter',sans-serif;">
                <div style="background:white;border-radius:16px;padding:2rem;max-width:500px;width:90%;">
                    <h3 style="margin-bottom:20px; text-align:center;">Compartir Itinerario</h3>
                    <input type="text" value="${url}" readonly style="width:100%;padding:10px;margin-bottom:15px;border:1px solid #ddd;border-radius:8px;">
                    <button onclick="navigator.clipboard.writeText('${url}').then(()=>alert('¡Copiado!'))" style="background:#1a9a8a;color:white;border:none;padding:10px 20px;border-radius:8px;cursor:pointer;">Copiar</button>
                    <button onclick="document.getElementById('shareModal').remove()" style="margin-left:10px;background:#f3f4f6;border:none;padding:10px 20px;border-radius:8px;cursor:pointer;">Cerrar</button>
                </div>
            </div>`;
        document.body.appendChild(b.firstElementChild);
    }
</script>
@endpush
