@props(['trip' => null])

@php
    $tripId = $trip ? $trip->id : 0;
@endphp

<div id="viantryp-copilot-container" data-trip-id="{{ $tripId }}" data-csrf="{{ csrf_token() }}">
    <!-- Floating Trigger Button -->
    <button type="button" id="copilot-trigger-btn" class="copilot-trigger" aria-label="Abrir Viantryp Copilot"
        title="Viantryp Copilot - Asistente de Viaje">
        <div class="copilot-trigger-glow"></div>
        <div class="copilot-trigger-icon">
            <!-- Sparkles SVG -->
            <svg class="sparkles-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                stroke-linecap="round" stroke-linejoin="round">
                <path
                    d="m12 3-1.912 5.813a2 2 0 0 1-1.275 1.275L3 12l5.813 1.912a2 2 0 0 1 1.275 1.275L12 21l1.912-5.813a2 2 0 0 1 1.275-1.275L21 12l-5.813-1.912a2 2 0 0 1-1.275-1.275L12 3Z" />
                <path d="M5 3v4" />
                <path d="M19 17v4" />
                <path d="M3 5h4" />
                <path d="M17 19h4" />
            </svg>
        </div>
        <span class="copilot-badge">
            <span class="badge-dot"></span>
            <span>Tryp AI</span>
        </span>
    </button>

    <!-- Chat Modal Window / Sheet -->
    <div id="copilot-chat-window" class="copilot-window copilot-hidden" aria-hidden="true">
        <!-- Drag & Drop Visual Overlay -->
        <div id="copilot-drag-overlay" class="copilot-drag-overlay">
            <div class="drag-overlay-content">
                <div class="drag-icon-wrapper">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                        <polyline points="17 8 12 3 7 8" />
                        <line x1="12" y1="3" x2="12" y2="15" />
                    </svg>
                </div>
                <h4>Suelta tus archivos aquí</h4>
                <p>PDFs de reservas, vouchers o capturas de pantalla</p>
            </div>
        </div>

        <!-- Header -->
        <div class="copilot-header">
            <div class="copilot-header-left">
                <div class="copilot-avatar">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path
                            d="m12 3-1.912 5.813a2 2 0 0 1-1.275 1.275L3 12l5.813 1.912a2 2 0 0 1 1.275 1.275L12 21l1.912-5.813a2 2 0 0 1 1.275-1.275L21 12l-5.813-1.912a2 2 0 0 1-1.275-1.275L12 3Z" />
                    </svg>
                </div>
                <div class="copilot-info">
                    <div class="copilot-title-row">
                        <h3>Tryp AI</h3>
                        <span class="status-indicator" title="En línea y listo">
                            <span class="status-pulse"></span>
                            <span class="status-dot"></span>
                        </span>
                    </div>
                    <span class="copilot-subtitle">Asistente de itinerario inteligente</span>
                </div>
            </div>

            <div class="copilot-header-actions">
                <button type="button" id="copilot-clear-btn" class="copilot-header-btn" title="Limpiar conversación">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path d="M3 6h18"></path>
                        <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path>
                        <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path>
                    </svg>
                </button>
                <button type="button" id="copilot-close-btn" class="copilot-header-btn" title="Minimizar">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Messages Area (Scrollable) -->
        <div id="copilot-messages-container" class="copilot-messages">
            <!-- Initial Welcome Message -->
            <div class="copilot-msg msg-ai">
                <div class="msg-avatar">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path
                            d="m12 3-1.912 5.813a2 2 0 0 1-1.275 1.275L3 12l5.813 1.912a2 2 0 0 1 1.275 1.275L12 21l1.912-5.813a2 2 0 0 1 1.275-1.275L21 12l-5.813-1.912a2 2 0 0 1-1.275-1.275L12 3Z" />
                    </svg>
                </div>
                <div class="msg-bubble">
                    <p>¡Hola! Soy tu asistente de viajes <strong>Viantryp Copilot</strong> ✈️🏨</p>
                    <p>Puedes pedirme cambios en el itinerario o <strong>arrastrar tus reservas</strong> en PDF o imagen
                        (vuelos, hoteles, actividades) para leer sus datos y colocarlos automáticamente en el lienzo.
                    </p>

                    <div class="quick-prompts">
                        <button type="button" class="quick-prompt-btn"
                            data-prompt="¿Qué vuelos tengo programados en este viaje?">
                            ✈️ ¿Qué vuelos tengo?
                        </button>
                        <button type="button" class="quick-prompt-btn"
                            data-prompt="Recomiéndame actividades para los días libres">
                            💡 Sugerir actividades
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Typing Indicator (Hidden by default) -->
        <div id="copilot-typing-indicator" class="copilot-typing-wrapper copilot-hidden">
            <div class="msg-avatar ai-small">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path
                        d="m12 3-1.912 5.813a2 2 0 0 1-1.275 1.275L3 12l5.813 1.912a2 2 0 0 1 1.275 1.275L12 21l1.912-5.813a2 2 0 0 1 1.275-1.275L21 12l-5.813-1.912a2 2 0 0 1-1.275-1.275L12 3Z" />
                </svg>
            </div>
            <div class="typing-bubble">
                <span class="typing-text" id="copilot-typing-text">Analizando información...</span>
                <span class="dot"></span>
                <span class="dot"></span>
                <span class="dot"></span>
            </div>
        </div>

        <!-- Footer / Input Form -->
        <div class="copilot-footer">
            <!-- File Previews Container -->
            <div id="copilot-files-preview" class="copilot-files-preview copilot-hidden"></div>

            <form id="copilot-form" class="copilot-form" onsubmit="return false;">
                <!-- Hidden File Input -->
                <input type="file" id="copilot-file-input" class="copilot-file-input" multiple
                    accept=".pdf,image/png,image/jpeg,image/jpg,image/webp,application/pdf">

                <div class="copilot-input-box">
                    <button type="button" id="copilot-attach-btn" class="copilot-icon-btn attach-btn"
                        title="Adjuntar documentos o imágenes (PDF, JPG, PNG)">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path
                                d="m21.44 11.05-9.19 9.19a6 6 0 0 1-8.49-8.49l8.57-8.57A4 4 0 1 1 18 8.84l-8.59 8.57a2 2 0 0 1-2.83-2.83l8.49-8.48" />
                        </svg>
                    </button>

                    <textarea id="copilot-textarea" class="copilot-textarea" rows="1"
                        placeholder="Escribe o arrastra tus archivos aquí..."></textarea>

                    <button type="submit" id="copilot-send-btn" class="copilot-icon-btn send-btn" title="Enviar mensaje"
                        disabled>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <line x1="22" y1="2" x2="11" y2="13" />
                            <polygon points="22 2 15 22 11 13 2 9 22 2" />
                        </svg>
                    </button>
                </div>
            </form>
            <div class="copilot-footer-note">
                <span>Enter para enviar &bull; Shift + Enter para salto de línea</span>
            </div>
        </div>
    </div>
</div>

<style>
    /* ==========================================================================
   VIANTRYP COPILOT AI - STYLES
   ========================================================================== */
    #viantryp-copilot-container {
        font-family: 'Barlow', 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        color: #0f2a3a;
    }

    /* Floating Trigger Button */
    .copilot-trigger {
        position: fixed;
        bottom: 24px;
        right: 24px;
        z-index: 99990;
        display: flex;
        align-items: center;
        gap: 8px;
        background: var(--accent, var(--avatar-gradient, #1D63B8));
        color: #ffffff;
        border: none;
        border-radius: 50px;
        padding: 10px 18px 10px 14px;
        cursor: pointer;
        box-shadow: 0 12px 28px -4px rgba(15, 42, 58, 0.35), 0 6px 12px -2px rgba(15, 42, 58, 0.15);
        transition: all 0.28s cubic-bezier(0.16, 1, 0.3, 1);
        outline: none;
        user-select: none;
    }

    .copilot-trigger:hover {
        transform: translateY(-2px) scale(1.04);
        filter: brightness(1.08);
        box-shadow: 0 16px 32px -4px rgba(15, 42, 58, 0.45), 0 8px 16px -2px rgba(15, 42, 58, 0.2);
    }

    .copilot-trigger:active {
        transform: translateY(0) scale(0.98);
    }

    .copilot-trigger-glow {
        position: absolute;
        inset: -2px;
        border-radius: 50px;
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.4), rgba(29, 99, 184, 0));
        opacity: 0;
        transition: opacity 0.3s ease;
        pointer-events: none;
    }

    .copilot-trigger:hover .copilot-trigger-glow {
        opacity: 1;
    }

    .copilot-trigger-icon {
        width: 28px;
        height: 28px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .copilot-trigger-icon svg.sparkles-icon {
        width: 22px;
        height: 22px;
        stroke: #ffffff;
        animation: copilot-sparkle-pulse 3s infinite ease-in-out;
    }

    @keyframes copilot-sparkle-pulse {

        0%,
        100% {
            transform: rotate(0deg) scale(1);
        }

        50% {
            transform: rotate(8deg) scale(1.1);
        }
    }

    .copilot-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-family: 'Syne', sans-serif;
        font-size: 14px;
        font-weight: 700;
        letter-spacing: 0.5px;
        color: #ffffff;
    }

    .badge-dot {
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background-color: #22c55e;
        box-shadow: 0 0 8px #22c55e;
    }

    /* Chat Window Modal / Sheet */
    .copilot-window {
        position: fixed;
        bottom: 84px;
        right: 24px;
        width: 385px;
        height: 550px;
        max-width: calc(100vw - 32px);
        max-height: calc(100vh - 110px);
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 20px;
        box-shadow: 0 24px 48px -12px rgba(15, 42, 58, 0.25), 0 0 0 1px rgba(0, 0, 0, 0.04);
        display: flex;
        flex-direction: column;
        z-index: 99995;
        overflow: hidden;
        opacity: 1;
        transform: translateY(0) scale(1);
        transform-origin: bottom right;
        transition: opacity 0.25s cubic-bezier(0.16, 1, 0.3, 1), transform 0.25s cubic-bezier(0.16, 1, 0.3, 1), visibility 0.25s;
        visibility: visible;
    }

    .copilot-hidden {
        display: none !important;
    }

    .copilot-window.copilot-hidden {
        display: flex !important;
        opacity: 0;
        transform: translateY(18px) scale(0.94);
        pointer-events: none;
        visibility: hidden;
    }

    /* Drag Overlay */
    .copilot-drag-overlay {
        position: absolute;
        inset: 8px;
        background: rgba(240, 246, 255, 0.96);
        border: 2px dashed #1D63B8;
        border-radius: 16px;
        z-index: 50;
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.2s ease, transform 0.2s ease;
        transform: scale(0.98);
    }

    .copilot-window.drag-active .copilot-drag-overlay {
        opacity: 1;
        pointer-events: all;
        transform: scale(1);
    }

    .drag-overlay-content {
        text-align: center;
        padding: 20px;
    }

    .drag-icon-wrapper {
        width: 54px;
        height: 54px;
        border-radius: 50%;
        background: var(--accent, var(--avatar-gradient, #1D63B8));
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 12px;
        box-shadow: 0 8px 16px rgba(15, 42, 58, 0.2);
    }

    .drag-icon-wrapper svg {
        width: 26px;
        height: 26px;
    }

    .drag-overlay-content h4 {
        font-family: 'Syne', sans-serif;
        font-size: 17px;
        font-weight: 700;
        color: #0f2a3a;
        margin-bottom: 4px;
    }

    .drag-overlay-content p {
        font-size: 13px;
        color: #64748b;
        margin: 0;
    }

    /* Header */
    .copilot-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 14px 18px;
        background: #ffffff;
        border-bottom: 1px solid #f1f5f9;
        user-select: none;
    }

    .copilot-header-left {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .copilot-avatar {
        width: 38px;
        height: 38px;
        border-radius: 12px;
        background: var(--avatar-gradient, var(--accent, #1D63B8));
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 10px rgba(15, 42, 58, 0.15);
    }

    .copilot-avatar svg {
        width: 20px;
        height: 20px;
    }

    .copilot-info {
        display: flex;
        flex-direction: column;
    }

    .copilot-title-row {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .copilot-title-row h3 {
        font-family: 'Syne', sans-serif;
        font-size: 15.5px;
        font-weight: 700;
        color: #0f2a3a;
        letter-spacing: -0.2px;
        margin: 0;
        line-height: 1.1;
    }

    .status-indicator {
        position: relative;
        width: 8px;
        height: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .status-pulse {
        position: absolute;
        width: 100%;
        height: 100%;
        border-radius: 50%;
        background: #22c55e;
        opacity: 0.6;
        animation: status-ping 2s cubic-bezier(0, 0, 0.2, 1) infinite;
    }

    .status-dot {
        position: relative;
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background-color: #22c55e;
    }

    @keyframes status-ping {

        75%,
        100% {
            transform: scale(2.2);
            opacity: 0;
        }
    }

    .copilot-subtitle {
        font-size: 11.5px;
        color: #64748b;
        font-weight: 500;
    }

    .copilot-header-actions {
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .copilot-header-btn {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        border: none;
        background: transparent;
        color: #94a3b8;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.18s ease;
    }

    .copilot-header-btn:hover {
        background: #f1f5f9;
        color: #0f2a3a;
    }

    .copilot-header-btn svg {
        width: 17px;
        height: 17px;
    }

    /* Messages Area */
    .copilot-messages {
        flex: 1;
        overflow-y: auto;
        padding: 16px;
        display: flex;
        flex-direction: column;
        gap: 14px;
        background: #fafbfe;
        scroll-behavior: smooth;
    }

    .copilot-messages::-webkit-scrollbar {
        width: 5px;
    }

    .copilot-messages::-webkit-scrollbar-track {
        background: transparent;
    }

    .copilot-messages::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 10px;
    }

    .copilot-msg {
        display: flex;
        gap: 10px;
        max-width: 92%;
        animation: msg-fade-in 0.25s ease-out;
    }

    @keyframes msg-fade-in {
        from {
            opacity: 0;
            transform: translateY(6px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .copilot-msg.msg-ai {
        align-self: flex-start;
    }

    .copilot-msg.msg-user {
        align-self: flex-end;
        flex-direction: row-reverse;
    }

    .msg-avatar {
        width: 28px;
        height: 28px;
        border-radius: 8px;
        background: var(--accent, var(--avatar-gradient, #1D63B8));
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        margin-top: 2px;
    }

    .msg-avatar svg {
        width: 15px;
        height: 15px;
    }

    .msg-avatar.ai-small {
        width: 24px;
        height: 24px;
    }

    .msg-avatar.ai-small svg {
        width: 13px;
        height: 13px;
    }

    .msg-bubble {
        padding: 10px 14px;
        border-radius: 14px;
        font-size: 13.5px;
        line-height: 1.45;
        word-break: break-word;
    }

    .msg-ai .msg-bubble {
        background: #ffffff;
        color: #1e293b;
        border: 1px solid #e2e8f0;
        border-top-left-radius: 4px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.02);
    }

    .msg-ai .msg-bubble p {
        margin: 0 0 8px 0;
    }

    .msg-ai .msg-bubble p:last-child {
        margin-bottom: 0;
    }

    .msg-user .msg-bubble {
        background: var(--accent, var(--avatar-gradient, #1D63B8));
        color: #ffffff;
        border-top-right-radius: 4px;
        box-shadow: 0 3px 8px rgba(15, 42, 58, 0.2);
    }

    .msg-user-files {
        display: flex;
        flex-direction: column;
        gap: 4px;
        margin-top: 6px;
    }

    .msg-file-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: rgba(255, 255, 255, 0.18);
        padding: 4px 8px;
        border-radius: 6px;
        font-size: 11.5px;
    }

    .msg-file-pill svg {
        width: 13px;
        height: 13px;
    }

    /* Quick Prompts inside Welcome Message */
    .quick-prompts {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
        margin-top: 10px;
    }

    .quick-prompt-btn {
        background: #f1f5f9;
        border: 1px solid #cbd5e1;
        color: #1e293b;
        border-radius: 20px;
        padding: 5px 10px;
        font-size: 11.5px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.15s ease;
        text-align: left;
    }

    .quick-prompt-btn:hover {
        background: #e2e8f0;
        border-color: #94a3b8;
        color: var(--accent, #1D63B8);
        transform: translateY(-1px);
    }

    /* Action Cards rendered by AI */
    .copilot-action-card {
        margin-top: 10px;
        background: #ffffff;
        border: 1px solid #cbd5e1;
        border-left: 4px solid var(--accent, #1D63B8);
        border-radius: 10px;
        padding: 10px 12px;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.04);
    }

    .action-card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 6px;
    }

    .action-card-type {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        color: var(--accent, #1D63B8);
        background: var(--accent-light, rgba(29, 99, 184, 0.08));
        padding: 2px 7px;
        border-radius: 4px;
    }

    .action-card-day {
        font-size: 11.5px;
        font-weight: 700;
        color: #64748b;
    }

    .action-card-title {
        font-weight: 700;
        font-size: 13.5px;
        color: #0f2a3a;
        margin-bottom: 4px;
    }

    .action-card-meta {
        font-size: 12px;
        color: #64748b;
        margin-bottom: 10px;
        line-height: 1.35;
    }

    .action-card-btn, .copilot-apply-btn {
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        background: var(--accent, var(--avatar-gradient, #1D63B8));
        color: #ffffff;
        border: none;
        border-radius: 8px;
        padding: 7px 12px;
        font-size: 12.5px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.15s ease;
    }

    .action-card-btn:hover, .copilot-apply-btn:hover {
        filter: brightness(1.08);
    }

    .action-card-btn.applied, .copilot-apply-btn.applied {
        background: #22c55e !important;
        cursor: default;
    }

    /* Typing indicator */
    .copilot-typing-wrapper {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 0 16px 10px 16px;
        background: #fafbfe;
    }

    .typing-bubble {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        padding: 6px 12px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        gap: 5px;
        font-size: 12px;
        color: #64748b;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.02);
    }

    .typing-text {
        margin-right: 2px;
        font-weight: 500;
    }

    .typing-bubble .dot {
        width: 5px;
        height: 5px;
        background-color: var(--accent, #1D63B8);
        border-radius: 50%;
        animation: typing-bounce 1.3s infinite ease-in-out;
    }

    .typing-bubble .dot:nth-child(2) {
        animation-delay: 0.15s;
    }

    .typing-bubble .dot:nth-child(3) {
        animation-delay: 0.3s;
    }

    .typing-bubble .dot:nth-child(4) {
        animation-delay: 0.45s;
    }

    @keyframes typing-bounce {

        0%,
        60%,
        100% {
            transform: translateY(0);
            opacity: 0.4;
        }

        30% {
            transform: translateY(-4px);
            opacity: 1;
        }
    }

    /* Footer / Input */
    .copilot-footer {
        padding: 10px 14px 12px;
        background: #ffffff;
        border-top: 1px solid #f1f5f9;
    }

    /* File previews container */
    .copilot-files-preview {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
        margin-bottom: 8px;
        max-height: 80px;
        overflow-y: auto;
    }

    .copilot-file-chip {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #eff6ff;
        border: 1px solid #bfdbfe;
        border-radius: 8px;
        padding: 4px 8px;
        font-size: 11.5px;
        color: #1e3a8a;
        max-width: 100%;
    }

    .copilot-file-chip svg {
        width: 14px;
        height: 14px;
        flex-shrink: 0;
    }

    .copilot-file-chip span {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        max-width: 170px;
        font-weight: 600;
    }

    .copilot-file-remove {
        background: transparent;
        border: none;
        color: #93c5fd;
        cursor: pointer;
        padding: 0 2px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: color 0.15s;
    }

    .copilot-file-remove:hover {
        color: #ef4444;
    }

    .copilot-file-input {
        display: none;
    }

    .copilot-input-box {
        display: flex;
        align-items: flex-end;
        gap: 8px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 6px 10px;
        transition: border-color 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
    }

    .copilot-input-box:focus-within {
        background: #ffffff;
        border-color: var(--accent, #1D63B8);
        box-shadow: 0 0 0 3px rgba(15, 42, 58, 0.1);
    }

    .copilot-textarea {
        flex: 1;
        border: none;
        background: transparent;
        resize: none;
        outline: none;
        font-family: inherit;
        font-size: 13.5px;
        color: #0f2a3a;
        max-height: 90px;
        min-height: 24px;
        line-height: 1.4;
        padding: 4px 0;
    }

    .copilot-textarea::placeholder {
        color: #94a3b8;
    }

    .copilot-icon-btn {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        border: none;
        background: transparent;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        flex-shrink: 0;
        transition: all 0.18s ease;
    }

    .copilot-icon-btn svg {
        width: 17px;
        height: 17px;
    }

    .attach-btn {
        color: #64748b;
    }

    .attach-btn:hover {
        background: #e2e8f0;
        color: #0f2a3a;
    }

    .send-btn {
        background: var(--accent, var(--avatar-gradient, #1D63B8));
        color: #ffffff;
    }

    .send-btn:hover:not(:disabled) {
        filter: brightness(1.08);
        transform: scale(1.05);
    }

    .send-btn:disabled {
        background: #cbd5e1;
        color: #ffffff;
        cursor: not-allowed;
        opacity: 0.6;
    }

    .copilot-footer-note {
        font-size: 10.5px;
        color: #94a3b8;
        text-align: center;
        margin-top: 6px;
    }

    @media (max-width: 480px) {
        .copilot-window {
            right: 12px;
            bottom: 80px;
            width: calc(100vw - 24px);
            height: calc(100vh - 100px);
        }

        .copilot-trigger {
            right: 16px;
            bottom: 16px;
        }
    }
</style>

<script>
    (function () {
        'use strict';

        // State
        const state = {
            isOpen: false,
            isLoading: false,
            files: [],
            tripId: null,
            csrfToken: ''
        };

        // DOM Elements
        let container, triggerBtn, chatWindow, closeBtn, clearBtn, messagesContainer,
            typingIndicator, typingText, form, textarea, fileInput, attachBtn, sendBtn,
            filesPreview, dragOverlay;

        function init() {
            container = document.getElementById('viantryp-copilot-container');
            if (!container) return;

            state.tripId = container.dataset.tripId || 0;
            state.csrfToken = container.dataset.csrf || document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

            triggerBtn = document.getElementById('copilot-trigger-btn');
            chatWindow = document.getElementById('copilot-chat-window');
            closeBtn = document.getElementById('copilot-close-btn');
            clearBtn = document.getElementById('copilot-clear-btn');
            messagesContainer = document.getElementById('copilot-messages-container');
            typingIndicator = document.getElementById('copilot-typing-indicator');
            typingText = document.getElementById('copilot-typing-text');
            form = document.getElementById('copilot-form');
            textarea = document.getElementById('copilot-textarea');
            fileInput = document.getElementById('copilot-file-input');
            attachBtn = document.getElementById('copilot-attach-btn');
            sendBtn = document.getElementById('copilot-send-btn');
            filesPreview = document.getElementById('copilot-files-preview');
            dragOverlay = document.getElementById('copilot-drag-overlay');

            bindEvents();
            checkSendState();
        }

        function bindEvents() {
            // Toggle chat window
            triggerBtn.addEventListener('click', toggleChat);
            closeBtn.addEventListener('click', closeChat);

            // Clear chat
            clearBtn.addEventListener('click', () => {
                if (confirm('¿Deseas reiniciar la conversación con Copilot?')) {
                    resetChat();
                }
            });

            // Quick prompts
            chatWindow.addEventListener('click', (e) => {
                const promptBtn = e.target.closest('.quick-prompt-btn');
                if (promptBtn) {
                    const prompt = promptBtn.dataset.prompt;
                    if (prompt) {
                        textarea.value = prompt;
                        adjustTextareaHeight();
                        checkSendState();
                        submitMessage();
                    }
                }
            });

            // Input & auto-resize
            textarea.addEventListener('input', () => {
                adjustTextareaHeight();
                checkSendState();
            });

            textarea.addEventListener('keydown', (e) => {
                if (e.key === 'Enter' && !e.shiftKey) {
                    e.preventDefault();
                    if (!sendBtn.disabled && !state.isLoading) {
                        submitMessage();
                    }
                }
            });

            // Attach files click
            attachBtn.addEventListener('click', () => fileInput.click());

            fileInput.addEventListener('change', (e) => {
                handleFilesSelected(Array.from(e.target.files));
                fileInput.value = ''; // Reset input to allow selecting same file again
            });

            // Form submit
            form.addEventListener('submit', (e) => {
                e.preventDefault();
                if (!sendBtn.disabled && !state.isLoading) {
                    submitMessage();
                }
            });

            // Drag & Drop on chat window
            ['dragenter', 'dragover'].forEach(eventName => {
                chatWindow.addEventListener(eventName, (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                    chatWindow.classList.add('drag-active');
                });
            });

            ['dragleave', 'drop'].forEach(eventName => {
                chatWindow.addEventListener(eventName, (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                    if (eventName === 'drop' || e.relatedTarget === null || !chatWindow.contains(e.relatedTarget)) {
                        chatWindow.classList.remove('drag-active');
                    }
                });
            });

            chatWindow.addEventListener('drop', (e) => {
                if (e.dataTransfer && e.dataTransfer.files && e.dataTransfer.files.length > 0) {
                    handleFilesSelected(Array.from(e.dataTransfer.files));
                }
            });
        }

        function toggleChat() {
            if (state.isOpen) {
                closeChat();
            } else {
                openChat();
            }
        }

        function openChat() {
            state.isOpen = true;
            chatWindow.classList.remove('copilot-hidden');
            chatWindow.setAttribute('aria-hidden', 'false');
            setTimeout(() => textarea.focus(), 150);
            scrollToBottom();
        }

        function closeChat() {
            state.isOpen = false;
            chatWindow.classList.add('copilot-hidden');
            chatWindow.setAttribute('aria-hidden', 'true');
        }

        function adjustTextareaHeight() {
            textarea.style.height = 'auto';
            textarea.style.height = Math.min(textarea.scrollHeight, 90) + 'px';
        }

        function checkSendState() {
            const hasText = textarea.value.trim().length > 0;
            const hasFiles = state.files.length > 0;
            sendBtn.disabled = (!hasText && !hasFiles) || state.isLoading;
        }

        function handleFilesSelected(newFiles) {
            const validExtensions = ['pdf', 'png', 'jpg', 'jpeg', 'webp'];
            let added = 0;

            newFiles.forEach(file => {
                if (state.files.length >= 2) {
                    appendAiMessage('Solo puedes adjuntar un máximo de 2 archivos por mensaje.');
                    return;
                }

                const ext = file.name.split('.').pop().toLowerCase();
                if (!validExtensions.includes(ext)) {
                    appendAiMessage('Formato no válido. Solo se permiten archivos PDF o imágenes (PNG, JPG, WEBP).');
                    return;
                }

                if (file.size > 10 * 1024 * 1024) {
                    appendAiMessage(`El archivo "${file.name}" es demasiado grande (máximo 10MB).`);
                    return;
                }

                // Prevent duplicate files with same name and size
                const exists = state.files.some(f => f.name === file.name && f.size === file.size);
                if (!exists) {
                    state.files.push(file);
                    added++;
                }
            });

            if (added > 0) {
                renderFilePreviews();
                checkSendState();
                if (!state.isOpen) openChat();
            }
        }

        function renderFilePreviews() {
            if (state.files.length === 0) {
                filesPreview.innerHTML = '';
                filesPreview.classList.add('copilot-hidden');
                return;
            }

            filesPreview.classList.remove('copilot-hidden');
            filesPreview.innerHTML = '';

            state.files.forEach((file, index) => {
                const chip = document.createElement('div');
                chip.className = 'copilot-file-chip';

                const isPdf = file.name.toLowerCase().endsWith('.pdf');
                const iconSvg = isPdf
                    ? '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>'
                    : '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>';

                chip.innerHTML = `
                ${iconSvg}
                <span title="${escapeHtml(file.name)}">${escapeHtml(file.name)}</span>
                <button type="button" class="copilot-file-remove" data-index="${index}" title="Quitar archivo">&times;</button>
            `;

                chip.querySelector('.copilot-file-remove').addEventListener('click', (e) => {
                    const idx = parseInt(e.currentTarget.dataset.index, 10);
                    state.files.splice(idx, 1);
                    renderFilePreviews();
                    checkSendState();
                });

                filesPreview.appendChild(chip);
            });
        }

        function scrollToBottom() {
            setTimeout(() => {
                messagesContainer.scrollTop = messagesContainer.scrollHeight;
            }, 50);
        }

        function appendUserMessage(text, files) {
            const msgDiv = document.createElement('div');
            msgDiv.className = 'copilot-msg msg-user';

            let filesHtml = '';
            if (files && files.length > 0) {
                filesHtml = '<div class="msg-user-files">';
                files.forEach(f => {
                    const isPdf = f.name.toLowerCase().endsWith('.pdf');
                    const iconSvg = isPdf
                        ? '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:12px;height:12px;"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline></svg>'
                        : '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:12px;height:12px;"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect></svg>';
                    filesHtml += `<span class="user-file-badge">${iconSvg} ${escapeHtml(f.name)}</span>`;
                });
                filesHtml += '</div>';
            }

            let textHtml = '';
            if (text) {
                textHtml = `<p>${escapeHtml(text)}</p>`;
            }

            msgDiv.innerHTML = `
            <div class="msg-bubble">
                ${filesHtml}
                ${textHtml}
            </div>
        `;

            messagesContainer.appendChild(msgDiv);
            scrollToBottom();
        }

        function appendAiMessage(text, actions = []) {
            const msgDiv = document.createElement('div');
            msgDiv.className = 'copilot-msg msg-ai';

            let formattedText = formatAiResponse(text);

            // Filter out FOCUS_DAY from button cards since FOCUS_DAY automatically navigates
            const cardActions = actions.filter(a => (a.action || a.type) !== 'FOCUS_DAY');

            let cardsHtml = '';
            if (cardActions && cardActions.length > 0) {
                cardActions.forEach((action, idx) => {
                    const typeIcon = getTypeIcon(action.type);
                    const typeLabel = getTypeLabel(action.type);
                    const dayNum = action.day || 1;
                    const title = escapeHtml(action.title || 'Nuevo elemento');
                    const details = formatActionDetails(action.type, action.data);

                    cardsHtml += `
                    <div class="copilot-action-card" data-action-index="${idx}">
                        <div class="action-card-header">
                            <span class="action-type-badge">
                                ${typeIcon} ${typeLabel}
                            </span>
                            <span class="action-day-badge">Día ${dayNum}</span>
                        </div>
                        <div class="action-card-title">${title}</div>
                        ${details ? `<div class="action-card-details">${details}</div>` : ''}
                        <div class="action-card-footer">
                            <button type="button" class="copilot-apply-btn" data-action-index="${idx}">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:14px;height:14px;"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                                Agregar al lienzo
                            </button>
                        </div>
                    </div>
                `;
                });
            }

            msgDiv.innerHTML = `
            <div class="msg-avatar">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="m12 3-1.912 5.813a2 2 0 0 1-1.275 1.275L3 12l5.813 1.912a2 2 0 0 1 1.275 1.275L12 21l1.912-5.813a2 2 0 0 1 1.275-1.275L21 12l-5.813-1.912a2 2 0 0 1-1.275-1.275L12 3Z"/>
                </svg>
            </div>
            <div class="msg-bubble">
                ${formattedText}
                ${cardsHtml}
            </div>
        `;

            // Attach action handlers to buttons
            msgDiv.querySelectorAll('.copilot-apply-btn').forEach(btn => {
                const actionIdx = parseInt(btn.dataset.actionIndex, 10);
                btn._actionData = cardActions[actionIdx];
                btn.addEventListener('click', handleApplyAction);
            });

            messagesContainer.appendChild(msgDiv);
            scrollToBottom();

            // Check if there is a FOCUS_DAY action to trigger navigation immediately
            const focusAction = actions.find(a => (a.action || a.type) === 'FOCUS_DAY');
            if (focusAction && window.ViantrypCopilot && typeof window.ViantrypCopilot.onApplyAction === 'function') {
                window.ViantrypCopilot.onApplyAction(focusAction);
            }
        }

        function handleApplyAction(e) {
            const btn = e.currentTarget;
            try {
                const actionData = btn._actionData;
                if (!actionData) return;

                // Disparar evento global para que el editor de Viantryp lo inserte en el lienzo
                if (window.ViantrypCopilot && typeof window.ViantrypCopilot.onApplyAction === 'function') {
                    window.ViantrypCopilot.onApplyAction(actionData);
                } else {
                    console.log('Action applied to canvas:', actionData);
                }

                btn.classList.add('applied');
                btn.innerHTML = `
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:14px;height:14px;"><polyline points="20 6 9 17 4 12"></polyline></svg>
                ¡Agregado al lienzo!
            `;
                btn.disabled = true;
            } catch (err) {
                console.error('Error applying action:', err);
            }
        }

        function setTyping(isTyping, customText = 'Analizando información...') {
            state.isLoading = isTyping;
            if (isTyping) {
                typingText.textContent = customText;
                typingIndicator.classList.remove('copilot-hidden');
                scrollToBottom();
            } else {
                typingIndicator.classList.add('copilot-hidden');
            }
            checkSendState();
        }

        async function submitMessage() {
            const text = textarea.value.trim();
            const filesToSend = [...state.files];

            if ((!text && filesToSend.length === 0) || state.isLoading) {
                return;
            }

            // Add user message to UI
            appendUserMessage(text, filesToSend);

            // Reset input state immediately
            textarea.value = '';
            state.files = [];
            renderFilePreviews();
            adjustTextareaHeight();
            checkSendState();

            // Show typing indicator
            const typingMsg = filesToSend.length > 0
                ? 'Leyendo documentos y extrayendo fechas...'
                : 'Pensando respuesta...';
            setTyping(true, typingMsg);

            // Prepare FormData for Backend POST request
            const formData = new FormData();
            formData.append('message', text);
            formData.append('_token', state.csrfToken);

            filesToSend.forEach((file, i) => {
                formData.append(`files[${i}]`, file);
            });

            try {
                const endpoint = `/trips/${state.tripId}/ai/chat-agent`;
                const response = await fetch(endpoint, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': state.csrfToken,
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                const data = await response.json().catch(() => ({}));

                setTyping(false);

                if (response.status === 429) {
                    appendAiMessage('Has enviado varias solicitudes seguidas. Por favor espera un momento antes de enviar otra consulta.');
                    return;
                }

                if (response.status === 422) {
                    const errorMsg = data.error || data.message || 'El archivo es demasiado grande (máximo 10MB) o el formato no es válido (solo PDF o imágenes).';
                    appendAiMessage(errorMsg);
                    return;
                }

                if (response.ok && data.success) {
                    appendAiMessage(data.message || 'He procesado tu solicitud.', data.actions || []);
                } else {
                    appendAiMessage(data.message || data.error || 'Tuvimos un problema leyendo tu archivo en este momento. Por favor, intenta de nuevo o sube una imagen más clara.');
                }
            } catch (error) {
                console.error('Copilot API Error:', error);
                setTyping(false);
                appendAiMessage('Tuvimos un problema leyendo tu archivo en este momento. Por favor, intenta de nuevo o sube una imagen más clara.');
            }
        }

        function resetChat() {
            messagesContainer.innerHTML = `
            <div class="copilot-msg msg-ai">
                <div class="msg-avatar">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="m12 3-1.912 5.813a2 2 0 0 1-1.275 1.275L3 12l5.813 1.912a2 2 0 0 1 1.275 1.275L12 21l1.912-5.813a2 2 0 0 1 1.275-1.275L21 12l-5.813-1.912a2 2 0 0 1-1.275-1.275L12 3Z"/>
                    </svg>
                </div>
                <div class="msg-bubble">
                    <p>¡Conversación reiniciada! ¿En qué te puedo ayudar hoy con tu itinerario?</p>
                </div>
            </div>
        `;
            state.files = [];
            renderFilePreviews();
            checkSendState();
        }

        // Helper functions
        function escapeHtml(string) {
            if (!string) return '';
            const entityMap = {
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#39;'
            };
            return String(string).replace(/[&<>"']/g, s => entityMap[s]);
        }

        function formatAiResponse(text) {
            if (!text) return '';
            let formatted = escapeHtml(text);
            // Basic markdown formatting
            formatted = formatted.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
            formatted = formatted.replace(/\*(.*?)\*/g, '<em>$1</em>');
            formatted = formatted.replace(/\n\n/g, '</p><p>');
            formatted = formatted.replace(/\n/g, '<br>');
            return `<p>${formatted}</p>`;
        }

        function getTypeIcon(type) {
            const icons = {
                flight: '✈️',
                hotel: '🏨',
                activity: '📍',
                transport: '🚗',
                note: '📝'
            };
            return icons[type] || '📌';
        }

        function getTypeLabel(type) {
            const labels = {
                flight: 'Vuelo',
                hotel: 'Hotel / Alojamiento',
                activity: 'Actividad',
                transport: 'Transporte',
                note: 'Nota'
            };
            return labels[type] || 'Elemento';
        }

        function formatActionMeta(act) {
            const parts = [];
            if (act.data?.departure_time || act.data?.check_in || act.data?.time) {
                parts.push(`⏰ ${act.data.departure_time || act.data.check_in || act.data.time}`);
            }
            if (act.data?.address || act.data?.departure_airport) {
                parts.push(`📍 ${act.data.address || (act.data.departure_airport + (act.data.arrival_airport ? ' → ' + act.data.arrival_airport : ''))}`);
            }
            if (act.data?.confirmation_code) {
                parts.push(`🔖 Código: ${act.data.confirmation_code}`);
            }
            return parts.join(' &bull; ');
        }

        // Public API
        window.ViantrypCopilot = Object.assign(window.ViantrypCopilot || {}, {
            open: openChat,
            close: closeChat,
            toggle: toggleChat,
            addFile: (file) => handleFilesSelected([file])
        });

        // Auto-init on DOM ready
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', init);
        } else {
            init();
        }
    })();
</script>