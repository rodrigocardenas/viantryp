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
      background: #0f2a3a;
      height: 75px;
      display: flex; align-items: center; justify-content: space-between;
      padding: 0 80px;
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
      font-size: 12px; font-weight: 500; font-family: 'DM Sans' sans-serif;
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
    .content { flex: 1; padding: 40px 10px 56px; max-width: 1200px; width: 100%; margin: 0 auto; }

    .toolbar { display: flex; align-items: center; gap: 10px; margin-bottom: 30px; }
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

    .id-chip, .name-display, .email-display { font-size: 10.5px; font-weight: 700; font-family: monospace; letter-spacing: 0.5px; color: #071917; background: #e7f7f51a; border: 1px solid rgba(7, 25, 23, 0.15); padding: 3px 8px; border-radius: 6px; cursor: pointer; transition: background 0.2s; display: inline-block; }
    .id-chip:hover, .name-display:hover, .email-display:hover { background: #e7f7f5; border-color: #071917; }
    .name-display, .email-display { font-family: 'DM Sans', sans-serif; letter-spacing: 0.2px; }
    .name-display { font-size: 13px; }
    .email-display { font-size: 10px; font-weight: 500; color: #1a9a8a; }
    .code-input { width: 80px; padding: 2px 4px; border: 1px solid var(--bdr); border-radius: 4px; font-family: monospace; font-size: 10.5px; text-transform: uppercase; }

    .trip-name { font-size: 16px; font-weight: 700; color: var(--ink); line-height: 1.3; }
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
        font-size: 12px;
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

    @media (max-width: 768px) {
        .topbar { padding: 0 15px; }
        .uname { display: none; }
        .btn-out { font-size: 11px; padding: 6px 12px; gap: 4px; }
        .btn-out svg { width: 12px; height: 12px; }
        .topbar-right { flex-direction: row-reverse; gap: 12px; }
        .ubadge { padding: 0; border: none; margin: 0; }

        /* Mobile Trips Card Layout */
        .toolbar { flex-direction: column; align-items: stretch; }
        .sbox { max-width: 100%; }
        .toolbar .btn-primary { display: none !important; }

        .tbl-wrap { background: transparent; border: none; box-shadow: none; border-radius: 0; }
        table, thead, tbody, th, td, tr { display: block; }
        thead { display: none; }
        
        .trip-row {
            background: white; border: 1px solid var(--bdr); border-radius: 12px;
            margin-bottom: 16px; position: relative; padding: 20px 20px 16px;
            box-shadow: 0 4px 12px rgba(10,22,40,0.04);
        }
        .trip-row:hover { background: white; transform: translateY(-2px); box-shadow: 0 8px 16px rgba(10,22,40,0.06); }
        
        /* Hide checkbox and ID on mobile */
        .trip-row > td:nth-child(1),
        .trip-row > td:nth-child(3) { display: none; }
        
        /* Status Band */
        .bar-cell { position: absolute; top: 0; left: 0; width: 100%; height: 5px; padding: 0 !important; border-radius: 12px 12px 0 0; overflow: hidden; }
        .bar-inner { width: 100%; height: 100%; border-radius: 0; }

        /* Header Line: Title + Status */
        .trip-row > td:nth-child(4) { padding: 0 0 12px 0; border: none; display: flex; flex-direction: column; gap: 8px; }
        
        .trip-name { font-size: 18px; line-height: 1.2; display: flex; align-items: flex-start; justify-content: space-between; gap: 12px; }
        
        /* Status Pill Re-styling for Card Header */
        .trip-row > td:nth-child(7) { position: absolute; top: 20px; right: 20px; padding: 0; border: none; width: auto; z-index: 10; }
        .status-select { 
            padding: 4px 26px 4px 10px; font-size: 11.5px; 
            border-radius: 6px; pointer-events: auto;
        }

        /* Destiny */
        .trip-dest { font-size: 13.5px; margin-top: 0; }
        .trip-dest svg { width: 13px; height: 13px; }

        /* Info Text Layout */
        .mobile-info-row { display: flex !important; flex-direction: column; gap: 3px; margin-top: 14px; }
        .mobile-client-name { font-size: 14px; font-weight: 600; color: var(--ink); }
        .mobile-client-email { font-size: 13px; color: var(--teal); text-decoration: none; display: inline-block; }
        .mobile-trip-date { font-size: 13px; color: var(--gray); font-weight: 500; margin-top: 2px; }

        /* Overwrite logic to hide old rows and use new mobile info row */
        .trip-row > td:nth-child(5), 
        .trip-row > td:nth-child(6) { display: none; }

        /* Action Buttons Block */
        .trip-row > td:nth-child(8) { 
            padding: 16px 0 0 0; margin-top: 16px; border-top: 1px solid #f0f2f5; 
            display: flex; gap: 10px;
        }
        .acts-cell { text-align: left; }
        .acts { justify-content: space-between; width: 100%; gap: 10px; }
        
        /* Specialized Mobile Buttons */
        .abt { 
            width: auto; flex: 1; height: 38px; border-radius: 8px; font-size: 13px; 
            font-weight: 600; font-family: 'DM Sans', sans-serif; 
            display: flex; align-items: center; justify-content: center; gap: 8px;
            color: var(--gray); border-color: var(--bdr); background: transparent;
        }
        .abt::after { display: none !important; } /* Hide tooltips */
        .abt.edit { display: none; } /* User requested to hide edit on mobile */
        
        .abt.view::before { content: 'Ver'; }
        .abt.share::before { content: 'Compartir'; }
        
        .abt.del { flex: 0 0 42px; }
        .abt svg { width: 15px; height: 15px; }

        /* Empty state adaptation */
        .empty { padding: 40px 15px; }
    }
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


<!-- ══ CONTENT ══ -->
<div class="content">

  <div class="toolbar">
    <div class="sbox">
      <span class="sico">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg>
      </span>
      <input type="text" placeholder="Buscar por ID, nombre, cliente..." id="searchInput" oninput="searchTripsRows(this.value)"/>
    </div>
    
    <div style="margin-left: auto;">
        <button onclick="showCreateTripModal()" class="btn btn-primary" style="font-family: 'DM Sans', sans-serif; background: linear-gradient(135deg, var(--teal), var(--teal2)); border: none; box-shadow: 0 4px 14px rgba(26,154,138,0.25);">
          <span>+ Crear viaje</span>
        </button>
    </div>
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
          <th class="sortable" onclick="sortTable(2, 'string')" style="cursor: pointer; user-select: none; white-space: nowrap;">ID <span class="sort-icon" style="margin-left: 10px; display: inline-block; vertical-align: middle;"><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round" style="opacity: 0.3;"><path d="M15 4v16"/><path d="M15 20l-4-4"/><path d="M15 20l4-4"/><path d="M4 8l2-6 2 6"/><path d="M5 5h2"/><path d="M4 14h4l-4 6h4"/></svg></span></th>
          <th class="sortable" onclick="sortTable(3, 'string')" style="cursor: pointer; user-select: none; white-space: nowrap;">Nombre del Viaje <span class="sort-icon" style="margin-left: 10px; display: inline-block; vertical-align: middle;"><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round" style="opacity: 0.3;"><path d="M15 4v16"/><path d="M15 20l-4-4"/><path d="M15 20l4-4"/><path d="M4 8l2-6 2 6"/><path d="M5 5h2"/><path d="M4 14h4l-4 6h4"/></svg></span></th>
          <th class="sortable" onclick="sortTable(4, 'date')" style="cursor: pointer; user-select: none; white-space: nowrap;">Inicio del Viaje <span class="sort-icon" style="margin-left: 10px; display: inline-block; vertical-align: middle;"><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round" style="opacity: 0.3;"><path d="M15 4v16"/><path d="M15 20l-4-4"/><path d="M15 20l4-4"/><path d="M4 8l2-6 2 6"/><path d="M5 5h2"/><path d="M4 14h4l-4 6h4"/></svg></span></th>
          <th class="sortable" onclick="sortTable(5, 'string')" style="cursor: pointer; user-select: none; white-space: nowrap;">Cliente <span class="sort-icon" style="margin-left: 10px; display: inline-block; vertical-align: middle;"><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round" style="opacity: 0.3;"><path d="M15 4v16"/><path d="M15 20l-4-4"/><path d="M15 20l4-4"/><path d="M4 8l2-6 2 6"/><path d="M5 5h2"/><path d="M4 14h4l-4 6h4"/></svg></span></th>
          <th class="sortable" onclick="sortTable(6, 'string')" style="cursor: pointer; user-select: none; white-space: nowrap;">Estado <span class="sort-icon" style="margin-left: 10px; display: inline-block; vertical-align: middle;"><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round" style="opacity: 0.3;"><path d="M15 4v16"/><path d="M15 20l-4-4"/><path d="M15 20l4-4"/><path d="M4 8l2-6 2 6"/><path d="M5 5h2"/><path d="M4 14h4l-4 6h4"/></svg></span></th>
          <th class="right">Acciones</th>
        </tr>
      </thead>
      <tbody id="tbody">
          @if(count($trips) > 0)
              @foreach($trips as $index => $trip)
                <tr class="trip-row" data-trip-id="{{ $trip->id }}" data-is-pro="{{ $trip->is_pro ? '1' : '0' }}" style="animation-delay: {{ $index * 0.04 }}s; animation: rowIn 0.28s ease both; cursor: pointer;" onclick="if(window.innerWidth > 768) { @if($trip->is_pro) window.location='{{ route('trips.edit', $trip->id) }}'; @else window.location='{{ route('trips.edit', $trip->id) }}'; @endif }">
                    <td onclick="event.stopPropagation()"><input type="checkbox" class="rchk trip-checkbox" data-trip-id="{{ $trip->id }}" onchange="updateSelectAllState()"/></td>
                    <td class="bar-cell"></td>
                    <td>
                        <span class="id-chip code-display" onclick="event.stopPropagation(); editTripCode({{ $trip->id }}, '{{ $trip->code }}')">{{ $trip->code ?? 'N/A' }}</span>
                        <input type="text" class="code-input" id="code-input-{{ $trip->id }}" style="display: none;" onblur="saveTripCode({{ $trip->id }})" onkeypress="handleCodeKeyPress(event, {{ $trip->id }})" maxlength="20">
                    </td>
                    <td style="width: 225.73px; max-width: 225.73px;">
                      <div class="trip-name">
                          <span class="title-display" id="title-display-{{ $trip->id }}" style="display: inline-block; width: 100%; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $trip->title }}</span>
                      </div>
                      @if($trip->destinations && count($trip->destinations) > 0)
                         <div class="trip-dest"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg> <span style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 180px; display: inline-block; vertical-align: bottom;">{{ rtrim($trip->destinations->pluck('name')->join(' · '), ' · ') ?: 'Sin destino' }}</span></div>
                      @endif
                      
                      {{-- Mobile only info chips --}}
                      <div class="mobile-info-row" style="display: none;">
                          @php
                              $clientMobile = collect($trip->persons)->firstWhere('type', 'client') ?? collect($trip->persons)->first();
                          @endphp
                          @if($clientMobile)
                          <div class="mobile-client-name">
                              {{ $clientMobile->name }}
                          </div>
                          @if($clientMobile->email)
                          <a href="mailto:{{ $clientMobile->email }}" class="mobile-client-email" onclick="event.stopPropagation()">
                              {{ $clientMobile->email }}
                          </a>
                          @endif
                          @endif
                          <div class="mobile-trip-date">
                              Inicio del viaje: {{ $trip->start_date ? \Carbon\Carbon::parse($trip->start_date)->translatedFormat('j M Y') : 'Sin fecha' }}
                          </div>
                      </div>
                    </td>
                    <td style="width: 127px; min-width: 127px;">
                      <div class="trip-date">{{ $trip->start_date ? \Carbon\Carbon::parse($trip->start_date)->translatedFormat('j M Y') : 'Sin fecha' }}</div>
                    </td>
                    @php
                        $client = collect($trip->persons)->firstWhere('type', 'client') ?? collect($trip->persons)->first();
                    @endphp
                    <td>
                      <div class="client-name" onclick="event.stopPropagation(); editTripField({{ $trip->id }}, 'client_name')" title="Haz clic para editar">
                          <span class="name-display" id="name-display-{{ $trip->id }}">{{ $client ? $client->name : 'Sin cliente' }}</span>
                          <input type="text" class="field-input code-input" id="name-input-{{ $trip->id }}" style="display: none; width: 100%; border-radius: 4px; border: 1px solid var(--bdr); padding: 4px; font-family: inherit; font-size: 13px; text-transform: none;" onblur="saveTripField({{ $trip->id }}, 'client_name')" onkeypress="handleFieldKeyPress(event, {{ $trip->id }}, 'client_name')" onclick="event.stopPropagation()">
                      </div>
                      
                      <div class="client-email-container" onclick="event.stopPropagation(); editTripField({{ $trip->id }}, 'client_email')" style="margin-top: 4px;" title="Haz clic para editar">
                          <span class="email-display" id="email-display-{{ $trip->id }}" style="display: inline-block;">{{ ($client && $client->email) ? $client->email : 'Añadir correo' }}</span>
                          <input type="email" class="field-input code-input" id="email-input-{{ $trip->id }}" style="display: none; width: 100%; border-radius: 4px; border: 1px solid var(--bdr); padding: 4px; font-family: inherit; font-size: 11.5px; text-transform: none;" onblur="saveTripField({{ $trip->id }}, 'client_email')" onkeypress="handleFieldKeyPress(event, {{ $trip->id }}, 'client_email')" onclick="event.stopPropagation()">
                      </div>
                    </td>
                    <td onclick="event.stopPropagation()">
                      <select class="status-select status-{{ $trip->status }}" data-status="{{ $trip->status }}" onchange="changeTripStatus({{ $trip->id }}, this.value)">
                          <option value="draft" {{ $trip->status === 'draft' ? 'selected' : '' }}>En diseño</option>
                          <option value="sent" {{ $trip->status === 'sent' ? 'selected' : '' }}>Enviado</option>
                          <option value="reserved" {{ $trip->status === 'reserved' ? 'selected' : '' }}>Reservado</option>
                          <option value="completed" {{ $trip->status === 'completed' ? 'selected' : '' }}>Completado</option>
                          <option value="discarded" {{ $trip->status === 'discarded' ? 'selected' : '' }}>Descartado</option>
                      </select>
                    </td>
                    <td class="acts-cell" onclick="event.stopPropagation()">
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
</script>
        <script src="{{ asset('js/trips/pro-viewer.js') }}"></script>
    <script>
        function previewTrip(tripId) {
            const row = document.querySelector(`.trip-row[data-trip-id="${tripId}"]`);
            const isPro = row && row.dataset.isPro === '1';

            if (isPro) {
                openProPreview(tripId);
            } else {
                window.open(`{{ url('trips') }}/${tripId}/preview`, '_blank');
            }
        }

        async function openProPreview(tripId) {
            try {
                // Show a loading indicator if possible, or just fetch
                const response = await fetch(`{{ url('trips') }}/${tripId}/get-pro-data`);
                const data = await response.json();

                if (data.success && data.pro_state) {
                    let proState = data.pro_state;
                    if (typeof proState === 'string') {
                        try { proState = JSON.parse(proState); } catch(e) {}
                    }

                    // Add necessary context for buildPreviewHTML
                    proState.isPublicLink = false;
                    proState.csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    proState.tripId = tripId;
                    proState.userName = data.user_name || 'Viantryp';
                    proState.origin = window.location.origin;

                    const previewHTML = buildPreviewHTML(proState);
                    const blob = new Blob([previewHTML], { type: 'text/html' });
                    const url = URL.createObjectURL(blob);
                    window.open(url, '_blank');
                } else {
                    alert('Error al cargar los datos del viaje PRO: ' + (data.message || 'Error desconocido'));
                }
            } catch (error) {
                console.error('Error fetching PRO data:', error);
                alert('Ocurrió un error al intentar abrir la vista previa.');
            }
        }

        function editTrip(tripId) {
            window.location.href = `{{ url('trips') }}/${tripId}/edit`;
        }
    function showCreateTripModal() {
        const modalHtml = `
            <div id="createTripModal" style="position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(15, 42, 58, 0.4); backdrop-filter:blur(8px); z-index:2000; display:flex; align-items:center; justify-content:center; animation: fadeIn 0.3s ease;">
                <div style="background:white; width:90%; max-width:450px; border-radius:16px; overflow:hidden; box-shadow:0 20px 40px rgba(0,0,0,0.1); animation: slideUp 0.3s ease;">
                    <div style="background:linear-gradient(135deg, var(--teal), var(--teal2)); padding:24px; color:white;">
                        <h3 style="margin:0; font-family:'Playfair Display', serif; font-size:24px;">+ Nuevo Viaje</h3>
                        <p style="margin:8px 0 0; font-size:13px; opacity:0.85;">Comienza a diseñar una experiencia inolvidable.</p>
                    </div>
                    <div style="padding:24px;">
                        <form id="createTripForm">
                            <div style="margin-bottom:16px;">
                                <label style="display:block; font-size:11px; font-weight:700; text-transform:uppercase; color:var(--gray2); margin-bottom:6px; letter-spacing:0.5px;">Nombre del Viaje</label>
                                <input type="text" name="title" required placeholder="Ej: Luna de Miel en Bali" style="width:100%; height:44px; padding:0 14px; border:1.5px solid var(--bdr); border-radius:10px; font-size:14px; outline:none; transition:border-color 0.2s;">
                            </div>
                            <div style="margin-bottom:16px;">
                                <label style="display:block; font-size:11px; font-weight:700; text-transform:uppercase; color:var(--gray2); margin-bottom:6px; letter-spacing:0.5px;">Nombre del Cliente</label>
                                <input type="text" name="client_name" placeholder="Ej: Juan Pérez" style="width:100%; height:44px; padding:0 14px; border:1.5px solid var(--bdr); border-radius:10px; font-size:14px; outline:none; transition:border-color 0.2s;">
                            </div>
                            <div style="margin-bottom:20px;">
                                <label style="display:block; font-size:11px; font-weight:700; text-transform:uppercase; color:var(--gray2); margin-bottom:6px; letter-spacing:0.5px;">Correo del Cliente</label>
                                <input type="email" name="client_email" placeholder="ejemplo@correo.com" style="width:100%; height:44px; padding:0 14px; border:1.5px solid var(--bdr); border-radius:10px; font-size:14px; outline:none; transition:border-color 0.2s;">
                            </div>
                            <div style="display:flex; gap:12px;">
                                <button type="button" onclick="document.getElementById('createTripModal').remove()" style="flex:1; height:44px; border:none; background:var(--sand); color:var(--ink); font-weight:600; border-radius:10px; cursor:pointer; font-size:13px;">Cancelar</button>
                                <button type="submit" style="flex:1; height:44px; border:none; background:linear-gradient(135deg, var(--teal), var(--teal2)); color:white; font-weight:700; border-radius:10px; cursor:pointer; font-size:13px; box-shadow:0 4px 12px rgba(26,154,138,0.3);">Diseñar Viaje</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <style>
                @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
                @keyframes slideUp { from { transform: translateY(20px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
                #createTripForm input:focus { border-color: var(--teal) !important; box-shadow: 0 0 0 3px rgba(26,154,138,0.1); }
            </style>
        `;

        document.body.insertAdjacentHTML('beforeend', modalHtml);

        const form = document.getElementById('createTripForm');
        form.onsubmit = async (e) => {
            e.preventDefault();
            const btn = form.querySelector('button[type="submit"]');
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Creando...';

            const formData = new FormData(form);
            const data = {};
            formData.forEach((value, key) => data[key] = value);

            try {
                const response = await fetch(`{{ route('trips.store-pro') }}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(data)
                });

                const result = await response.json();
                if (result.success) {
                    window.location.href = result.redirect_url;
                } else {
                    alert('Error al crear el viaje: ' + (result.message || 'Error desconocido'));
                    btn.disabled = false;
                    btn.textContent = 'Diseñar Viaje';
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Ocurrió un error de red o del servidor.');
                btn.disabled = false;
                btn.textContent = 'Diseñar Viaje';
            }
        };
    }
    
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

    // Inline field editing (Title, Client Name, Client Email)
    function editTripField(tripId, fieldName) {
        let displaySpan, inputField;
        
        if (fieldName === 'title') {
            displaySpan = document.getElementById(`title-display-${tripId}`);
            inputField = document.getElementById(`title-input-${tripId}`);
        } else if (fieldName === 'client_email') {
            displaySpan = document.getElementById(`email-display-${tripId}`);
            inputField = document.getElementById(`email-input-${tripId}`);
        } else if (fieldName === 'client_name') {
            displaySpan = document.getElementById(`name-display-${tripId}`);
            inputField = document.getElementById(`name-input-${tripId}`);
        }
        
        if (displaySpan && inputField) {
            displaySpan.style.display = 'none';
            inputField.style.display = 'inline-block';
            
            // Start with empty value if placeholder text is present
            if (fieldName === 'client_email' && displaySpan.textContent.trim() === 'Añadir correo') {
                inputField.value = '';
            } else if (fieldName === 'client_name' && displaySpan.textContent.trim() === 'Sin cliente') {
                inputField.value = '';
            } else {
                inputField.value = displaySpan.textContent.trim();
            }
            
            inputField.focus();
            inputField.select();
        }
    }

    function saveTripField(tripId, fieldName) {
        let inputField, displaySpan;
        
        if (fieldName === 'title') {
            inputField = document.getElementById(`title-input-${tripId}`);
            displaySpan = document.getElementById(`title-display-${tripId}`);
            
            const newValue = inputField.value.trim();
            if(!newValue) {
                // Restore if empty
                inputField.value = displaySpan.textContent.trim();
                inputField.style.display = 'none';
                displaySpan.style.display = 'inline-block';
                showNotification('Error', 'El título no puede estar vacío.', 'error');
                return;
            }
        } else if (fieldName === 'client_email') {
            inputField = document.getElementById(`email-input-${tripId}`);
            displaySpan = document.getElementById(`email-display-${tripId}`);
        } else if (fieldName === 'client_name') {
            inputField = document.getElementById(`name-input-${tripId}`);
            displaySpan = document.getElementById(`name-display-${tripId}`);
        }
        
        const newValue = inputField.value.trim();
        let currentValue = displaySpan.textContent.trim();
        if (fieldName === 'client_email' && currentValue === 'Añadir correo') currentValue = '';
        if (fieldName === 'client_name' && currentValue === 'Sin cliente') currentValue = '';
        
        if (newValue === currentValue) {
            inputField.style.display = 'none';
            displaySpan.style.display = 'inline-block';
            return;
        }

        fetch(`{{ url('trips') }}/${tripId}/inline-update`, {
            method: 'POST',
            headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')},
            body: JSON.stringify({ field: fieldName, value: newValue })
        }).then(r => r.json()).then(d => {
            if (d.success) {
                if(fieldName === 'client_email' && !newValue) {
                    displaySpan.textContent = 'Añadir correo';
                } else if (fieldName === 'client_name' && !newValue) {
                    displaySpan.textContent = 'Sin cliente';
                } else {
                    displaySpan.textContent = newValue;
                }
                inputField.style.display = 'none'; 
                displaySpan.style.display = 'inline-block';
            } else {
                showNotification('Error', d.message || 'No se pudo actualizar el campo.', 'error');
                // Restore on error
                inputField.style.display = 'none';
                displaySpan.style.display = 'inline-block';
            }
        }).catch(err => {
            console.error('Update error:', err);
            showNotification('Error', 'Error de conexión al actualizar.', 'error');
            inputField.style.display = 'none';
            displaySpan.style.display = 'inline-block';
        });
    }

    function handleFieldKeyPress(event, tripId, fieldName) {
        if (event.key === 'Enter') saveTripField(tripId, fieldName);
        else if (event.key === 'Escape') {
            let inputField, displaySpan;
            if (fieldName === 'title') {
                inputField = document.getElementById(`title-input-${tripId}`);
                displaySpan = document.getElementById(`title-display-${tripId}`);
            } else if (fieldName === 'client_email') {
                inputField = document.getElementById(`email-input-${tripId}`);
                displaySpan = document.getElementById(`email-display-${tripId}`);
            } else if (fieldName === 'client_name') {
                inputField = document.getElementById(`name-input-${tripId}`);
                displaySpan = document.getElementById(`name-display-${tripId}`);
            }
            inputField.style.display = 'none';
            displaySpan.style.display = 'inline-block';
        }
    }

    // Share Modal
    function shareTripIndex(tripId, token) {
        if(token) return showShareModalIndex(`${window.location.origin}/trips/share/${token}`);
        fetch(`{{ url('trips') }}/${tripId}/generate-share-token`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') }
        }).then(r => r.json()).then(d => { if(d.success) showShareModalIndex(d.share_url); });
    }

    function showShareModalIndex(url) {
        // Remove existing modal if present
        const existingModal = document.getElementById('shareModal');
        if (existingModal) {
            existingModal.remove();
        }

        // Create modal HTML with premium styles matching auth-header.blade.php
        const modalHtml = `
            <div id="shareModal" class="share-modal-overlay" style="
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(0, 0, 0, 0.5);
                display: flex;
                align-items: center;
                justify-content: center;
                z-index: 10000;
                font-family: 'Poppins', sans-serif;
            ">
                <div class="share-modal" style="
                    background: white;
                    border-radius: 16px;
                    padding: 2rem;
                    max-width: 500px;
                    width: 90%;
                    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
                    position: relative;
                ">
                    <div class="share-modal-header" style="
                        text-align: center;
                        margin-bottom: 1.5rem;
                    ">
                        <h3 style="
                            font-size: 1.5rem;
                            font-weight: 700;
                            color: #1f2937;
                            margin: 0 0 0.5rem 0;
                        ">Compartir Itinerario</h3>
                        <p style="
                            color: #6b7280;
                            margin: 0;
                            font-size: 0.9rem;
                        ">Copia el enlace para compartir este viaje</p>
                    </div>

                    <div class="share-modal-body">
                        <div class="share-url-container" style="
                            margin-bottom: 1.5rem;
                        ">
                            <label style="
                                display: block;
                                font-size: 0.85rem;
                                font-weight: 600;
                                color: #374151;
                                margin-bottom: 0.5rem;
                            ">Enlace de compartición:</label>
                            <div class="share-url-input-group" style="
                                display: flex;
                                gap: 0.5rem;
                            ">
                                <input type="text" id="shareUrlInput" value="${url}" readonly style="
                                    flex: 1;
                                    padding: 0.75rem;
                                    border: 1px solid #d1d5db;
                                    border-radius: 8px;
                                    font-size: 0.9rem;
                                    background: #f9fafb;
                                    color: #374151;
                                    font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace;
                                ">
                                <button id="copyShareUrlBtn" style="
                                    padding: 0.75rem 1rem;
                                    background: linear-gradient(135deg, #1a7a8a, #1e293b);
                                    color: white;
                                    border: none;
                                    border-radius: 8px;
                                    cursor: pointer;
                                    font-weight: 600;
                                    display: flex;
                                    align-items: center;
                                    gap: 0.5rem;
                                    transition: all 0.3s ease;
                                    white-space: nowrap;
                                ">
                                    <i class="fas fa-copy"></i>
                                    Copiar
                                </button>
                            </div>
                        </div>

                        <div class="share-modal-actions" style="
                            display: flex;
                            gap: 0.75rem;
                            justify-content: flex-end;
                        ">
                            <button id="closeShareModalBtn" style="
                                padding: 0.625rem 1.25rem;
                                background: #f3f4f6;
                                color: #374151;
                                border: 1px solid #d1d5db;
                                border-radius: 8px;
                                cursor: pointer;
                                font-weight: 500;
                                transition: all 0.3s ease;
                            ">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>
        `;

        // Add modal to body
        document.body.insertAdjacentHTML('beforeend', modalHtml);

        // Get modal elements
        const modal = document.getElementById('shareModal');
        const urlInput = document.getElementById('shareUrlInput');
        const copyBtn = document.getElementById('copyShareUrlBtn');
        const closeBtn = document.getElementById('closeShareModalBtn');

        // Auto-select the URL
        setTimeout(() => {
            urlInput.select();
            urlInput.focus();
        }, 100);

        // Copy button functionality
        copyBtn.addEventListener('click', async () => {
            try {
                await navigator.clipboard.writeText(url);
                copyBtn.innerHTML = '<i class="fas fa-check"></i> ¡Copiado!';
                copyBtn.style.background = 'linear-gradient(135deg, #1e293b, #047857)';

                // Reset button after 2 seconds
                setTimeout(() => {
                    copyBtn.innerHTML = '<i class="fas fa-copy"></i> Copiar';
                    copyBtn.style.background = 'linear-gradient(135deg, #1a7a8a, #1e293b)';
                }, 2000);
            } catch (error) {
                // Fallback for older browsers
                urlInput.select();
                document.execCommand('copy');
                copyBtn.innerHTML = '<i class="fas fa-check"></i> ¡Copiado!';
                copyBtn.style.background = 'linear-gradient(135deg, #1e293b, #047857)';

                setTimeout(() => {
                    copyBtn.innerHTML = '<i class="fas fa-copy"></i> Copiar';
                    copyBtn.style.background = 'linear-gradient(135deg, #1a7a8a, #1e293b)';
                }, 2000);
            }
        });

        // Close modal functionality
        closeBtn.addEventListener('click', () => {
            modal.remove();
        });

        // Close on overlay click
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                modal.remove();
            }
        });

        // Close on Escape key
        document.addEventListener('keydown', function closeOnEscape(e) {
            if (e.key === 'Escape') {
                modal.remove();
                document.removeEventListener('keydown', closeOnEscape);
            }
        });
    }

    // Table Sorting
    function sortTable(columnIndex, type = 'string') {
        const table = document.getElementById("mainTable");
        let rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
        switching = true;
        // Set the sorting direction to ascending:
        dir = "asc"; 
        
        // Reset all header icons
        const headers = table.querySelectorAll('th.sortable');
        headers.forEach(th => {
            const icon = th.querySelector('.sort-icon');
            if (icon) icon.innerHTML = '<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round" style="opacity: 0.3;"><path d="M15 4v16"/><path d="M15 20l-4-4"/><path d="M15 20l4-4"/><path d="M4 8l2-6 2 6"/><path d="M5 5h2"/><path d="M4 14h4l-4 6h4"/></svg>'; // default
        });
        
        const currentHeader = headers[columnIndex - 2]; // Adjust index based on sortable headers offset
        const currentIcon = currentHeader.querySelector('.sort-icon');

        while (switching) {
            switching = false;
            rows = table.querySelectorAll("tbody .trip-row");
            
            for (i = 0; i < (rows.length - 1); i++) {
                shouldSwitch = false;
                x = rows[i].getElementsByTagName("TD")[columnIndex];
                y = rows[i + 1].getElementsByTagName("TD")[columnIndex];
                
                let valX = x.textContent || x.innerText;
                let valY = y.textContent || y.innerText;
                
                // Special handling for inputs inside the cell (like status dropdown)
                if (columnIndex === 6) { // Status column
                   valX = x.querySelector('select').options[x.querySelector('select').selectedIndex].text;
                   valY = y.querySelector('select').options[y.querySelector('select').selectedIndex].text;
                }
                
                if (type === 'number') {
                    valX = parseFloat(valX.replace(/[^0-9.-]+/g,""));
                    valY = parseFloat(valY.replace(/[^0-9.-]+/g,""));
                } else if (type === 'date') {
                    // Extract date using a simpler approach if the specific formats vary 
                    // This attempts to extract a parseable date or uses raw string comparison
                    valX = valX.trim().toLowerCase();
                    valY = valY.trim().toLowerCase();
                } else {
                    valX = valX.toLowerCase().trim();
                    valY = valY.toLowerCase().trim();
                }

                if (dir == "asc") {
                    if (valX > valY) {
                        shouldSwitch = true;
                        break;
                    }
                } else if (dir == "desc") {
                    if (valX < valY) {
                        shouldSwitch = true;
                        break;
                    }
                }
            }
            if (shouldSwitch) {
                rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
                switching = true;
                switchcount ++;      
            } else {
                if (switchcount == 0 && dir == "asc") {
                    dir = "desc";
                    switching = true;
                }
            }
        }
        
        // Update the clicked header icon
        if (currentIcon) {
            currentIcon.innerHTML = dir === 'asc' ? '<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"><path d="M15 4v16"/><path d="M15 20l-4-4"/><path d="M15 20l4-4"/><path d="M4 8l2-6 2 6"/><path d="M5 5h2"/><path d="M4 14h4l-4 6h4"/></svg>' : '<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"><path d="M15 20V4"/><path d="M15 4l-4 4"/><path d="M15 4l4 4"/><path d="M4 8l2-6 2 6"/><path d="M5 5h2"/><path d="M4 14h4l-4 6h4"/></svg>';
        }
    }
</script>
@endpush
