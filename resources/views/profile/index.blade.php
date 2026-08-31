@extends('layouts.app')

@section('title', 'Viantryp | Mi Perfil')

@push('styles')
  @include('layouts.theme-styles')
  <link
    href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@700;800;900&family=Barlow:wght@400;500;600;700&display=swap"
    rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/driver.js@1.0.1/dist/driver.css" />
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
    [data-theme="ocean"] {
      --accent-light: #e6f0f7;
      --accent-dark: #0d3d5e;
    }

    [data-theme="sunset"] {
      --accent-light: #fdf0eb;
      --accent-dark: #8c3a1a;
    }

    [data-theme="gold"] {
      --accent-light: #fdf8e6;
      --accent-dark: #7a5800;
    }

    [data-theme="blush"] {
      --accent-light: #fdf0f4;
      --accent-dark: #b55677;
    }

    [data-theme="mint"] {
      --accent-light: #e8f8f5;
      --accent-dark: #267a65;
    }

    [data-theme="lavender"] {
      --accent-light: #f4eeff;
      --accent-dark: #6d4ea0;
    }

    [data-theme="silver"] {
      --accent-light: #eef1f1;
      --accent-dark: #4a5859;
    }

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

    .avatar-delete-btn:hover {
      opacity: 1;
      transform: scale(1.1);
    }

    .avatar-delete-btn svg {
      width: 12px;
      height: 12px;
      stroke: white;
      fill: none;
      stroke-width: 2.5;
    }

    .avatar-wrapper {
      position: relative;
      display: inline-block;
      margin-bottom: 0px;
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
      top: 0;
      left: 0;
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

    .avatar-edit-btn svg {
      width: 12px;
      height: 12px;
      stroke: white;
      fill: none;
      stroke-width: 2.5;
    }

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

    .avatar-delete-btn:hover {
      opacity: 1;
      transform: scale(1.1);
    }

    .avatar-delete-btn svg {
      width: 12px;
      height: 12px;
      stroke: white;
      fill: none;
      stroke-width: 2.5;
    }

    .profile-name {
      font-family: 'Barlow', sans-serif;
      font-size: 20px;
      font-weight: 700;
      color: var(--text);
      margin-top: 16px;
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

    .plan-dot {
      width: 7px;
      height: 7px;
      background: var(--accent);
      border-radius: 50%;
    }


    .sidebar-nav {
      padding: 8px;
    }

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

    .nav-item:hover {
      background: var(--accent-light);
      color: var(--accent);
    }

    .nav-item.active {
      background: var(--accent-light);
      color: var(--accent);
      font-weight: 600;
    }

    .nav-item svg {
      width: 17px;
      height: 17px;
      stroke: currentColor;
      fill: none;
      stroke-width: 1.8;
      flex-shrink: 0;
    }

    .main-content {
      display: flex;
      flex-direction: column;
      gap: 20px;
    }

    .section-label {
      font-size: 11px;
      font-weight: 700;
      letter-spacing: 1.2px;
      color: var(--muted);
      text-transform: uppercase;
      margin-bottom: 18px;
    }

    .card-body {
      padding: 28px;
    }

    .form-group {
      margin-bottom: 20px;
    }

    .form-row {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 16px;
    }

    label {
      display: block;
      font-size: 12px;
      font-weight: 600;
      color: var(--muted);
      margin-bottom: 7px;
      text-transform: uppercase;
      letter-spacing: 0.6px;
    }

    input[type="text"],
    input[type="email"],
    input[type="tel"],
    input[type="password"],
    select,
    textarea {
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

    input:focus,
    select:focus,
    textarea:focus {
      border-color: var(--accent);
      box-shadow: 0 0 0 3px var(--accent-light);
    }

    textarea {
      resize: vertical;
      min-height: 80px;
    }

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

    .logo-upload-area input {
      position: absolute;
      inset: 0;
      opacity: 0;
      cursor: pointer;
      width: 100%;
      height: 100%;
    }

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

    .upload-icon svg {
      width: 20px;
      height: 20px;
      stroke: var(--accent);
      fill: none;
      stroke-width: 1.8;
    }

    .upload-text {
      font-size: 13px;
      font-weight: 600;
      color: var(--text);
      margin-bottom: 4px;
    }

    .upload-hint {
      font-size: 12px;
      color: var(--muted);
    }

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

    .theme-option.selected .theme-swatch::after {
      opacity: 1;
    }

    .theme-name {
      font-size: 11px;
      font-weight: 600;
      color: var(--muted);
    }

    .itinerary-preview {
      background: var(--bg);
      border-radius: 12px;
      padding: 16px;
      margin-top: 20px;
      border: 1px solid var(--border);
    }

    .preview-label {
      font-size: 11px;
      font-weight: 700;
      color: var(--muted);
      text-transform: uppercase;
      letter-spacing: 1px;
      margin-bottom: 12px;
    }

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

    .preview-day {
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .preview-day-dot {
      width: 8px;
      height: 8px;
      border-radius: 50%;
      background: var(--accent);
      flex-shrink: 0;
      transition: background 0.3s;
    }

    .preview-day-line {
      height: 8px;
      border-radius: 4px;
      background: var(--border);
      flex: 1;
    }

    .preview-day-line.short {
      flex: 0.6;
    }

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

    .btn-save:hover {
      background: var(--accent-dark);
      transform: translateY(-1px);
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

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

    .btn-secondary:hover {
      color: var(--text);
      border-color: var(--text);
    }

    .btn-row {
      display: flex;
      align-items: center;
      gap: 12px;
    }

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
      box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
      opacity: 0;
      transform: translateY(10px);
      transition: all 0.3s;
      pointer-events: none;
      z-index: 999;
    }

    .toast-profile.show {
      opacity: 1;
      transform: translateY(0);
    }

    .divider {
      height: 1px;
      background: var(--border);
      margin: 4px 0;
    }

    .danger-text {
      color: #c0392b;
      font-size: 13px;
      margin-bottom: 16px;
    }

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

    .btn-danger-profile:hover {
      background: #fff0f0;
      border-color: #c0392b;
    }

    .tab-section {
      display: none;
    }

    .tab-section.active {
      display: block;
    }

    @media (max-width: 768px) {
      .grid {
        grid-template-columns: 1fr;
      }

      .form-row {
        grid-template-columns: 1fr;
      }

      .theme-grid {
        grid-template-columns: repeat(5, 1fr);
      }
    }

    /* Multi-element highlight helper */
    .tutorial-extra-highlight {
      z-index: 1000004 !important;
      /* Above Driver.js overlay */
      position: relative !important;
      transition: all 0.2s ease !important;
      background: var(--accent-light) !important;
      color: var(--accent) !important;
    }

    /* ==========================================================================
           NUEVO DISEÑO DASHBOARD (Mismo estilo que Mis Viajes)
           ========================================================================== */

    /* Contenedor principal que envuelve todo el Dashboard (100% de la pantalla) */
    .dashboard-wrapper {
      background: var(--accent);
      min-height: 100vh;
      display: flex;
      align-items: stretch;
      justify-content: stretch;
      padding: 0;
      box-sizing: border-box;
    }

    /* Caja contenedora blanca */
    .dashboard-container {
      display: flex;
      background: #ffffff;
      border-radius: 0;
      width: 100%;
      max-width: 100%;
      min-height: 100vh;
      box-shadow: none;
      overflow: hidden;
    }

    /* BARRA LATERAL IZQUIERDA */
    .dashboard-sidebar {
      width: 280px;
      background: var(--sidebar-bg);
      color: #ffffff;
      padding: 35px 24px;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      border-radius: 0 40px 40px 0;
      position: relative;
      flex-shrink: 0;
      z-index: 10;
    }

    .dashboard-sidebar .sidebar-logo {
      margin-bottom: 30px;
      display: flex;
      align-items: center;
      padding-left: 8px;
    }

    .dashboard-sidebar .sidebar-logo img {
      height: 32px;
      width: auto;
      filter: brightness(0) invert(1);
    }

    .dashboard-sidebar .sidebar-nav {
      display: flex;
      flex-direction: column;
      gap: 8px;
      flex: 1;
      padding: 0;
      background: transparent !important;
      border: none !important;
    }

    /* Sidebar links — idéntico al estilo de trips/index.blade.php */
    .dashboard-sidebar .sidebar-link {
      display: flex;
      align-items: center;
      gap: 16px;
      padding: 12px 20px;
      border-radius: 12px;
      color: rgba(255, 255, 255, 0.7);
      text-decoration: none;
      font-weight: 600;
      font-size: 14px;
      transition: all 0.2s ease;
      font-family: 'Barlow', sans-serif;
    }

    .dashboard-sidebar .sidebar-link:hover {
      color: #ffffff;
      background: rgba(255, 255, 255, 0.08);
      text-decoration: none;
    }

    .dashboard-sidebar .sidebar-link.active {
      background: #ffffff !important;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05) !important;
    }

    .dashboard-sidebar .sidebar-link.active,
    .dashboard-sidebar .sidebar-link.active i,
    .dashboard-sidebar .sidebar-link.active span {
      color: var(--sidebar-accent-color) !important;
    }

    .dashboard-sidebar .sidebar-link i {
      font-size: 18px;
      width: 20px;
      text-align: center;
    }

    .dashboard-sidebar .sidebar-link.disabled {
      opacity: 0.5;
      cursor: not-allowed !important;
      pointer-events: none;
      user-select: none;
    }

    .dashboard-sidebar .sidebar-link.disabled:hover {
      background: transparent !important;
      color: rgba(255, 255, 255, 0.7) !important;
    }

    .dashboard-sidebar .sidebar-badge-soon {
      margin-left: auto;
      font-size: 10px;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.4px;
      padding: 2px 8px;
      border-radius: 10px;
      background: rgba(255, 255, 255, 0.15);
      color: rgba(255, 255, 255, 0.9);
    }

    .dashboard-sidebar .divider {
      height: 1px;
      background: rgba(255, 255, 255, 0.1);
      margin: 8px 0;
    }

    /* Sidebar Footer / Uso de Plan */
    .dashboard-sidebar .sidebar-footer {
      margin-top: 30px;
      border-top: 1px solid rgba(255, 255, 255, 0.1);
      padding-top: 24px;
    }

    .dashboard-sidebar .sidebar-footer .footer-title {
      font-size: 11px;
      font-weight: 800;
      color: rgba(255, 255, 255, 0.4);
      letter-spacing: 1px;
      margin-bottom: 16px;
    }

    .dashboard-sidebar .sidebar-footer .usage-item {
      margin-bottom: 16px;
    }

    .dashboard-sidebar .sidebar-footer .usage-label-row {
      display: flex;
      justify-content: space-between;
      font-size: 12px;
      font-weight: 600;
      color: rgba(255, 255, 255, 0.9);
      margin-bottom: 6px;
    }

    .dashboard-sidebar .sidebar-footer .usage-label-row i {
      margin-right: 4px;
      opacity: 0.7;
    }

    .dashboard-sidebar .sidebar-footer .usage-progress-bar {
      height: 6px;
      background: rgba(255, 255, 255, 0.15);
      border-radius: 3px;
      overflow: hidden;
    }

    .dashboard-sidebar .sidebar-footer .usage-progress-fill {
      height: 100%;
      background: #ffffff;
      border-radius: 3px;
      transition: width 0.3s ease;
    }

    .dashboard-sidebar .sidebar-footer .usage-upgrade-row {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-top: 20px;
    }

    .dashboard-sidebar .sidebar-footer .plan-badge {
      background: rgba(255, 255, 255, 0.15);
      color: #ffffff;
      border: 1px solid rgba(255, 255, 255, 0.25);
      padding: 4px 10px;
      border-radius: 12px;
      font-size: 11px;
      font-weight: 700;
      letter-spacing: 0.5px;
      margin-bottom: 0px;
    }

    .dashboard-sidebar .sidebar-footer .btn-upgrade-link {
      color: #ffffff;
      font-size: 12px;
      font-weight: 700;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: 4px;
      opacity: 0.8;
      transition: opacity 0.2s;
    }

    .dashboard-sidebar .sidebar-footer .btn-upgrade-link:hover {
      opacity: 1;
      text-decoration: underline;
    }

    /* SECCIÓN DE CONTENIDO PRINCIPAL */
    .dashboard-main {
      flex: 1;
      display: flex;
      flex-direction: column;
      background: #ffffff;
      position: relative;
      overflow: hidden;
    }

    /* Cabecera superior */
    .dashboard-topbar {
      height: 80px;
      border-bottom: 1px solid #f1f5f9;
      padding: 0 40px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      flex-shrink: 0;
      background: #ffffff;
      z-index: 9;
    }

    .topbar-search {
      flex: 1;
      max-width: 480px;
    }

    .search-box-wrapper {
      position: relative;
      width: 100%;
    }

    .search-box-wrapper .search-icon {
      position: absolute;
      left: 18px;
      top: 50%;
      transform: translateY(-50%);
      color: #94a3b8;
      font-size: 15px;
      pointer-events: none;
    }

    .search-box-wrapper input {
      width: 100%;
      height: 46px;
      background: #f8fafc;
      border: 1px solid #f1f5f9;
      border-radius: 24px;
      padding-left: 48px;
      padding-right: 20px;
      font-size: 14px;
      font-weight: 500;
      color: #1e293b;
      transition: all 0.2s ease;
      outline: none;
    }

    .search-box-wrapper input:focus {
      background: #ffffff;
      border-color: var(--accent);
      box-shadow: 0 0 0 4px var(--accent-light);
    }

    .topbar-actions {
      display: flex;
      align-items: center;
      gap: 16px;
    }

    .btn-topbar-create {
      background: var(--accent);
      color: #ffffff;
      border: none;
      padding: 12px 28px;
      border-radius: 50px;
      font-weight: 700;
      font-size: 14px;
      cursor: pointer;
      display: flex;
      align-items: center;
      gap: 8px;
      transition: all 0.2s ease;
      margin-right: 20px;
      box-shadow: 0 6px 16px rgba(0, 0, 0, 0.08);
      letter-spacing: 0.2px;
    }

    .btn-topbar-create:hover {
      opacity: 0.95;
      transform: translateY(-1px);
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
    }

    .btn-topbar-icon {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      border: 1px solid #e2e8ef;
      background: #ffffff;
      color: #64748b;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      position: relative;
      transition: all 0.2s ease;
    }

    .btn-topbar-icon:hover {
      border-color: var(--accent);
      color: var(--accent);
      background: var(--accent-light);
    }

    /* Notificaciones */
    .noti-wrapper {
      position: relative;
    }

    .noti-dropdown {
      position: absolute;
      top: 50px;
      right: 0;
      width: 320px;
      background: #ffffff;
      border-radius: 16px;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
      border: 1px solid #f1f5f9;
      overflow: hidden;
      display: none;
      z-index: 100;
    }

    .noti-dropdown-header {
      padding: 14px 18px;
      border-bottom: 1px solid #f1f5f9;
      display: flex;
      justify-content: space-between;
      align-items: center;
      font-size: 13px;
      font-weight: 700;
      color: #1e293b;
    }

    .noti-dropdown-header button {
      border: none;
      background: none;
      color: var(--accent);
      font-weight: 600;
      font-size: 11px;
      cursor: pointer;
    }

    .noti-dropdown-header button:hover {
      text-decoration: underline;
    }

    #notiList {
      max-height: 280px;
      overflow-y: auto;
    }

    .noti-item {
      padding: 12px 18px;
      border-bottom: 1px solid #f8fafc;
      display: flex;
      gap: 10px;
      font-size: 12px;
      color: #475569;
      text-decoration: none;
      transition: background 0.15s;
    }

    .noti-item:hover {
      background: #f8fafc;
    }

    .noti-item.unread {
      background: var(--accent-light);
    }

    .noti-item-icon {
      color: var(--accent);
      font-size: 14px;
      margin-top: 2px;
    }

    .noti-item-content {
      flex: 1;
    }

    .noti-item-title {
      font-weight: 600;
      color: #1e293b;
      margin-bottom: 2px;
    }

    .noti-item-time {
      font-size: 10px;
      color: #94a3b8;
      margin-top: 4px;
    }

    .noti-empty {
      padding: 24px;
      text-align: center;
      color: #94a3b8;
      font-size: 12px;
    }

    .noti-loading {
      padding: 24px;
      text-align: center;
      color: #94a3b8;
      font-size: 12px;
    }

    /* Menú de Perfil */
    .profile-dropdown-wrapper {
      position: relative;
    }

    .profile-trigger {
      display: flex;
      align-items: center;
      gap: 10px;
      cursor: pointer;
      padding: 6px 12px;
      border-radius: 20px;
      transition: background 0.2s;
    }

    .profile-trigger:hover {
      background: #f8fafc;
    }

    .profile-trigger .avatar {
      width: 34px;
      height: 34px;
      border-radius: 50%;
      background: var(--accent-light);
      color: var(--accent);
      font-weight: 700;
      font-size: 13px;
      display: flex;
      align-items: center;
      justify-content: center;
      overflow: hidden;
    }

    .profile-trigger .avatar img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    .profile-trigger .profile-name {
      font-size: 14px;
      font-weight: 600;
      color: #334155;
      margin-top: 0;
      margin-bottom: 0;
      font-family: 'Barlow', sans-serif;
    }

    .profile-trigger i {
      font-size: 11px;
      color: #64748b;
    }

    .profile-menu {
      position: absolute;
      top: 50px;
      right: 0;
      width: 200px;
      background: #ffffff;
      border-radius: 12px;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
      border: 1px solid #f1f5f9;
      overflow: hidden;
      display: none;
      z-index: 100;
    }

    .profile-menu a,
    .profile-menu button {
      display: flex;
      align-items: center;
      gap: 10px;
      width: 100%;
      padding: 12px 16px;
      font-size: 13px;
      color: #475569;
      text-decoration: none;
      background: none;
      border: none;
      text-align: left;
      cursor: pointer;
      transition: background 0.15s;
    }

    .profile-menu a:hover,
    .profile-menu button:hover {
      background: #f8fafc;
      color: #1e293b;
    }

    .profile-menu .btn-logout {
      border-top: 1px solid #f1f5f9;
      color: #ef4444;
    }

    .profile-menu .btn-logout:hover {
      background: #fef2f2;
      color: #ef4444;
    }

    /* Contenedor de scroll para el contenido principal */
    .dashboard-content-scroll {
      flex: 1;
      overflow-y: auto;
      background: #ffffff;
    }

    /* Sobrescribimos estilos del wrapper de la página de perfil */
    .page-wrapper {
      max-width: 1200px;
      margin: 0 auto;
      padding: 40px;
    }

    /* RESPONSIVIDAD PARA EL ESCRITORIO DEL DASHBOARD */
    @media (max-width: 1024px) {
      .dashboard-wrapper {
        padding: 0;
      }

      .dashboard-container {
        flex-direction: column;
        border-radius: 0;
        min-height: 100vh;
        box-shadow: none;
      }

      .dashboard-sidebar {
        width: 100% !important;
        border-radius: 0 !important;
        padding: 20px 24px;
        height: auto !important;
      }

      .dashboard-sidebar .sidebar-logo {
        margin-bottom: 15px;
      }

      .dashboard-sidebar .sidebar-nav {
        flex-direction: row;
        flex-wrap: wrap;
        gap: 8px;
        margin-bottom: 15px;
      }

      .dashboard-sidebar .nav-item {
        padding: 8px 16px;
        font-size: 13px;
      }

      .dashboard-sidebar .sidebar-footer {
        display: none;
      }

      .dashboard-main {
        border-radius: 0 !important;
      }

      .dashboard-topbar {
        padding: 0 20px;
        height: 72px;
      }

      .page-wrapper {
        padding: 20px;
      }
    }

    /* Forzar que la cuadrícula de perfil use el ancho completo */
    .grid {
      display: block !important;
    }

    /* ==========================================================================
           ESTILOS DE CONFIGURACIÓN ESTILO FINPAY
           ========================================================================== */
    .settings-grid {
      display: grid;
      grid-template-columns: 280px 1fr;
      gap: 30px;
      align-items: start;
    }

    @media (max-width: 991px) {
      .settings-grid {
        grid-template-columns: 1fr;
        gap: 20px;
      }
    }

    .settings-sub-nav {
      background: #ffffff;
      border-radius: 16px;
      border: 1px solid #e2e8f0;
      padding: 16px;
      display: flex;
      flex-direction: column;
      gap: 6px;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.01);
    }

    .settings-sub-nav .nav-item {
      display: flex;
      align-items: center;
      gap: 12px;
      padding: 12px 16px;
      border-radius: 10px;
      font-size: 14px;
      font-weight: 600;
      color: #64748b;
      background: transparent;
      border: none;
      width: 100%;
      text-align: left;
      cursor: pointer;
      transition: all 0.2s ease;
      font-family: 'Barlow', sans-serif;
    }

    .settings-sub-nav .nav-item:hover {
      background: var(--accent-light);
      color: var(--accent);
    }

    .settings-sub-nav .nav-item.active {
      background: var(--accent-light) !important;
      color: var(--accent) !important;
      font-weight: 700 !important;
    }

    .settings-sub-nav .nav-item i {
      font-size: 18px;
      color: inherit;
    }

    /* Estilo premium para las tarjetas de ajustes */
    .tab-section.card {
      border: 1px solid #e2e8f0 !important;
      border-radius: 20px !important;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.02) !important;
      background: #ffffff !important;
      transition: all 0.3s ease !important;
    }

    .tab-section.card .card-body {
      padding: 24px !important;
    }

    .tab-section.card .form-group {
      margin-bottom: 14px !important;
    }

    .tab-section.card .section-label {
      font-size: 16px !important;
      font-weight: 700 !important;
      color: #0f172a !important;
      margin-bottom: 20px !important;
      text-transform: none !important;
      letter-spacing: 0px !important;
      border-bottom: 1px solid #f1f5f9 !important;
      padding-bottom: 12px !important;
      font-family: 'Barlow', sans-serif !important;
    }

    /* Inputs y controles de formulario más amigables y redondeados */
    .tab-section.card input[type="text"],
    .tab-section.card input[type="email"],
    .tab-section.card input[type="tel"],
    .tab-section.card input[type="password"],
    .tab-section.card select,
    .tab-section.card textarea {
      background: #ffffff !important;
      border: 1.5px solid #e2e8f0 !important;
      border-radius: 8px !important;
      padding: 11px 14px !important;
      font-size: 14px !important;
      color: #1e293b !important;
      font-family: 'Barlow', sans-serif !important;
      font-weight: 500 !important;
      transition: all 0.2s ease !important;
    }

    .tab-section.card input:focus,
    .tab-section.card select:focus,
    .tab-section.card textarea:focus {
      border-color: var(--accent) !important;
      box-shadow: 0 0 0 4px var(--accent-light) !important;
    }

    .tab-section.card label {
      font-size: 12px !important;
      font-weight: 700 !important;
      color: #475569 !important;
      margin-bottom: 6px !important;
      text-transform: none !important;
      letter-spacing: 0px !important;
    }

    /* Botón de guardar cambios */
    .tab-section.card .btn-save {
      background: var(--accent) !important;
      color: #ffffff !important;
      border: none !important;
      padding: 12px 28px !important;
      border-radius: 8px !important;
      font-weight: 700 !important;
      font-size: 14px !important;
      font-family: 'Barlow', sans-serif !important;
      cursor: pointer !important;
      display: inline-flex !important;
      align-items: center !important;
      gap: 8px !important;
      transition: all 0.2s ease !important;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05) !important;
    }

    .tab-section.card .btn-save:hover {
      opacity: 0.95 !important;
      transform: translateY(-1px) !important;
      box-shadow: 0 6px 16px rgba(0, 0, 0, 0.1) !important;
    }

    .tab-section.card .btn-secondary {
      background: #f1f5f9 !important;
      color: #475569 !important;
      border: none !important;
      padding: 12px 28px !important;
      border-radius: 8px !important;
      font-weight: 700 !important;
      font-size: 14px !important;
      font-family: 'Barlow', sans-serif !important;
      cursor: pointer !important;
      display: inline-flex !important;
      align-items: center !important;
      gap: 8px !important;
      transition: all 0.2s ease !important;
    }

    .tab-section.card .btn-secondary:hover {
      background: #e2e8f0 !important;
    }
  </style>
@endpush

@section('content')
  <div class="dashboard-wrapper">
    <div class="dashboard-container">
      <!-- Sidebar -->
      <aside class="dashboard-sidebar">
        <!-- Sidebar Header / Logo (Now in white and inside sidebar) -->
        <div class="sidebar-logo" style="margin-bottom: 30px; display: flex; align-items: center; padding-left: 8px;">
          <a href="{{ route('home') }}">
            <img src="/images/logo-viantryp.png" alt="Viantryp"
              style="height: 32px; width: auto; filter: brightness(0) invert(1);">
          </a>
        </div>

        <!-- Sidebar Nav Links (Same as trips, Ajustes active) -->
        <nav class="sidebar-nav">
          <a href="{{ route('trips.index') }}?filter=personal" class="sidebar-link">
            <i class="fas fa-suitcase-rolling"></i>
            <span>Mis viajes</span>
          </a>
          <a href="{{ route('trips.index') }}?filter=shared" class="sidebar-link">
            <i class="fas fa-users"></i>
            <span>Viajes Compartidos</span>
          </a>
          <div class="sidebar-link disabled" title="Próximamente">
            <i class="fas fa-layer-group"></i>
            <span>Plantillas</span>
            <span class="sidebar-badge-soon">Próximamente</span>
          </div>
          <a href="{{ route('profile.index') }}" class="sidebar-link active">
            <i class="fas fa-cog"></i>
            <span>Ajustes</span>
          </a>
          <a href="mailto:hola@viantryp.com" class="sidebar-link">
            <i class="fas fa-envelope"></i>
            <span>Contáctanos</span>
          </a>
        </nav>

        <!-- Sidebar Footer / Plan Usage -->
        @php
          $planUser = auth()->user();
          $tripCount = \App\Models\Trip::where('user_id', $planUser->id)->count();
          $editorCount = \DB::table('trip_collaborators')
            ->join('trips', 'trip_collaborators.trip_id', '=', 'trips.id')
            ->where('trips.user_id', $planUser->id)
            ->where('trip_collaborators.role', 'editor')
            ->distinct('trip_collaborators.email')
            ->count();
          $limits = $planUser->getPlanLimits();
          $maxTrips = $limits['max_trips'] ?? 5;
          $maxEditors = $limits['max_editors'] ?? 0;
          $tripPercent = min(100, ($tripCount / max(1, $maxTrips)) * 100);
          $editorPercent = $maxEditors > 0 ? min(100, ($editorCount / $maxEditors) * 100) : 0;
        @endphp
        <div class="sidebar-footer">
          <div class="footer-title">USO DEL PLAN</div>
          <div class="usage-item">
            <div class="usage-label-row">
              <span><i class="fas fa-route"></i> Itinerarios</span>
              <span>{{ $tripCount }} / {{ $maxTrips >= 1000000 ? '∞' : $maxTrips }}</span>
            </div>
            <div class="usage-progress-bar">
              <div class="usage-progress-fill" style="width: {{ $tripPercent }}%"></div>
            </div>
          </div>
          <div class="usage-item">
            <div class="usage-label-row">
              <span><i class="fas fa-users"></i> Colaboradores</span>
              <span>{{ $editorCount }} / {{ $maxEditors >= 1000000 ? '∞' : $maxEditors }}</span>
            </div>
            <div class="usage-progress-bar">
              <div class="usage-progress-fill" style="width: {{ $editorPercent }}%"></div>
            </div>
          </div>
          <div class="usage-upgrade-row">
            <span class="plan-badge">{{ ucfirst($planUser->plan) }}</span>
            <a href="javascript:void(0)" onclick="openUpgradeModal(true)" class="btn-upgrade-link">
              Mejorar plan <i class="fas fa-arrow-up-right-from-square"></i>
            </a>
          </div>
        </div>
      </aside>

      <!-- Main Content Area -->
      <div class="dashboard-main">
        <!-- Topbar -->
        <header class="dashboard-topbar">
          <a href="{{ route('trips.index') }}" class="app-topbar-logo" title="Viantryp">
            <img src="/images/logo-viantryp.png" alt="Viantryp" style="height: 28px; width: auto; filter: brightness(0) invert(1); object-fit: contain; display: block;">
          </a>
          <div class="topbar-actions">
            <!-- Notifications -->
            <div class="noti-wrapper">
              <button id="notiTrigger" class="btn-topbar-icon" title="Notificaciones">
                <i class="fas fa-bell"></i>
                <span id="notiBadge" style="display: none;">0</span>
              </button>
              <div id="notiMenu" class="noti-dropdown" style="display: none;">
                <div class="noti-dropdown-header">
                  <span>Notificaciones</span>
                  <button onclick="markNotificationsAsRead()">Marcar como leídas</button>
                </div>
                <div id="notiList">
                  <div class="noti-loading">Cargando...</div>
                </div>
              </div>
            </div>

            <!-- Profile dropdown -->
            <div class="profile-dropdown-wrapper">
              <div class="profile-trigger" id="profileTrigger">
                <div class="avatar" id="navAvatar">
                  @if(auth()->user()->avatar)
                    <img src="{{ str_starts_with(auth()->user()->avatar, 'http') ? auth()->user()->avatar : asset('storage/' . auth()->user()->avatar) }}" alt="">
                  @else
                    {{ collect(explode(' ', auth()->user()->name))->map(fn($w) => strtoupper(substr($w, 0, 1)))->take(2)->join('') }}
                  @endif
                </div>
                <span class="profile-name">{{ auth()->user()->name }}</span>
                <i class="fas fa-chevron-down"></i>
              </div>

              <div id="profileMenu" class="profile-menu" style="display: none;">
                <a href="{{ route('profile.index') }}"><i class="fas fa-user-circle"></i> Mi Cuenta</a>
                <form method="POST" action="{{ route('logout') }}" id="logout-form" style="margin:0;">
                  @csrf
                  <button type="submit" class="btn-logout"><i class="fas fa-sign-out-alt"></i> Cerrar sesión</button>
                </form>
              </div>
            </div>
          </div>
        </header>

        <!-- Dashboard Main Content Scroll Container -->
        <div class="dashboard-content-scroll">
          <div class="page-wrapper" style="max-width: 1200px; margin: 0 auto; padding: 40px;">
            <h1 class="page-title"
              style="font-family: 'Barlow Condensed', sans-serif; font-size: 28px; font-weight: 900; color: #0f172a; margin-bottom: 24px; text-transform: uppercase; letter-spacing: -0.5px;">
              Ajustes de cuenta</h1>

            <div class="settings-grid">
              <!-- Left Column: Settings sub-navigation card -->
              <div class="settings-sub-sidebar">
                <div class="settings-sub-nav">
                  @if($user->account_type === 'personal')
                    <button class="nav-item active" data-section="info">
                      <i class="fas fa-user-circle"></i>
                      <span>Información Personal</span>
                    </button>
                  @else
                    <button class="nav-item active" data-section="agencia">
                      <i class="fas fa-briefcase"></i>
                      <span>Mi Agencia</span>
                    </button>
                  @endif
                  <button class="nav-item" data-section="tema">
                    <i class="fas fa-palette"></i>
                    <span>Tema e Identidad</span>
                  </button>
                  <button class="nav-item" data-section="subscription">
                    <i class="fas fa-credit-card"></i>
                    <span>Planes y Suscripción</span>
                  </button>
                  <button class="nav-item" data-section="pagos">
                    <i class="fas fa-wallet"></i>
                    <span>Métodos de Pago</span>
                  </button>
                  <div class="divider" style="height: 1px; background: #e2e8f0; margin: 8px 0;"></div>
                  <button class="nav-item" data-section="seguridad">
                    <i class="fas fa-shield-alt"></i>
                    <span>Seguridad</span>
                  </button>
                </div>
              </div>

              <!-- Right Column: MAIN CONTENT -->
              <div class="main-content">

                <!-- INFORMACIÓN PERSONAL -->
                @if($user->account_type === 'personal')
                  <div class="card tab-section active" id="section-info">
                    <div class="card-body">
                      <!-- Avatar Section (Finpay style) -->
                      <div class="avatar-finpay-section"
                        style="display: flex; align-items: center; gap: 24px; margin-bottom: 32px; border-bottom: 1px solid #f1f5f9; padding-bottom: 24px;">
                        <div class="avatar-wrapper" style="position: relative; display: inline-block;">
                          <div class="avatar-big" id="avatarBig"
                            style="width: 100px; height: 100px; border-radius: 50%; background: var(--accent-light); color: var(--accent); font-size: 32px; font-weight: 700; display: flex; align-items: center; justify-content: center; overflow: hidden; border: 3px solid #ffffff; box-shadow: 0 4px 12px rgba(0,0,0,0.08); cursor: pointer;">
                            <span id="avatarInitial"
                              style="{{ $user->avatar ? 'display:none' : '' }}; color: #ffffff;">{{ $user->display_initials }}</span>
                            <img id="avatarImg"
                              src="{{ $user->avatar ? (str_starts_with($user->avatar, 'http') ? $user->avatar : asset('storage/' . $user->avatar)) : '' }}"
                              alt=""
                              style="{{ $user->avatar ? '' : 'display:none' }}; width: 100%; height: 100%; object-fit: cover;">
                          </div>
                          <input type="file" id="avatarUpload" accept="image/jpeg, image/png, image/webp"
                            style="display:none">
                        </div>
                        <div class="avatar-actions-col" style="display: flex; gap: 12px; align-items: center;">
                          <button type="button" onclick="document.getElementById('avatarUpload').click();"
                            class="btn-upload-new"
                            style="background: var(--accent); color: #ffffff; border: none; padding: 10px 20px; border-radius: 8px; font-weight: 700; font-size: 13px; cursor: pointer; transition: all 0.2s;">
                            Subir foto
                          </button>
                          <button type="button" id="avatarDeleteBtn"
                            style="{{ $user->avatar ? '' : 'display:none' }}; background: #f1f5f9; color: #475569; border: 1px solid #e2e8f0; padding: 10px 20px; border-radius: 8px; font-weight: 700; font-size: 13px; cursor: pointer; transition: all 0.2s;"
                            onmouseover="this.style.background='#e2e8f0';" onmouseout="this.style.background='#f1f5f9';">
                            Eliminar foto
                          </button>
                        </div>
                      </div>

                      <div class="form-group">
                        <label>Nombre completo</label>
                        <input type="text" id="inputNombre" value="{{ trim($user->name . ' ' . $user->last_name) }}">
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

                      <div class="btn-row">
                        <button class="btn-save" id="savePersonalInfo">
                          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2.5">
                            <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z" />
                            <polyline points="17 21 17 13 7 13 7 21" />
                            <polyline points="7 3 7 8 15 8" />
                          </svg>
                          Guardar Cambios
                        </button>
                        <button class="btn-secondary">Cancelar</button>
                      </div>

                      <div style="border-top: 1px solid #f0f2f5; margin: 30px 0 20px; padding-top: 24px;"></div>

                      <div
                        style="display: flex; align-items: center; justify-content: space-between; padding: 20px; background: #f8fafc; border-radius: 16px; border: 1px solid #e2e8f0; flex-wrap: wrap; gap: 16px;">
                        <div style="flex: 1; min-width: 250px;">
                          <h3
                            style="font-size: 15px; font-weight: 800; color: #0f172a; margin: 0 0 4px; font-family: 'DM Sans', sans-serif;">
                            ¿Trabajas como negocio o agencia de viajes?</h3>
                          <p style="font-size: 12px; color: #64748b; margin: 0; line-height: 1.4; font-weight: 500;">
                            Cambia tu tipo de perfil para habilitar la personalización de marca corporativa, subir tu logo,
                            sitio web, eslogan y presentarte profesionalmente ante tus viajeros.
                          </p>
                        </div>
                        <button type="button" onclick="requestChangeAccountType('agency')" class="p-btn"
                          style="width: auto; margin: 0; padding: 10px 20px; background: var(--accent); color: white; border-color: var(--accent); border-radius: 12px; font-size: 12px; font-weight: 700; cursor: pointer; transition: background 0.2s;">
                          Cambiar a Perfil de Agencia
                        </button>
                      </div>
                    </div>
                  </div>
                @endif

                <!-- AGENCIA -->
                @if($user->account_type === 'agency')
                  <div class="card tab-section {{ $user->account_type === 'agency' ? 'active' : '' }}" id="section-agencia">
                    <div class="card-body">

                      <!-- OPCIONES DE PRESENTACIÓN (al tope) -->
                      <div style="margin-bottom: 20px; padding-bottom: 18px; border-bottom: 1px solid #f1f5f9;">
                        <label
                          style="font-size: 13px; font-weight: 700; color: var(--text); text-transform: none; margin-bottom: 3px; display: block; letter-spacing: normal; font-family: 'Barlow', sans-serif;">¿Cómo
                          quieres presentarte?</label>
                        <p
                          style="font-size: 12px; color: var(--muted); margin: 0 0 12px; line-height: 1.4; font-weight: 500;">
                          Elige la identidad visual que verán tus viajeros en sus itinerarios.</p>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                          <!-- Option Agent -->
                          <label class="presentation-option-label" id="label-present-agent"
                            style="display: flex; flex-direction: column; padding: 12px 14px; background: white; border: 1.5px solid {{ $user->display_name_type === 'personal' ? 'var(--accent)' : 'var(--border)' }}; border-radius: 10px; cursor: pointer; transition: all 0.2s ease; box-shadow: 0 1px 2px rgba(0,0,0,0.02); text-transform: none; letter-spacing: normal;">
                            <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 3px;">
                              <input type="radio" name="displayNameType" value="personal" {{ $user->display_name_type === 'personal' ? 'checked' : '' }}
                                style="width: 14px; height: 14px; accent-color: var(--accent);"
                                onchange="updatePresentationBorder()">
                              <span style="font-size: 13px; font-weight: 600; color: var(--text);">Como agente</span>
                            </div>
                            <span style="font-size: 11px; color: var(--muted); line-height: 1.3; padding-left: 22px;">Tu
                              nombre personal y foto.</span>
                          </label>
                          <!-- Option Agency -->
                          <label class="presentation-option-label" id="label-present-agency"
                            style="display: flex; flex-direction: column; padding: 12px 14px; background: white; border: 1.5px solid {{ $user->display_name_type === 'agency' ? 'var(--accent)' : 'var(--border)' }}; border-radius: 10px; cursor: pointer; transition: all 0.2s ease; box-shadow: 0 1px 2px rgba(0,0,0,0.02); text-transform: none; letter-spacing: normal;">
                            <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 3px;">
                              <input type="radio" name="displayNameType" value="agency" {{ $user->display_name_type === 'agency' ? 'checked' : '' }}
                                style="width: 14px; height: 14px; accent-color: var(--accent);"
                                onchange="updatePresentationBorder()">
                              <span style="font-size: 13px; font-weight: 600; color: var(--text);">Como agencia</span>
                            </div>
                            <span style="font-size: 11px; color: var(--muted); line-height: 1.3; padding-left: 22px;">Logo,
                              nombre corporativo y eslogan.</span>
                          </label>
                        </div>
                      </div>

                      <!-- DATOS DEL AGENTE -->
                      <div class="section-label" style="margin-bottom: 6px; font-size: 10px;">Datos del Agente</div>

                      <!-- Avatar Section (Finpay style) -->
                      <div class="avatar-finpay-section"
                        style="display: flex; align-items: center; gap: 20px; margin-bottom: 18px; border-bottom: 1px solid #f1f5f9; padding-bottom: 18px;">
                        <div class="avatar-wrapper" style="position: relative; display: inline-block;">
                          <div class="avatar-big" id="avatarBig"
                            style="width: 80px; height: 80px; border-radius: 50%; background: var(--accent-light); color: var(--accent); font-size: 26px; font-weight: 700; display: flex; align-items: center; justify-content: center; overflow: hidden; border: 3px solid #ffffff; box-shadow: 0 4px 12px rgba(0,0,0,0.08); cursor: pointer;">
                            <span id="avatarInitial"
                              style="{{ $user->avatar ? 'display:none' : '' }}; color: #ffffff;">{{ $user->display_initials }}</span>
                            <img id="avatarImg"
                              src="{{ $user->avatar ? (str_starts_with($user->avatar, 'http') ? $user->avatar : asset('storage/' . $user->avatar)) : '' }}"
                              alt=""
                              style="{{ $user->avatar ? '' : 'display:none' }}; width: 100%; height: 100%; object-fit: cover;">
                          </div>
                          <input type="file" id="avatarUpload" accept="image/jpeg, image/png, image/webp"
                            style="display:none">
                        </div>
                        <div class="avatar-actions-col" style="display: flex; gap: 10px; align-items: center;">
                          <button type="button" onclick="document.getElementById('avatarUpload').click();"
                            class="btn-upload-new"
                            style="background: var(--accent); color: #ffffff; border: none; padding: 8px 16px; border-radius: 8px; font-weight: 700; font-size: 12px; cursor: pointer; transition: all 0.2s;">
                            Subir foto
                          </button>
                          <button type="button" id="avatarDeleteBtn"
                            style="{{ $user->avatar ? '' : 'display:none' }}; background: #f1f5f9; color: #475569; border: 1px solid #e2e8f0; padding: 8px 16px; border-radius: 8px; font-weight: 700; font-size: 12px; cursor: pointer; transition: all 0.2s;"
                            onmouseover="this.style.background='#e2e8f0';" onmouseout="this.style.background='#f1f5f9';">
                            Eliminar foto
                          </button>
                        </div>
                      </div>

                      <div class="form-row">
                        <div class="form-group">
                          <label>Nombre completo</label>
                          <input type="text" id="inputNombre" value="{{ trim($user->name . ' ' . $user->last_name) }}">
                        </div>
                        <div class="form-group">
                          <label>Correo Electrónico</label>
                          <input type="email" value="{{ auth()->user()->email }}" disabled>
                        </div>
                      </div>
                      <div class="form-row">
                        <div class="form-group">
                          <label>Teléfono del Agente</label>
                          <input type="tel" id="inputPhone" value="{{ $user->phone }}" placeholder="+57 300 000 0000">
                        </div>
                        <div class="form-group">
                          <label>País del Agente</label>
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

                      <!-- DATOS DE LA AGENCIA -->
                      <div class="section-label" style="margin-top: 8px; margin-bottom: 6px; font-size: 10px;">Datos de la
                        Agencia</div>

                      <div class="form-row">
                        <div class="form-group">
                          <label>Nombre de la Agencia</label>
                          <input type="text" id="inputAgencia" value="{{ $user->agency_name }}">
                        </div>
                        <div class="form-group">
                          <label>Eslogan</label>
                          <input type="text" id="inputSlogan" value="{{ $user->agency_slogan }}"
                            placeholder="Tu agencia de confianza">
                        </div>
                      </div>
                      <div class="form-row">
                        <div class="form-group">
                          <label>Sitio Web</label>
                          <input type="text" id="inputWebsite" value="{{ $user->agency_website }}"
                            placeholder="https://miagencia.com">
                        </div>
                        <div class="form-group">
                          <label>WhatsApp</label>
                          <input type="tel" id="inputWhatsapp" value="{{ $user->agency_whatsapp }}"
                            placeholder="+57 300 000 0000">
                        </div>
                      </div>
                      <div class="form-group">
                        <label>Logo de la Agencia</label>
                        <div class="logo-upload-area" id="logoDropArea">
                          <input type="file" accept="image/*">
                          <div id="logoPlaceholder">
                            <div class="upload-icon">
                              <svg viewBox="0 0 24 24">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                                <polyline points="17 8 12 3 7 8" />
                                <line x1="12" y1="3" x2="12" y2="15" />
                              </svg>
                            </div>
                            <div class="upload-text">Sube el logo de tu agencia</div>
                            <div class="upload-hint">PNG, SVG o JPG · Máx. 2MB · Recomendado 200×200px</div>
                          </div>
                          <img id="logoPreview" class="logo-preview"
                            src="{{ $user->agency_logo ? (str_starts_with($user->agency_logo, 'http') ? $user->agency_logo : asset('storage/' . $user->agency_logo)) : '' }}"
                            alt="" style="{{ $user->agency_logo ? '' : 'display:none' }}">
                        </div>
                      </div>

                      <div class="btn-row" style="margin-top: 8px;">
                        <button class="btn-save" id="saveAgencyInfo">
                          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2.5">
                            <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z" />
                            <polyline points="17 21 17 13 7 13 7 21" />
                            <polyline points="7 3 7 8 15 8" />
                          </svg>
                          Guardar Información
                        </button>
                      </div>

                      <div style="border-top: 1px solid #f0f2f5; margin: 20px 0 16px; padding-top: 0;"></div>

                      <div
                        style="display: flex; align-items: center; justify-content: space-between; padding: 16px; background: #f8fafc; border-radius: 12px; border: 1px solid #e2e8f0; flex-wrap: wrap; gap: 12px;">
                        <div style="flex: 1; min-width: 200px;">
                          <h3
                            style="font-size: 13px; font-weight: 800; color: #0f172a; margin: 0 0 3px; font-family: 'DM Sans', sans-serif;">
                            ¿Usas Viantryp solo para viajes personales?</h3>
                          <p style="font-size: 11px; color: #64748b; margin: 0; line-height: 1.4; font-weight: 500;">
                            Desactiva la vista de marca corporativa cuando quieras.</p>
                        </div>
                        <button type="button" onclick="requestChangeAccountType('personal')" class="p-btn"
                          style="white-space: nowrap; width: auto; margin: 0; padding: 8px 16px; background: var(--accent); color: white; border-color: var(--accent); border-radius: 10px; font-size: 12px; font-weight: 700; cursor: pointer; transition: background 0.2s;">
                          Cambiar a Perfil Personal
                        </button>
                      </div>
                    </div>
                  </div>
                @endif

                <!-- TEMA -->
                <div class="card tab-section" id="section-tema">
                  <div class="card-body">
                    <div class="section-label">Tema e Identidad Visual</div>
                    <p style="font-size:13px;color:var(--muted);margin-bottom:24px;">Elige el color principal que
                      aparecerá en todos tus itinerarios y propuestas de viaje.</p>

                    <div class="theme-grid" id="themeGrid" data-selected="{{ $user->theme_color ?? 'default' }}">
                      <div class="theme-option {{ ($user->theme_color ?? 'default') == 'default' ? 'selected' : '' }}"
                        data-theme="default">
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
                      <div class="theme-option {{ $user->theme_color == 'sunset' ? 'selected' : '' }}"
                        data-theme="sunset">
                        <div class="theme-swatch" style="background:#c0552a"></div>
                        <span class="theme-name">Terracota</span>
                      </div>
                      <div class="theme-option {{ $user->theme_color == 'blush' ? 'selected' : '' }}" data-theme="blush">
                        <div class="theme-swatch" style="background:linear-gradient(135deg,#e07b9a,#f4a5bd)"></div>
                        <span class="theme-name">Blush</span>
                      </div>
                      <div class="theme-option {{ $user->theme_color == 'silver' ? 'selected' : '' }}"
                        data-theme="silver">
                        <div class="theme-swatch" style="background:linear-gradient(135deg,#6e7f80,#9aa8a9)"></div>
                        <span class="theme-name">Silver</span>
                      </div>
                      <div class="theme-option {{ $user->theme_color == 'mint' ? 'selected' : '' }}" data-theme="mint">
                        <div class="theme-swatch" style="background:linear-gradient(135deg,#3db898,#62d4b5)"></div>
                        <span class="theme-name">Menta</span>
                      </div>
                      <div class="theme-option {{ $user->theme_color == 'lavender' ? 'selected' : '' }}"
                        data-theme="lavender">
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
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                          stroke-width="2.5">
                          <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z" />
                          <polyline points="17 21 17 13 7 13 7 21" />
                          <polyline points="7 3 7 8 15 8" />
                        </svg>
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
                    <p class="danger-text">Eliminar tu cuenta borrará permanentemente todos tus itinerarios, datos y
                      configuración. Esta acción no se puede deshacer.</p>
                    <button class="btn-danger-profile">Eliminar mi cuenta</button>
                  </div>
                </div>

                <!-- SUSCRIPCIÓN -->
                <div class="card tab-section" id="section-subscription">
                  <div class="card-body">
                    <div class="section-label">Planes y Suscripción</div>

                    @php
                      $currentPlanKey = strtolower($planUser->plan ?? $user->plan);
                      $planLabels = [
                          'básico' => 'Plan Básico',
                          'esencial' => 'Plan Esencial',
                          'avanzado' => 'Plan Avanzado',
                          'colaborativo' => 'Plan Colaborativo',
                          'corporativo' => 'Plan Corporativo',
                      ];
                      $planLabel = $planLabels[$currentPlanKey] ?? ucfirst($currentPlanKey);
                      $isTrial = $user->isTrialActive();
                      $trialEnd = $isTrial ? $user->trial_ends_at : null;
                      $subStart = isset($user->subscription_starts_at) ? \Carbon\Carbon::parse($user->subscription_starts_at) : null;
                      $subEnd = isset($user->subscription_ends_at) ? \Carbon\Carbon::parse($user->subscription_ends_at) : null;
                      $isPaidPlan = in_array($currentPlanKey, ['esencial', 'avanzado', 'colaborativo']);
                      
                      if ($isTrial && $trialEnd) {
                          $endDate = \Carbon\Carbon::parse($trialEnd)->format('d M, Y');
                      } elseif ($subEnd) {
                          $endDate = $subEnd->format('d M, Y');
                      } elseif ($isPaidPlan) {
                          $endDate = \Carbon\Carbon::parse($user->updated_at)->addMonth()->format('d M, Y');
                      } else {
                          $endDate = 'Sin costo recurrente';
                      }

                      $planPrices = [
                          'básico' => '$0.00 USD / mes',
                          'esencial' => '$5.00 USD / mes',
                          'avanzado' => '$12.00 USD / mes',
                          'colaborativo' => '$29.00 USD / mes',
                          'corporativo' => 'A medida / Ventas',
                      ];
                      $planPrice = $planPrices[$currentPlanKey] ?? '$0.00 USD / mes';
                    @endphp

                    <!-- Ficha plan: 2 columnas sincronizada con Paddle -->
                    <div style="border: 1px solid #e2e8f0; border-radius: 16px; overflow: hidden; margin-bottom: 24px; box-shadow: 0 4px 12px -2px rgba(0,0,0,0.03);">

                      <div style="display: grid; grid-template-columns: 1fr 1px 1fr; min-height: 130px;">

                        <!-- Izquierda: plan -->
                        <div style="padding: 24px 28px;">
                          <div
                            style="font-size: 12px; color: #94a3b8; font-weight: 600; margin-bottom: 8px; letter-spacing: 0.3px; display: flex; align-items: center; gap: 8px;">
                            <span>ESTADO DEL PLAN</span>
                            @if($isTrial)
                              <span style="background:#fef9c3;color:#854d0e;border-radius:20px;padding:2px 10px;font-size:10px;font-weight:800;letter-spacing:0.5px;">PRUEBA ACTIVA</span>
                            @elseif($isPaidPlan)
                              <span style="background:#dcfce7;color:#15803d;border-radius:20px;padding:2px 10px;font-size:10px;font-weight:800;letter-spacing:0.5px;"><i class="fas fa-check-circle"></i> ACTIVO (PADDLE)</span>
                            @else
                              <span style="background:#f1f5f9;color:#64748b;border-radius:20px;padding:2px 10px;font-size:10px;font-weight:800;letter-spacing:0.5px;">PLAN GRATUITO</span>
                            @endif
                          </div>
                          <div
                            style="font-size: 26px; font-weight: 900; color: #0f172a; font-family: 'Barlow Condensed', sans-serif; text-transform: uppercase; letter-spacing: -0.3px; margin-bottom: 6px;">
                            {{ $planLabel }}
                          </div>
                          <div style="font-size: 12.5px; color: #64748b; font-weight: 500; line-height: 1.4;">
                            @if($currentPlanKey === 'básico')
                              Hasta {{ $user->getPlanLimits()['max_trips'] }} itinerario. Sin colaboradores.
                            @elseif($currentPlanKey === 'esencial')
                              Hasta {{ $user->getPlanLimits()['max_trips'] }} itinerarios. Google Places incluido.
                            @elseif($currentPlanKey === 'avanzado')
                              Hasta {{ $user->getPlanLimits()['max_trips'] }} itinerarios y {{ $user->getPlanLimits()['max_editors'] }} editores incluidos.
                            @elseif($currentPlanKey === 'colaborativo')
                              Itinerarios ilimitados y colaboradores ilimitados.
                            @elseif($isTrial)
                              Prueba gratuita activa de 7 días.
                            @else
                              Acceso completo a las funciones profesionales.
                            @endif
                          </div>
                        </div>

                        <!-- Divisor vertical -->
                        <div style="background: #e2e8f0;"></div>

                        <!-- Derecha: fechas + costo en filas -->
                        <div style="display: flex; flex-direction: column;">

                          <!-- Próximo pago / fin de prueba -->
                          <div style="padding: 20px 28px; flex: 1; border-bottom: 1px solid #e2e8f0;">
                            <div
                              style="font-size: 12px; color: #94a3b8; font-weight: 600; margin-bottom: 6px; letter-spacing: 0.3px;">
                              @if($isTrial) Fin de prueba gratuita @else Próxima fecha de facturación @endif
                            </div>
                            <div
                              style="font-size: 20px; font-weight: 800; color: #0f172a; font-family: 'Barlow Condensed', sans-serif; margin-bottom: 4px;">
                              {{ $endDate }}
                            </div>
                            <div style="font-size: 11.5px; color: #94a3b8; font-weight: 500;">
                              @if($isTrial)
                                Se requiere activar suscripción al finalizar la prueba
                              @elseif($isPaidPlan)
                                Renovación automática gestionada de forma segura por Paddle
                              @else
                                Plan gratuito sin cargos recurrentes
                              @endif
                            </div>
                          </div>

                          <!-- Costo mensual -->
                          <div style="padding: 20px 28px; flex: 1;">
                            <div
                              style="font-size: 12px; color: #94a3b8; font-weight: 600; margin-bottom: 6px; letter-spacing: 0.3px;">
                              Costo recurrente</div>
                            <div
                              style="font-size: 20px; font-weight: 800; color: #0f172a; font-family: 'Barlow Condensed', sans-serif; margin-bottom: 4px;">
                              {{ $planPrice }}
                            </div>
                            <div style="font-size: 11.5px; color: #94a3b8; font-weight: 500;">
                              @if($currentPlanKey === 'básico' || $isTrial)
                                Sin cobro en modo de prueba / básico
                              @else
                                Facturación recurrente en Dólares Estadounidenses (USD)
                              @endif
                            </div>
                          </div>

                        </div>
                      </div>

                      <!-- Pie: botón administrar -->
                      <div
                        style="padding: 14px 28px; border-top: 1px solid #f1f5f9; background: #fafbfc; display: flex; align-items: center; justify-content: space-between;">
                        <div style="font-size: 11.5px; color: #64748b; font-weight: 600; display: flex; align-items: center; gap: 6px;">
                          <i class="fas fa-lock" style="color: #10b981;"></i> Facturación respaldada por <strong>Paddle Merchant of Record</strong>
                        </div>
                        <button onclick="openUpgradeModal()" style="
                    display: inline-flex; align-items: center; gap: 8px;
                    background: var(--accent); color: #ffffff; border: none;
                    padding: 9px 20px; border-radius: 8px;
                    font-weight: 700; font-size: 13px; font-family: 'Barlow', sans-serif;
                    cursor: pointer; transition: all 0.2s ease;
                  " onmouseover="this.style.opacity='0.9';this.style.transform='translateY(-1px)';"
                          onmouseout="this.style.opacity='1';this.style.transform='none';">
                          <i class="fas fa-layer-group" style="font-size:12px;"></i>
                          Cambiar o Gestionar Plan
                        </button>
                      </div>
                    </div>

                    <!-- Uso del plan -->
                    <div
                      style="display: grid; grid-template-columns: repeat(auto-fit, minmax(160px, 1fr)); gap: 14px; margin-bottom: 20px;">
                      <div
                        style="padding: 14px; border: 1px solid var(--border); border-radius: 12px; background: white;">
                        <div
                          style="font-size: 10px; font-weight: 800; color: var(--muted); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px;">
                          Itinerarios PRO</div>
                        <div style="display: flex; align-items: baseline; gap: 4px; margin-bottom: 8px;">
                          <span style="font-size: 20px; font-weight: 800; color: var(--text);">{{ $tripCount }}</span>
                          <span style="font-size: 13px; color: var(--muted); font-weight: 600;">/
                            {{ $user->getPlanLimits()['max_trips'] >= 1000000 ? '∞' : $user->getPlanLimits()['max_trips'] }}</span>
                        </div>
                        <div style="height: 4px; background: #f1f5f9; border-radius: 10px; overflow: hidden;">
                          @php $tripPerc = min(100, ($user->getPlanLimits()['max_trips'] > 0 ? ($tripCount / $user->getPlanLimits()['max_trips']) * 100 : 0)); @endphp
                          <div
                            style="width: {{ $tripPerc }}%; height: 100%; background: var(--accent); border-radius: 10px;">
                          </div>
                        </div>
                      </div>
                      <div
                        style="padding: 14px; border: 1px solid var(--border); border-radius: 12px; background: white;">
                        <div
                          style="font-size: 10px; font-weight: 800; color: var(--muted); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px;">
                          Editores Premium</div>
                        <div style="display: flex; align-items: baseline; gap: 4px; margin-bottom: 8px;">
                          <span style="font-size: 20px; font-weight: 800; color: var(--text);">{{ $editorCount }}</span>
                          <span style="font-size: 13px; color: var(--muted); font-weight: 600;">/
                            {{ ($user->getPlanLimits()['max_editors'] ?? 0) >= 1000000 ? '∞' : ($user->getPlanLimits()['max_editors'] ?? 0) }}</span>
                        </div>
                        <div style="height: 4px; background: #f1f5f9; border-radius: 10px; overflow: hidden;">
                          @php $editPerc = min(100, (($user->getPlanLimits()['max_editors'] ?? 0) > 0 ? ($editorCount / ($user->getPlanLimits()['max_editors'] ?? 0)) * 100 : 0)); @endphp
                          <div style="width: {{ $editPerc }}%; height: 100%; background: #8b5cf6; border-radius: 10px;">
                          </div>
                        </div>
                      </div>
                    </div>

                    <!-- Historial de Facturación -->
                    <div class="form-group">
                      <label style="font-weight: 700; color: #334155; font-size: 13px;">Historial de Recibos y Facturas</label>
                      @if($isPaidPlan)
                        <div style="border: 1px solid #e2e8f0; border-radius: 12px; overflow: hidden; background: white;">
                          <div style="padding: 16px 20px; display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid #f1f5f9;">
                            <div style="display: flex; align-items: center; gap: 12px;">
                              <div style="width: 36px; height: 36px; border-radius: 8px; background: #f0fdf4; display: flex; align-items: center; justify-content: center; color: #16a34a;">
                                <i class="fas fa-check-circle"></i>
                              </div>
                              <div>
                                <div style="font-weight: 700; font-size: 13.5px; color: #0f172a;">Suscripción Viantryp {{ $planLabel }}</div>
                                <div style="font-size: 11.5px; color: #94a3b8;">Factura electrónica emitida en USD · Pasarela Paddle</div>
                              </div>
                            </div>
                            <div style="text-align: right;">
                              <div style="font-weight: 800; font-size: 14px; color: #0f172a;">{{ $planPrice }}</div>
                              <span style="font-size: 10px; font-weight: 800; background: #dcfce7; color: #15803d; padding: 2px 8px; border-radius: 20px;">PAGADO</span>
                            </div>
                          </div>
                        </div>
                      @else
                        <div
                          style="padding: 20px; background: var(--bg); border: 1px dashed var(--border); border-radius: 12px; text-align: center; color: var(--muted); font-size: 13px;">
                          <i class="fas fa-file-invoice-dollar"
                            style="font-size: 22px; margin-bottom: 10px; display: block; opacity: 0.4;"></i>
                          No hay facturas pendientes. Al mejorar a un plan de pago, tus recibos aparecerán aquí automáticamente.
                        </div>
                      @endif
                    </div>
                  </div>
                </div>

                <!-- MÉTODOS DE PAGO (SINCRONIZADO CON PADDLE) -->
                <div class="card tab-section" id="section-pagos">
                  <div class="card-body">
                    <div class="section-label">Métodos de Pago & Facturación</div>

                    @if($isPaidPlan)
                      <!-- Tarjeta activa sincronizada con Paddle -->
                      <div style="background: linear-gradient(135deg, #0f2a3a, #1a7a8a); border-radius: 18px; padding: 26px 28px; color: white; margin-bottom: 24px; box-shadow: 0 10px 25px -5px rgba(26, 122, 138, 0.25); position: relative; overflow: hidden;">
                        <div style="position: absolute; right: -20px; bottom: -20px; opacity: 0.08; font-size: 140px;">
                          <i class="fas fa-credit-card"></i>
                        </div>
                        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 24px;">
                          <div>
                            <div style="font-size: 11px; text-transform: uppercase; letter-spacing: 1px; color: rgba(255,255,255,0.7); font-weight: 700; margin-bottom: 4px;">MÉTODO DE PAGO REGISTRADO</div>
                            <div style="font-size: 18px; font-weight: 800; font-family: 'Barlow Condensed', sans-serif; letter-spacing: 0.5px;">Tarjeta de Crédito / Débito</div>
                          </div>
                          <div style="background: rgba(255,255,255,0.2); backdrop-filter: blur(8px); padding: 4px 12px; border-radius: 20px; font-size: 11px; font-weight: 700;">
                            <i class="fas fa-lock"></i> Paddle Secure
                          </div>
                        </div>

                        <div style="font-size: 19px; font-weight: 700; letter-spacing: 2px; margin-bottom: 20px; font-family: monospace;">
                          •••• •••• •••• {{ $user->card_last_four ?? '••••' }}
                        </div>

                        <div style="display: flex; justify-content: space-between; align-items: flex-end; border-top: 1px solid rgba(255,255,255,0.15); padding-top: 14px;">
                          <div>
                            <div style="font-size: 10px; color: rgba(255,255,255,0.7); text-transform: uppercase;">Titular</div>
                            <div style="font-size: 13px; font-weight: 700;">{{ $user->name }}</div>
                          </div>
                          <div>
                            <div style="font-size: 10px; color: rgba(255,255,255,0.7); text-transform: uppercase;">Moneda</div>
                            <div style="font-size: 13px; font-weight: 700;">USD ($)</div>
                          </div>
                          <div>
                            <span style="background: #10b981; color: white; font-size: 10.5px; font-weight: 800; padding: 3px 10px; border-radius: 20px;">
                              <i class="fas fa-check"></i> ACTIVA
                            </span>
                          </div>
                        </div>
                      </div>

                      <div style="display: flex; gap: 12px; align-items: center; margin-bottom: 24px;">
                        <button onclick="openUpgradeModal()" class="btn-save" style="background: var(--accent); padding: 10px 22px; font-size: 13px;">
                          <i class="fas fa-sync-alt"></i> Cambiar Plan o Facturación
                        </button>
                        <button onclick="openUpgradeModal()" class="btn-secondary" style="padding: 9px 20px; font-size: 13px;">
                          <i class="fas fa-credit-card"></i> Actualizar Tarjeta
                        </button>
                      </div>

                    @else
                      <!-- Estado sin tarjetas activas para plan gratuito -->
                      <div
                        style="background: #f8fafc; border: 1.5px dashed #cbd5e1; border-radius: 16px; padding: 32px 24px; text-align: center; margin-bottom: 24px;">
                        <div
                          style="width: 54px; height: 54px; background: #e2e8f0; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px; color: #64748b;">
                          <i class="fas fa-credit-card" style="font-size: 22px;"></i>
                        </div>
                        <h4 style="font-size: 17px; font-weight: 800; color: #0f172a; margin: 0 0 8px; font-family: 'Barlow Condensed', sans-serif; text-transform: uppercase;">
                          Sin métodos de pago asociados
                        </h4>
                        <p style="font-size: 13px; color: #64748b; margin: 0 auto 20px; max-width: 420px; line-height: 1.5;">
                          Tu cuenta se encuentra en el <strong>Plan Básico gratuito</strong>. Al contratar cualquier plan PRO, tu tarjeta de crédito o débito se registrará de forma 100% segura mediante <strong>Paddle</strong> para renovaciones automáticas en USD.
                        </p>
                        <button onclick="openUpgradeModal()" class="btn-save" style="margin: 0 auto; padding: 11px 24px; font-size: 13.5px;">
                          <i class="fas fa-rocket"></i> Explorar Planes PRO
                        </button>
                      </div>
                    @endif

                    <div style="background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 12px; padding: 14px 18px; display: flex; align-items: flex-start; gap: 12px;">
                      <i class="fas fa-shield-check" style="font-size: 18px; color: #16a34a; margin-top: 2px;"></i>
                      <div style="font-size: 12px; color: #166534; line-height: 1.5;">
                        <strong>Seguridad de Grado Bancario:</strong> Todos los pagos, suscripciones y datos de tarjetas son procesados y resguardados directamente por <strong>Paddle.com</strong> bajo el estándar internacional <strong>PCI-DSS Nivel 1</strong>. Viantryp nunca almacena los números completos de tus tarjetas.
                      </div>
                    </div>
                  </div>
                </div>

              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- TOAST -->
  <div class="toast-profile" id="toastProfile">
    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5">
      <polyline points="20 6 9 17 4 12" />
    </svg>
    Cambios guardados exitosamente
  </div>

  <x-upgrade-modal />
  <x-welcome-modal />
@endsection

@push('scripts')
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      // CSRF Setup for Fetch
      const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

      // Dropdowns and Notifications initialization
      (function () {
        const initMenu = () => {
          const trigger = document.getElementById('profileTrigger');
          const menu = document.getElementById('profileMenu');

          if (trigger && menu) {
            trigger.addEventListener('click', function (e) {
              e.stopPropagation();
              const isVisible = menu.style.display === 'block';
              menu.style.display = isVisible ? 'none' : 'block';
              trigger.style.background = isVisible ? 'transparent' : 'rgba(0, 0, 0, 0.04)';
            });

            document.addEventListener('click', function (e) {
              if (!trigger.contains(e.target) && !menu.contains(e.target)) {
                menu.style.display = 'none';
                trigger.style.background = 'transparent';
              }
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
                  list.innerHTML = '<div style="padding: 20px; text-align: center; color: #94a3b8; font-size: 12px;">No tienes notificaciones nuevas</div>';
                } else {
                  list.innerHTML = d.notifications.map(n => `
                                <div style="padding: 12px 16px; border-bottom: 1px solid #f8fafc; cursor: pointer; transition: background 0.2s; ${n.read_at ? '' : 'background: var(--accent-light);'}" onclick="handleNotiClick('${n.id}', '${n.data.invite_url}')">
                                    <div style="font-size: 13px; color: #1e293b; font-weight: ${n.read_at ? '400' : '600'}; margin-bottom: 2px;">${n.data.message}</div>
                                    <div style="font-size: 11px; color: #94a3b8;">${new Date(n.created_at).toLocaleString()}</div>
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

          trigger.addEventListener('click', function (e) {
            e.stopPropagation();
            const isVisible = menu.style.display === 'block';
            menu.style.display = isVisible ? 'none' : 'block';
            if (!isVisible) fetchNotis();
          });

          document.addEventListener('click', function (e) {
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

        initMenu();
        initNotis();
      })();

      // NAV ITEMS — navegación entre secciones
      document.querySelectorAll('.nav-item[data-section]').forEach(function (btn) {
        btn.addEventListener('click', function () {
          var id = btn.getAttribute('data-section');
          document.querySelectorAll('.tab-section').forEach(function (s) { s.classList.remove('active'); });
          document.querySelectorAll('.nav-item').forEach(function (b) { b.classList.remove('active'); });
          document.getElementById('section-' + id).classList.add('active');
          btn.classList.add('active');
        });
      });

      // Manejo de pestaña por URL (ej: /profile?section=subscription o /profile?tab=subscription)
      const urlParams = new URLSearchParams(window.location.search);
      const targetSection = urlParams.get('section') || urlParams.get('tab') || window.location.hash.replace('#', '');
      const isPaid = urlParams.get('paid') === 'true';

      if (targetSection) {
        const targetBtn = document.querySelector(`.nav-item[data-section="${targetSection}"]`);
        if (targetBtn) {
          setTimeout(() => {
            targetBtn.click();
            if (isPaid) {
              showToast('¡Pago exitoso! Tu suscripción ha sido confirmada y activada.');
              const cleanUrl = window.location.protocol + "//" + window.location.host + window.location.pathname + '?section=subscription';
              window.history.replaceState({ path: cleanUrl }, '', cleanUrl);
            }
          }, 50);
        } else {
          // Fallback if data-section doesn't match directly
          const sectionEl = document.getElementById('section-' + targetSection);
          if (sectionEl) {
            document.querySelectorAll('.tab-section').forEach(function (s) { s.classList.remove('active'); });
            document.querySelectorAll('.nav-item').forEach(function (b) { b.classList.remove('active'); });
            sectionEl.classList.add('active');
          }
        }
      }

      // Toast helper
      function showToast(message) {
        var toast = document.getElementById('toastProfile');
        toast.innerHTML = `<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg> ${message}`;
        toast.classList.add('show');
        setTimeout(function () { toast.classList.remove('show'); }, 3000);
      }

      // SAVE PERSONAL INFO
      const savePersonalBtn = document.getElementById('savePersonalInfo');
      if (savePersonalBtn) {
        savePersonalBtn.addEventListener('click', function () {
          const fullName = document.getElementById('inputNombre').value.trim();
          const parts = fullName.split(/\s+/);
          const firstName = parts[0] || '';
          const lastName = parts.slice(1).join(' ') || '';

          const data = {
            name: firstName,
            last_name: lastName,
            phone: document.getElementById('inputPhone').value,
            country: document.getElementById('inputCountry').value,
            bio: document.getElementById('inputBio').value,
            display_name_type: 'personal'
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
              if (res.success) {
                showToast(res.message);
                const nameDisplay = document.getElementById('profileName');
                if (nameDisplay) {
                  nameDisplay.textContent = data.name;
                }
              }
            });
        });
      }

      // SAVE AGENCY INFO
      const saveAgencyBtn = document.getElementById('saveAgencyInfo');
      if (saveAgencyBtn) {
        saveAgencyBtn.addEventListener('click', function () {
          const data = {
            agency_name: document.getElementById('inputAgencia').value,
            agency_website: document.getElementById('inputWebsite').value,
            agency_whatsapp: document.getElementById('inputWhatsapp').value,
            agency_slogan: document.getElementById('inputSlogan').value,
            display_name_type: document.querySelector('input[name="displayNameType"]:checked').value
          };

          @if($user->account_type === 'agency')
            const fullName = document.getElementById('inputNombre').value.trim();
            const parts = fullName.split(/\s+/);
            const firstName = parts[0] || '';
            const lastName = parts.slice(1).join(' ') || '';

            data.name = firstName;
            data.last_name = lastName;
            data.phone = document.getElementById('inputPhone').value;
            data.country = document.getElementById('inputCountry').value;
            const bioEl = document.getElementById('inputBio');
            data.bio = bioEl ? bioEl.value : '{{ addslashes($user->bio ?? "") }}';
          @endif

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
              if (res.success) showToast(res.message);
            });
        });
      }

      // SAVE THEME
      document.getElementById('saveTheme').addEventListener('click', function () {
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
            if (res.success) {
              showToast(res.message);
              // Dynamic change of UI colors if needed immediately
              location.reload(); // Simple way to apply global theme injection
            }
          });
      });

      // THEME OPTIONS - Preview only
      document.querySelectorAll('.theme-option[data-theme]').forEach(function (el) {
        el.addEventListener('click', function () {
          var theme = el.getAttribute('data-theme');
          var color = el.querySelector('.theme-swatch').style.background;

          document.body.setAttribute('data-theme', theme === 'default' ? '' : theme);
          document.querySelectorAll('.theme-option').forEach(function (o) { o.classList.remove('selected'); });
          el.classList.add('selected');

          // Real-time preview update
          const previewHeader = document.querySelector('.preview-header');
          if (previewHeader) {
            previewHeader.style.background = color || '#1a7f77';
          }

          // Update accent dots in preview
          document.querySelectorAll('.preview-day-dot').forEach(dot => {
            dot.style.background = color || '#1a7f77';
          });

          // Update avatar background in sidebar for instant feel
          const avatarBig = document.querySelector('.avatar-big');
          if (avatarBig) {
            avatarBig.style.background = color || '#1a7f77';
          }

          // Update mobile app FAB create button in real-time
          const fabBtn = document.getElementById('fabCreateTripBtn');
          if (fabBtn) {
            fabBtn.style.background = color || '#1a7f77';
          }
        });
      });

      // NOMBRE en tiempo real
      var inputNombre = document.getElementById('inputNombre');
      if (inputNombre) inputNombre.addEventListener('input', updateName);

      function updateName() {
        updateDisplayNames();
      }

      // AGENCIA en tiempo real
      var inputAgencia = document.getElementById('inputAgencia');
      if (inputAgencia) {
        inputAgencia.addEventListener('input', function () {
          updateDisplayNames();
        });
      }

      // DISPLAY NAME PREFERENCE in real-time
      document.querySelectorAll('input[name="displayNameType"]').forEach(function (radio) {
        radio.addEventListener('change', function () {
          var val = this.value;
          // Sync radios between sections
          document.querySelectorAll('input[name="displayNameType"]').forEach(r => {
            if (r.value === val) r.checked = true;
          });
          updatePresentationBorder();
          updateDisplayNames();
        });
      });

      function updatePresentationBorder() {
        const typeEl = document.querySelector('input[name="displayNameType"]:checked');
        if (!typeEl) return;
        const isPersonal = typeEl.value === 'personal';
        const agentLabel = document.getElementById('label-present-agent');
        const agencyLabel = document.getElementById('label-present-agency');

        if (agentLabel && agencyLabel) {
          if (isPersonal) {
            agentLabel.style.borderColor = 'var(--accent)';
            agentLabel.style.background = 'var(--accent-light)';
            agencyLabel.style.borderColor = 'var(--border)';
            agencyLabel.style.background = 'white';
          } else {
            agentLabel.style.borderColor = 'var(--border)';
            agentLabel.style.background = 'white';
            agencyLabel.style.borderColor = 'var(--accent)';
            agencyLabel.style.background = 'var(--accent-light)';
          }
        }
      }

      // Call initially to set correct active border color
      updatePresentationBorder();

      window.requestChangeAccountType = function (targetType) {
        let msg = `¿Estás seguro de que quieres cambiar tu tipo de cuenta a ${targetType === 'personal' ? 'Perfil Personal' : 'Perfil de Agencia'}?`;
        if (!confirm(msg)) return;

        fetch('{{ route('profile.change-account-type') }}', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
          },
          body: JSON.stringify({ account_type: targetType })
        })
          .then(res => res.json())
          .then(res => {
            if (res.success) {
              showToast(res.message);
              setTimeout(() => location.reload(), 1000);
            } else {
              alert(res.message || 'Error al cambiar el tipo de cuenta');
            }
          })
          .catch(err => {
            console.error(err);
            alert('Error de red al intentar cambiar el tipo de cuenta');
          });
      };

      function updateDisplayNames() {
        var typeEl = document.querySelector('input[name="displayNameType"]:checked');
        var type = typeEl ? typeEl.value : '{{ $user->account_type }}';
        var nameVal = '';
        var initials = '';

        if (type === 'personal') {
          var fullName = inputNombre ? inputNombre.value.trim() : '';
          nameVal = fullName || 'Tu Nombre';
          var words = fullName.split(/\s+/).filter(w => w.length > 0);
          initials = (words[0] ? words[0][0] : '?').toUpperCase() + (words[1] ? words[1][0] : '').toUpperCase();
        } else {
          var aName = inputAgencia ? inputAgencia.value : '';
          nameVal = aName || 'Mi Agencia';
          var words = nameVal.split(' ').filter(w => w.length > 0);
          initials = (words[0] ? words[0][0] : 'V').toUpperCase() + (words[1] ? words[1][0] : '').toUpperCase();
        }

        const profileNameEl = document.getElementById('profileName');
        if (profileNameEl) profileNameEl.textContent = nameVal;

        const avatarInitialEl = document.getElementById('avatarInitial');
        if (avatarInitialEl) avatarInitialEl.textContent = initials;

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
        logoInput.addEventListener('change', function () {
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
              if (res.success) {
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
        avatarInput.addEventListener('change', function () {
          var file = this.files[0];
          if (!file) return;

          if (file.size > 2 * 1024 * 1024) {
            showToast('La imagen es muy pesada. Máximo 2MB.');
            this.value = '';
            return;
          }

          const validTypes = ['image/jpeg', 'image/png', 'image/webp'];
          if (!validTypes.includes(file.type)) {
            showToast('Formato no válido. Usa JPG, PNG o WEBP.');
            this.value = '';
            return;
          }

          const formData = new FormData();
          formData.append('avatar', file);

          fetch('{{ route('profile.upload.avatar') }}', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrfToken },
            body: formData
          })
            .then(res => res.json())
            .then(res => {
              if (res.success) {
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
        avatarEditBtn.addEventListener('click', function () {
          document.getElementById('avatarUpload').click();
        });
      }

      // AVATAR DELETE BTN
      var avatarDeleteBtn = document.getElementById('avatarDeleteBtn');
      if (avatarDeleteBtn) {
        avatarDeleteBtn.addEventListener('click', function () {
          if (confirm('¿Estás seguro de que quieres eliminar tu foto de perfil?')) {
            fetch('{{ route('profile.delete.avatar') }}', {
              method: 'POST',
              headers: { 'X-CSRF-TOKEN': csrfToken },
            })
              .then(res => res.json())
              .then(res => {
                if (res.success) {
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

      // Auto-open upgrade modal if ?upgrade=true
      const upgradeUrlParams = new URLSearchParams(window.location.search);
      if (upgradeUrlParams.get('upgrade') === 'true') {
        const upgradeNavBtn = document.querySelector('[data-section="subscription"]');
        if (upgradeNavBtn) upgradeNavBtn.click();

        setTimeout(() => {
          if (typeof openUpgradeModal === 'function') {
            openUpgradeModal();
          }
        }, 500);
      }

      // Auto-start tutorial (Solo si ya eligió su plan inicial)
      @if($user->initial_plan_chosen_at)
        setTimeout(() => {
          if (typeof initProfileTutorial === 'function') {
            initProfileTutorial();
          }
        }, 1200);
      @endif
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
          @if($user->account_type === 'personal')
                    {
              element: '#savePersonalInfo',
              popover: {
                title: 'Información Personal',
                description: 'Configura tus datos básicos de perfil personal para que se muestren de forma simplificada en tus itinerarios.',
                position: 'top'
              },
              onHighlightStarted: () => {
                const btn = document.querySelector('.nav-item[data-section="info"]');
                if (btn) btn.click();
              }
            },
          @else
            {
              element: '#label-present-agent',
              popover: {
                title: 'Identidad de Agente',
                description: 'Si prefieres presentarte ante tus clientes usando tu nombre y foto personal, marca "Presentación de Agente".',
                position: 'top'
              },
              onHighlightStarted: () => {
                const btn = document.querySelector('.nav-item[data-section="agencia"]');
                if (btn) btn.click();
              }
            },
            {
              element: '#label-present-agency',
              popover: {
                title: 'Identidad de Agencia',
                description: '¿Prefieres usar tu marca corporativa? Sube el logo de tu agencia y selecciona esta opción para mostrar tu imagen empresarial en los itinerarios.',
                position: 'top'
              }
            },
          @endif
          {
            element: '#themeGrid',
            popover: {
              title: 'Personalización Visual',
              description: '¡Dale color a tus propuestas! Elige el tema cromático que mejor represente tu estilo o marca de viajes. Verás el cambio reflejado al instante.',
              position: 'top'
            },
            onHighlightStarted: () => {
              const btn = document.querySelector('.nav-item[data-section="tema"]');
              if (btn) {
                btn.click();
                btn.classList.add('tutorial-extra-highlight');
              }
            },
            onDeselected: () => {
              const btn = document.querySelector('.nav-item[data-section="tema"]');
              if (btn) btn.classList.remove('tutorial-extra-highlight');
            }
          },
          {
            element: '.itinerary-preview',
            popover: {
              title: 'Vista Previa en Vivo',
              description: 'Observa al instante cómo luce la cabecera de tu itinerario con los colores y presentación que has seleccionado.'
            }
          },
          {
            element: '.nav-item[data-section="subscription"]',
            popover: {
              title: 'Planes y Suscripción',
              description: 'Revisa los límites de tu plan actual, gestiona tus facturas y mejora tu suscripción cuando lo necesites.',
              position: 'right'
            },
            onHighlightStarted: () => {
              const btn = document.querySelector('.nav-item[data-section="subscription"]');
              if (btn) btn.click();
            }
          },
          {
            element: '.secondary-nav-link',
            popover: {
              title: '¡Empieza a Diseñar!',
              description: '¡Excelente! Haz clic aquí en "Ir a Mis Viajes" para comenzar a crear propuestas profesionales increíbles para tus clientes.',
              position: 'bottom'
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