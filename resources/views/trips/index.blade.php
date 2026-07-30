@extends('layouts.app')

@section('title', 'Viantryp | Mis Viajes')

@push('styles')
    <link
        href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@700;800;900&family=Barlow:wght@400;500;600;700&display=swap"
        rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/driver.js@1.0.1/dist/driver.css" />
    <style>
        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        :root {
            --ink: #0f2a3a;
            --teal: #1a9a8a;
            --teal2: #0c4a5b;
            --tealL: rgba(26, 154, 138, 0.10);
            --cream: #f4f6f8;
            --sand: #e2e8ef;
            --bdr: rgba(15, 42, 58, 0.09);
            --gray: #6b7a8d;
            --gray2: #8f9db0;
            --white: #ffffff;
        }

        /* Driver.js Custom Styles */
        .driver-popover {
            background-color: var(--white);
            border-radius: 16px;
            padding: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
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
            width: 32px;
            height: 32px;
            border-radius: 50%;
            border: 1px solid rgba(255, 255, 255, 0.2);
            background: rgba(255, 255, 255, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            color: rgba(255, 255, 255, 0.8);
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
            font-size: 14px;
        }

        .btn-help:hover {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border-color: white;
            transform: translateY(-1px);
        }

        html,
        body {
            height: 100%;
            font-family: 'Barlow', sans-serif;
            color: var(--dark);
            background: var(--light);
        }

        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* ════════════════════════════════════════
                   TOPBAR
                ════════════════════════════════════════ */
        .topbar {
            position: sticky;
            top: 0;
            z-index: 200;
            height: 64px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 40px;
            flex-shrink: 0;
        }

        .topbar-bg-decorators {
            display: none;
        }

        .topbar-bg-decorators::before {
            content: '';
            position: absolute;
            top: 0;
            right: 120px;
            width: 160px;
            height: 300%;
            background: var(--teal);
            transform: skewX(-16deg);
            opacity: 0.07;
        }

        .topbar-bg-decorators::after {
            content: '';
            position: absolute;
            top: 0;
            right: 60px;
            width: 60px;
            height: 300%;
            background: var(--teal);
            transform: skewX(-16deg);
            opacity: 0.04;
        }

        .topbar-left {
            display: flex;
            align-items: center;
            gap: 28px;
            position: relative;
            z-index: 1;
        }

        .logo {
            display: flex;
            align-items: center;
            text-decoration: none;
        }

        .logo img {
            height: 28px;
            width: auto;
            filter: brightness(0) invert(1);
        }

        .nav-links {
            display: flex;
            gap: 4px;
        }

        .nav-link {
            font-size: 14px;
            font-weight: 500;
            color: var(--dark);
            text-decoration: none;
            padding: 7px 14px;
            border-radius: 8px;
            transition: background 0.18s, color 0.18s;
        }

        .nav-link:hover {
            background: var(--light);
            color: var(--accent);
        }

        .nav-link.active {
            color: var(--accent);
            background: var(--accent-light);
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 10px;
            position: relative;
            z-index: 1;
        }

        .ubadge {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 4px 14px 4px 4px;
            border-left: 1px solid rgba(255, 255, 255, 0.15);
            margin-left: 8px;
        }

        .avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: var(--avatar-gradient);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            font-weight: 700;
            color: white;
            letter-spacing: 0.5px;
        }

        .uname {
            font-size: 14px;
            font-weight: 600;
            color: rgba(255, 255, 255, 0.9);
        }

        .btn-out {
            display: flex;
            align-items: center;
            gap: 6px;
            border: 1px solid rgba(255, 255, 255, 0.16);
            border-radius: 24px;
            padding: 7px 16px;
            background: transparent;
            color: rgba(255, 255, 255, 0.6);
            font-size: 12px;
            font-weight: 500;
            font-family: 'DM Sans' sans-serif;
            cursor: pointer;
            transition: all 0.18s;
        }

        .btn-out:hover {
            background: rgba(255, 255, 255, 0.09);
            color: white;
        }

        .btn-out svg {
            width: 13px;
            height: 13px;
        }

        /* ════════════════════════════════════════
                   HERO BAND
                ════════════════════════════════════════ */
        .hero {
            background: var(--white);
            padding: 48px 40px 0;
            position: relative;
            overflow: hidden;
            border-bottom: 1px solid var(--border);
        }

        .hero-rings {
            display: none;
        }

        .hero-dot {
            display: none;
        }

        .hero-watermark {
            display: none;
        }

        .hero-tag {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: var(--accent-light);
            border: 1px solid var(--accent-border);
            border-radius: 6px;
            padding: 4px 12px;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: var(--accent);
            margin-bottom: 12px;
        }

        .htag-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: var(--accent);
            animation: blink 2s infinite;
        }

        .hero-title {
            font-family: 'Barlow Condensed', sans-serif;
            font-weight: 900;
            font-size: 32px;
            line-height: 1.1;
            color: #000000;
            letter-spacing: -0.5px;
            margin-bottom: 8px;
            text-transform: uppercase;
        }

        .hero-sub {
            font-size: 15px;
            font-weight: 400;
            color: var(--gray);
            margin-bottom: 0;
        }

        .hero-header-mobile {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
            gap: 10px;
        }

        .btn-mobile-only {
            display: none !important;
        }

        /* STAT CHIPS */
        .stat-chips {
            display: flex;
            gap: 8px;
            padding-bottom: 0;
        }

        .schip {
            background: var(--teal);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-bottom: none;
            border-radius: 12px 12px 0 0;
            padding: 14px 24px;
            min-width: 120px;
            display: flex;
            flex-direction: column;
            align-items: center;
            cursor: pointer;
            transition: all 0.2s;
            position: relative;
        }

        .schip::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: var(--white);
            transform: scaleX(0);
            transition: transform 0.25s;
        }

        .schip.on {
            background: var(--dark);
        }

        .schip.on::after {
            transform: scaleX(1);
        }

        .schip:hover:not(.on) {
            background: var(--teal-dark);
        }

        .schip:active {
            transform: translateY(1px);
        }

        .chip-num {
            font-family: 'Barlow Condensed', sans-serif;
            font-weight: 800;
            font-size: 28px;
            line-height: 1;
            color: var(--white);
        }

        .chip-lbl {
            font-size: 11px;
            font-weight: 600;
            color: rgba(255, 255, 255, 0.8);
            margin-top: 4px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .schip.on .chip-lbl {
            color: rgba(255, 255, 255, 0.75);
        }

        /* ACTION BUTTON */
        .btn-create {
            display: flex;
            align-items: center;
            gap: 10px;
            height: 44px;
            padding: 0 24px;
            border-radius: 50px;
            background: var(--teal);
            color: white;
            border: none;
            font-size: 14px;
            font-weight: 700;
            font-family: 'Barlow', sans-serif;
            cursor: pointer;
            text-decoration: none;
            box-shadow: 0 4px 16px rgba(26, 158, 143, 0.3);
            transition: all 0.2s;
        }

        .btn-create:hover {
            background: var(--teal2);
            transform: translateY(-1px);
            box-shadow: 0 8px 24px rgba(26, 158, 143, 0.4);
            color: white;
        }

        .btn-create:active {
            transform: translateY(0);
        }

        .wave {
            display: none;
        }

        /* ════════════════════════════════════════
                   MAIN CONTENT
                ════════════════════════════════════════ */
        .content {
            flex: 1;
            padding: 40px 10px 56px;
            max-width: 1200px;
            width: 100%;
            margin: 0 auto;
        }

        .toolbar {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 30px;
        }

        .sbox {
            flex: 1;
            position: relative;
            max-width: 420px;
        }

        .sbox input {
            width: 100%;
            height: 44px;
            background: var(--white);
            border: 1.5px solid var(--bdr);
            border-radius: 12px;
            padding: 0 14px 0 42px;
            font-size: 14px;
            font-family: 'DM Sans', sans-serif;
            color: var(--ink);
            outline: none;
            box-shadow: 0 2px 10px rgba(10, 22, 40, 0.05);
            transition: border-color 0.18s, box-shadow 0.18s;
        }

        .sbox input::placeholder {
            color: #b8c0cc;
        }

        .sbox input:focus {
            border-color: var(--teal);
            box-shadow: 0 0 0 3px rgba(26, 154, 138, 0.10);
        }

        .sico {
            position: absolute;
            left: 13px;
            top: 50%;
            transform: translateY(-50%);
            color: #b8c0cc;
            pointer-events: none;
            display: flex;
        }

        /* BULK ACTIONS */
        .bulk-actions {
            display: none;
            align-items: center;
            gap: 0.5rem;
            justify-content: space-between;
            margin-bottom: 20px;
            padding: 12px 20px;
            background: var(--white);
            border: 1px solid var(--bdr);
            border-radius: 12px;
            box-shadow: 0 4px 24px rgba(10, 22, 40, 0.06);
        }

        .bulk-actions.show {
            display: flex;
        }

        .bulk-actions-info {
            font-size: 14px;
            font-weight: 600;
            color: var(--ink);
        }

        .bulk-actions-info i {
            color: var(--teal);
            margin-right: 6px;
        }

        .bulk-action-btn {
            padding: 7px 14px;
            border: 1px solid var(--bdr);
            border-radius: 10px;
            font-size: 12px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 6px;
            background: white;
            color: var(--ink);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.02);
        }

        .bulk-duplicate-btn:hover {
            background: #f0faf9;
            border-color: var(--teal);
            color: var(--teal);
        }

        .bulk-delete-btn:hover {
            background: #fff5f5;
            border-color: #d94040;
            color: #d94040;
        }

        .bulk-clear-btn:hover {
            background: #f8fafc;
            border-color: var(--gray);
        }

        /* TABLE */
        .tbl-wrap {
            background: var(--white);
            border: 1px solid var(--bdr);
            border-radius: 18px;
            overflow: visible;
            box-shadow: 0 4px 24px rgba(10, 22, 40, 0.06);
        }

        table {
            width: 100% !important;
            border-collapse: separate;
            border-spacing: 0;
            table-layout: auto;
        }

        thead {
            background: #f8fafc;
        }

        thead tr {
            border-bottom: 1px solid #e2e8ef;
            background: #f8fafc;
        }

        thead th {
            position: relative;
            padding: 14px 16px;
            text-align: left;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: 0.1px;
            text-transform: none;
            color: #475569;
            line-height: 1.4;
            vertical-align: middle;
            background: #f8fafc;
            border-bottom: 1px solid #e2e8ef;
            border-right: 1px solid #f1f5f9;
        }

        thead th.sortable {
            transition: background 0.15s;
        }

        thead th.sortable:hover {
            background: #e9e9e9;
        }

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

        thead th:hover .col-menu-btn {
            display: flex;
        }

        .col-menu-btn:hover {
            background: rgba(0, 0, 0, 0.05);
            color: var(--teal);
        }

        .header-dropdown {
            position: absolute;
            top: 100%;
            right: 0;
            background: white;
            border: 1px solid var(--bdr);
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.12);
            z-index: 1000;
            padding: 8px;
            min-width: 180px;
            display: none;
            text-transform: none;
            letter-spacing: normal;
            font-weight: 500;
        }

        .header-dropdown.show {
            display: block;
        }

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

        .header-dropdown-item:hover {
            background: #f3f3f3;
        }

        .header-dropdown-item svg {
            width: 14px;
            height: 14px;
            color: var(--gray);
        }


        thead th:first-child {
            width: 46px;
            padding-left: 22px;
            border-top-left-radius: 17px;
        }

        thead th:last-child {
            border-top-right-radius: 17px;
        }

        thead th.right {
            text-align: center;
            padding-right: 22px;
        }

        tbody tr {
            border-bottom: 1px solid #f1f5f9;
            transition: background 0.15s ease;
            background: #ffffff;
        }

        tbody tr:last-child {
            border-bottom: none;
        }

        tbody tr:hover {
            background: #f8fafc;
        }

        tbody td {
            position: relative;
            padding: 14px 16px;
            vertical-align: middle;
            font-size: 13.5px;
            color: #334155;
            border-bottom: 1px solid #f1f5f9;
        }

        tbody tr:last-child td {
            border-bottom: none;
        }

        tbody td:first-child {
            padding-left: 22px;
        }

        tbody tr:last-child td:first-child {
            border-bottom-left-radius: 18px;
        }

        tbody tr:last-child td:last-child {
            border-bottom-right-radius: 18px;
        }

        input[type=checkbox] {
            width: 15px;
            height: 15px;
            accent-color: var(--teal);
            cursor: pointer;
        }

        .id-chip,
        .name-display,
        .email-display {
            font-size: 10.5px;
            font-weight: 700;
            font-family: monospace;
            letter-spacing: 0.5px;
            color: #071917;
            background: #e7f7f51a;
            border: 1px solid rgba(7, 25, 23, 0.15);
            padding: 3px 8px;
            border-radius: 6px;
            cursor: pointer;
            transition: background 0.2s;
            display: inline-block;
        }

        .id-chip:hover,
        .name-display:hover,
        .email-display:hover {
            background: #e7f7f5;
            border-color: #071917;
        }

        .name-display,
        .email-display {
            font-family: 'DM Sans', sans-serif;
            letter-spacing: 0.2px;
        }

        .name-display {
            font-size: 13px;
        }

        .email-display {
            font-size: 10px;
            font-weight: 500;
            color: #1a9a8a;
            white-space: normal;
            word-break: break-all;
            max-width: 100%;
            display: inline-block;
        }

        .code-input {
            width: 80px;
            padding: 2px 4px;
            border: 1px solid var(--bdr);
            border-radius: 4px;
            font-family: monospace;
            font-size: 10.5px;
            text-transform: uppercase;
        }

        .trip-name {
            font-size: 16px;
            font-weight: 700;
            color: var(--ink);
            line-height: 1.3;
        }

        .trip-dest {
            display: flex;
            align-items: center;
            gap: 4px;
            font-size: 12px;
            color: var(--gray2);
            margin-top: 3px;
        }

        .trip-dest svg {
            width: 11px;
            height: 11px;
            color: var(--teal);
            flex-shrink: 0;
        }

        .trip-date {
            font-size: 13px;
            color: var(--gray);
            font-weight: 500;
        }

        .trip-range {
            font-size: 11.5px;
            color: var(--gray2);
            margin-top: 2px;
        }

        /* STATUS SELECTOR - ANCHO COMPACTO */
        .status-select {
            display: block;
            width: 115px !important;
            max-width: 115px !important;
            box-sizing: border-box !important;
            padding: 5px 20px 5px 8px !important;
            border: 1px solid #e2e8ef;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 600;
            line-height: 1.3;
            cursor: pointer;
            transition: all 0.15s ease;
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            background-color: #ffffff;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%2364748b' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
            background-position: right 6px center;
            background-repeat: no-repeat;
            background-size: 12px;
            color: #334155;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.03);
            margin: 0 auto;
            text-overflow: ellipsis;
            white-space: nowrap;
            overflow: hidden;
        }

        .status-select:focus {
            outline: none;
            border-color: #94a3b8;
            box-shadow: 0 0 0 2px rgba(148, 163, 184, 0.15);
        }

        .status-select:hover {
            border-color: #cbd5e1;
            background-color: #f8fafc;
        }

        /* COLORES PASTEL SOBRIOS Y ELEGANTES PARA ESTADOS DE VIAJE */
        .status-color-cyan {
            background-color: #ecfeff !important;
            color: #0891b2 !important;
            border-color: #a5f3fc !important;
        }

        .status-color-blue {
            background-color: #eff6ff !important;
            color: #1d4ed8 !important;
            border-color: #bfdbfe !important;
        }

        .status-color-purple {
            background-color: #faf5ff !important;
            color: #7e22ce !important;
            border-color: #e9d5ff !important;
        }

        .status-color-green {
            background-color: #f0fdf4 !important;
            color: #15803d !important;
            border-color: #bbf7d0 !important;
        }

        .status-color-orange {
            background-color: #fff7ed !important;
            color: #c2410c !important;
            border-color: #ffedd5 !important;
        }

        .status-color-pink {
            background-color: #fff1f2 !important;
            color: #be123c !important;
            border-color: #fecdd3 !important;
        }

        .status-color-slate {
            background-color: #f8fafc !important;
            color: #475569 !important;
            border-color: #cbd5e1 !important;
        }

        /* EFECTO HOVER PARA EDITAR ID, NOMBRE Y CORREO DEL VIAJERO */
        .id-container,
        .client-name,
        .client-email-container {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 3px 7px;
            margin: -3px -7px;
            border-radius: 6px;
            border: 1px dashed transparent;
            transition: all 0.18s ease;
            cursor: pointer;
        }

        .id-container:hover,
        .client-name:hover,
        .client-email-container:hover {
            background-color: #f1f5f9;
            border-color: #cbd5e1;
        }

        .id-container::after,
        .client-name::after,
        .client-email-container::after {
            content: "\f304";
            font-family: "Font Awesome 5 Free";
            font-weight: 900;
            font-size: 10px;
            color: #94a3b8;
            opacity: 0;
            transition: opacity 0.15s ease, transform 0.15s ease;
            transform: scale(0.85);
            margin-left: 2px;
        }

        .id-container:hover::after,
        .client-name:hover::after,
        .client-email-container:hover::after {
            opacity: 1;
            transform: scale(1);
            color: var(--teal);
        }

        .name-display.traveler-name-clean {
            font-size: 13.5px;
            font-weight: 600;
            color: #0f172a;
            background: transparent !important;
            border: none !important;
            padding: 0 !important;
            border-radius: 0 !important;
            font-family: inherit;
        }

        .email-display.traveler-email-clean {
            font-size: 12px;
            font-weight: 400;
            color: #64748b !important;
            background: transparent !important;
            border: none !important;
            padding: 0 !important;
            border-radius: 0 !important;
            font-family: inherit;
        }

        #mainTable thead th.right,
        #mainTable tbody td.acts-cell {
            width: 210px !important;
            min-width: 210px !important;
            max-width: 210px !important;
            text-align: center !important;
        }

        .acts-cell {
            text-align: center !important;
        }

        .acts {
            display: flex;
            align-items: center;
            gap: 8px;
            justify-content: center;
            width: 100%;
        }

        .abt {
            width: 36px;
            height: 36px;
            border-radius: 9px;
            border: 1.5px solid var(--bdr);
            background: transparent;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--gray);
            cursor: pointer;
            position: relative;
            transition: all 0.16s;
        }

        .abt svg {
            width: 16px;
            height: 16px;
        }

        .abt i {
            font-size: 15px;
        }

        .abt .btn-txt {
            display: none !important;
        }

        .abt:hover {
            transform: translateY(-1px);
        }

        .abt.view:hover {
            border-color: var(--teal);
            color: var(--teal);
            background: rgba(26, 154, 138, 0.07);
            box-shadow: 0 3px 10px rgba(26, 154, 138, 0.18);
        }

        .abt.edit:hover {
            border-color: #7c3aed;
            color: #7c3aed;
            background: rgba(124, 58, 237, 0.07);
            box-shadow: 0 3px 10px rgba(124, 58, 237, 0.18);
        }

        .abt.share:hover {
            border-color: #2878d4;
            color: #2878d4;
            background: rgba(40, 120, 212, 0.07);
            box-shadow: 0 3px 10px rgba(40, 120, 212, 0.18);
        }

        .abt.share-edit:hover {
            border-color: #ea580c;
            color: #ea580c;
            background: rgba(234, 88, 12, 0.07);
            box-shadow: 0 3px 10px rgba(234, 88, 12, 0.18);
        }

        .abt.del:hover {
            border-color: #d94040;
            color: #d94040;
            background: rgba(217, 64, 64, 0.07);
            box-shadow: 0 3px 10px rgba(217, 64, 64, 0.18);
        }

        .abt::after {
            content: attr(data-tip);
            position: absolute;
            bottom: calc(100% + 6px);
            left: 50%;
            transform: translateX(-50%);
            background: var(--ink);
            color: white;
            font-size: 10px;
            font-weight: 600;
            padding: 3px 8px;
            border-radius: 6px;
            white-space: nowrap;
            pointer-events: none;
            opacity: 0;
            transition: opacity 0.15s;
        }

        .abt:hover::after {
            opacity: 1;
            z-index: 1000;
        }

        .empty {
            display: none;
            text-align: center;
            padding: 72px 24px;
        }

        .e-ring {
            width: 72px;
            height: 72px;
            border-radius: 50%;
            background: var(--accent-light);
            border: 1.5px dashed var(--accent-border);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 18px;
        }

        .e-ring svg {
            width: 30px;
            height: 30px;
            color: var(--accent);
        }

        .empty h3 {
            font-family: Inter, sans-serif;
            font-weight: 600;
            font-size: 20px;
            color: var(--ink);
            margin-bottom: 7px;
        }

        .empty p {
            font-size: 13px;
            color: var(--gray);
        }

        .bar-cell {
            width: 4px;
            padding: 0 !important;
        }

        .bar-inner {
            width: 4px;
            height: 100%;
            border-radius: 2px;
        }

        .mobile-search-wrapper {
            display: none;
        }

        .mobile-hamburger-btn {
            display: none;
            width: 36px;
            height: 36px;
            border-radius: 8px;
            border: none;
            background: var(--accent);
            color: white;
            font-size: 16px;
            cursor: pointer;
            align-items: center;
            justify-content: center;
            margin-right: 8px;
            flex-shrink: 0;
            box-shadow: 0 2px 8px rgba(15, 42, 58, 0.2);
        }

        .mobile-sidebar-close {
            display: none;
            background: transparent;
            border: none;
            color: white;
            font-size: 18px;
            cursor: pointer;
            padding: 4px 8px;
        }

        .sidebar-backdrop {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: rgba(15, 42, 58, 0.4);
            backdrop-filter: blur(4px);
            z-index: 2999;
            transition: opacity 0.3s ease;
        }

        @media (max-width: 768px) {
            .mobile-hamburger-btn {
                display: inline-flex !important;
            }

            .mobile-sidebar-close {
                display: inline-flex !important;
            }

            .sidebar-backdrop.active {
                display: block !important;
            }

            .dashboard-sidebar {
                position: fixed !important;
                top: 0 !important;
                left: -280px !important;
                width: 260px !important;
                height: 100vh !important;
                z-index: 3000 !important;
                transition: left 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
                box-shadow: 4px 0 20px rgba(0,0,0,0.2) !important;
                display: flex !important;
                flex-direction: column !important;
            }

            .dashboard-sidebar.mobile-open {
                left: 0 !important;
            }

            /* VIAJES RECIENTES: Desplazamiento horizontal hacia la izquierda/derecha en móvil */
            .quick-access-grid {
                display: flex !important;
                flex-direction: row !important;
                overflow-x: auto !important;
                scroll-snap-type: x mandatory !important;
                gap: 12px !important;
                padding-bottom: 12px !important;
                -webkit-overflow-scrolling: touch;
                scrollbar-width: none;
            }

            .quick-access-grid::-webkit-scrollbar {
                display: none;
            }

            .quick-access-card {
                flex: 0 0 260px !important;
                width: 260px !important;
                min-width: 260px !important;
                max-width: 260px !important;
                scroll-snap-align: start !important;
                margin-bottom: 0 !important;
            }

            /* Mobile Search Bar below Recent Trips */
            .topbar-search {
                display: none !important;
            }

            .mobile-search-wrapper {
                display: block !important;
                margin: 6px 0 16px 0 !important;
            }

            /* Responsive Crear Viaje button with 13px font size */
            .btn-topbar-create {
                height: 36px !important;
                padding: 0 14px !important;
                font-size: 13px !important;
                margin-right: 0 !important;
                gap: 6px !important;
            }

            .btn-topbar-create i {
                font-size: 12px !important;
            }

            .btn-topbar-create span {
                font-size: 13px !important;
            }

            /* Hide Notification bell on mobile so Crear Viaje shifts to that spot */
            .noti-wrapper {
                display: none !important;
            }

            /* Profile Trigger on top right without profile name */
            .profile-trigger .profile-name {
                display: none !important;
            }

            .profile-trigger {
                padding: 2px 4px !important;
                gap: 4px !important;
            }

            /* Wider Trip Row Cards with Full Width */
            .quick-access-section {
                padding: 16px 12px 0 !important;
            }

            .trips-list-section {
                padding: 16px 12px 0 !important;
            }

            .tbl-wrap {
                width: 100% !important;
                padding: 0 !important;
            }

            .trip-row {
                width: 100% !important;
                box-sizing: border-box !important;
                padding: 18px 16px 14px 16px !important;
                margin-bottom: 16px !important;
            }

            .topbar {
                padding: 0 10px;
            }

            .uname {
                display: none;
            }

            .btn-out {
                font-size: 11px;
                padding: 6px 12px;
                gap: 4px;
            }

            .btn-out svg {
                width: 12px;
                height: 12px;
            }

            .topbar-right {
                flex-direction: row-reverse;
                gap: 2px;
            }

            .btn-help {
                display: none !important;
            }

            .ubadge {
                padding: 0;
                border: none;
                margin: 0;
            }

            /* Mobile Trips Card Layout */
            .toolbar {
                flex-direction: column;
                align-items: stretch;
            }

            .sbox {
                max-width: 100%;
            }

            .toolbar .btn-create {
                display: none !important;
            }

            .btn-mobile-only {
                display: flex !important;
                margin-left: auto;
            }

            .hero-header-mobile {
                flex-wrap: wrap;
            }

            .tbl-wrap {
                background: transparent;
                border: none;
                box-shadow: none;
                border-radius: 0;
            }

            table,
            thead,
            tbody,
            th,
            td,
            tr {
                display: block;
            }

            thead {
                display: none;
            }

            .trip-row {
                background: white;
                border: 1px solid var(--bdr);
                border-radius: 12px;
                margin-bottom: 16px;
                position: relative;
                padding: 20px 20px 16px;
                box-shadow: 0 4px 12px rgba(10, 22, 40, 0.04);
            }

            .trip-row:hover {
                background: white;
                transform: translateY(-2px);
                box-shadow: 0 8px 16px rgba(10, 22, 40, 0.06);
            }

            /* Hide checkbox and ID on mobile */
            .trip-row>td:nth-child(1),
            .trip-row>td:nth-child(3) {
                display: none;
            }

            /* Status Band */
            .bar-cell {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 5px;
                padding: 0 !important;
                border-radius: 12px 12px 0 0;
                overflow: hidden;
            }

            .bar-inner {
                width: 100%;
                height: 100%;
                border-radius: 0;
            }

            /* Header Line: Title + Status */
            .trip-row>td:nth-child(4) {
                padding: 0 0 12px 0;
                border: none;
                display: flex;
                flex-direction: column;
                gap: 8px;
            }

            .trip-name {
                font-size: 18px;
                line-height: 1.2;
                display: flex;
                align-items: flex-start;
                justify-content: space-between;
                gap: 12px;
            }

            /* Status Pill Re-styling for Card Header */
            .trip-row>td:nth-child(7) {
                position: relative;
                top: 0;
                right: 0;
                padding: 4px 0 0 0;
                border: none;
                width: auto;
                z-index: 10;
                margin-top: -8px;
            }

            .status-select {
                padding: 4px 26px 4px 10px;
                font-size: 11.5px;
                border-radius: 6px;
                pointer-events: auto;
                width: fit-content;
            }

            /* Destiny */
            .trip-dest {
                font-size: 13.5px;
                margin-top: 0;
            }

            .trip-dest svg {
                width: 13px;
                height: 13px;
            }

            /* Info Text Layout */
            .mobile-info-row {
                display: flex !important;
                flex-direction: column;
                gap: 3px;
                margin-top: 14px;
            }

            .mobile-client-name {
                font-size: 14px;
                font-weight: 600;
                color: var(--ink);
            }

            .mobile-client-email {
                font-size: 13px;
                color: var(--teal);
                text-decoration: none;
                display: inline-block;
            }

            .mobile-trip-date {
                font-size: 13px;
                color: var(--gray);
                font-weight: 500;
                margin-top: 2px;
            }

            /* Overwrite logic to hide old rows and use new mobile info row */
            .trip-row>td:nth-child(5),
            .trip-row>td:nth-child(6) {
                display: none;
            }

            /* Owner/Views Block */
            .trip-row>td:nth-child(8) {
                padding: 8px 0 0 0;
                margin-top: 8px;
                border: none;
                display: flex;
                align-items: center;
            }

            /* Action Buttons Block: Full Width 4 Buttons Grid */
            .acts-cell {
                padding: 12px 0 0 0 !important;
                margin-top: 12px !important;
                border-top: 1px solid #f0f2f5 !important;
                display: flex !important;
                width: 100% !important;
                max-width: 100% !important;
                box-sizing: border-box !important;
            }

            .acts {
                display: flex !important;
                align-items: center !important;
                justify-content: space-between !important;
                width: 100% !important;
                gap: 6px !important;
                flex-wrap: nowrap !important;
            }

            .acts .abt.view,
            .acts .abt.edit,
            .acts .abt.share {
                flex: 0 0 80px !important;
                width: 80px !important;
                min-width: 80px !important;
                max-width: 80px !important;
            }

            .acts .acts-menu-container {
                flex: 0 0 40px !important;
                width: 40px !important;
                min-width: 40px !important;
                max-width: 40px !important;
            }

            .acts .acts-menu-container .abt.more {
                flex: 0 0 40px !important;
                width: 40px !important;
                min-width: 40px !important;
                max-width: 40px !important;
                padding: 0 !important;
            }

            .acts .abt {
                width: 100% !important;
                height: 34px !important;
                border-radius: 8px !important;
                font-size: 11px !important;
                font-weight: 600 !important;
                font-family: 'DM Sans', sans-serif !important;
                display: inline-flex !important;
                align-items: center !important;
                justify-content: center !important;
                gap: 4px !important;
                color: var(--gray) !important;
                border: 1.5px solid var(--bdr) !important;
                background: white !important;
                padding: 0 4px !important;
                white-space: nowrap !important;
                box-sizing: border-box !important;
            }

            .dashboard-topbar {
                padding: 0 15px !important;
            }

            .abt .btn-txt {
                display: inline-block !important;
                font-size: 11px !important;
                font-weight: 600 !important;
                margin-left: 3px !important;
            }

            .abt.share-edit {
                display: none !important;
            }

            .acts .acts-menu-container .abt.more {
                width: 100% !important;
            }

            .abt svg {
                width: 13px;
                height: 13px;
                flex-shrink: 0;
            }

            .acts-menu-container {
                flex: 1;
                display: flex;
            }

            .acts-menu-container .abt {
                flex: 1;
                width: 100%;
            }

            .trip-row.menu-open {
                z-index: 100 !important;
            }

            /* Modal overlay z-index priority over rows */
            .modal,
            .modal-backdrop,
            .modal-overlay,
            .modal-container,
            #manageStatusesModal,
            #sharingModal,
            #collaboratorsModal,
            #transferModal,
            #tripInfoModal,
            #createTripModal {
                z-index: 999999 !important;
            }

            /* Empty state adaptation */
            .empty {
                padding: 40px 15px;
            }
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
            transition: color 0.2s, background-color 0.2s;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .segment-item:not(.active):hover {
            background: rgba(0, 0, 0, 0.045);
            border-radius: 9px;
            color: var(--ink);
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
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 0;
        }

        /* TRIAL BANNER */
        .trial-banner {
            background: linear-gradient(135deg, #1a9a8a 0%, #0c4a5b 100%);
            border-radius: 16px;
            padding: 16px 24px;
            margin-bottom: 30px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            color: white;
            box-shadow: 0 10px 25px rgba(26, 154, 138, 0.15);
            animation: slideDownFade 0.5s ease;
        }

        @keyframes slideDownFade {
            from {
                transform: translateY(-10px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .trial-banner-content {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .trial-icon {
            width: 44px;
            height: 44px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }

        .trial-text h4 {
            font-family: 'Barlow Condensed', sans-serif;
            font-weight: 800;
            font-size: 18px;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .trial-text p {
            font-size: 13.5px;
            margin: 2px 0 0;
            opacity: 0.9;
        }

        .trial-cta {
            display: flex;
            gap: 12px;
        }

        .btn-trial-upgrade {
            background: white;
            color: #0c4a5b;
            border: none;
            padding: 10px 20px;
            border-radius: 10px;
            font-weight: 700;
            font-size: 13px;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
        }

        .btn-trial-upgrade:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
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
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
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
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* ════════════════════════════════════════
                   NUEVO DISEÑO DASHBOARD (ESTILO GOOGLE DRIVE)
                ════════════════════════════════════════ */
        body {
            background-color: var(--accent) !important;
            margin: 0 !important;
            padding: 0 !important;
            min-height: 100vh !important;
            display: flex !important;
            flex-direction: column !important;
        }

        .dashboard-wrapper {
            display: flex;
            align-items: stretch;
            justify-content: center;
            min-height: 100vh;
            width: 100%;
            padding: 0;
            box-sizing: border-box;
        }

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

        .sidebar-logo {
            margin-bottom: 30px;
            display: flex;
            align-items: center;
            padding-left: 8px;
        }

        .sidebar-logo img {
            height: 32px;
            width: auto;
            filter: brightness(0) invert(1);
        }

        .sidebar-action {
            margin-bottom: 30px;
        }

        .btn-sidebar-create {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            background: #ffffff;
            color: var(--accent);
            border: none;
            padding: 14px 24px;
            border-radius: 50px;
            font-weight: 700;
            font-size: 15px;
            font-family: 'Barlow', sans-serif;
            width: 100%;
            cursor: pointer;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
            transition: all 0.25s ease;
        }

        .btn-sidebar-create:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.25);
            background: #f8fafc;
        }

        .sidebar-nav {
            display: flex;
            flex-direction: column;
            gap: 8px;
            flex: 1;
        }

        .sidebar-link {
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
        }

        .sidebar-link:hover {
            color: #ffffff;
            background: rgba(255, 255, 255, 0.08);
        }

        .sidebar-link.active {
            background: #ffffff !important;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05) !important;
        }

        .sidebar-link.active,
        .sidebar-link.active i,
        .sidebar-link.active span {
            color: var(--sidebar-accent-color) !important;
        }

        .sidebar-link i {
            font-size: 18px;
            width: 20px;
            text-align: center;
        }

        .sidebar-link.disabled {
            opacity: 0.5;
            cursor: not-allowed !important;
            pointer-events: none;
            user-select: none;
        }

        .sidebar-link.disabled:hover {
            background: transparent !important;
            color: rgba(255, 255, 255, 0.7) !important;
        }

        .sidebar-badge-soon {
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

        /* PIE DE BARRA LATERAL (USO DEL PLAN) */
        .sidebar-footer {
            border-top: 1px solid rgba(255, 255, 255, 0.15);
            padding-top: 24px;
            margin-top: 20px;
        }

        .footer-title {
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 1.5px;
            color: rgba(255, 255, 255, 0.4);
            margin-bottom: 12px;
            text-transform: uppercase;
        }

        .usage-item {
            margin-bottom: 16px;
        }

        .usage-label-row {
            display: flex;
            justify-content: space-between;
            font-size: 12px;
            font-weight: 600;
            color: rgba(255, 255, 255, 0.9);
            margin-bottom: 6px;
        }

        .usage-progress-bar {
            height: 6px;
            background: rgba(255, 255, 255, 0.15);
            border-radius: 10px;
            overflow: hidden;
        }

        .usage-progress-fill {
            height: 100%;
            background: #ffffff;
            border-radius: 10px;
            transition: width 0.3s ease;
        }

        .usage-upgrade-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: 15px;
        }

        .plan-badge {
            font-size: 11px;
            font-weight: 800;
            padding: 4px 8px;
            background: rgba(255, 255, 255, 0.15);
            color: #ffffff;
            border-radius: 6px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .btn-upgrade-link {
            font-size: 12px;
            font-weight: 700;
            color: #ffffff;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 6px;
            transition: opacity 0.2s ease;
        }

        .btn-upgrade-link:hover {
            opacity: 0.8;
            text-decoration: underline;
        }

        /* ÁREA DE CONTENIDO PRINCIPAL */
        .dashboard-main {
            flex: 1;
            display: flex;
            flex-direction: column;
            background: #ffffff;
            border-radius: 0;
            overflow: hidden;
        }

        /* BARRA SUPERIOR */
        .dashboard-topbar {
            height: 80px;
            border-bottom: 1px solid #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 40px;
            flex-shrink: 0;
        }

        .topbar-search {
            flex: 1;
            max-width: 500px;
        }

        .search-box-wrapper {
            position: relative;
            width: 100%;
        }

        .search-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #64748b;
            font-size: 14px;
            pointer-events: none;
        }

        .search-box-wrapper input {
            width: 100%;
            height: 44px;
            background: #f1f5f9;
            border: 1px solid transparent;
            border-radius: 50px;
            padding: 0 20px 0 46px;
            font-size: 14px;
            font-family: 'Barlow', sans-serif;
            color: #0f172a;
            transition: all 0.2s ease;
        }

        .search-box-wrapper input:focus {
            background: #ffffff;
            border-color: var(--accent);
            box-shadow: 0 0 0 4px var(--accent-light);
            outline: none;
        }

        .topbar-actions {
            display: flex;
            align-items: center;
            gap: 16px;
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

        .btn-topbar-create {
            background: var(--accent);
            color: #ffffff;
            border: none;
            padding: 12px 28px;
            border-radius: 50px;
            font-weight: 700;
            font-size: 15px;
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

        .noti-wrapper {
            position: relative;
        }

        .noti-dropdown {
            position: absolute;
            top: calc(100% + 10px);
            right: 0;
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            width: 280px;
            overflow: hidden;
            z-index: 1000;
            border: 1px solid #e2e8ef;
        }

        .noti-dropdown-header {
            padding: 12px 16px;
            border-bottom: 1px solid #e2e8ef;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .noti-dropdown-header span {
            font-size: 13px;
            font-weight: 700;
            color: var(--dark);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .noti-dropdown-header button {
            border: none;
            background: transparent;
            color: var(--teal);
            font-size: 11px;
            font-weight: 600;
            cursor: pointer;
        }

        .noti-loading {
            padding: 20px;
            text-align: center;
            color: var(--gray2);
            font-size: 12px;
        }

        .profile-dropdown-wrapper {
            position: relative;
        }

        .profile-trigger {
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            padding: 4px 16px 4px 4px;
            border-radius: 50px;
            border: 1px solid #e2e8ef;
            background: #ffffff;
            transition: all 0.2s ease;
        }

        .profile-trigger:hover {
            border-color: var(--accent);
            background: var(--accent-light);
        }

        .profile-trigger .avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: var(--avatar-gradient);
            color: #ffffff;
            font-weight: 700;
            font-size: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            border: none !important;
            letter-spacing: 0.5px;
        }

        .profile-trigger .avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .profile-name {
            font-size: 14px;
            font-weight: 600;
            color: #0f172a;
        }

        .profile-trigger i {
            font-size: 10px;
            color: #64748b;
        }

        .profile-menu {
            position: absolute;
            top: calc(100% + 10px);
            right: 0;
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            width: 180px;
            overflow: hidden;
            z-index: 1000;
            border: 1px solid #e2e8ef;
        }

        .profile-menu a,
        .profile-menu button {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 16px;
            color: var(--dark);
            text-decoration: none;
            font-size: 13px;
            font-weight: 500;
            transition: background 0.2s;
            width: 100%;
            border: none;
            background: transparent;
            text-align: left;
            font-family: 'Barlow', sans-serif;
            cursor: pointer;
        }

        .profile-menu a:hover,
        .profile-menu button:hover {
            background: #f8fafc;
        }

        .profile-menu .btn-logout {
            color: #c0392b;
            border-top: 1px solid #e2e8ef;
        }

        /* SCROLL CONTAINER */
        .dashboard-content-scroll {
            flex: 1;
            overflow-y: auto;
            padding-bottom: 60px;
        }

        /* SECCIONES */
        .quick-access-section {
            padding: 35px 40px 0;
        }

        .trips-list-section {
            padding: 40px 40px 0;
        }

        .section-title {
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 1.5px;
            color: #64748b;
            margin-bottom: 20px;
            text-transform: uppercase;
        }

        /* FOLDER CARDS (RECENT TRIPS) */
        .quick-access-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 20px;
        }

        .quick-access-card {
            background: #f8fafc;
            border: 1px solid #e2e8ef;
            border-radius: 16px;
            padding: 20px;
            cursor: pointer;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .quick-access-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: var(--accent);
            opacity: 0;
            transition: opacity 0.2s ease;
        }

        .quick-access-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
            border-color: var(--accent);
        }

        .quick-access-card:hover::before {
            opacity: 1;
        }

        .status-badge {
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
            border: 1.5px solid transparent;
            text-transform: uppercase;
            display: inline-flex;
            align-items: center;
        }

        /* FEATURED FOLDER CARD (primera de la lista) */
        .quick-access-card.featured {
            background: var(--accent);
            color: #ffffff;
            border-color: transparent;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
        }

        .quick-access-card.featured::before {
            display: none;
        }

        .quick-access-card.featured:hover {
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.18);
        }

        .card-folder-body {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .card-folder-header-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .card-folder-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            background: var(--accent-light);
            color: var(--accent);
            transition: all 0.2s ease;
        }

        .quick-access-card.featured .card-folder-icon {
            background: rgba(255, 255, 255, 0.2);
            color: #ffffff;
        }

        .card-folder-avatars {
            display: flex;
            align-items: center;
        }

        .avatar-mini {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background: #ffffff;
            border: 2px solid #f8fafc;
            margin-left: -8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 9px;
            font-weight: 700;
            color: var(--accent);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .avatar-mini:first-child {
            margin-left: 0;
        }

        .quick-access-card.featured .avatar-mini {
            border-color: var(--accent);
            background: rgba(255, 255, 255, 0.2);
            color: #ffffff;
        }

        .card-folder-info {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .card-folder-title {
            font-size: 16px;
            font-weight: 700;
            color: #0f172a;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .quick-access-card.featured .card-folder-title {
            color: #ffffff;
        }

        .card-folder-subtitle {
            font-size: 13px;
            font-weight: 500;
            color: #64748b;
        }

        .quick-access-card.featured .card-folder-subtitle {
            color: rgba(255, 255, 255, 0.85);
        }

        .card-folder-meta {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: 8px;
            border-top: 1px solid rgba(0, 0, 0, 0.05);
            padding-top: 12px;
        }

        .quick-access-card.featured .card-folder-meta {
            border-top-color: rgba(255, 255, 255, 0.1);
        }

        .card-folder-date {
            font-size: 12px;
            color: #64748b;
            font-weight: 500;
        }

        .quick-access-card.featured .card-folder-date {
            color: rgba(255, 255, 255, 0.75);
        }

        /* MEJORAS DE LA TABLA (ESTILO INSPIRADO EN REFERENCIA) */
        .tbl-wrap {
            border-radius: 14px;
            border: 1px solid #eaecf0;
            box-shadow: 0 1px 3px rgba(16, 24, 40, 0.05), 0 1px 2px rgba(16, 24, 40, 0.04);
            overflow: hidden;
            background: #ffffff;
        }

        #mainTable {
            border-collapse: separate;
            border-spacing: 0;
            width: 100%;
        }

        #mainTable thead th {
            background: #f8fafc;
            color: #475569;
            font-size: 12.5px;
            font-weight: 600;
            letter-spacing: 0.1px;
            text-transform: none;
            border-bottom: 1px solid #eaecf0;
            border-right: 1px solid #f1f5f9;
            padding: 13px 16px;
            position: relative;
        }

        #mainTable thead th:last-child {
            border-right: none;
        }

        #mainTable thead th.sortable {
            cursor: pointer;
            transition: all 0.15s ease;
        }

        #mainTable thead th.sortable:hover {
            background-color: #f1f5f9 !important;
            color: #0f172a !important;
        }

        #mainTable thead th.sortable .col-menu-btn {
            display: inline-flex !important;
            position: static;
            transform: none;
            vertical-align: middle;
            margin-left: 6px;
            opacity: 0.4;
            color: currentColor;
            transition: opacity 0.2s ease;
        }

        #mainTable thead th.sortable:hover .col-menu-btn {
            opacity: 1;
            color: var(--accent);
        }

        #mainTable tbody tr.trip-row {
            border-bottom: 1px solid #f1f5f9;
            transition: background 0.15s ease;
        }

        #mainTable tbody tr.trip-row:last-child {
            border-bottom: none;
        }

        #mainTable tbody tr.trip-row:hover {
            background: #f8fafc !important;
        }

        #mainTable tbody td {
            padding: 14px 16px;
            color: #334155;
            font-size: 13.5px;
            border-bottom: 1px solid #f1f5f9;
        }

        .trip-name .title-display {
            font-weight: 600;
            color: #0f172a;
            font-size: 14px;
        }

        .trip-dest {
            font-size: 12px;
            color: #64748b;
            margin-top: 4px;
        }

        /* RESPONSIVIDAD PARA EL ESCRITORIO DEL DASHBOARD */
        @media (max-width: 1024px) and (min-width: 769px) {
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

            .sidebar-logo {
                margin-bottom: 15px;
            }

            .sidebar-action {
                margin-bottom: 15px;
            }

            .sidebar-nav {
                flex-direction: row;
                flex-wrap: wrap;
                gap: 8px;
                margin-bottom: 15px;
            }

            .sidebar-link {
                padding: 8px 16px;
                font-size: 13px;
            }

            .sidebar-footer {
                display: none;
            }

            .dashboard-main {
                border-radius: 0 !important;
            }

            .dashboard-topbar {
                padding: 0 20px;
                height: 72px;
            }

            .quick-access-section {
                padding: 20px 20px 0;
            }

            .trips-list-section {
                padding: 25px 20px 0;
            }
        }
    </style>
@endpush

@section('content')

    @php
        $userCustomStatuses = Auth::check() ? Auth::user()->getCustomStatuses() : [
            'draft' => ['label' => 'Diseño', 'color' => 'cyan'],
            'sent' => ['label' => 'Planeado', 'color' => 'blue'],
            'reserved' => ['label' => 'Reservado', 'color' => 'purple'],
            'completed' => ['label' => 'Finalizado', 'color' => 'green'],
            'discarded' => ['label' => 'Descartado', 'color' => 'pink'],
        ];

        function getStatusBand($status)
        {
            $colors = [
                'draft' => 'linear-gradient(180deg,#2878d4,#60a5fa)',
                'sent' => 'linear-gradient(180deg,#0ea5e9,#7dd3fc)',
                'reserved' => 'linear-gradient(180deg,#16a34a,#4ade80)',
                'completed' => 'linear-gradient(180deg,#0d9488,#2dd4bf)',
                'discarded' => 'linear-gradient(180deg,#d94040,#f87171)',
            ];
            return $colors[$status] ?? 'linear-gradient(180deg,#a8b2bc,#cbd5e1)';
        }
        function getStatusLabel($status, $customMap = [])
        {
            if (!empty($customMap[$status])) {
                return is_array($customMap[$status]) ? ($customMap[$status]['label'] ?? $status) : $customMap[$status];
            }
            $labels = [
                'draft' => 'Diseño',
                'sent' => 'Planeado',
                'reserved' => 'Reservado',
                'completed' => 'Finalizado',
                'discarded' => 'Descartado',
            ];
            return $labels[$status] ?? ucfirst($status);
        }
    @endphp

    <div class="dashboard-wrapper">
        <div class="sidebar-backdrop" id="sidebarBackdrop" onclick="toggleMobileSidebar()"></div>
        <div class="dashboard-container">
            <!-- Sidebar -->
            <aside class="dashboard-sidebar">
                <!-- Sidebar Header / Logo -->
                <div class="sidebar-logo"
                    style="margin-bottom: 30px; display: flex; align-items: center; justify-content: space-between; padding-left: 8px;">
                    <a href="{{ route('home') }}">
                        <img src="/images/logo-viantryp.png" alt="Viantryp"
                            style="height: 32px; width: auto; filter: brightness(0) invert(1);">
                    </a>
                    <button type="button" class="mobile-sidebar-close" onclick="toggleMobileSidebar()" title="Cerrar menú">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <!-- Sidebar Create Button (Moved to topbar) -->

                <!-- Sidebar Nav Links -->
                <nav class="sidebar-nav">
                    <a href="{{ route('trips.index') }}?filter=personal"
                        class="sidebar-link {{ $activeMainTab === 'personal' ? 'active' : '' }}">
                        <i class="fas fa-suitcase-rolling"></i>
                        <span>Mis viajes</span>
                    </a>
                    <a href="{{ route('trips.index') }}?filter=shared"
                        class="sidebar-link {{ $activeMainTab === 'shared' ? 'active' : '' }}">
                        <i class="fas fa-users"></i>
                        <span>Viajes Compartidos</span>
                    </a>

                    <div class="sidebar-link disabled" title="Próximamente">
                        <i class="fas fa-layer-group"></i>
                        <span>Plantillas</span>
                        <span class="sidebar-badge-soon">Próximamente</span>
                    </div>

                    <a href="{{ route('profile.index') }}" class="sidebar-link">
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
                    $user = auth()->user();
                    $tripCount = \App\Models\Trip::where('user_id', $user->id)->count();
                    $editorCount = \DB::table('trip_collaborators')
                        ->join('trips', 'trip_collaborators.trip_id', '=', 'trips.id')
                        ->where('trips.user_id', $user->id)
                        ->where('trip_collaborators.role', 'editor')
                        ->distinct('trip_collaborators.email')
                        ->count();
                    $limits = $user->getPlanLimits();
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
                            <span>{{ $tripCount }} / {{ $maxTrips }}</span>
                        </div>
                        <div class="usage-progress-bar">
                            <div class="usage-progress-fill" style="width: {{ $tripPercent }}%"></div>
                        </div>
                    </div>
                    <div class="usage-item">
                        <div class="usage-label-row">
                            <span><i class="fas fa-users"></i> Colaboradores</span>
                            <span>{{ $editorCount }} / {{ $maxEditors }}</span>
                        </div>
                        <div class="usage-progress-bar">
                            <div class="usage-progress-fill" style="width: {{ $editorPercent }}%"></div>
                        </div>
                    </div>
                    <div class="usage-upgrade-row">
                        <span class="plan-badge">{{ ucfirst($user->plan) }}</span>
                        <a href="javascript:void(0)" onclick="openUpgradeModal(true)" class="btn-upgrade-link">
                            Mejorar plan <i class="fas fa-arrow-up-right-from-square"></i>
                        </a>
                    </div>
                </div>
            </aside>

            <!-- Main Content Area -->
            <div class="dashboard-main">
                <!-- Topbar (Search & User Dropdown) -->
                <header class="dashboard-topbar">
                    <button type="button" class="mobile-hamburger-btn" onclick="toggleMobileSidebar()" title="Abrir menú">
                        <i class="fas fa-bars"></i>
                    </button>
                    <div class="topbar-search">
                        <div class="search-box-wrapper">
                            <span class="search-icon"><i class="fas fa-search"></i></span>
                            <input type="text" placeholder="Buscar por ID, nombre, viajero..." id="searchInput"
                                oninput="searchTripsRows(this.value)" />
                        </div>
                    </div>

                    <div class="topbar-actions">
                        @if($activeMainTab !== 'shared')
                            <button onclick="showCreateTripModal()" class="btn-topbar-create">
                                <i class="fas fa-plus"></i>
                                <span>Crear viaje</span>
                            </button>
                        @endif



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
                                        <img src="{{ str_starts_with(auth()->user()->avatar, 'http') ? auth()->user()->avatar : asset('storage/' . auth()->user()->avatar) }}"
                                            alt="">
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
                                    <button type="submit" class="btn-logout"><i class="fas fa-sign-out-alt"></i> Cerrar
                                        sesión</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </header>

                <!-- Dashboard Main Content Scroll Container -->
                <div class="dashboard-content-scroll">
                    @if(auth()->user()->isTrialActive() && auth()->user()->getTrialDaysRemaining() <= 3)
                        <div class="trial-banner" style="margin: 30px 40px 10px;">
                            <div class="trial-banner-content">
                                <div class="trial-icon"><i class="fas fa-rocket"></i></div>
                                <div class="trial-text">
                                    <h4>Prueba gratuita del Plan Avanzado</h4>
                                    <p>Te quedan <strong>{{ auth()->user()->getTrialDaysRemaining() }}
                                            {{ auth()->user()->getTrialDaysRemaining() == 1 ? 'día' : 'días' }}</strong> para
                                        disfrutar de todas las herramientas.</p>
                                </div>
                            </div>
                            <div class="trial-cta">
                                <button onclick="openUpgradeModal(true)" class="btn-trial-upgrade">Mejorar mi plan ahora
                                    →</button>
                            </div>
                        </div>
                    @endif

                    <!-- Quick Access / Recent Trips Section -->
                    <section class="quick-access-section">
                        <h2 class="section-title">VIAJES RECIENTES</h2>
                        <div class="quick-access-grid">
                            @php
                                $recentTrips = $trips->take(3);
                            @endphp
                            @forelse($recentTrips as $idx => $rtrip)
                                @php
                                    $client = collect($rtrip->persons)->firstWhere('type', 'client') ?? collect($rtrip->persons)->first();
                                    $travelerName = $client ? $client->name : ($rtrip->travelers ?: 'Sin viajero');
                                @endphp
                                <div class="quick-access-card featured"
                                    style="background-image: linear-gradient(rgba(0, 0, 0, 0.25), rgba(0, 0, 0, 0.7)), url('{{ $rtrip->cover_image_url ?: asset('images/default-cover.jpg') }}'); background-size: cover; background-position: center;"
                                    onclick="window.location='{{ route('trips.edit', $rtrip->id) }}'">
                                    <div class="card-folder-body">
                                        @php
                                            $rtripColorInfo = $userCustomStatuses[$rtrip->status] ?? null;
                                            $rtripColor = is_array($rtripColorInfo) ? ($rtripColorInfo['color'] ?? 'blue') : 'blue';
                                        @endphp
                                        <div class="card-folder-header-row" style="margin-bottom: 12px;">
                                            <span class="status-badge status-color-{{ $rtripColor }} status-{{ $rtrip->status }}">
                                                {{ getStatusLabel($rtrip->status, $userCustomStatuses) }}
                                            </span>
                                        </div>
                                        <div class="card-folder-info">
                                            <div class="card-folder-title" title="{{ $rtrip->title }}">{{ $rtrip->title }}</div>
                                            <div class="card-folder-subtitle">
                                                {{ $travelerName }}
                                            </div>
                                        </div>
                                        <div class="card-folder-meta">
                                            <div class="card-folder-date">
                                                Inicio del viaje: {{ $rtrip->start_date ? \Carbon\Carbon::parse($rtrip->start_date)->format('d/m/Y') : 'Sin fecha' }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="quick-access-empty"
                                    style="grid-column: 1 / -1; padding: 30px; text-align: center; background: #f8fafc; border-radius: 12px; border: 1px dashed #e2e8ef; color: #64748b; font-size: 14px;">
                                    No hay viajes recientes.
                                </div>
                            @endforelse
                        </div>
                    </section>

                    <!-- Main Trips List Section -->
                    <section class="trips-list-section">
                        <!-- Mobile Search Bar (under Viajes Recientes) -->
                        <div class="mobile-search-wrapper">
                            <div class="search-box-wrapper" style="width: 100%; box-sizing: border-box;">
                                <span class="search-icon"><i class="fas fa-search"></i></span>
                                <input type="text" placeholder="Buscar por ID, nombre, viajero..."
                                    oninput="searchTripsRows(this.value)"
                                    style="width: 100%; height: 42px; padding: 0 14px 0 38px; border-radius: 10px; border: 1.5px solid var(--bdr); font-size: 13px; outline: none; background: white; box-sizing: border-box; box-shadow: 0 2px 6px rgba(0,0,0,0.03);" />
                            </div>
                        </div>

                        <div class="list-section-header">
                            <h2 class="section-title">
                                @if($activeMainTab === 'shared')
                                    VIAJES COMPARTIDOS
                                @else
                                    TODOS LOS VIAJES
                                @endif
                            </h2>
                        </div>

                        <!-- Bulk actions -->
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

                        <!-- Table -->
                        <div class="tbl-wrap">
                            <table id="mainTable">
                                <thead>
                                    <tr>
                                        <th><input type="checkbox" id="checkAll" onchange="toggleSelectAll(this)" /></th>
                                        <th style="width:4px;padding:0"></th>
                                        <th class="sortable" style="user-select: none; width: 80px; min-width: 70px;">
                                            <i class="fas fa-hashtag" style="font-size: 11px; color: #94a3b8; margin-right: 4px;"></i>ID
                                            <div class="col-menu-btn" onclick="toggleHeaderMenu(event, this)">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <path d="M6 9l6 6 6-6" />
                                                </svg>
                                            </div>
                                            <div class="header-dropdown" onclick="event.stopPropagation()">
                                                <div class="header-dropdown-item"
                                                    onclick="sortTableFromMenu(this, 'asc', 'string')"><svg
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2">
                                                        <path d="M12 19V5M5 12l7-7 7 7" />
                                                    </svg> Ordenar A - Z</div>
                                                <div class="header-dropdown-item"
                                                    onclick="sortTableFromMenu(this, 'desc', 'string')"><svg
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2">
                                                        <path d="M12 5v14M5 12l7 7 7-7" />
                                                    </svg> Ordenar Z - A</div>
                                            </div>
                                        </th>
                                        <th class="sortable" style="user-select: none; max-width: 200px;">
                                            <i class="fas fa-plane" style="font-size: 11px; color: #94a3b8; margin-right: 4px;"></i>Nombre del Viaje
                                            <div class="col-menu-btn" onclick="toggleHeaderMenu(event, this)">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <path d="M6 9l6 6 6-6" />
                                                </svg>
                                            </div>
                                            <div class="header-dropdown" onclick="event.stopPropagation()">
                                                <div class="header-dropdown-item"
                                                    onclick="sortTableFromMenu(this, 'asc', 'string')"><svg
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2">
                                                        <path d="M12 19V5M5 12l7-7 7 7" />
                                                    </svg> Ordenar A - Z</div>
                                                <div class="header-dropdown-item"
                                                    onclick="sortTableFromMenu(this, 'desc', 'string')"><svg
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2">
                                                        <path d="M12 5v14M5 12l7 7 7-7" />
                                                    </svg> Ordenar Z - A</div>
                                            </div>
                                        </th>
                                        <th class="sortable" style="user-select: none;">
                                            <i class="fas fa-calendar-alt" style="font-size: 11px; color: #94a3b8; margin-right: 4px;"></i>Inicio de viaje
                                            <div class="col-menu-btn" onclick="toggleHeaderMenu(event, this)">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <path d="M6 9l6 6 6-6" />
                                                </svg>
                                            </div>
                                            <div class="header-dropdown" onclick="event.stopPropagation()">
                                                <div class="header-dropdown-item"
                                                    onclick="sortTableFromMenu(this, 'asc', 'date')"><svg
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2">
                                                        <path d="M12 19V5M5 12l7-7 7 7" />
                                                    </svg> Ordenar A - Z</div>
                                                <div class="header-dropdown-item"
                                                    onclick="sortTableFromMenu(this, 'desc', 'date')"><svg
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2">
                                                        <path d="M12 5v14M5 12l7 7 7-7" />
                                                    </svg> Ordenar Z - A</div>
                                            </div>
                                        </th>
                                        <th class="sortable" style="user-select: none; min-width: 180px; width: 220px;">
                                            <i class="fas fa-user" style="font-size: 11px; color: #94a3b8; margin-right: 4px;"></i>Viajero principal
                                            <div class="col-menu-btn" onclick="toggleHeaderMenu(event, this)">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <path d="M6 9l6 6 6-6" />
                                                </svg>
                                            </div>
                                            <div class="header-dropdown" onclick="event.stopPropagation()">
                                                <div class="header-dropdown-item"
                                                    onclick="sortTableFromMenu(this, 'asc', 'string')"><svg
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2">
                                                        <path d="M12 19V5M5 12l7-7 7 7" />
                                                    </svg> Ordenar A - Z</div>
                                                <div class="header-dropdown-item"
                                                    onclick="sortTableFromMenu(this, 'desc', 'string')"><svg
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2">
                                                        <path d="M12 5v14M5 12l7 7 7-7" />
                                                    </svg> Ordenar Z - A</div>
                                            </div>
                                        </th>
                                        <th class="sortable" style="user-select: none; width: 120px; min-width: 120px; text-align: center; padding: 13px 4px;">
                                            <i class="fas fa-tag" style="font-size: 11px; color: #94a3b8; margin-right: 4px;"></i>Estado
                                            <div class="col-menu-btn" onclick="toggleHeaderMenu(event, this)">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <path d="M6 9l6 6 6-6" />
                                                </svg>
                                            </div>
                                            <div class="header-dropdown" onclick="event.stopPropagation()">
                                                <div class="header-dropdown-item"
                                                    onclick="sortTableFromMenu(this, 'asc', 'string')"><svg
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2">
                                                        <path d="M12 19V5M5 12l7-7 7 7" />
                                                    </svg> Ordenar A - Z</div>
                                                <div class="header-dropdown-item"
                                                    onclick="sortTableFromMenu(this, 'desc', 'string')"><svg
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2">
                                                        <path d="M12 5v14M5 12l7 7 7-7" />
                                                    </svg> Ordenar Z - A</div>
                                            </div>
                                        </th>
                                        @if($activeMainTab === 'shared')
                                            <th class="sortable" style="user-select: none; width: 150px !important; min-width: 150px !important; max-width: 150px !important;">
                                                <i class="fas fa-user-shield" style="font-size: 11px; color: #94a3b8; margin-right: 4px;"></i>Propietario
                                                <div class="col-menu-btn" onclick="toggleHeaderMenu(event, this)">
                                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                        <path d="M6 9l6 6 6-6" />
                                                    </svg>
                                                </div>
                                                <div class="header-dropdown" onclick="event.stopPropagation()">
                                                    <div class="header-dropdown-item"
                                                        onclick="sortTableFromMenu(this, 'asc', 'string')"><svg
                                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                            stroke-width="2">
                                                            <path d="M12 19V5M5 12l7-7 7 7" />
                                                        </svg> Ordenar A - Z</div>
                                                    <div class="header-dropdown-item"
                                                        onclick="sortTableFromMenu(this, 'desc', 'string')"><svg
                                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                            stroke-width="2">
                                                            <path d="M12 5v14M5 12l7 7 7-7" />
                                                        </svg> Ordenar Z - A</div>
                                                </div>
                                            </th>
                                        @endif
                                        <th class="right" style="width: 150px; min-width: 150px; max-width: 150px; text-align: center;">
                                            <i class="fas fa-bolt" style="font-size: 11px; color: #94a3b8; margin-right: 4px;"></i>Acciones
                                        </th>
                                    </tr>
                                </thead>
                                <tbody id="tbody">
                                    @if(count($trips) > 0)
                                        @foreach($trips as $index => $trip)
                                            <tr class="trip-row" data-trip-id="{{ $trip->id }}"
                                                data-is-pro="{{ $trip->is_pro ? '1' : '0' }}"
                                                style="animation-delay: {{ $index * 0.04 }}s; animation: rowIn 0.28s ease both; cursor: pointer;"
                                                onclick="if(window.innerWidth > 768) { window.location='{{ route('trips.edit', $trip->id) }}'; }">
                                                <td onclick="event.stopPropagation()"><input type="checkbox"
                                                        class="rchk trip-checkbox" data-trip-id="{{ $trip->id }}"
                                                        onchange="updateSelectAllState()" /></td>
                                                <td class="bar-cell"></td>
                                                <td style="width: 80px;">
                                                    <div class="id-container" onclick="event.stopPropagation(); editTripCode({{ $trip->id }}, '{{ $trip->code }}')" title="Haz clic para editar ID">
                                                        <span class="id-chip code-display" id="code-display-{{ $trip->id }}">{{ $trip->code ?? 'N/A' }}</span>
                                                        <input type="text" class="code-input" id="code-input-{{ $trip->id }}"
                                                            style="display: none; width: 100%; border-radius: 4px; border: 1px solid var(--bdr); padding: 4px; font-family: inherit; font-size: 12px; text-transform: uppercase;" onblur="saveTripCode({{ $trip->id }})"
                                                            onkeypress="handleCodeKeyPress(event, {{ $trip->id }})" maxlength="20">
                                                    </div>
                                                </td>
                                                <td style="max-width: 200px;">
                                                    <div class="trip-name">
                                                        <span class="title-display" id="title-display-{{ $trip->id }}"
                                                            style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; word-break: break-word; line-height: 1.3;">{{ $trip->title }}</span>
                                                    </div>
                                                    @if($trip->destinations && count($trip->destinations) > 0)
                                                        <div class="trip-dest"><svg viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                                stroke-linejoin="round">
                                                                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                                                <circle cx="12" cy="10" r="3"></circle>
                                                            </svg> <span
                                                                style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 180px; display: inline-block; vertical-align: bottom;">{{ rtrim($trip->destinations->pluck('name')->join(' · '), ' · ') ?: 'Sin destino' }}</span>
                                                        </div>
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
                                                                <a href="mailto:{{ $clientMobile->email }}" class="mobile-client-email"
                                                                    onclick="event.stopPropagation()">
                                                                    {{ $clientMobile->email }}
                                                                </a>
                                                            @endif
                                                        @endif
                                                        <div class="mobile-trip-date">
                                                            Inicio del viaje:
                                                            {!! $trip->start_date ? \Carbon\Carbon::parse($trip->start_date)->translatedFormat('j M Y') : '<span style="color:#d94040;font-weight:700"><i class="fas fa-exclamation-triangle"></i> ¡Fecha vacía!</span>' !!}
                                                        </div>
                                                    </div>
                                                </td>
                                                <td style="min-width: 120px;">
                                                    <div class="trip-date">
                                                        {!! $trip->start_date ? \Carbon\Carbon::parse($trip->start_date)->translatedFormat('j M Y') : '<span style="color:#d94040;font-weight:700;font-size:11px;text-transform:uppercase;background:#fee2e2;padding:2px 6px;border-radius:4px;"><i class="fas fa-exclamation-triangle"></i> Vacío</span>' !!}
                                                    </div>
                                                </td>
                                                @php
                                                    $client = collect($trip->persons)->firstWhere('type', 'client') ?? collect($trip->persons)->first();
                                                @endphp
                                                <td style="min-width: 180px;">
                                                    <div class="client-name"
                                                        onclick="event.stopPropagation(); editTripField({{ $trip->id }}, 'client_name')"
                                                        title="Haz clic para editar">
                                                        <span class="name-display traveler-name-clean"
                                                            id="name-display-{{ $trip->id }}">{{ $client ? $client->name : 'Sin viajero' }}</span>
                                                        <input type="text" class="field-input code-input"
                                                            id="name-input-{{ $trip->id }}"
                                                            style="display: none; width: 100%; border-radius: 4px; border: 1px solid var(--bdr); padding: 4px; font-family: inherit; font-size: 13px; text-transform: none;"
                                                            onblur="saveTripField({{ $trip->id }}, 'client_name')"
                                                            onkeypress="handleFieldKeyPress(event, {{ $trip->id }}, 'client_name')"
                                                            onclick="event.stopPropagation()">
                                                    </div>

                                                    <div class="client-email-container"
                                                        onclick="event.stopPropagation(); editTripField({{ $trip->id }}, 'client_email')"
                                                        style="margin-top: 2px;" title="Haz clic para editar">
                                                        <span class="email-display traveler-email-clean" id="email-display-{{ $trip->id }}"
                                                            style="display: inline-block;">{{ ($client && $client->email) ? $client->email : 'Añadir correo' }}</span>
                                                        <input type="email" class="field-input code-input"
                                                            id="email-input-{{ $trip->id }}"
                                                            style="display: none; width: 100%; border-radius: 4px; border: 1px solid var(--bdr); padding: 4px; font-family: inherit; font-size: 11.5px; text-transform: none;"
                                                            onblur="saveTripField({{ $trip->id }}, 'client_email')"
                                                            onkeypress="handleFieldKeyPress(event, {{ $trip->id }}, 'client_email')"
                                                            onclick="event.stopPropagation()">
                                                    </div>
                                                </td>
                                                <td onclick="event.stopPropagation()" style="width: 120px; min-width: 120px; padding: 12px 4px; text-align: center;">
                                                    @php
                                                        $currStatusInfo = $userCustomStatuses[$trip->status] ?? ['label' => ucfirst($trip->status), 'color' => 'blue'];
                                                        $currColor = is_array($currStatusInfo) ? ($currStatusInfo['color'] ?? 'blue') : 'blue';
                                                    @endphp
                                                    <select class="status-select status-color-{{ $currColor }} status-{{ $trip->status }}"
                                                        data-status="{{ $trip->status }}"
                                                        onchange="if(this.value === '__manage__'){ openManageStatusesModal(); this.value = this.getAttribute('data-status'); return; } changeTripStatus({{ $trip->id }}, this.value)">
                                                        @foreach($userCustomStatuses as $stKey => $stInfo)
                                                            @if($stKey !== 'discarded')
                                                                @php $stLabel = is_array($stInfo) ? ($stInfo['label'] ?? $stKey) : $stInfo; @endphp
                                                                <option value="{{ $stKey }}" {{ $trip->status === $stKey ? 'selected' : '' }}>{{ $stLabel }}</option>
                                                            @endif
                                                        @endforeach
                                                        <option value="__manage__" style="font-weight: 400 !important; color: #64748b !important;">⚙ Editar estados...</option>
                                                    </select>
                                                </td>
                                                @if($activeMainTab === 'shared')
                                                    <td style="width: 150px !important; min-width: 150px !important; max-width: 150px !important;">
                                                        @php
                                                            $myCollab = $trip->collaborators->first();
                                                            $isPending = $myCollab && !$myCollab->accepted_at;
                                                        @endphp
                                                        <div style="display: flex; align-items: center; gap: 8px;">
                                                            <div class="owner-avatar"
                                                                style="width: 24px; height: 24px; border-radius: 50%; background: var(--sand); color: var(--accent); display: flex; align-items: center; justify-content: center; font-size: 10px; font-weight: 700; border: 1px solid var(--bdr);">
                                                                {{ strtoupper(substr($trip->user->name, 0, 1) . substr($trip->user->last_name, 0, 1)) }}
                                                            </div>
                                                            <div>
                                                                <div style="font-size: 12px; font-weight: 600; color: var(--ink);">
                                                                    {{ $trip->user->name }}
                                                                </div>
                                                                @if($isPending)
                                                                    <span
                                                                        style="font-size: 10px; color: #c0392b; font-weight: 700; text-transform: uppercase;">Invitación
                                                                        Pendiente</span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </td>
                                                @endif
                                                <td class="acts-cell" style="width: 210px; min-width: 210px; max-width: 210px; text-align: center;" onclick="event.stopPropagation()">
                                                    <div class="acts">
                                                        @if($activeMainTab === 'shared' && isset($isPending) && $isPending)
                                                            <a href="{{ route('trips.accept-invite', ['token' => $myCollab->token]) }}"
                                                                class="btn-create"
                                                                style="padding: 6px 12px; font-size: 11px; height: 28px; background: var(--teal); border: none; color: white; border-radius: 6px; text-decoration: none; font-weight: 700; display: inline-flex; align-items: center; gap: 4px;">
                                                                <i class="fas fa-check"></i> Aceptar
                                                            </a>
                                                        @endif
                                                        <button class="abt view" data-tip="Ver propuesta"
                                                            onclick="previewTrip({{ $trip->id }})">
                                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                                                                <circle cx="12" cy="12" r="3" />
                                                            </svg>
                                                            <span class="btn-txt">Ver</span>
                                                        </button>
                                                        <button class="abt edit" data-tip="Editar propuesta"
                                                            onclick="editTrip({{ $trip->id }})">
                                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                                <path
                                                                    d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                                                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
                                                            </svg>
                                                            <span class="btn-txt">Editar</span>
                                                        </button>
                                                        <button class="abt share" data-tip="Ver enlace del viaje"
                                                            onclick="shareTripIndex({{ $trip->id }}, '{{ $trip->share_token }}')">
                                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                                <path
                                                                    d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71" />
                                                                <path
                                                                    d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71" />
                                                            </svg>
                                                            <span class="btn-txt">Enlace</span>
                                                        </button>
                                                        @if($trip->user_id == Auth::id())
                                                            <button class="abt share-edit" data-tip="Compartir para editar"
                                                                onclick="openSharingModal({{ $trip->id }}, 'editor')">
                                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                                    <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                                                                    <circle cx="8.5" cy="7" r="4" />
                                                                    <line x1="20" y1="8" x2="20" y2="14" />
                                                                    <line x1="23" y1="11" x2="17" y2="11" />
                                                                </svg>
                                                            </button>
                                                        @endif

                                                        <div class="acts-menu-container">
                                                            <button class="abt more"
                                                                onclick="toggleActsMenu(event, {{ $trip->id }})"
                                                                data-tip="Más opciones" title="Más opciones">
                                                                <i class="fas fa-ellipsis-v"></i>
                                                            </button>
                                                            <div class="acts-menu" id="menu-{{ $trip->id }}">
                                                                @if($trip->user_id == Auth::id())
                                                                    <div class="acts-menu-item"
                                                                        onclick="openSharingModal({{ $trip->id }}, 'editor')">
                                                                        <i class="fas fa-user-plus"></i> Compartir
                                                                    </div>
                                                                @endif
                                                                @if($activeMainTab === 'shared')
                                                                    <div class="acts-menu-item danger"
                                                                        onclick="confirmLeaveCollaboration({{ $trip->id }}, '{{ $trip->title }}')">
                                                                        <i class="fas fa-trash"></i> Eliminar
                                                                    </div>
                                                                @else
                                                                    @if($trip->user_id == Auth::id())
                                                                        <div class="acts-menu-item"
                                                                            onclick="openCollaboratorsModal({{ $trip->id }})">
                                                                            <i class="fas fa-users"></i> Ver colaboradores
                                                                        </div>
                                                                    @endif
                                                                    @if($trip->user_id == Auth::id())
                                                                        <div class="acts-menu-item"
                                                                            onclick="openTransferModal({{ $trip->id }})">
                                                                            <i class="fas fa-exchange-alt"></i> Cambiar propietario
                                                                        </div>
                                                                        <div class="acts-menu-item"
                                                                            onclick="duplicateTrip({{ $trip->id }})">
                                                                            <i class="fas fa-copy"></i> Duplicar viaje
                                                                        </div>
                                                                        <div class="acts-menu-item" onclick="openTripInfoModal({
                                                                            id: {{ $trip->id }},
                                                                            code: '{{ e($trip->code) }}',
                                                                            title: '{{ e($trip->title) }}',
                                                                            created_at: '{{ $trip->created_at ? \Carbon\Carbon::parse($trip->created_at)->format('d/m/Y H:i') : 'Sin fecha' }}',
                                                                            updated_at: '{{ $trip->updated_at ? \Carbon\Carbon::parse($trip->updated_at)->format('d/m/Y H:i') : 'Sin fecha' }}',
                                                                            updated_by: '{{ e($trip->user ? $trip->user->name : 'Sistema') }}',
                                                                            views_count: {{ $trip->views_count ?? 0 }},
                                                                            price: '{{ $trip->price ? ($trip->currency ?: '$') . ' ' . number_format($trip->price, 2) : 'Sin definir' }}',
                                                                            start_date: '{{ $trip->start_date ? \Carbon\Carbon::parse($trip->start_date)->format('d/m/Y') : 'Sin fecha' }}',
                                                                            end_date: '{{ $trip->end_date ? \Carbon\Carbon::parse($trip->end_date)->format('d/m/Y') : 'Sin fecha' }}',
                                                                            status: '{{ e(getStatusLabel($trip->status)) }}',
                                                                            traveler: '{{ e($client ? $client->name : ($trip->travelers ?: 'Sin viajero')) }}',
                                                                            destination: '{{ e($trip->destinations && count($trip->destinations) > 0 ? $trip->destinations->pluck('name')->join(' · ') : 'Sin destino') }}'
                                                                        })">
                                                                            <i class="fas fa-info-circle"></i> Más información
                                                                        </div>
                                                                        <div class="acts-menu-item danger"
                                                                            onclick="delRow({{ $trip->id }})">
                                                                            <i class="fas fa-trash"></i> Eliminar
                                                                        </div>
                                                                    @endif
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr id="emptyRow">
                                            <td colspan="8">
                                                <div class="empty" style="display:block;">
                                                    <div class="e-ring"><svg viewBox="0 0 24 24" fill="none"
                                                            stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                                            stroke-linejoin="round">
                                                            <path
                                                                d="M21 16v-2l-8-5V3.5a1.5 1.5 0 0 0-3 0V9l-8 5v2l8-2.5V19l-2 1.5V22l3.5-1 3.5 1v-1.5L13 19v-5.5l8 2.5z" />
                                                        </svg></div>
                                                    <h3>No hay viajes en tu lista.</h3>
                                                    <p> Haz clic en ‘Crear viaje’ y empieza a explorar.</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
    <x-upgrade-modal />
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/driver.js@1.0.1/dist/driver.js.iife.js"></script>
    <script>
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
    <script src="{{ asset('js/trips/pro-viewer.js') }}?v={{ time() }}"></script>
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
                        try { proState = JSON.parse(proState); } catch (e) { }
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
                    proState.googleClientId = "{{ config('services.google.client_id') }}";

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
                let r = parseInt(col.substring(0, 2), 16) + amt;
                let g = parseInt(col.substring(2, 4), 16) + amt;
                let b = parseInt(col.substring(4, 6), 16) + amt;
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
                                            <label style="display:block; font-size:11px; font-weight:700; text-transform:uppercase; color:var(--gray2); margin-bottom:6px; letter-spacing:0.5px;">Nombre del Viajero</label>
                                            <input type="text" name="client_name" placeholder="Ej: Juan Pérez" style="width:100%; height:44px; padding:0 14px; border:1.5px solid var(--bdr); border-radius:10px; font-size:14px; outline:none; transition:border-color 0.2s;">
                                        </div>
                                        <div style="margin-bottom:20px;">
                                            <label style="display:block; font-size:11px; font-weight:700; text-transform:uppercase; color:var(--gray2); margin-bottom:6px; letter-spacing:0.5px;">Correo del Viajero</label>
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
            const rawMap = @json($userCustomStatuses);
            const statusInfo = rawMap ? rawMap[newStatus] : null;
            const color = (statusInfo && typeof statusInfo === 'object' && statusInfo.color) ? statusInfo.color : 'blue';

            const selectElement = document.querySelector(`select[onchange*="${tripId}"]`);
            if (selectElement) {
                selectElement.className = `status-select status-color-${color} status-${newStatus}`;
            }

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
                        if (selectElement) {
                            selectElement.setAttribute('data-status', newStatus);
                            selectElement.className = `status-select status-color-${color} status-${newStatus}`;
                        }
                        setTimeout(() => location.reload(), 800);
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

        function confirmLeaveCollaboration(tripId, tripTitle) {
            const message = `¿Estás seguro de que quieres eliminar el viaje "${tripTitle}"?\n\nAl hacerlo, ya no aparecerá en tu pestaña de viajes compartidos y se le notificará automáticamente al propietario del viaje que has dejado de colaborar.`;
            if (!confirm(message)) return;

            showNotification('Procesando', 'Eliminando viaje de compartidos...', 'info');

            fetch(`{{ url('trips') }}/${tripId}/collaborators/leave`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json'
                }
            })
                .then(r => r.json())
                .then(d => {
                    if (d.success) {
                        showNotification('Éxito', d.message, 'success');
                        setTimeout(() => location.reload(), 1000);
                    } else {
                        showNotification('Error', d.message || 'No se pudo eliminar el viaje.', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('Error', 'Error de red al intentar eliminar el viaje.', 'error');
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
            if (checked.length > 0) {
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
            if (ids.length === 0) return;
            if (confirm(`¿Eliminar ${ids.length} viaje(s)?`)) {
                fetch(`{{ url('trips/bulk-delete') }}`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') },
                    body: JSON.stringify({ trip_ids: ids })
                }).then(r => r.json()).then(d => { if (d.success) location.reload(); });
            }
        }

        function duplicateSelectedTrips() {
            const ids = getSelectedTrips();
            if (ids.length === 0) return;
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
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') },
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
                } else if (fieldName === 'client_name' && displaySpan.textContent.trim() === 'Sin viajero') {
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
                if (!newValue) {
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
            if (fieldName === 'client_name' && currentValue === 'Sin viajero') currentValue = '';

            if (newValue === currentValue) {
                inputField.style.display = 'none';
                displaySpan.style.display = 'inline-block';
                return;
            }

            fetch(`{{ url('trips') }}/${tripId}/inline-update`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') },
                body: JSON.stringify({ field: fieldName, value: newValue })
            }).then(r => r.json()).then(d => {
                if (d.success) {
                    if (fieldName === 'client_email' && !newValue) {
                        displaySpan.textContent = 'Añadir correo';
                    } else if (fieldName === 'client_name' && !newValue) {
                        displaySpan.textContent = 'Sin viajero';
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
            if (token) return showShareModalIndex(`${window.location.origin}/trips/share/${token}`);
            fetch(`{{ url('trips') }}/${tripId}/generate-share-token`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') }
            }).then(r => r.json()).then(d => { if (d.success) showShareModalIndex(d.share_url); });
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
                        valX = parseFloat(valX.replace(/[^0-9.-]+/g, "")) || 0;
                        valY = parseFloat(valY.replace(/[^0-9.-]+/g, "")) || 0;
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
                    switchcount++;
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
                if (th.innerText && (th.innerText.includes('Propietario') || th.innerText.includes('Acciones'))) {
                    th.style.width = '150px';
                    th.style.minWidth = '150px';
                    th.style.maxWidth = '150px';
                    return;
                }
                if (savedWidths[index]) {
                    th.style.width = savedWidths[index];
                }

                const resizer = th.querySelector('.resizer');
                if (!resizer) return;

                resizer.addEventListener('mousedown', function (e) {
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

                resizer.addEventListener('click', function (e) {
                    e.stopPropagation();
                });
            });

            if (Object.keys(savedWidths).length > 0) {
                table.style.tableLayout = 'fixed';
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
                        element: window.innerWidth > 768 ? '.toolbar .btn-create' : '.btn-mobile-only',
                        popover: {
                            title: 'Crear Viaje',
                            description: 'Utiliza este botón para comenzar a diseñar una nueva experiencia para tus viajeros.'
                        }
                    },
                    {
                        element: '.sbox',
                        popover: {
                            title: 'Buscador Inteligente',
                            description: 'Encuentra cualquier viaje rápidamente por nombre, destino o viajero.'
                        }
                    },
                    {
                        element: '.tbl-wrap',
                        popover: {
                            title: 'Gestión de Viajes',
                            description: 'Aquí verás tus itinerarios. Configura tu vista a tu manera: cambia el tamaño de las columnas u ordénalas según lo que necesites.'
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
                                            <input type="email" name="email" required placeholder="hola@viantryp.com" style="width:100%; height:44px; padding:0 14px; border:1.5px solid var(--bdr); border-radius:10px; font-size:14px; outline:none;">
                                            <div style="background:#fff7ed; padding:12px; border-radius:8px; border:1px solid #ffedd5; margin-top:16px;">
                                                <p style="font-size:11px; color:#9a3412; margin:0;">
                                                    <strong>⚠ Importante:</strong> Al transferir, el viaje pasará a tu pestaña de <b>Viajes Compartidos</b> y tú quedarás como editor. La marca y colores del viaje cambiarán al perfil del nuevo dueño.
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
                if (!confirm('¿Estás seguro de transferir la propiedad? Esta acción no se puede deshacer fácilmente.')) return;

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

        function openTripInfoModal(info) {
            const modalHtml = `
                <div id="tripInfoModal" style="position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(15, 42, 58, 0.4); backdrop-filter:blur(8px); z-index:2000; display:flex; align-items:center; justify-content:center; animation: fadeIn 0.3s ease;">
                    <div style="background:white; width:90%; max-width:460px; border-radius:16px; overflow:hidden; box-shadow:0 20px 40px rgba(0,0,0,0.12); animation: slideUp 0.3s ease;">
                        <div style="background:var(--accent); padding:20px; color:white; text-align:center;">
                            <h3 style="margin:0; font-size:18px; font-weight:700;">Información del viaje</h3>
                            <p style="margin:4px 0 0; font-size:12px; opacity:0.9; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">${info.title}</p>
                        </div>
                        <div style="padding:20px 24px; display:flex; flex-direction:column; gap:12px; max-height:70vh; overflow-y:auto;">
                            <div style="display:grid; grid-template-columns: 1fr 1fr; gap:10px;">
                                <div style="background:#f8fafc; padding:12px; border-radius:10px; border:1px solid #e2e8ef;">
                                    <span style="font-size:11px; font-weight:700; text-transform:uppercase; color:#64748b; display:block; margin-bottom:4px;"><i class="fas fa-calendar-alt"></i> Inicio de viaje</span>
                                    <strong style="font-size:13px; color:#0f172a;">${info.start_date}</strong>
                                </div>
                                <div style="background:#f8fafc; padding:12px; border-radius:10px; border:1px solid #e2e8ef;">
                                    <span style="font-size:11px; font-weight:700; text-transform:uppercase; color:#64748b; display:block; margin-bottom:4px;"><i class="fas fa-calendar-check"></i> Final del viaje</span>
                                    <strong style="font-size:13px; color:#0f172a;">${info.end_date}</strong>
                                </div>
                            </div>

                            <div style="display:grid; grid-template-columns: 1fr 1fr; gap:10px;">
                                <div style="background:#f8fafc; padding:12px; border-radius:10px; border:1px solid #e2e8ef;">
                                    <span style="font-size:11px; font-weight:700; text-transform:uppercase; color:#64748b; display:block; margin-bottom:4px;"><i class="fas fa-eye"></i> Vistas</span>
                                    <strong style="font-size:13px; color:#0f172a;">${info.views_count} vistas</strong>
                                </div>
                                <div style="background:#f8fafc; padding:12px; border-radius:10px; border:1px solid #e2e8ef;">
                                    <span style="font-size:11px; font-weight:700; text-transform:uppercase; color:#64748b; display:block; margin-bottom:4px;"><i class="fas fa-dollar-sign"></i> Valor Total</span>
                                    <strong style="font-size:13px; color:#0f172a;">${info.price}</strong>
                                </div>
                            </div>

                            <div style="background:#f8fafc; padding:12px; border-radius:10px; border:1px solid #e2e8ef; display:flex; flex-direction:column; gap:8px;">
                                <div style="display:flex; justify-content:space-between; align-items:center; border-bottom:1px solid #edf2f7; padding-bottom:6px;">
                                    <span style="font-size:12px; color:#64748b;"><i class="fas fa-user"></i> Viajero:</span>
                                    <strong style="font-size:12.5px; color:#0f172a;">${info.traveler}</strong>
                                </div>
                                <div style="display:flex; justify-content:space-between; align-items:center; border-bottom:1px solid #edf2f7; padding-bottom:6px;">
                                    <span style="font-size:12px; color:#64748b;"><i class="fas fa-tag"></i> Estado:</span>
                                    <strong style="font-size:12.5px; color:#0f172a;">${info.status}</strong>
                                </div>
                                <div style="display:flex; justify-content:space-between; align-items:center; border-bottom:1px solid #edf2f7; padding-bottom:6px;">
                                    <span style="font-size:12px; color:#64748b;"><i class="fas fa-user-edit"></i> Última modificación por:</span>
                                    <strong style="font-size:12.5px; color:#0f172a;">${info.updated_by}</strong>
                                </div>
                                <div style="display:flex; justify-content:space-between; align-items:center; border-bottom:1px solid #edf2f7; padding-bottom:6px;">
                                    <span style="font-size:12px; color:#64748b;"><i class="fas fa-clock"></i> Última modificación:</span>
                                    <strong style="font-size:12.5px; color:#0f172a;">${info.updated_at}</strong>
                                </div>
                                <div style="display:flex; justify-content:space-between; align-items:center;">
                                    <span style="font-size:12px; color:#64748b;"><i class="fas fa-calendar-plus"></i> Fecha de creación:</span>
                                    <strong style="font-size:12.5px; color:#0f172a;">${info.created_at}</strong>
                                </div>
                            </div>
                        </div>
                        <div style="padding:0 24px 20px;">
                            <button type="button" onclick="document.getElementById('tripInfoModal').remove()" style="width:100%; height:42px; border:none; background:var(--sand); color:var(--ink); font-weight:600; border-radius:10px; cursor:pointer; font-size:13px;">Cerrar</button>
                        </div>
                    </div>
                </div>
            `;

            const old = document.getElementById('tripInfoModal');
            if (old) old.remove();

            document.body.insertAdjacentHTML('beforeend', modalHtml);
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

        function openManageStatusesModal() {
            const rawStatuses = @json($userCustomStatuses);

            const colorOptions = [
                { id: 'cyan', bg: '#ecfeff', border: '#a5f3fc', text: '#0891b2', name: 'Cian' },
                { id: 'blue', bg: '#eff6ff', border: '#bfdbfe', text: '#1d4ed8', name: 'Azul' },
                { id: 'purple', bg: '#faf5ff', border: '#e9d5ff', text: '#7e22ce', name: 'Morado' },
                { id: 'green', bg: '#f0fdf4', border: '#bbf7d0', text: '#15803d', name: 'Verde' },
                { id: 'orange', bg: '#fff7ed', border: '#ffedd5', text: '#c2410c', name: 'Naranja' },
                { id: 'pink', bg: '#fff1f2', border: '#fecdd3', text: '#be123c', name: 'Rosa' },
                { id: 'slate', bg: '#f8fafc', border: '#cbd5e1', text: '#475569', name: 'Gris' }
            ];

            let statusesList = [];
            if (rawStatuses && typeof rawStatuses === 'object') {
                Object.keys(rawStatuses).forEach(k => {
                    if (k !== 'discarded') {
                        const item = rawStatuses[k];
                        const label = typeof item === 'object' ? item.label : item;
                        const color = typeof item === 'object' ? (item.color || 'blue') : 'blue';
                        statusesList.push({ key: k, label: label, color: color });
                    }
                });
            }

            if (statusesList.length === 0) {
                statusesList = [
                    { key: 'draft', label: 'Diseño', color: 'cyan' },
                    { key: 'sent', label: 'Planeado', color: 'blue' },
                    { key: 'reserved', label: 'Reservado', color: 'purple' },
                    { key: 'completed', label: 'Finalizado', color: 'green' }
                ];
            }

            function renderRows() {
                const container = document.getElementById('statusesInputsContainer');
                if (!container) return;
                let html = '';
                statusesList.forEach((item, index) => {
                    const isDefault = ['draft', 'sent', 'reserved', 'completed'].includes(item.key);
                    const selectedColorId = item.color || 'blue';
                    const currColorObj = colorOptions.find(c => c.id === selectedColorId) || colorOptions[1];

                    let colorDotsHtml = colorOptions.map(c => `
                        <button type="button" onclick="selectRowColor('${item.key}', '${c.id}', event)"
                            style="width:18px; height:18px; border-radius:50%; background:${c.bg}; border:1.5px solid ${selectedColorId === c.id ? c.text : c.border}; cursor:pointer; padding:0; display:inline-flex; align-items:center; justify-content:center; transition:transform 0.15s ease;"
                            title="${c.name}">
                            ${selectedColorId === c.id ? `<span style="width:4px; height:4px; border-radius:50%; background:${c.text};"></span>` : ''}
                        </button>
                    `).join('');

                    html += `
                        <div class="status-row-item" style="margin-bottom:14px; position:relative;" data-key="${item.key}">
                            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:4px;">
                                <label class="status-row-label" style="font-size:11px; font-weight:700; text-transform:uppercase; color:#64748b; margin:0;">Estado #${index + 1}</label>
                                ${!isDefault ? `
                                    <button type="button" class="btn-remove-status" onclick="removeStatusRow('${item.key}')" style="border:none; background:transparent; color:#ef4444; cursor:pointer; font-size:11.5px; padding:0;" title="Eliminar estado">
                                        <i class="fas fa-trash-alt"></i> Eliminar
                                    </button>
                                ` : ''}
                            </div>

                            <div style="display:flex; gap:8px; align-items:center; position:relative;">
                                <!-- Bolita sutil de color (Trigger del selector de colores) -->
                                <button type="button" onclick="toggleColorMenu('${item.key}', event)"
                                    style="width:20px; height:20px; border-radius:50%; background:${currColorObj.bg}; border:1.5px solid ${currColorObj.text}; cursor:pointer; flex-shrink:0; padding:0; display:inline-flex; align-items:center; justify-content:center; transition:all 0.15s ease;"
                                    title="Cambiar color (${currColorObj.name})">
                                    <span style="width:5px; height:5px; border-radius:50%; background:${currColorObj.text}; opacity:0.9;"></span>
                                </button>

                                <!-- Campo de Texto para el Nombre del Estado -->
                                <input type="text" class="status-input-val" data-key="${item.key}" value="${item.label}" required maxlength="30" style="flex:1; height:38px; padding:0 12px; border:1.5px solid var(--bdr); border-radius:8px; font-size:13px; outline:none; background:white; box-sizing:border-box;">

                                <!-- Menu desplegable sutil de colores para este estado -->
                                <div id="colorMenu-${item.key}" class="color-picker-dropdown"
                                    style="display:none; position:absolute; top:40px; left:0; z-index:2200; background:white; border-radius:10px; padding:6px 8px; box-shadow:0 6px 18px rgba(0,0,0,0.12); border:1px solid #eaecf0; gap:5px; align-items:center;">
                                    ${colorDotsHtml}
                                </div>
                            </div>
                        </div>
                    `;
                });
                container.innerHTML = html;
            }

            window.toggleColorMenu = function(key, e) {
                if (e) e.stopPropagation();
                const menu = document.getElementById('colorMenu-' + key);
                const isOpened = menu && menu.style.display === 'flex';
                document.querySelectorAll('.color-picker-dropdown').forEach(m => m.style.display = 'none');
                if (menu && !isOpened) {
                    menu.style.display = 'flex';
                }
            };

            window.selectRowColor = function(key, colorId, e) {
                if (e) e.stopPropagation();
                document.querySelectorAll('.status-input-val').forEach(input => {
                    const k = input.getAttribute('data-key');
                    const found = statusesList.find(s => s.key === k);
                    if (found) found.label = input.value;
                });

                const target = statusesList.find(s => s.key === key);
                if (target) target.color = colorId;
                renderRows();
            };

            window.removeStatusRow = function(key) {
                statusesList = statusesList.filter(s => s.key !== key);
                renderRows();
            };

            window.addNewStatusRow = function() {
                document.querySelectorAll('.status-input-val').forEach(input => {
                    const k = input.getAttribute('data-key');
                    const found = statusesList.find(s => s.key === k);
                    if (found) found.label = input.value;
                });

                const newKey = 'custom_' + Date.now();
                const newNum = statusesList.length + 1;
                const defaultColors = ['cyan', 'blue', 'purple', 'green', 'orange', 'pink'];
                const nextColor = defaultColors[statusesList.length % defaultColors.length];
                statusesList.push({ key: newKey, label: `Estado #${newNum}`, color: nextColor });
                renderRows();
            };

            const modalHtml = `
                <div id="manageStatusesModal" onclick="document.querySelectorAll('.color-picker-dropdown').forEach(m => m.style.display = 'none')" style="position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(15, 42, 58, 0.4); backdrop-filter:blur(8px); z-index:2000; display:flex; align-items:center; justify-content:center; animation: fadeIn 0.3s ease;">
                    <div style="background:white; width:90%; max-width:440px; border-radius:16px; overflow:hidden; box-shadow:0 20px 40px rgba(0,0,0,0.12); animation: slideUp 0.3s ease;" onclick="event.stopPropagation()">
                        <div style="background:var(--accent); padding:20px; color:white; text-align:center;">
                            <h3 style="margin:0; font-size:18px; font-weight:700;"><i class="fas fa-sliders-h" style="margin-right:6px;"></i> Gestionar Nombres y Colores de Estados</h3>
                            <p style="margin:4px 0 0; font-size:12px; opacity:0.9;">Haz clic en la bolita de color para elegir su tono pastel</p>
                        </div>
                        <div style="padding:20px 24px; max-height:70vh; overflow-y:auto;" onclick="document.querySelectorAll('.color-picker-dropdown').forEach(m => m.style.display = 'none')">
                            <form id="manageStatusesForm">
                                <div id="statusesInputsContainer"></div>

                                <button type="button" onclick="addNewStatusRow()" style="width:100%; height:38px; border:1.5px dashed #cbd5e1; background:#f8fafc; color:#475569; font-weight:600; border-radius:8px; cursor:pointer; font-size:12.5px; margin:8px 0 20px; display:flex; align-items:center; justify-content:center; gap:6px; transition:all 0.15s ease;">
                                    <i class="fas fa-plus" style="color:var(--accent);"></i> Agregar otro estado
                                </button>

                                <div style="display:flex; gap:10px;">
                                    <button type="button" onclick="document.getElementById('manageStatusesModal').remove()" style="flex:1; height:42px; border:none; background:var(--sand); color:var(--ink); font-weight:600; border-radius:10px; cursor:pointer; font-size:13px;">Cancelar</button>
                                    <button type="submit" style="flex:1; height:42px; border:none; background:var(--accent); color:white; font-weight:700; border-radius:10px; cursor:pointer; font-size:13px;"><i class="fas fa-save" style="margin-right:4px;"></i> Guardar Cambios</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            `;

            const old = document.getElementById('manageStatusesModal');
            if (old) old.remove();

            document.body.insertAdjacentHTML('beforeend', modalHtml);
            renderRows();

            const form = document.getElementById('manageStatusesForm');
            form.onsubmit = async (e) => {
                e.preventDefault();
                const btn = form.querySelector('button[type="submit"]');
                const originalContent = btn.innerHTML;
                btn.disabled = true;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Guardando...';

                const items = [];
                document.querySelectorAll('.status-input-val').forEach(input => {
                    const key = input.getAttribute('data-key');
                    const label = input.value.trim();
                    const found = statusesList.find(s => s.key === key);
                    const color = found ? (found.color || 'blue') : 'blue';
                    if (key && label) {
                        items.push({ key, label, color });
                    }
                });

                const payload = { statuses: items };

                try {
                    const response = await fetch("{{ route('user.custom-statuses') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify(payload)
                    });

                    const res = await response.json();
                    if (res.success) {
                        if (typeof showNotification === 'function') {
                            showNotification('Estados Actualizados', res.message);
                        } else {
                            alert(res.message);
                        }
                        document.getElementById('manageStatusesModal').remove();
                        setTimeout(() => location.reload(), 600);
                    } else {
                        alert('Error: ' + (res.message || 'No se pudieron guardar los estados'));
                        btn.disabled = false;
                        btn.innerHTML = originalContent;
                    }
                } catch (err) {
                    console.error('Error al guardar estados:', err);
                    alert('Ocurrió un error al guardar los estados.');
                    btn.disabled = false;
                    btn.innerHTML = originalContent;
                }
            };
        }

        function closeAllActsMenus() {
            document.querySelectorAll('.trip-row').forEach(r => {
                r.style.zIndex = '';
                r.classList.remove('menu-open');
            });
            document.querySelectorAll('.acts-menu').forEach(m => {
                m.classList.remove('show');
                m.style.display = 'none';
            });
        }

        function toggleActsMenu(event, tripId) {
            if (event) {
                event.preventDefault();
                event.stopPropagation();
            }
            const menu = document.getElementById('menu-' + tripId);
            if (!menu) return;
            const isVisible = menu.classList.contains('show') || menu.style.display === 'block';

            closeAllActsMenus();

            if (!isVisible) {
                const parentRow = menu.closest('.trip-row');
                if (parentRow) {
                    parentRow.style.zIndex = '100';
                    parentRow.classList.add('menu-open');
                }
                menu.style.display = 'block';
                menu.classList.add('show');
            }
        }

        document.addEventListener('click', function(e) {
            if (e.target.closest('.acts-menu-item')) {
                closeAllActsMenus();
            } else if (!e.target.closest('.acts-menu-container')) {
                closeAllActsMenus();
            }
        });

        function toggleMobileSidebar() {
            const sidebar = document.querySelector('.dashboard-sidebar');
            const backdrop = document.getElementById('sidebarBackdrop');
            if (sidebar) sidebar.classList.toggle('mobile-open');
            if (backdrop) backdrop.classList.toggle('active');
        }

        document.addEventListener('DOMContentLoaded', () => {
            initTableResizer();
            initSegmentSlider();

            // Pequeño delay para dejar que las animaciones de la tabla terminen
            setTimeout(initTutorial, 800);
        });
    </script>
@endpush