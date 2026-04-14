@extends('layouts.app')

@section('title', 'Viantryp | Mis Viajes')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@700;800;900&family=Barlow:wght@400;500;600;700&display=swap" rel="stylesheet"/>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/driver.js@1.0.1/dist/driver.css"/>
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

    /* Driver.js Custom Styles */
    .driver-popover {
        background-color: var(--white);
        border-radius: 16px;
        padding: 20px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.15);
        border: 1px solid var(--bdr);
        font-family: 'Barlow', sans-serif;
    }
    .driver-popover-title {
        font-family: 'Barlow Condensed', sans-serif;
        font-weight: 800;
        font-size: 20px;
        color: var(--ink);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .driver-popover-description {
        font-size: 14px;
        color: var(--gray);
        line-height: 1.5;
        margin-top: 8px;
    }
    .driver-popover-footer {
        margin-top: 15px;
    }
    .driver-popover-btn {
        background: var(--teal);
        color: white;
        text-shadow: none;
        border: none;
        padding: 6px 14px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 12px;
        transition: all 0.2s;
    }
    .driver-popover-btn:hover {
        background: var(--teal2);
    }
    .driver-popover-close-btn {
        color: var(--gray2);
    }
    .driver-popover-arrow {
        border-color: var(--white);
    }

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

    html, body {
      height: 100%;
      font-family: 'Barlow', sans-serif;
      color: var(--dark);
      background: var(--light);
    }

    body { display: flex; flex-direction: column; min-height: 100vh; }

    /* ════════════════════════════════════════
       TOPBAR
    ════════════════════════════════════════ */
    .topbar {
      position: sticky; top: 0; z-index: 200;
      height: 64px;
      display: flex; align-items: center; justify-content: space-between;
      padding: 0 40px;
      flex-shrink: 0;
    }
    .topbar-bg-decorators { display: none; }
    .topbar-bg-decorators::before {
      content: ''; position: absolute; top: 0; right: 120px;
      width: 160px; height: 300%; background: var(--teal);
      transform: skewX(-16deg); opacity: 0.07;
    }
    .topbar-bg-decorators::after {
      content: ''; position: absolute; top: 0; right: 60px;
      width: 60px; height: 300%; background: var(--teal);
      transform: skewX(-16deg); opacity: 0.04;
    }
    .topbar-left { display: flex; align-items: center; gap: 28px; position: relative; z-index: 1; }
    
    .logo {
      display: flex; align-items: center; text-decoration: none;
    }
    .logo img {
      height: 28px; width: auto;
      filter: brightness(0) invert(1);
    }

    .nav-links { display: flex; gap: 4px; }
    .nav-link {
      font-size: 14px; font-weight: 500; color: var(--dark); text-decoration: none;
      padding: 7px 14px; border-radius: 8px; transition: background 0.18s, color 0.18s;
    }
    .nav-link:hover { background: var(--light); color: var(--accent); }
    .nav-link.active { color: var(--accent); background: var(--accent-light); }

    .topbar-right { display: flex; align-items: center; gap: 10px; position: relative; z-index: 1; }
    .ubadge {
      display: flex; align-items: center; gap: 8px; padding: 4px 14px 4px 4px; border-left: 1px solid rgba(255,255,255,0.15); margin-left:8px;
    }
    .avatar {
      width: 32px; height: 32px; border-radius: 50%;
      background: var(--avatar-gradient);
      display: flex; align-items: center; justify-content: center;
      font-size: 11px; font-weight: 700; color: white; letter-spacing: 0.5px;
    }
    .uname { font-size: 14px; font-weight: 600; color: rgba(255,255,255,0.9); }
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
      background: var(--white); padding: 48px 40px 0;
      position: relative; overflow: hidden;
      border-bottom: 1px solid var(--border);
    }
    .hero-rings { display: none; }
    .hero-dot { display: none; }
    .hero-watermark { display: none; }
    
    .hero-tag {
      display: inline-flex; align-items: center; gap: 6px;
      background: var(--accent-light); border: 1px solid var(--accent-border);
      border-radius: 6px; padding: 4px 12px; font-size: 11px; font-weight: 700;
      letter-spacing: 1px; text-transform: uppercase; color: var(--accent); margin-bottom: 12px;
    }
    .htag-dot { width: 6px; height: 6px; border-radius: 50%; background: var(--accent); animation: blink 2s infinite; }
    
    .hero-title {
      font-family: 'Barlow Condensed', sans-serif; font-weight: 900; font-size: 32px; line-height: 1.1;
      color: #000000; letter-spacing: -0.5px; margin-bottom: 8px; text-transform: uppercase;
    }
    .hero-sub { font-size: 15px; font-weight: 400; color: var(--gray); margin-bottom: 0; }
    .hero-header-mobile { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; gap: 10px; }
    .btn-mobile-only { display: none !important; }

    /* STAT CHIPS */
    .stat-chips { display: flex; gap: 8px; padding-bottom: 0; }
    .schip {
      background: var(--teal); border: 1px solid rgba(255,255,255,0.1); border-bottom: none;
      border-radius: 12px 12px 0 0; padding: 14px 24px; min-width: 120px;
      display: flex; flex-direction: column; align-items: center; cursor: pointer;
      transition: all 0.2s; position: relative;
    }
    .schip::after {
      content: ''; position: absolute; bottom: 0; left: 0; right: 0; height: 3px;
      background: var(--white); transform: scaleX(0); transition: transform 0.25s;
    }
    .schip.on { background: var(--dark); }
    .schip.on::after { transform: scaleX(1); }
    .schip:hover:not(.on) { background: var(--teal-dark); }
    .schip:active { transform: translateY(1px); }
    .chip-num { font-family: 'Barlow Condensed', sans-serif; font-weight: 800; font-size: 28px; line-height: 1; color: var(--white); }
    .chip-lbl { font-size: 11px; font-weight: 600; color: rgba(255,255,255,0.8); margin-top: 4px; text-transform: uppercase; letter-spacing: 0.5px; }
    .schip.on .chip-lbl { color: rgba(255,255,255,0.75); }

    /* ACTION BUTTON */
    .btn-create {
      display: flex; align-items: center; gap: 10px; height: 44px; padding: 0 24px; border-radius: 50px;
      background: var(--teal); color: white; border: none;
      font-size: 14px; font-weight: 700; font-family: 'Barlow', sans-serif; cursor: pointer; text-decoration: none;
      box-shadow: 0 4px 16px rgba(26,158,143,0.3); transition: all 0.2s;
    }
    .btn-create:hover { background: var(--teal2); transform: translateY(-1px); box-shadow: 0 8px 24px rgba(26,158,143,0.4); color: white; }
    .btn-create:active { transform: translateY(0); }

    .wave { display: none; }

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
        padding: 7px 14px; border: 1px solid var(--bdr); border-radius: 10px; font-size: 12px; font-weight: 500;
        cursor: pointer; transition: all 0.2s; display: flex; align-items: center; gap: 6px; background: white; color: var(--ink);
        box-shadow: 0 2px 4px rgba(0,0,0,0.02);
    }
    .bulk-duplicate-btn:hover { background: #f0faf9; border-color: var(--teal); color: var(--teal); }
    .bulk-delete-btn:hover { background: #fff5f5; border-color: #d94040; color: #d94040; }
    .bulk-clear-btn:hover { background: #f8fafc; border-color: var(--gray); }

    /* TABLE */
    .tbl-wrap { background: var(--white); border: 1px solid var(--bdr); border-radius: 18px; overflow: visible; box-shadow: 0 4px 24px rgba(10,22,40,0.06); }
    table { width: 100% !important; border-collapse: collapse; table-layout: fixed; }
    thead { background: #f3f3f3; }
    thead tr { border-bottom: 1px solid var(--bdr); background: transparent; }
    thead th { position: relative; padding: 13px 20px; text-align: left; font-size: 10.5px; font-weight: 700; letter-spacing: 0.8px; text-transform: uppercase; color: #24292e; line-height: 1.4; vertical-align: top; background: transparent; }
    thead th.sortable { transition: background 0.15s; }
    thead th.sortable:hover { background: #e9e9e9; }
    
    .col-menu-btn {
        position: absolute;
        right: 8px;
        top: 50%;
        transform: translateY(-50%);
        width: 20px;
        height: 20px;
        display: none;
        align-items: center;
        justify-content: center;
        border-radius: 4px;
        cursor: pointer;
        color: var(--gray);
        transition: all 0.2s;
        z-index: 5;
    }
    thead th:hover .col-menu-btn { display: flex; }
    .col-menu-btn:hover { background: rgba(0,0,0,0.05); color: var(--teal); }
    
    .header-dropdown {
        position: absolute;
        top: 100%;
        right: 0;
        background: white;
        border: 1px solid var(--bdr);
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.12);
        z-index: 1000;
        padding: 8px;
        min-width: 180px;
        display: none;
        text-transform: none;
        letter-spacing: normal;
        font-weight: 500;
    }
    .header-dropdown.show { display: block; }
    
    .header-dropdown-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 8px 12px;
        border-radius: 8px;
        cursor: pointer;
        font-size: 12px;
        color: var(--ink);
        transition: background 0.2s;
    }
    .header-dropdown-item:hover { background: #f3f3f3; }
    .header-dropdown-item svg { width: 14px; height: 14px; color: var(--gray); }
    

    thead th:first-child { width: 46px; padding-left: 22px; border-top-left-radius: 17px; }
    thead th:last-child { border-top-right-radius: 17px; }
    thead th.right { text-align: center; padding-right: 22px; }

    /* Column Resizer */
    .resizer {
        position: absolute;
        top: 0;
        right: -5px;
        width: 10px;
        cursor: col-resize;
        user-select: none;
        height: 100%;
        z-index: 10;
        display: flex;
        justify-content: center;
    }
    .resizer::after {
        content: "";
        width: 1px;
        height: 100%;
        border-right: 1px dotted rgba(36, 41, 46, 0.3);
        transition: border-color 0.2s, opacity 0.2s;
    }
    .resizer:hover::after, .resizing .resizer::after {
        border-right: 1px dotted var(--teal);
        border-right-style: solid; /* Make it solid on hover for better visibility */
        opacity: 1;
    }
    .resizing {
        cursor: col-resize !important;
        user-select: none !important;
    }
    
    tbody tr { border-bottom: 1px solid var(--bdr); transition: transform 0.22s, opacity 0.22s, background 0.14s; }
    tbody tr:last-child { border-bottom: none; }
    tbody tr:hover { background: #f9f9f9; }
    tbody td { position: relative; padding: 20px 20px; vertical-align: middle; font-size: 14px; }
    tbody td:not(:first-child):not(:last-child):not(.bar-cell)::after {
        content: "";
        position: absolute;
        right: 0;
        top: 0;
        bottom: 0;
        border-right: 1px dotted rgba(36, 41, 46, 0.15);
        pointer-events: none;
    }
    tbody td:first-child { padding-left: 22px; }
    tbody tr:last-child td:first-child { border-bottom-left-radius: 18px; }
    tbody tr:last-child td:last-child { border-bottom-right-radius: 18px; }
    input[type=checkbox] { width: 15px; height: 15px; accent-color: var(--teal); cursor: pointer; }

    .id-chip, .name-display, .email-display { font-size: 10.5px; font-weight: 700; font-family: monospace; letter-spacing: 0.5px; color: #071917; background: #e7f7f51a; border: 1px solid rgba(7, 25, 23, 0.15); padding: 3px 8px; border-radius: 6px; cursor: pointer; transition: background 0.2s; display: inline-block; }
    .id-chip:hover, .name-display:hover, .email-display:hover { background: #e7f7f5; border-color: #071917; }
    .name-display, .email-display { font-family: 'DM Sans', sans-serif; letter-spacing: 0.2px; }
    .name-display { font-size: 13px; }
    .email-display { font-size: 10px; font-weight: 500; color: #1a9a8a; white-space: normal; word-break: break-all; max-width: 100%; display: inline-block; }
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
        font-size: 10px;
        font-weight: 500;
        white-space: normal;
        height: auto;
        line-height: 1.2;
        padding: 8px 28px 8px 12px;
        cursor: pointer;
        transition: all 0.2s ease;
        min-width: 100px;
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
    .status-sent     { background-color: #fef9c3 !important; color: #854d0e !important; border-color: #fef08a !important; }
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
      width: 72px; height: 72px; border-radius: 50%; background: var(--accent-light);
      border: 1.5px dashed var(--accent-border); display: flex; align-items: center; justify-content: center; margin: 0 auto 18px;
    }
    .e-ring svg { width: 30px; height: 30px; color: var(--accent); }
    .empty h3 { font-family: Inter, sans-serif; font-weight: 600; font-size: 20px; color: var(--ink); margin-bottom: 7px; }
    .empty p  { font-size: 13px; color: var(--gray); }

    .bar-cell { width: 4px; padding: 0 !important; }
    .bar-inner { width: 4px; height: 100%; border-radius: 2px; }

    @media (max-width: 768px) {
        .topbar { padding: 0 10px; }
        .uname { display: none; }
        .btn-out { font-size: 11px; padding: 6px 12px; gap: 4px; }
        .btn-out svg { width: 12px; height: 12px; }
        .topbar-right { flex-direction: row-reverse; gap: 2px; }
        .btn-help { display: none !important; }
        .ubadge { padding: 0; border: none; margin: 0; }

        /* Mobile Trips Card Layout */
        .toolbar { flex-direction: column; align-items: stretch; }
        .sbox { max-width: 100%; }
        .toolbar .btn-create { display: none !important; }
        .btn-mobile-only { display: flex !important; margin-left: auto; }
        .hero-header-mobile { flex-wrap: wrap; }

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
        .trip-row > td:nth-child(7) { position: relative; top: 0; right: 0; padding: 4px 0 0 0; border: none; width: auto; z-index: 10; margin-top: -8px; }
        .status-select { 
            padding: 4px 26px 4px 10px; font-size: 11.5px; 
            border-radius: 6px; pointer-events: auto;
            width: fit-content;
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
        .acts { justify-content: space-between; width: 100%; gap: 6px; }
        
        /* Specialized Mobile Buttons */
        .abt { 
            width: auto; flex: 1; height: 36px; border-radius: 8px; font-size: 11.5px; 
            font-weight: 600; font-family: 'DM Sans', sans-serif; 
            display: flex; align-items: center; justify-content: center; gap: 4px;
            color: var(--gray); border-color: var(--bdr); background: transparent;
            padding: 0 4px;
        }
        .abt::after { display: none !important; } /* Hide tooltips */
        
        .abt.view::before { content: 'Ver'; }
        .abt.edit::before { content: 'Editar'; }
        .abt.share::before { content: 'Compartir'; }
        .abt.more::before { content: 'Más'; }
        
        .abt.del { flex: 0 0 36px; }
        .abt svg { width: 13px; height: 13px; flex-shrink: 0; }

        .acts-menu-container { flex: 1; display: flex; }
        .acts-menu-container .abt { flex: 1; width: 100%; }
        
        .trip-row.menu-open { z-index: 1000 !important; }

        /* Empty state adaptation */
        .empty { padding: 40px 15px; }
    }

    /* SEGMENTED CONTROL */
    .segmented-control-container {
        display: flex;
        justify-content: flex-start;
        margin-bottom: 24px;
        margin-top: 8px;
    }
    .segmented-control {
        position: relative;
        display: flex;
        background: #f1f1f1;
        padding: 4px;
        border-radius: 12px;
        width: fit-content;
        user-select: none;
    }
    .segment-item {
        position: relative;
        padding: 8px 18px;
        font-size: 13.5px;
        font-weight: 600;
        color: var(--gray);
        cursor: pointer;
        z-index: 1;
        transition: color 0.3s;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .segment-item.active {
        color: var(--teal);
    }
    .segment-item i {
        font-size: 14px;
    }
    .segment-slider {
        position: absolute;
        top: 4px;
        left: 4px;
        height: calc(100% - 8px);
        background: white;
        border-radius: 9px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        z-index: 0;
    }

    /* ACTIONS MENU */
    .acts-menu-container {
        position: relative;
        display: inline-block;
    }
    .acts-menu {
        position: absolute;
        right: 0;
        top: 100%;
        background: white;
        border: 1px solid var(--bdr);
        border-radius: 10px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        z-index: 100;
        min-width: 180px;
        display: none;
        overflow: hidden;
        margin-top: 5px;
    }
    .acts-menu.show {
        display: block;
        animation: slideDown 0.2s ease;
    }
    .acts-menu-item {
        padding: 10px 15px;
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 13px;
        color: var(--ink);
        cursor: pointer;
        transition: background 0.2s;
    }
    .acts-menu-item:hover {
        background: var(--sand);
    }
    .acts-menu-item i {
        width: 16px;
        color: var(--gray2);
    }
    .acts-menu-item.danger {
        color: #d94040;
    }
    .acts-menu-item.danger i {
        color: #d94040;
    }

    @keyframes slideDown {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
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
            'sent' => 'Propuesta',
            'reserved' => 'Reservado',
            'completed' => 'Pago Completo',
            'discarded' => 'Descartado',
        ];
        return $labels[$status] ?? ucfirst($status);
    }
@endphp

<x-header :tutorialOnclick="'initTutorial(true)'" />


<!-- ══ CONTENT ══ -->
<div class="content">

    <div class="hero-tag">
        <div class="htag-dot"></div>
        Plan {{ ucfirst(auth()->user()->plan) }}
    </div>
    <h1 class="hero-title">Panel de Control</h1>
    <div class="hero-header-mobile">
        <p class="hero-sub">Diseña tus itinerarios y gestiona tus viajes de forma profesional.</p>
        <button onclick="showCreateTripModal()" class="btn-create btn-mobile-only">
          <i class="fas fa-plus"></i>
          <span>Crear viaje</span>
        </button>
    </div>

    <div class="segmented-control-container">
        <div class="segmented-control">
            <div class="segment-slider" id="segmentSlider"></div>
            <div class="segment-item {{ $activeMainTab === 'personal' ? 'active' : '' }}" onclick="switchTripsTab('personal', this)">
                <i class="fas fa-suitcase-rolling"></i>
                Mis viajes
            </div>
            <div class="segment-item {{ $activeMainTab === 'shared' ? 'active' : '' }}" onclick="switchTripsTab('shared', this)">
                <i class="fas fa-users"></i>
                Compartidos
            </div>
        </div>
    </div>

  <div class="toolbar">
    <div class="sbox">
      <span class="sico">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg>
      </span>
      <input type="text" placeholder="Buscar por ID, nombre, cliente..." id="searchInput" oninput="searchTripsRows(this.value)"/>
    </div>
    
    @if($activeMainTab !== 'shared')
    <div style="margin-left: auto; display: flex; align-items: center; gap: 12px;">
        <button onclick="showCreateTripModal()" class="btn-create">
          <i class="fas fa-plus"></i>
          <span>Crear viaje</span>
        </button>
    </div>
    @endif
  </div>

  <div class="bulk-actions" id="bulk-actions">
      <div class="bulk-actions-info">
          <span>
              <i class="fas fa-check-circle"></i>
              <span id="selected-count">0</span> viaje(s) seleccionado(s)
          </span>
      </div>
      <div style="display: flex; gap: 8px;">
          <button class="bulk-action-btn bulk-duplicate-btn" onclick="duplicateSelectedTrips()">
              <i class="fas fa-copy" style="font-size: 11px; opacity: 0.7;"></i> Duplicar
          </button>
          <button class="bulk-action-btn bulk-delete-btn" onclick="deleteSelectedTrips()">
              <i class="fas fa-trash-alt" style="font-size: 11px; opacity: 0.7;"></i> Eliminar
          </button>
          <button class="bulk-action-btn bulk-clear-btn" onclick="clearSelection()">
              <i class="fas fa-times" style="font-size: 11px; opacity: 0.7;"></i> Limpiar
          </button>
      </div>
  </div>

  <div id="table-options-bar" style="display: flex; justify-content: flex-end; margin-bottom: 20px;">
      <button id="show-columns-btn" style="background: white; color: var(--ink); display: none; padding: 8px 14px; border: 1px solid var(--bdr); border-radius: 10px; font-weight: 500; font-size: 12px; cursor: pointer; align-items: center; gap: 6px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); transition: all 0.2s;" onclick="showAllColumns()">
          <i class="fas fa-eye" style="color: var(--gray); font-size: 11px;"></i> Mostrar columnas ocultas
      </button>
  </div>

  <!-- Table -->
  <div class="tbl-wrap">
    <table id="mainTable">
      <thead>
        <tr>
          <th><input type="checkbox" id="checkAll" onchange="toggleSelectAll(this)"/></th>
          <th style="width:4px;padding:0"></th>
          <th class="sortable" style="user-select: none; min-width: 90px;">
            ID 
            <div class="col-menu-btn" onclick="toggleHeaderMenu(event, this)">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9l6 6 6-6"/></svg>
            </div>
            <div class="header-dropdown" onclick="event.stopPropagation()">
                <div class="header-dropdown-item" onclick="sortTableFromMenu(this, 'asc', 'string')"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 19V5M5 12l7-7 7 7"/></svg> Ordenar A - Z</div>
                <div class="header-dropdown-item" onclick="sortTableFromMenu(this, 'desc', 'string')"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12l7 7 7-7"/></svg> Ordenar Z - A</div>
                <div style="border-top: 1px solid var(--bdr); margin: 6px 0;"></div>
                <div class="header-dropdown-item" onclick="hideColumn(this)"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24M1 1l22 22"/></svg> Ocultar campo</div>
            </div>
            <div class="resizer"></div>
          </th>
          <th class="sortable" style="user-select: none;">
            Nombre del Viaje 
            <div class="col-menu-btn" onclick="toggleHeaderMenu(event, this)">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9l6 6 6-6"/></svg>
            </div>
            <div class="header-dropdown" onclick="event.stopPropagation()">
                <div class="header-dropdown-item" onclick="sortTableFromMenu(this, 'asc', 'string')"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 19V5M5 12l7-7 7 7"/></svg> Ordenar A - Z</div>
                <div class="header-dropdown-item" onclick="sortTableFromMenu(this, 'desc', 'string')"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12l7 7 7-7"/></svg> Ordenar Z - A</div>
                <div style="border-top: 1px solid var(--bdr); margin: 6px 0;"></div>
                <div class="header-dropdown-item" onclick="hideColumn(this)"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24M1 1l22 22"/></svg> Ocultar campo</div>
            </div>
            <div class="resizer"></div>
          </th>
          <th class="sortable" style="user-select: none;">
            Inicio 
            <div class="col-menu-btn" onclick="toggleHeaderMenu(event, this)">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9l6 6 6-6"/></svg>
            </div>
            <div class="header-dropdown" onclick="event.stopPropagation()">
                <div class="header-dropdown-item" onclick="sortTableFromMenu(this, 'asc', 'date')"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 19V5M5 12l7-7 7 7"/></svg> Ordenar A - Z</div>
                <div class="header-dropdown-item" onclick="sortTableFromMenu(this, 'desc', 'date')"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12l7 7 7-7"/></svg> Ordenar Z - A</div>
                <div style="border-top: 1px solid var(--bdr); margin: 6px 0;"></div>
                <div class="header-dropdown-item" onclick="hideColumn(this)"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24M1 1l22 22"/></svg> Ocultar campo</div>
            </div>
            <div class="resizer"></div>
          </th>
          <th class="sortable" style="user-select: none;">
            Cliente 
            <div class="col-menu-btn" onclick="toggleHeaderMenu(event, this)">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9l6 6 6-6"/></svg>
            </div>
            <div class="header-dropdown" onclick="event.stopPropagation()">
                <div class="header-dropdown-item" onclick="sortTableFromMenu(this, 'asc', 'string')"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 19V5M5 12l7-7 7 7"/></svg> Ordenar A - Z</div>
                <div class="header-dropdown-item" onclick="sortTableFromMenu(this, 'desc', 'string')"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12l7 7 7-7"/></svg> Ordenar Z - A</div>
                <div style="border-top: 1px solid var(--bdr); margin: 6px 0;"></div>
                <div class="header-dropdown-item" onclick="hideColumn(this)"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24M1 1l22 22"/></svg> Ocultar campo</div>
            </div>
            <div class="resizer"></div>
          </th>
          <th class="sortable" style="user-select: none; min-width: 150px;">
            Estado 
            <div class="col-menu-btn" onclick="toggleHeaderMenu(event, this)">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9l6 6 6-6"/></svg>
            </div>
            <div class="header-dropdown" onclick="event.stopPropagation()">
                <div class="header-dropdown-item" onclick="sortTableFromMenu(this, 'asc', 'string')"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 19V5M5 12l7-7 7 7"/></svg> Ordenar A - Z</div>
                <div class="header-dropdown-item" onclick="sortTableFromMenu(this, 'desc', 'string')"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12l7 7 7-7"/></svg> Ordenar Z - A</div>
                <div style="border-top: 1px solid var(--bdr); margin: 6px 0;"></div>
                <div class="header-dropdown-item" onclick="hideColumn(this)"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24M1 1l22 22"/></svg> Ocultar campo</div>
            </div>
            <div class="resizer"></div>
          </th>
          <th class="sortable" style="user-select: none; {{ $activeMainTab === 'shared' ? 'min-width: 130px;' : '' }}">
            @if($activeMainTab === 'shared')
                Propietario
            @else
                Vistas 
            @endif
            <div class="col-menu-btn" onclick="toggleHeaderMenu(event, this)">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9l6 6 6-6"/></svg>
            </div>
            <div class="header-dropdown" onclick="event.stopPropagation()">
                @if($activeMainTab === 'shared')
                    <div class="header-dropdown-item" onclick="sortTableFromMenu(this, 'asc', 'string')"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 19V5M5 12l7-7 7 7"/></svg> Ordenar A - Z</div>
                    <div class="header-dropdown-item" onclick="sortTableFromMenu(this, 'desc', 'string')"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12l7 7 7-7"/></svg> Ordenar Z - A</div>
                @else
                    <div class="header-dropdown-item" onclick="sortTableFromMenu(this, 'asc', 'number')"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 19V5M5 12l7-7 7 7"/></svg> Ordenar Mayor a Menor</div>
                    <div class="header-dropdown-item" onclick="sortTableFromMenu(this, 'desc', 'number')"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12l7 7 7-7"/></svg> Ordenar Menor a Mayor</div>
                @endif
                <div style="border-top: 1px solid var(--bdr); margin: 6px 0;"></div>
                <div class="header-dropdown-item" onclick="hideColumn(this)"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24M1 1l22 22"/></svg> Ocultar campo</div>
            </div>
            <div class="resizer"></div>
          </th>
          <th class="right" style="min-width: 180px;">Acciones</th>
        </tr>
      </thead>
      <tbody id="tbody">
          @if(count($trips) > 0)
              @foreach($trips as $index => $trip)
                <tr class="trip-row" data-trip-id="{{ $trip->id }}" data-is-pro="{{ $trip->is_pro ? '1' : '0' }}" style="animation-delay: {{ $index * 0.04 }}s; animation: rowIn 0.28s ease both; cursor: pointer;" onclick="if(window.innerWidth > 768) { window.location='{{ route('trips.edit', $trip->id) }}'; }">
                    <td onclick="event.stopPropagation()"><input type="checkbox" class="rchk trip-checkbox" data-trip-id="{{ $trip->id }}" onchange="updateSelectAllState()"/></td>
                    <td class="bar-cell"></td>
                    <td>
                        <span class="id-chip code-display" onclick="event.stopPropagation(); editTripCode({{ $trip->id }}, '{{ $trip->code }}')">{{ $trip->code ?? 'N/A' }}</span>
                        <input type="text" class="code-input" id="code-input-{{ $trip->id }}" style="display: none;" onblur="saveTripCode({{ $trip->id }})" onkeypress="handleCodeKeyPress(event, {{ $trip->id }})" maxlength="20">
                    </td>
                    <td style="min-width: 150px;">
                      <div class="trip-name">
                          <span class="title-display" id="title-display-{{ $trip->id }}" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; word-break: break-word; line-height: 1.3;">{{ $trip->title }}</span>
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
                               Inicio: {!! $trip->start_date ? \Carbon\Carbon::parse($trip->start_date)->translatedFormat('j M Y') : '<span style="color:#d94040;font-weight:700"><i class="fas fa-exclamation-triangle"></i> ¡Fecha vacía!</span>' !!}
                           </div>
                      </div>
                    </td>
                    <td style="min-width: 120px;">
                      <div class="trip-date">{!! $trip->start_date ? \Carbon\Carbon::parse($trip->start_date)->translatedFormat('j M Y') : '<span style="color:#d94040;font-weight:700;font-size:11px;text-transform:uppercase;background:#fee2e2;padding:2px 6px;border-radius:4px;"><i class="fas fa-exclamation-triangle"></i> Vacío</span>' !!}</div>
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
                          <option value="sent" {{ $trip->status === 'sent' ? 'selected' : '' }}>Propuesta</option>
                          <option value="reserved" {{ $trip->status === 'reserved' ? 'selected' : '' }}>Reservado</option>
                          <option value="completed" {{ $trip->status === 'completed' ? 'selected' : '' }}>Pago Completo</option>
                          <option value="discarded" {{ $trip->status === 'discarded' ? 'selected' : '' }}>Descartado</option>
                      </select>
                    </td>
                    <td>
                      @if($activeMainTab === 'shared')
                        @php
                            $myCollab = $trip->collaborators->first();
                            $isPending = $myCollab && !$myCollab->accepted_at;
                        @endphp
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <div class="owner-avatar" style="width: 24px; height: 24px; border-radius: 50%; background: var(--sand); color: var(--accent); display: flex; align-items: center; justify-content: center; font-size: 10px; font-weight: 700; border: 1px solid var(--bdr);">
                                {{ strtoupper(substr($trip->user->name, 0, 1) . substr($trip->user->last_name, 0, 1)) }}
                            </div>
                            <div>
                                <div style="font-size: 12px; font-weight: 600; color: var(--ink);">{{ $trip->user->name }}</div>
                                @if($isPending)
                                    <span style="font-size: 10px; color: #c0392b; font-weight: 700; text-transform: uppercase;">Invitación Pendiente</span>
                                @endif
                            </div>
                        </div>
                      @else
                        <div style="display: flex; align-items: center; gap: 6px; color: var(--gray2); font-weight: 500; font-size: 12px;">
                          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width: 14px; height: 14px; opacity: 0.7;">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>
                          </svg> 
                          {{ $trip->views_count ?? 0 }}
                        </div>
                      @endif
                    </td>
                    <td class="acts-cell" onclick="event.stopPropagation()">
                      <div class="acts">
                        @if($activeMainTab === 'shared' && isset($isPending) && $isPending)
                            <a href="{{ route('trips.accept-invite', ['token' => $myCollab->token]) }}" class="btn-create" style="padding: 6px 12px; font-size: 11px; height: 28px; background: var(--teal); border: none; color: white; border-radius: 6px; text-decoration: none; font-weight: 700; display: inline-flex; align-items: center; gap: 4px;">
                                <i class="fas fa-check"></i> Aceptar
                            </a>
                        @endif
                        <button class="abt view" data-tip="Ver propuesta" onclick="previewTrip({{ $trip->id }})">
                          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                        </button>
                        <button class="abt edit" data-tip="Editar" onclick="editTrip({{ $trip->id }})">
                          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                        </button>
                        <button class="abt share" data-tip="Enviar al cliente" onclick="shareTripIndex({{ $trip->id }}, '{{ $trip->share_token }}')">
                          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
                        </button>
                        
                        @if($activeMainTab !== 'shared')
                        <div class="acts-menu-container">
                            <button class="abt more" onclick="toggleActsMenu(event, {{ $trip->id }})" title="Más opciones">
                                <i class="fas fa-ellipsis-v"></i>
                            </button>
                            <div class="acts-menu" id="menu-{{ $trip->id }}">
                                @if($trip->user_id == Auth::id())
                                <div class="acts-menu-item" onclick="openSharingModal({{ $trip->id }}, 'editor')">
                                    <i class="fas fa-edit"></i> Compartir para editar
                                </div>
                                <div class="acts-menu-item" onclick="openCollaboratorsModal({{ $trip->id }})">
                                    <i class="fas fa-users"></i> Ver colaboradores
                                </div>
                                @endif
                                @if($trip->user_id == Auth::id())
                                <div class="acts-menu-item" onclick="openTransferModal({{ $trip->id }})">
                                    <i class="fas fa-exchange-alt"></i> Cambiar propietario
                                </div>
                                <div class="acts-menu-item" onclick="duplicateTrip({{ $trip->id }})">
                                    <i class="fas fa-copy"></i> Duplicar viaje
                                </div>
                                <div class="acts-menu-item danger" onclick="delRow({{ $trip->id }})">
                                    <i class="fas fa-trash"></i> Eliminar
                                </div>
                                @endif
                            </div>
                        </div>
                        @endif
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
    <x-upgrade-modal />
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/driver.js@1.0.1/dist/driver.js.iife.js"></script>
<script>
    function filterTrips(filter) {
        const url = new URL(window.location.href);
        url.searchParams.set('status', filter);
        window.location.href = url.toString();
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
            openProPreview(tripId);
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
                    proState.status = data.status || 'draft';
                    proState.userName = data.user_name || 'Viantryp';
                    proState.origin = window.location.origin;
                    proState.themeColor = data.theme_color || '#2b2d42';
                    proState.displayNameType = data.display_name_type || 'personal';
                    proState.agencyLogo = data.agency_logo || '';
                    proState.agencyName = data.agency_name || '';
                    proState.userFullName = data.user_full_name || '';

                    const previewHTML = buildPreviewHTML(proState);
                    const blob = new Blob([previewHTML], { type: 'text/html' });
                    const url = URL.createObjectURL(blob);
                    window.open(url, '_blank');
                } else {
                    alert(data.message || 'Error desconocido al cargar el viaje PRO');
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
        @if(auth()->user()->hasReachedTripLimit())
            openUpgradeModal();
            return;
        @endif
        const themeColor = '{{ auth()->user()->theme_color ?? "default" }}';
        const themes = {
            'default': '#1c7182',
            'ocean': '#1a5f8f',
            'gold': '#b08000',
            'sunset': '#c0552a',
            'blush': 'linear-gradient(135deg,#e07b9a,#f4a5bd)',
            'silver': 'linear-gradient(135deg,#6e7f80,#9aa8a9)',
            'mint': 'linear-gradient(135deg,#3db898,#62d4b5)',
            'lavender': 'linear-gradient(135deg,#9b72cf,#b39ddb)'
        };
        const currentTheme = themes[themeColor] || themes['default'];
        
        const adjustColor = (hex, amt) => {
            if (hex.includes('gradient')) return hex;
            let col = hex.replace('#', '');
            let r = parseInt(col.substring(0,2),16) + amt;
            let g = parseInt(col.substring(2,4),16) + amt;
            let b = parseInt(col.substring(4,6),16) + amt;
            r = Math.max(0, Math.min(255, r)).toString(16).padStart(2, '0');
            g = Math.max(0, Math.min(255, g)).toString(16).padStart(2, '0');
            b = Math.max(0, Math.min(255, b)).toString(16).padStart(2, '0');
            return '#' + r + g + b;
        };

        const modalHeaderBg = currentTheme.includes('gradient') ? currentTheme : `linear-gradient(135deg, ${adjustColor(currentTheme, -40)}, ${currentTheme})`;

        const modalHtml = `
            <div id="createTripModal" style="position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(15, 42, 58, 0.4); backdrop-filter:blur(8px); z-index:2000; display:flex; align-items:center; justify-content:center; animation: fadeIn 0.3s ease;">
                <div style="background:white; width:90%; max-width:450px; border-radius:16px; overflow:hidden; box-shadow:0 20px 40px rgba(0,0,0,0.1); animation: slideUp 0.3s ease;">
                    <div style="background:${modalHeaderBg}; padding:24px; color:white;">
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
                                <button type="submit" class="btn-viantryp" style="flex:1; height:44px; border:none; background:var(--accent); color:white; font-weight:700; border-radius:10px; cursor:pointer; font-size:13px; box-shadow:0 4px 12px rgba(26,106,120,0.3);">Diseñar Viaje</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <style>
                @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
                @keyframes slideUp { from { transform: translateY(20px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
                #createTripForm input:focus { border-color: var(--accent) !important; box-shadow: 0 0 0 3px rgba(26,154,138,0.1); }
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
                } else if (result.error_code === 'LIMIT_REACHED') {
                    document.getElementById('createTripModal').remove();
                    openUpgradeModal();
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
            headers: { 
                'Content-Type': 'application/json', 
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: JSON.stringify({ trip_ids: ids })
        })
        .then(async r => {
            if (!r.ok) {
                const err = await r.json();
                throw new Error(err.message || 'Error del servidor');
            }
            return r.json();
        })
        .then(d => {
            if (d.success) {
                showNotification('Viajes Duplicados', 'Los viajes seleccionados han sido duplicados.');
                setTimeout(() => location.reload(), 1000);
            }
        })
        .catch(err => {
            console.error('Bulk duplication error:', err);
            alert('Error al duplicar viajes: ' + err.message);
        });
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
                        ">Compartir viaje</h3>
                        <p style="
                            color: #6b7280;
                            margin: 0;
                            font-size: 0.9rem;
                        ">Cualquiera con este enlace podrá ver el itinerario.</p>
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
                            ">Copiar enlace:</label>
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
                                <button id="copyShareUrlBtn" class="btn-create" style="
                                    padding: 0 1.25rem;
                                    white-space: nowrap;
                                    height: 44px;
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
                copyBtn.style.background = '#047857';

                // Reset button after 2 seconds
                setTimeout(() => {
                    copyBtn.innerHTML = '<i class="fas fa-copy"></i> Copiar';
                    copyBtn.style.background = '';
                }, 2000);
            } catch (error) {
                // Fallback for older browsers
                urlInput.select();
                document.execCommand('copy');
                copyBtn.innerHTML = '<i class="fas fa-check"></i> ¡Copiado!';
                copyBtn.style.background = '#047857';

                setTimeout(() => {
                    copyBtn.innerHTML = '<i class="fas fa-copy"></i> Copiar';
                    copyBtn.style.background = '';
                }, 2000);
            }
        });

        // Close modal functionality
        const closeBtn_local = document.getElementById('closeShareModalBtn');
        if (closeBtn_local) {
            closeBtn_local.addEventListener('click', () => modal.remove());
        }
        
        modal.addEventListener('click', (e) => {
            if (e.target === modal) modal.remove();
        });

        const closeOnEscape = (e) => {
            if (e.key === 'Escape') {
                modal.remove();
                document.removeEventListener('keydown', closeOnEscape);
            }
        };
        document.addEventListener('keydown', closeOnEscape);
    }

    // Table Sorting
    function sortTable(columnIndex, type = 'string', forcedDir = null) {
        const table = document.getElementById("mainTable");
        let rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
        switching = true;
        
        // Determine initial direction
        dir = forcedDir || "asc"; 
        
        while (switching) {
            switching = false;
            rows = table.querySelectorAll("tbody .trip-row");
            
            for (i = 0; i < (rows.length - 1); i++) {
                shouldSwitch = false;
                x = rows[i].cells[columnIndex];
                y = rows[i + 1].cells[columnIndex];
                
                let valX = x.textContent || x.innerText;
                let valY = y.textContent || y.innerText;
                
                if (columnIndex === 6) { 
                   valX = x.querySelector('select').options[x.querySelector('select').selectedIndex].text;
                   valY = y.querySelector('select').options[y.querySelector('select').selectedIndex].text;
                }
                
                if (type === 'number') {
                    valX = parseFloat(valX.replace(/[^0-9.-]+/g,"")) || 0;
                    valY = parseFloat(valY.replace(/[^0-9.-]+/g,"")) || 0;
                } else if (type === 'date') {
                    valX = valX.trim().toLowerCase();
                    valY = valY.trim().toLowerCase();
                } else {
                    valX = valX.toLowerCase().trim();
                    valY = valY.toLowerCase().trim();
                }

                if (dir == "asc") {
                    if (valX > valY) { shouldSwitch = true; break; }
                } else if (dir == "desc") {
                    if (valX < valY) { shouldSwitch = true; break; }
                }
            }
            if (shouldSwitch) {
                rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
                switching = true;
                switchcount ++;      
            } else {
                if (switchcount == 0 && dir == "asc" && !forcedDir) {
                    dir = "desc";
                    switching = true;
                }
            }
        }
    }

    // Advanced Table Menu Functions
    function toggleHeaderMenu(event, btn) {
        event.stopPropagation();
        const menu = btn.nextElementSibling;
        const allMenus = document.querySelectorAll('.header-dropdown');
        
        allMenus.forEach(m => {
            if (m !== menu) m.classList.remove('show');
        });
        
        menu.classList.toggle('show');
        
        const closeMenu = (e) => {
            if (!menu.contains(e.target) && e.target !== btn) {
                menu.classList.remove('show');
                document.removeEventListener('click', closeMenu);
            }
        };
        if (menu.classList.contains('show')) {
            document.addEventListener('click', closeMenu);
        }
    }

    function sortTableFromMenu(item, dir, type) {
        const th = item.closest('th');
        const columnIndex = th.cellIndex;
        item.closest('.header-dropdown').classList.remove('show');
        sortTable(columnIndex, type, dir);
    }

    function hideColumn(item) {
        const th = item.closest('th');
        const colIndex = th.cellIndex;
        const table = document.getElementById('mainTable');
        
        th.style.display = 'none';
        const rows = table.rows;
        for (let i = 0; i < rows.length; i++) {
            if (rows[i].cells[colIndex]) {
                rows[i].cells[colIndex].style.display = 'none';
            }
        }
        
        document.getElementById('show-columns-btn').style.display = 'flex';
        
        const hiddenCols = JSON.parse(localStorage.getItem('tripsTableHiddenCols') || '[]');
        if (!hiddenCols.includes(colIndex)) {
            hiddenCols.push(colIndex);
            localStorage.setItem('tripsTableHiddenCols', JSON.stringify(hiddenCols));
        }
    }

    function showAllColumns() {
        const table = document.getElementById('mainTable');
        const rows = table.rows;
        const headers = table.querySelectorAll('th');
        
        // Disable fixed layout temporarily to help browser recalculate
        table.style.tableLayout = 'auto';
        
        for (let i = 0; i < rows.length; i++) {
            for (let j = 0; j < rows[i].cells.length; j++) {
                rows[i].cells[j].style.display = '';
            }
        }
        
        // Re-apply saved widths
        const savedWidths = JSON.parse(localStorage.getItem('tripsTableWidths') || '{}');
        let hasWidths = false;
        headers.forEach((th, index) => {
            if (savedWidths[index]) {
                th.style.width = savedWidths[index];
                hasWidths = true;
            } else {
                th.style.width = '';
            }
        });
        
        // Re-enable fixed layout if we had saved widths
        if (hasWidths) {
            table.style.tableLayout = 'fixed';
        }
        
        document.getElementById('show-columns-btn').style.display = 'none';
        localStorage.removeItem('tripsTableHiddenCols');
    }

    // Column Resizer Logic
    function initTableResizer() {
        const table = document.getElementById('mainTable');
        if (!table) return;

        const headers = table.querySelectorAll('th');
        const savedWidths = JSON.parse(localStorage.getItem('tripsTableWidths') || '{}');

        if (Object.keys(savedWidths).length > 0) {
            table.style.tableLayout = 'fixed';
        }

        headers.forEach((th, index) => {
            if (savedWidths[index]) {
                th.style.width = savedWidths[index];
            }

            const resizer = th.querySelector('.resizer');
            if (!resizer) return;

            resizer.addEventListener('mousedown', function(e) {
                e.preventDefault();
                e.stopPropagation();

                const startWidth = th.offsetWidth;
                const downX = e.pageX;
                const baseMinW = parseInt(window.getComputedStyle(th).minWidth) || 80;
                
                // Find next visible header sibling
                let nextTh = th.nextElementSibling;
                while (nextTh && nextTh.style.display === 'none') {
                    nextTh = nextTh.nextElementSibling;
                }
                
                let startWidthNext = nextTh ? nextTh.offsetWidth : 0;
                let minWNext = nextTh ? (parseInt(window.getComputedStyle(nextTh).minWidth) || 80) : 80;
                
                document.body.classList.add('resizing');
                
                const onMouseMove = (moveE) => {
                    const diff = moveE.pageX - downX;
                    const newWidth = startWidth + diff;
                    
                    if (nextTh) {
                        const newWidthNext = startWidthNext - diff;
                        if (newWidth >= baseMinW && newWidthNext >= minWNext) { 
                            th.style.width = newWidth + 'px';
                            nextTh.style.width = newWidthNext + 'px';
                            table.style.tableLayout = 'fixed';
                        }
                    } else {
                        if (newWidth >= baseMinW) {
                            th.style.width = newWidth + 'px';
                            table.style.tableLayout = 'fixed';
                        }
                    }
                };
                
                const onMouseUp = () => {
                    document.removeEventListener('mousemove', onMouseMove);
                    document.removeEventListener('mouseup', onMouseUp);
                    document.body.classList.remove('resizing');
                    
                    const widths = {};
                    table.querySelectorAll('th').forEach((header, idx) => {
                        if (header.style.width) {
                            widths[idx] = header.style.width;
                        }
                    });
                    localStorage.setItem('tripsTableWidths', JSON.stringify(widths));
                };
                
                document.addEventListener('mousemove', onMouseMove);
                document.addEventListener('mouseup', onMouseUp);
            });

            resizer.addEventListener('click', function(e) {
                e.stopPropagation();
            });
        });

        if (Object.keys(savedWidths).length > 0) {
            table.style.tableLayout = 'fixed';
        }
        
        const hiddenCols = JSON.parse(localStorage.getItem('tripsTableHiddenCols') || '[]');
        if (hiddenCols.length > 0) {
            hiddenCols.forEach(colIndex => {
                if (headers[colIndex]) {
                    headers[colIndex].style.display = 'none';
                    const rows = table.rows;
                    for (let i = 0; i < rows.length; i++) {
                        if (rows[i].cells[colIndex]) {
                            rows[i].cells[colIndex].style.display = 'none';
                        }
                    }
                }
            });
            document.getElementById('show-columns-btn').style.display = 'flex';
        }
    }

    function initTutorial(force = false) {
        const driver = window.driver.js.driver;
        const tutorialsSeen = window.ViantrypTutorials || [];
        const hasSeenTutorial = tutorialsSeen.includes('trips');

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
                    element: '.hero-title', 
                    popover: { 
                        title: '¡Bienvenido!', 
                        description: 'Este es tu Panel de Control. Aquí podrás gestionar todos tus itinerarios de forma profesional.' 
                    } 
                },
                { 
                    element: '.segmented-control', 
                    popover: { 
                        title: 'Navegación de Viajes', 
                        description: 'Organizamos tus viajes en dos secciones principales para que siempre tengas el control.' 
                    } 
                },
                { 
                    element: '.segment-item:nth-child(2)', 
                    popover: { 
                        title: 'Mis Viajes', 
                        description: 'Aquí encontrarás todos los itinerarios que has creado tú. Eres el propietario de esta información.' 
                    } 
                },
                { 
                    element: '.segment-item:nth-child(3)', 
                    popover: { 
                        title: 'Compartidos', 
                        description: 'En esta pestaña verás los viajes que otros agentes han compartido contigo para colaborar.' 
                    } 
                },
                { 
                    element: '.btn-create', 
                    popover: { 
                        title: 'Crear Viaje', 
                        description: 'Utiliza este botón para comenzar a diseñar una nueva experiencia para tus clientes.' 
                    } 
                },
                { 
                    element: '.sbox', 
                    popover: { 
                        title: 'Buscador Inteligente', 
                        description: 'Encuentra cualquier viaje rápidamente por nombre, destino o cliente.' 
                    } 
                },
                { 
                    element: '.tbl-wrap', 
                    popover: { 
                        title: 'Gestión de Viajes', 
                        description: 'Aquí verás tus itinerarios. Configura tu vista a tu manera: cambia el tamaño de las columnas, ordénalas o escóndelas según lo que necesites.' 
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
                        body: JSON.stringify({ tutorial: 'trips' })
                    });
                    if (!window.ViantrypTutorials.includes('trips')) {
                        window.ViantrypTutorials.push('trips');
                    }
                }
            }
        });

        driverObj.drive();
    }

    function switchTripsTab(tab, el) {
        window.location.href = `{{ route('trips.index') }}?filter=${tab}`;
    }

    function duplicateTrip(tripId) {
        showNotification('Procesando', 'Duplicando el viaje seleccionado...', 'info');
        
        fetch(`{{ url('trips') }}/${tripId}/duplicate`, {
            method: 'POST',
            headers: { 
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            }
        })
        .then(async r => {
            if (!r.ok) {
                const err = await r.json();
                throw new Error(err.message || 'Error del servidor');
            }
            return r.json();
        })
        .then(d => {
            if (d.success) {
                showNotification('Viaje Duplicado', 'El viaje ha sido duplicado exitosamente.');
                setTimeout(() => location.reload(), 800);
            } else {
                alert('Error: ' + d.message);
            }
        })
        .catch(err => {
            console.error('Duplication error:', err);
            alert('No se pudo duplicar el viaje: ' + err.message);
        });
    }

    function toggleActsMenu(event, tripId) {
        event.stopPropagation();
        const menu = document.getElementById(`menu-${tripId}`);
        const allMenus = document.querySelectorAll('.acts-menu');
        const row = menu.closest('.trip-row');
        const allRows = document.querySelectorAll('.trip-row');
        
        const isOpening = !menu.classList.contains('show');

        // Close all other menus and remove active class from all rows
        allMenus.forEach(m => m.classList.remove('show'));
        allRows.forEach(r => r.classList.remove('menu-open'));
        
        if (isOpening) {
            menu.classList.add('show');
            if (row) row.classList.add('menu-open');
            
            // Close menu when clicking outside
            const closeHandler = (e) => {
                if (!menu.contains(e.target)) {
                    menu.classList.remove('show');
                    if (row) row.classList.remove('menu-open');
                    document.removeEventListener('click', closeHandler);
                }
            };
            // Use timeout to avoid immediate trigger if the click event bubbles
            setTimeout(() => {
                document.addEventListener('click', closeHandler);
            }, 10);
        }
    }

    function openSharingModal(tripId, role) {
        const roleLabel = role === 'editor' ? 'EDICIÓN' : 'LECTURA';
        const roleText = role === 'editor' ? 'podrá realizar cambios en el itinerario PRO.' : 'solo podrá ver la propuesta del viaje.';
        const themeColor = '{{ auth()->user()->theme_color ?? "default" }}';
        
        const modalHtml = `
            <div id="shareTripModal" style="position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(15, 42, 58, 0.4); backdrop-filter:blur(8px); z-index:2000; display:flex; align-items:center; justify-content:center; animation: fadeIn 0.3s ease;">
                <div style="background:white; width:90%; max-width:400px; border-radius:16px; overflow:hidden; box-shadow:0 20px 40px rgba(0,0,0,0.1); animation: slideUp 0.3s ease;">
                    <div style="background:var(--accent); padding:20px; color:white; text-align:center;">
                        <h3 style="margin:0; font-size:18px;">Compartir para ${role === 'editor' ? 'editar' : 'ver'}</h3>
                        <p style="margin:5px 0 0; font-size:12px; opacity:0.9;">Permisos de ${roleLabel}</p>
                    </div>
                    <div style="padding:24px;">
                        <form id="shareTripForm">
                            <input type="hidden" name="role" value="${role}">
                            <div style="margin-bottom:16px;">
                                <label style="display:block; font-size:11px; font-weight:700; text-transform:uppercase; color:var(--gray2); margin-bottom:6px;">Correo electrónico del colaborador</label>
                                <input type="email" name="email" required placeholder="ejemplo@correo.com" style="width:100%; height:44px; padding:0 14px; border:1.5px solid var(--bdr); border-radius:10px; font-size:14px; outline:none;">
                                <p style="font-size:11px; color:var(--gray2); margin-top:8px;">
                                    <strong>Nota:</strong> El usuario ${roleText}
                                </p>
                            </div>
                            <div style="display:flex; gap:12px; margin-top:24px;">
                                <button type="button" onclick="document.getElementById('shareTripModal').remove()" style="flex:1; height:44px; border:none; background:var(--sand); color:var(--ink); font-weight:600; border-radius:10px; cursor:pointer; font-size:13px;">Cancelar</button>
                                <button type="submit" style="flex:1; height:44px; border:none; background:var(--accent); color:white; font-weight:700; border-radius:10px; cursor:pointer; font-size:13px; box-shadow:0 4px 12px rgba(26,106,120,0.2);">Enviar Invitación</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        `;

        document.body.insertAdjacentHTML('beforeend', modalHtml);

        const form = document.getElementById('shareTripForm');
        form.onsubmit = async (e) => {
            e.preventDefault();
            const btn = form.querySelector('button[type="submit"]');
            const originalText = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Enviando...';

            const formData = new FormData(form);
            const data = {
                email: formData.get('email'),
                role: formData.get('role')
            };

            try {
                const response = await fetch(`{{ url('trips') }}/${tripId}/invite`, {
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
                    showNotification('Invitación enviada', result.message);
                    document.getElementById('shareTripModal').remove();
                } else {
                    if (response.status === 403) {
                        document.getElementById('shareTripModal').remove();
                        openUpgradeModal();
                    } else {
                        showNotification('Error', (result.message || 'No se pudo enviar la invitación'));
                        btn.disabled = false;
                        btn.innerHTML = originalText;
                    }
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Ocurrió un error al intentar enviar la invitación.');
                btn.disabled = false;
                btn.innerHTML = originalText;
            }
        };
    }

    function openTransferModal(tripId) {
        const modalHtml = `
            <div id="transferTripModal" style="position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(15, 42, 58, 0.4); backdrop-filter:blur(8px); z-index:2000; display:flex; align-items:center; justify-content:center; animation: fadeIn 0.3s ease;">
                <div style="background:white; width:90%; max-width:400px; border-radius:16px; overflow:hidden; box-shadow:0 20px 40px rgba(0,0,0,0.1); animation: slideUp 0.3s ease;">
                    <div style="background:#1e293b; padding:20px; color:white; text-align:center;">
                        <h3 style="margin:0; font-size:18px;">Cambiar Propietario</h3>
                        <p style="margin:5px 0 0; font-size:12px; opacity:0.9;">Transferir el viaje a otro agente</p>
                    </div>
                    <div style="padding:24px;">
                        <form id="transferTripForm">
                            <div style="margin-bottom:16px;">
                                <label style="display:block; font-size:11px; font-weight:700; text-transform:uppercase; color:var(--gray2); margin-bottom:6px;">Correo del nuevo dueño</label>
                                <input type="email" name="email" required placeholder="agente@viantryp.com" style="width:100%; height:44px; padding:0 14px; border:1.5px solid var(--bdr); border-radius:10px; font-size:14px; outline:none;">
                                <div style="background:#fff7ed; padding:12px; border-radius:8px; border:1px solid #ffedd5; margin-top:16px;">
                                    <p style="font-size:11px; color:#9a3412; margin:0;">
                                        <strong>⚠ Importante:</strong> Al transferir, el viaje pasará a tu pestaña de <b>Compartidos</b> y tú quedarás como editor. La marca y colores del viaje cambiarán al perfil del nuevo dueño.
                                    </p>
                                </div>
                            </div>
                            <div style="display:flex; gap:12px; margin-top:24px;">
                                <button type="button" onclick="document.getElementById('transferTripModal').remove()" style="flex:1; height:44px; border:none; background:var(--sand); color:var(--ink); font-weight:600; border-radius:10px; cursor:pointer; font-size:13px;">Cancelar</button>
                                <button type="submit" style="flex:1; height:44px; border:none; background:#1e293b; color:white; font-weight:700; border-radius:10px; cursor:pointer; font-size:13px;">Transferir Viaje</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        `;

        document.body.insertAdjacentHTML('beforeend', modalHtml);

        const form = document.getElementById('transferTripForm');
        form.onsubmit = async (e) => {
            e.preventDefault();
            if(!confirm('¿Estás seguro de transferir la propiedad? Esta acción no se puede deshacer fácilmente.')) return;
            
            const btn = form.querySelector('button[type="submit"]');
            const originalText = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Procesando...';

            const formData = new FormData(form);
            const data = { email: formData.get('email') };

            try {
                const response = await fetch(`{{ url('trips') }}/${tripId}/transfer`, {
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
                    showNotification('Transferencia Exitosa', result.message);
                    document.getElementById('transferTripModal').remove();
                    setTimeout(() => location.reload(), 1000);
                } else {
                    alert('Error: ' + (result.message || 'No se pudo realizar la transferencia'));
                    btn.disabled = false;
                    btn.innerHTML = originalText;
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Ocurrió un error al intentar transferir el viaje.');
                btn.disabled = false;
                btn.innerHTML = originalText;
            }
        };
    }

    function initSegmentSlider() {
        const activeItem = document.querySelector('.segment-item.active');
        const slider = document.getElementById('segmentSlider');
        if (activeItem && slider) {
            slider.style.width = activeItem.offsetWidth + 'px';
            slider.style.left = activeItem.offsetLeft + 'px';
        }
    }

    function openCollaboratorsModal(tripId) {
        // Create modal structure
        const modalHtml = `
            <div id="collaboratorsModal" style="position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(15, 42, 58, 0.4); backdrop-filter:blur(8px); z-index:2000; display:flex; align-items:center; justify-content:center; animation: fadeIn 0.3s ease;">
                <div style="background:white; width:90%; max-width:450px; border-radius:16px; overflow:hidden; box-shadow:0 20px 40px rgba(0,0,0,0.1); animation: slideUp 0.3s ease;">
                    <div style="background:var(--accent); padding:20px; color:white; text-align:center;">
                        <h3 style="margin:0; font-size:18px;">Colaboradores del viaje</h3>
                        <p style="margin:5px 0 0; font-size:12px; opacity:0.9;">Gestiona quién tiene acceso a este viaje</p>
                    </div>
                    <div style="padding:24px;" id="collaboratorsListContainer">
                        <div style="text-align:center; padding:20px;">
                            <i class="fas fa-spinner fa-spin" style="font-size:24px; color:var(--accent);"></i>
                        </div>
                    </div>
                    <div style="padding:0 24px 24px;">
                        <button type="button" onclick="document.getElementById('collaboratorsModal').remove()" style="width:100%; height:44px; border:none; background:var(--sand); color:var(--ink); font-weight:600; border-radius:10px; cursor:pointer; font-size:13px;">Cerrar</button>
                    </div>
                </div>
            </div>
        `;

        document.body.insertAdjacentHTML('beforeend', modalHtml);

        loadCollaborators(tripId);
    }

    async function loadCollaborators(tripId) {
        const container = document.getElementById('collaboratorsListContainer');
        try {
            const response = await fetch(`{{ url('trips') }}/${tripId}/collaborators`);
            const data = await response.json();

            if (data.success) {
                if (data.collaborators.length === 0) {
                    container.innerHTML = `
                        <div style="text-align:center; padding:20px; color:var(--gray2);">
                            <p style="margin:0;">No hay colaboradores activos para este viaje.</p>
                        </div>
                    `;
                } else {
                    let html = '<div style="display:flex; flex-direction:column; gap:12px;">';
                    data.collaborators.forEach(collab => {
                        html += `
                            <div style="display:flex; align-items:center; justify-content:space-between; padding:12px; border:1px solid var(--bdr); border-radius:10px; background:#f9fafb;">
                                <div style="display:flex; flex-direction:column;">
                                    <span style="font-size:13px; font-weight:600; color:var(--ink);">${collab.email}</span>
                                    <span style="font-size:11px; color:var(--gray2); text-transform:uppercase;">${collab.role === 'editor' ? 'Editor' : 'Lector'} ${collab.accepted_at ? '' : '(Pendiente)'}</span>
                                </div>
                                <button onclick="removeCollaborator(${tripId}, '${collab.email}')" style="background:transparent; border:none; color:#d94040; cursor:pointer; padding:5px; transition:opacity 0.2s;" title="Eliminar acceso">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                        `;
                    });
                    html += '</div>';
                    container.innerHTML = html;
                }
            } else {
                container.innerHTML = `<p style="color:#d94040; font-size:13px; text-align:center;">${data.message || 'Error al cargar colaboradores'}</p>`;
            }
        } catch (error) {
            console.error('Error:', error);
            container.innerHTML = '<p style="color:#d94040; font-size:13px; text-align:center;">Ocurrió un error al cargar la lista.</p>';
        }
    }

    async function removeCollaborator(tripId, email) {
        if (!confirm(`¿Estás seguro de que quieres dejar de compartir el viaje con ${email}?`)) return;

        try {
            const response = await fetch(`{{ url('trips') }}/${tripId}/collaborators/remove`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ email: email })
            });

            const result = await response.json();
            if (result.success) {
                showNotification('Acceso Revocado', result.message);
                loadCollaborators(tripId);
            } else {
                alert('Error: ' + result.message);
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Error al intentar eliminar al colaborador.');
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        initTableResizer();
        initSegmentSlider();
        
        // Pequeño delay para dejar que las animaciones de la tabla terminen
        setTimeout(initTutorial, 800);
    });
</script>
@endpush
