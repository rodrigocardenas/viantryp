@props(['trip'])

@php
    $tripId = is_object($trip) ? ($trip->id ?? '') : $trip;
@endphp

<!-- Viantryp Copilot Trigger Button (Standby Mode) -->
<div id="viantryp-copilot-container" data-trip-id="{{ $tripId }}">
    <button type="button" id="copilot-trigger-btn" class="copilot-trigger" aria-label="Abrir Viantryp Copilot"
        title="Viantryp Copilot - Próximamente" onclick="alert('Tryp AI está en mantenimiento y volverá a estar disponible muy pronto.');">
        <div class="copilot-trigger-glow"></div>
        <div class="copilot-trigger-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="sparkles-icon">
                <path
                    d="m12 3-1.912 5.813a2 2 0 0 1-1.275 1.275L3 12l5.813 1.912a2 2 0 0 1 1.275 1.275L12 21l1.912-5.813a2 2 0 0 1 1.275-1.275L21 12l-5.813-1.912a2 2 0 0 1-1.275-1.275L12 3Z" />
                <path d="M5 3v4" />
                <path d="M19 17v4" />
                <path d="M3 5h4" />
                <path d="M17 19h4" />
            </svg>
        </div>
        <span class="copilot-trigger-label">Tryp AI</span>
        <span class="copilot-badge">
            <span class="badge-dot"></span>
            IA
        </span>
    </button>
</div>

<style>
    #viantryp-copilot-container {
        position: fixed;
        bottom: 24px;
        right: 24px;
        z-index: 9999;
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
    }

    .copilot-trigger {
        position: relative;
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 10px 18px 10px 14px;
        background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
        color: #ffffff;
        border: 1px solid rgba(255, 255, 255, 0.15);
        border-radius: 9999px;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.3), 0 8px 10px -6px rgba(0, 0, 0, 0.2);
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        user-select: none;
        outline: none;
    }

    .copilot-trigger:hover {
        transform: translateY(-2px) scale(1.02);
        box-shadow: 0 14px 30px -5px rgba(0, 0, 0, 0.4), 0 10px 12px -6px rgba(0, 0, 0, 0.3);
        border-color: rgba(99, 102, 241, 0.5);
    }

    .copilot-trigger:active {
        transform: translateY(0) scale(0.98);
    }

    .copilot-trigger-glow {
        position: absolute;
        inset: -2px;
        background: linear-gradient(135deg, rgba(99, 102, 241, 0.4), rgba(168, 85, 247, 0.4), rgba(236, 72, 153, 0.4));
        border-radius: 9999px;
        filter: blur(8px);
        opacity: 0.6;
        z-index: -1;
        transition: opacity 0.3s ease;
    }

    .copilot-trigger:hover .copilot-trigger-glow {
        opacity: 1;
        filter: blur(12px);
    }

    .copilot-trigger-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 28px;
        height: 28px;
        color: #818cf8;
    }

    .copilot-trigger-icon svg.sparkles-icon {
        width: 22px;
        height: 22px;
        animation: copilot-sparkle-pulse 3s infinite ease-in-out;
    }

    @keyframes copilot-sparkle-pulse {
        0%, 100% {
            transform: rotate(0deg) scale(1);
            filter: drop-shadow(0 0 2px rgba(129, 140, 248, 0.4));
        }
        50% {
            transform: rotate(8deg) scale(1.1);
            filter: drop-shadow(0 0 8px rgba(168, 85, 247, 0.8));
        }
    }

    .copilot-trigger-label {
        font-size: 14px;
        font-weight: 600;
        letter-spacing: -0.01em;
        background: linear-gradient(135deg, #ffffff 0%, #cbd5e1 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .copilot-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 2px 6px;
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        border-radius: 9999px;
        background: rgba(99, 102, 241, 0.2);
        color: #a5b4fc;
        border: 1px solid rgba(99, 102, 241, 0.3);
    }

    .copilot-badge .badge-dot {
        width: 5px;
        height: 5px;
        border-radius: 50%;
        background-color: #10b981;
        box-shadow: 0 0 6px #10b981;
    }
</style>