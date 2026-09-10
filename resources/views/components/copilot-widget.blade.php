@props(['trip'])

@php
    $tripId = is_object($trip) ? ($trip->id ?? '') : $trip;
@endphp

<!-- Viantryp Copilot Container -->
<div id="viantryp-copilot-container" data-trip-id="{{ $tripId }}">
    <!-- Floating Trigger Button -->
    <button type="button" id="copilot-trigger-btn" class="copilot-trigger" aria-label="Abrir Tryp AI Assistant" title="Tryp AI - Asistente de Ingesta">
        <div class="copilot-trigger-glow"></div>
        <div class="copilot-trigger-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="sparkles-icon">
                <path d="m12 3-1.912 5.813a2 2 0 0 1-1.275 1.275L3 12l5.813 1.912a2 2 0 0 1 1.275 1.275L12 21l1.912-5.813a2 2 0 0 1 1.275-1.275L21 12l-5.813-1.912a2 2 0 0 1-1.275-1.275L12 3Z" />
                <path d="M5 3v4" />
                <path d="M19 17v4" />
                <path d="M3 5h4" />
                <path d="M17 19h4" />
            </svg>
        </div>
        <span class="copilot-trigger-label">Tryp AI</span>
    </button>

    <!-- Slide-over Drawer / Panel -->
    <div id="trypai-drawer" class="trypai-drawer hidden">
        <div class="trypai-drawer-overlay" id="trypai-drawer-backdrop"></div>
        <div class="trypai-drawer-content">
            <!-- Header -->
            <div class="trypai-header">
                <div class="trypai-header-title">
                    <div class="trypai-logo-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="20" height="20">
                            <path d="m12 3-1.912 5.813a2 2 0 0 1-1.275 1.275L3 12l5.813 1.912a2 2 0 0 1 1.275 1.275L12 21l1.912-5.813a2 2 0 0 1 1.275-1.275L21 12l-5.813-1.912a2 2 0 0 1-1.275-1.275L12 3Z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="trypai-title-text">Tryp AI</h3>
                        <span class="trypai-sub-badge">Asistente de Itinerarios</span>
                    </div>
                </div>
                <button type="button" id="trypai-close-btn" class="trypai-close-btn" aria-label="Cerrar">&times;</button>
            </div>

            <!-- Body Area (Chat Thread) -->
            <div class="trypai-body" id="trypai-chat-thread">
                <!-- Single Welcome & Explanation Bubble -->
                <div class="chat-msg assistant">
                    <div class="chat-avatar">✨</div>
                    <div class="chat-bubble">
                        👋 ¡Hola! Soy <strong>Tryp AI</strong>, tu asistente para estructurar tu viaje en Viantryp.<br><br>
                        Mi objetivo es ayudarte a agregar tus vuelos, hospedajes, actividades y reservas directamente al lienzo de tu itinerario.
                    </div>
                </div>

                <!-- Message 3: Question & Mode Options -->
                <div class="chat-msg assistant" id="chat-question-msg">
                    <div class="chat-avatar">✨</div>
                    <div class="chat-bubble">
                        💬 <strong>¿Cómo deseas agregar información a tu itinerario hoy?</strong>
                        <div class="chat-options-grid">
                            <button type="button" class="chat-pill-btn" id="btn-mode-file">
                                📄 Cargar Archivo / PDF
                            </button>
                            <button type="button" class="chat-pill-btn" id="btn-mode-text">
                                ✍️ Pegar Texto / Confirmación
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Dynamic User Choice Bubble -->
                <div class="chat-msg user hidden" id="chat-user-choice">
                    <div class="chat-bubble" id="chat-user-choice-text"></div>
                </div>

                <!-- Assistant Response Prompt -->
                <div class="chat-msg assistant hidden" id="chat-assistant-prompt">
                    <div class="chat-avatar">✨</div>
                    <div class="chat-bubble">
                        <span id="chat-prompt-text"></span>
                        <div class="chat-change-mode-row">
                            <button type="button" id="btn-change-mode" class="chat-change-mode-btn">🔄 Cambiar opción</button>
                        </div>
                    </div>
                </div>

                <!-- Input Section: File Dropzone -->
                <div id="trypai-section-file" class="trypai-input-section hidden">
                    <div class="trypai-dropzone" id="trypai-dropzone">
                        <input type="file" id="trypai-file-input" multiple accept=".pdf,.png,.jpg,.jpeg" class="trypai-file-input" />
                        <div class="trypai-dropzone-content">
                            <div class="trypai-upload-icon">📁</div>
                            <p class="trypai-drop-title">Arrastra tus archivos aquí o haz clic</p>
                            <p class="trypai-drop-hint">PDFs de reservas, billetes de avión, confirmaciones de Booking/Airbnb o vouchers en imagen</p>
                        </div>
                    </div>
                    <div id="trypai-file-list" class="trypai-file-list"></div>
                </div>

                <!-- Input Section: Free Text -->
                <div id="trypai-section-text" class="trypai-input-section hidden">
                    <textarea id="trypai-text-input" class="trypai-textarea" rows="5" placeholder="Pega aquí el correo de confirmación, WhatsApp, notas de Booking o itinerario de tu viaje..."></textarea>
                    <p class="trypai-text-hint">💡 Ej: <em>"Vuelo Avianca AV120 sale de BOG a las 14:30 el 12 de Octubre y llega a MAD a las 06:00 del 13."</em></p>
                </div>

                <!-- Action Button -->
                <div class="trypai-action-row hidden" id="trypai-action-row">
                    <button type="button" id="trypai-btn-submit" class="trypai-submit-btn">
                        <span>Analizar información</span>
                    </button>
                </div>

                <!-- Loading / Skeleton State -->
                <div id="trypai-loading" class="trypai-loading hidden">
                    <div class="trypai-spinner-glow"></div>
                    <p class="trypai-loading-text">⚙️ Analizando tus reservas y organizando la línea de tiempo...</p>
                    <div class="trypai-skeleton-cards">
                        <div class="trypai-skeleton-line"></div>
                        <div class="trypai-skeleton-line short"></div>
                    </div>
                </div>

                <!-- Loop UX UX State -->
                <div id="trypai-loop-ux" class="trypai-loop-ux hidden">
                    <div class="chat-msg assistant">
                        <div class="chat-avatar">✨</div>
                        <div class="chat-bubble">
                            <div id="trypai-loop-msg"></div>
                            <div class="trypai-loop-options" id="trypai-loop-options"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Pre-Visualización Cronológica (IngestionPreviewModal) -->
    <div id="ingestion-preview-modal" class="ingestion-modal-backdrop hidden">
        <div class="ingestion-modal-card">
            <!-- Modal Header -->
            <div class="ingestion-modal-header">
                <div>
                    <h3 class="ingestion-modal-title">🗓️ Pre-visualización Cronológica de Reservas</h3>
                    <p class="ingestion-modal-subtitle">Revisa y edita los eventos detectados antes de inyectarlos al lienzo de viaje.</p>
                </div>
                <button type="button" id="btn-preview-close" class="trypai-close-btn">&times;</button>
            </div>

            <!-- Modal Content (Grouped Days) -->
            <div class="ingestion-modal-body" id="ingestion-preview-body">
                <!-- Grouped day blocks will be dynamically rendered here -->
            </div>

            <!-- Modal Footer -->
            <div class="ingestion-modal-footer">
                <button type="button" id="btn-preview-cancel" class="ingestion-btn-secondary">
                    Cancelar / Descartar
                </button>
                <button type="button" id="btn-preview-confirm" class="ingestion-btn-primary">
                    🚀 Agregar al Lienzo (<span id="preview-selected-count">0</span>)
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    #viantryp-copilot-container {
        position: fixed;
        bottom: 24px;
        right: 24px;
        z-index: 9999;
        font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
    }

    /* Trigger Button (Clean without badge) */
    .copilot-trigger {
        position: relative;
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 10px 18px 10px 14px;
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
        color: #ffffff;
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 9999px;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.4), 0 0 15px rgba(99, 102, 241, 0.3);
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        user-select: none;
        outline: none;
    }

    .copilot-trigger:hover {
        transform: translateY(-2px) scale(1.03);
        box-shadow: 0 14px 30px -5px rgba(0, 0, 0, 0.5), 0 0 25px rgba(99, 102, 241, 0.6);
        border-color: rgba(99, 102, 241, 0.6);
    }

    .copilot-trigger-glow {
        position: absolute;
        inset: -2px;
        background: linear-gradient(135deg, rgba(99, 102, 241, 0.5), rgba(168, 85, 247, 0.5), rgba(236, 72, 153, 0.5));
        border-radius: 9999px;
        filter: blur(8px);
        opacity: 0.7;
        z-index: -1;
        transition: opacity 0.3s ease;
    }

    .copilot-trigger-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 24px;
        height: 24px;
        color: #818cf8;
    }

    .sparkles-icon {
        width: 20px;
        height: 20px;
        animation: copilot-sparkle-pulse 3s infinite ease-in-out;
    }

    @keyframes copilot-sparkle-pulse {
        0%, 100% { transform: rotate(0deg) scale(1); filter: drop-shadow(0 0 2px rgba(129, 140, 248, 0.4)); }
        50% { transform: rotate(10deg) scale(1.15); filter: drop-shadow(0 0 8px rgba(168, 85, 247, 0.8)); }
    }

    .copilot-trigger-label {
        font-size: 14px;
        font-weight: 700;
        letter-spacing: -0.01em;
        background: linear-gradient(135deg, #ffffff 0%, #cbd5e1 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    /* Slide-over Drawer */
    .trypai-drawer {
        position: fixed;
        inset: 0;
        z-index: 10000;
        display: flex;
        justify-content: flex-end;
    }

    .trypai-drawer.hidden { display: none !important; }

    .trypai-drawer-overlay {
        position: absolute;
        inset: 0;
        background: rgba(15, 23, 42, 0.65);
        backdrop-filter: blur(4px);
    }

    .trypai-drawer-content {
        position: relative;
        width: 100%;
        max-width: 440px;
        height: 100%;
        background: #0f172a;
        border-left: 1px solid rgba(255, 255, 255, 0.1);
        box-shadow: -10px 0 40px rgba(0, 0, 0, 0.6);
        display: flex;
        flex-direction: column;
        z-index: 10001;
        overflow-y: auto;
    }

    .trypai-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 16px 20px;
        background: #1e293b;
        border-bottom: 1px solid rgba(255, 255, 255, 0.08);
    }

    .trypai-header-title {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .trypai-logo-icon {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        background: linear-gradient(135deg, #6366f1, #a855f7);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
    }

    .trypai-title-text {
        font-size: 16px;
        font-weight: 700;
        color: white;
        margin: 0;
    }

    .trypai-sub-badge {
        font-size: 11px;
        color: #94a3b8;
        display: block;
    }

    .trypai-close-btn {
        background: transparent;
        border: none;
        color: #94a3b8;
        font-size: 24px;
        cursor: pointer;
        line-height: 1;
        transition: color 0.2s;
    }

    .trypai-close-btn:hover { color: white; }

    /* Conversational Chat Body */
    .trypai-body {
        padding: 20px;
        display: flex;
        flex-direction: column;
        gap: 14px;
        flex: 1;
        overflow-y: auto;
    }

    .chat-msg {
        display: flex;
        gap: 10px;
        align-items: flex-start;
    }

    .chat-msg.user {
        justify-content: flex-end;
    }

    .chat-avatar {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        background: linear-gradient(135deg, #6366f1, #a855f7);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 13px;
        color: white;
        flex-shrink: 0;
        box-shadow: 0 2px 8px rgba(99, 102, 241, 0.4);
    }

    .chat-bubble {
        max-width: 85%;
        padding: 12px 14px;
        border-radius: 14px;
        font-size: 13px;
        line-height: 1.45;
        color: #f1f5f9;
        background: rgba(30, 41, 59, 0.7);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-top-left-radius: 3px;
    }

    .chat-msg.user .chat-bubble {
        background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
        color: white;
        border: none;
        border-top-left-radius: 14px;
        border-top-right-radius: 3px;
        box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
    }

    .chat-options-grid {
        display: flex;
        flex-direction: column;
        gap: 8px;
        margin-top: 10px;
    }

    .chat-pill-btn {
        width: 100%;
        padding: 10px 14px;
        font-size: 12px;
        font-weight: 600;
        text-align: left;
        border-radius: 10px;
        border: 1px solid rgba(99, 102, 241, 0.4);
        background: rgba(15, 23, 42, 0.7);
        color: #c7d2fe;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .chat-pill-btn:hover {
        background: linear-gradient(135deg, rgba(99, 102, 241, 0.3), rgba(168, 85, 247, 0.3));
        border-color: #818cf8;
        color: #ffffff;
        transform: translateX(3px);
    }

    .chat-change-mode-row {
        margin-top: 8px;
        text-align: right;
    }

    .chat-change-mode-btn {
        background: transparent;
        border: none;
        color: #818cf8;
        font-size: 11px;
        cursor: pointer;
        text-decoration: underline;
    }

    .hidden, .trypai-loading.hidden, .trypai-input-section.hidden, .trypai-action-row.hidden, .chat-msg.hidden, .trypai-loop-ux.hidden { display: none !important; }

    /* Dropzone */
    .trypai-dropzone {
        position: relative;
        border: 2px dashed rgba(99, 102, 241, 0.4);
        border-radius: 12px;
        padding: 22px 16px;
        text-align: center;
        background: rgba(15, 23, 42, 0.5);
        transition: all 0.2s ease;
        cursor: pointer;
    }

    .trypai-dropzone:hover, .trypai-dropzone.dragover {
        border-color: #818cf8;
        background: rgba(99, 102, 241, 0.12);
    }

    .trypai-file-input {
        position: absolute;
        inset: 0;
        opacity: 0;
        cursor: pointer;
        width: 100%;
        height: 100%;
    }

    .trypai-upload-icon { font-size: 26px; margin-bottom: 4px; }
    .trypai-drop-title { font-size: 13px; font-weight: 600; color: #f1f5f9; margin-bottom: 4px; }
    .trypai-drop-hint { font-size: 11px; color: #64748b; margin: 0; }

    .trypai-file-list {
        margin-top: 10px;
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .trypai-file-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 8px 12px;
        background: rgba(30, 41, 59, 0.8);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 8px;
        font-size: 12px;
        color: #e2e8f0;
    }

    .trypai-file-remove {
        color: #f87171;
        cursor: pointer;
        font-weight: bold;
    }

    /* Textarea */
    .trypai-textarea {
        width: 100%;
        background: rgba(15, 23, 42, 0.6);
        border: 1px solid rgba(255, 255, 255, 0.15);
        border-radius: 10px;
        padding: 12px;
        color: #f8fafc;
        font-size: 13px;
        resize: vertical;
        outline: none;
        box-sizing: border-box;
    }

    .trypai-textarea:focus {
        border-color: #6366f1;
        box-shadow: 0 0 10px rgba(99, 102, 241, 0.3);
    }

    .trypai-text-hint { font-size: 11px; color: #64748b; margin-top: 6px; }

    /* Submit Button */
    .trypai-submit-btn {
        width: 100%;
        padding: 12px;
        border-radius: 10px;
        border: none;
        background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
        color: white;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.2s ease;
        box-shadow: 0 4px 15px rgba(99, 102, 241, 0.4);
    }

    .trypai-submit-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 20px rgba(99, 102, 241, 0.6);
    }

    /* Loading State */
    .trypai-loading {
        text-align: center;
        padding: 24px 10px;
    }

    .trypai-spinner-glow {
        width: 36px;
        height: 36px;
        margin: 0 auto 12px;
        border: 3px solid rgba(99, 102, 241, 0.2);
        border-top-color: #6366f1;
        border-radius: 50%;
        animation: spin 1s infinite linear;
    }

    @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }

    .trypai-loading-text {
        font-size: 13px;
        font-weight: 600;
        color: #a5b4fc;
        animation: pulse 2s infinite ease-in-out;
    }

    @keyframes pulse { 0%, 100% { opacity: 0.7; } 50% { opacity: 1; } }

    .trypai-skeleton-cards {
        margin-top: 14px;
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .trypai-skeleton-line {
        height: 14px;
        background: linear-gradient(90deg, rgba(255,255,255,0.05) 25%, rgba(255,255,255,0.15) 50%, rgba(255,255,255,0.05) 75%);
        background-size: 200% 100%;
        border-radius: 6px;
        animation: loading-shimmer 1.5s infinite;
    }

    .trypai-skeleton-line.short { width: 60%; margin: 0 auto; }

    @keyframes loading-shimmer { 0% { background-position: 200% 0; } 100% { background-position: -200% 0; } }

    /* Loop UX */
    .trypai-loop-ux {
        margin-top: 10px;
    }

    .trypai-loop-options {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-top: 8px;
    }

    .trypai-loop-pill {
        padding: 7px 12px;
        border-radius: 9999px;
        background: rgba(99, 102, 241, 0.2);
        border: 1px solid rgba(99, 102, 241, 0.4);
        color: #c7d2fe;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .trypai-loop-pill:hover {
        background: #6366f1;
        color: white;
    }

    /* Modal IngestionPreviewModal */
    .ingestion-modal-backdrop {
        position: fixed;
        inset: 0;
        z-index: 11000;
        background: rgba(15, 23, 42, 0.85);
        backdrop-filter: blur(8px);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }

    .ingestion-modal-backdrop.hidden { display: none !important; }

    .ingestion-modal-card {
        width: 100%;
        max-width: 680px;
        max-height: 85vh;
        background: #0f172a;
        border: 1px solid rgba(255, 255, 255, 0.15);
        border-radius: 16px;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.7);
        display: flex;
        flex-direction: column;
        overflow: hidden;
    }

    .ingestion-modal-header {
        padding: 18px 24px;
        background: #1e293b;
        border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
    }

    .ingestion-modal-title {
        font-size: 17px;
        font-weight: 700;
        color: #f8fafc;
        margin: 0 0 4px 0;
    }

    .ingestion-modal-subtitle {
        font-size: 12px;
        color: #94a3b8;
        margin: 0;
    }

    .ingestion-modal-body {
        padding: 20px 24px;
        overflow-y: auto;
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .ingestion-day-group {
        border-left: 3px solid #6366f1;
        padding-left: 14px;
    }

    .ingestion-day-title {
        font-size: 14px;
        font-weight: 700;
        color: #818cf8;
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .ingestion-items-list {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .ingestion-item-card {
        background: rgba(30, 41, 59, 0.6);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 10px;
        padding: 12px 14px;
        display: flex;
        align-items: flex-start;
        gap: 12px;
        transition: border-color 0.2s;
    }

    .ingestion-item-card:hover {
        border-color: rgba(99, 102, 241, 0.4);
    }

    .ingestion-item-checkbox {
        margin-top: 3px;
        width: 16px;
        height: 16px;
        accent-color: #6366f1;
        cursor: pointer;
    }

    .ingestion-item-main {
        flex: 1;
    }

    .ingestion-item-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 4px;
    }

    .ingestion-item-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        font-size: 11px;
        font-weight: 700;
        padding: 2px 8px;
        border-radius: 6px;
        background: rgba(99, 102, 241, 0.2);
        color: #a5b4fc;
    }

    .ingestion-time-badge {
        font-size: 11px;
        font-weight: 600;
        color: #fbbf24;
        background: rgba(251, 191, 36, 0.15);
        padding: 2px 6px;
        border-radius: 4px;
    }

    .ingestion-item-title {
        font-size: 14px;
        font-weight: 600;
        color: #f1f5f9;
        margin: 4px 0;
    }

    .ingestion-item-meta {
        font-size: 11px;
        color: #94a3b8;
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        margin-top: 4px;
    }

    .ingestion-edit-btn {
        background: transparent;
        border: none;
        color: #94a3b8;
        cursor: pointer;
        font-size: 14px;
        padding: 4px;
        border-radius: 4px;
        transition: color 0.2s;
    }

    .ingestion-edit-btn:hover { color: #f8fafc; }

    /* Inline Edit Form */
    .ingestion-inline-edit {
        margin-top: 10px;
        padding-top: 10px;
        border-top: 1px solid rgba(255, 255, 255, 0.1);
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .ingestion-inline-input {
        background: #0f172a;
        border: 1px solid rgba(255, 255, 255, 0.15);
        border-radius: 6px;
        padding: 6px 10px;
        color: white;
        font-size: 12px;
    }

    .ingestion-modal-footer {
        padding: 16px 24px;
        background: #1e293b;
        border-top: 1px solid rgba(255, 255, 255, 0.08);
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 12px;
    }

    .ingestion-btn-secondary {
        padding: 9px 16px;
        border-radius: 8px;
        border: 1px solid rgba(255, 255, 255, 0.15);
        background: transparent;
        color: #cbd5e1;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
    }

    .ingestion-btn-secondary:hover { background: rgba(255, 255, 255, 0.05); }

    .ingestion-btn-primary {
        padding: 9px 18px;
        border-radius: 8px;
        border: none;
        background: linear-gradient(135deg, #6366f1, #8b5cf6);
        color: white;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
        box-shadow: 0 4px 12px rgba(99, 102, 241, 0.4);
    }

    .ingestion-btn-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(99, 102, 241, 0.6);
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const triggerBtn = document.getElementById('copilot-trigger-btn');
    const drawer = document.getElementById('trypai-drawer');
    const closeBtn = document.getElementById('trypai-close-btn');
    const backdrop = document.getElementById('trypai-drawer-backdrop');

    const modeBtnFile = document.getElementById('btn-mode-file');
    const modeBtnText = document.getElementById('btn-mode-text');
    const changeModeBtn = document.getElementById('btn-change-mode');

    const userChoiceMsg = document.getElementById('chat-user-choice');
    const userChoiceText = document.getElementById('chat-user-choice-text');
    const assistantPromptMsg = document.getElementById('chat-assistant-prompt');
    const promptText = document.getElementById('chat-prompt-text');

    const sectionFile = document.getElementById('trypai-section-file');
    const sectionText = document.getElementById('trypai-section-text');
    const actionRow = document.getElementById('trypai-action-row');

    const dropzone = document.getElementById('trypai-dropzone');
    const fileInput = document.getElementById('trypai-file-input');
    const fileListEl = document.getElementById('trypai-file-list');
    const textInput = document.getElementById('trypai-text-input');

    const submitBtn = document.getElementById('trypai-btn-submit');
    const loadingEl = document.getElementById('trypai-loading');
    const loopUxEl = document.getElementById('trypai-loop-ux');
    const loopMsgEl = document.getElementById('trypai-loop-msg');
    const loopOptionsEl = document.getElementById('trypai-loop-options');

    const modal = document.getElementById('ingestion-preview-modal');
    const modalBody = document.getElementById('ingestion-preview-body');
    const modalCloseBtn = document.getElementById('btn-preview-close');
    const modalCancelBtn = document.getElementById('btn-preview-cancel');
    const modalConfirmBtn = document.getElementById('btn-preview-confirm');
    const selectedCountEl = document.getElementById('preview-selected-count');

    let currentMode = null; // 'file' | 'text' | null
    let selectedFiles = [];
    let processedItems = [];

    // Open/Close Drawer
    function openDrawer() { drawer.classList.remove('hidden'); }
    function closeDrawer() { drawer.classList.add('hidden'); }

    if (triggerBtn) triggerBtn.addEventListener('click', openDrawer);
    if (closeBtn) closeBtn.addEventListener('click', closeDrawer);
    if (backdrop) backdrop.addEventListener('click', closeDrawer);

    // Switch Conversational Mode
    function selectMode(mode) {
        currentMode = mode;

        if (mode === 'file') {
            userChoiceText.innerText = 'Quiero cargar un archivo o PDF 📄';
            promptText.innerHTML = '¡Perfecto! Arrastra o sube tus confirmaciones en PDF o imagen aquí abajo:';
            
            sectionFile.classList.remove('hidden');
            sectionText.classList.add('hidden');
        } else {
            userChoiceText.innerText = 'Quiero pegar la información en texto ✍️';
            promptText.innerHTML = '¡Genial! Pega el texto o correo de tu reserva aquí abajo:';

            sectionText.classList.remove('hidden');
            sectionFile.classList.add('hidden');
        }

        userChoiceMsg.classList.remove('hidden');
        assistantPromptMsg.classList.remove('hidden');
        actionRow.classList.remove('hidden');
        loopUxEl.classList.add('hidden');

        // Scroll chat thread to bottom
        const thread = document.getElementById('trypai-chat-thread');
        if (thread) thread.scrollTop = thread.scrollHeight;
    }

    function resetMode() {
        currentMode = null;
        userChoiceMsg.classList.add('hidden');
        assistantPromptMsg.classList.add('hidden');
        sectionFile.classList.add('hidden');
        sectionText.classList.add('hidden');
        actionRow.classList.add('hidden');
        loopUxEl.classList.add('hidden');
    }

    if (modeBtnFile) modeBtnFile.addEventListener('click', () => selectMode('file'));
    if (modeBtnText) modeBtnText.addEventListener('click', () => selectMode('text'));
    if (changeModeBtn) changeModeBtn.addEventListener('click', () => resetMode());

    // File Input / Drag & Drop
    if (fileInput) {
        fileInput.addEventListener('change', (e) => {
            addFiles(Array.from(e.target.files));
        });
    }

    if (dropzone) {
        ['dragenter', 'dragover'].forEach(name => {
            dropzone.addEventListener(name, (e) => { e.preventDefault(); dropzone.classList.add('dragover'); });
        });
        ['dragleave', 'drop'].forEach(name => {
            dropzone.addEventListener(name, (e) => { e.preventDefault(); dropzone.classList.remove('dragover'); });
        });
        dropzone.addEventListener('drop', (e) => {
            if (e.dataTransfer.files) addFiles(Array.from(e.dataTransfer.files));
        });
    }

    function addFiles(files) {
        selectedFiles = selectedFiles.concat(files);
        renderFileList();
    }

    function renderFileList() {
        if (!fileListEl) return;
        fileListEl.innerHTML = selectedFiles.map((f, i) => `
            <div class="trypai-file-item">
                <span>📄 ${f.name} (${(f.size / 1024).toFixed(0)} KB)</span>
                <span class="trypai-file-remove" onclick="removeFile(${i})">&times;</span>
            </div>
        `).join('');
    }

    window.removeFile = function(index) {
        selectedFiles.splice(index, 1);
        renderFileList();
    };

    // Submit Ingestion (Paso 4)
    if (submitBtn) {
        submitBtn.addEventListener('click', async () => {
            const tripId = document.getElementById('viantryp-copilot-container').dataset.tripId;
            if (!tripId) return;

            const formData = new FormData();
            if (currentMode === 'file') {
                if (selectedFiles.length === 0) {
                    alert('Por favor selecciona o arrastra al menos un archivo.');
                    return;
                }
                selectedFiles.forEach(f => formData.append('files[]', f));
            } else {
                const text = textInput.value.trim();
                if (!text) {
                    alert('Por favor pega la confirmación o nota de tu viaje.');
                    return;
                }
                formData.append('message', text);
            }

            // UI Loading state
            actionRow.classList.add('hidden');
            loadingEl.classList.remove('hidden');
            loopUxEl.classList.add('hidden');
            submitBtn.disabled = true;

            const thread = document.getElementById('trypai-chat-thread');
            if (thread) thread.scrollTop = thread.scrollHeight;

            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';
                const response = await fetch(`/trips/${tripId}/ai/chat-agent`, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': csrfToken },
                    body: formData
                });

                const data = await response.json();
                loadingEl.classList.add('hidden');
                submitBtn.disabled = false;

                if (!data.success) {
                    actionRow.classList.remove('hidden');
                    alert(data.message || 'Ocurrió un error al analizar tus reservas.');
                    return;
                }

                processedItems = data.items || data.actions || [];
                if (processedItems.length === 0) {
                    showLoopUx(data.message || 'No se encontraron reservas en el contenido provisto.', [
                        { label: '📄 Cargar otro Archivo', action: () => selectMode('file') },
                        { label: '✍️ Pegar Texto', action: () => selectMode('text') }
                    ]);
                    return;
                }

                // Open IngestionPreviewModal (Paso 5)
                openPreviewModal(processedItems);

            } catch (err) {
                console.error(err);
                loadingEl.classList.add('hidden');
                actionRow.classList.remove('hidden');
                submitBtn.disabled = false;
                alert('Ocurrió un error al procesar tu solicitud. Intenta nuevamente.');
            }
        });
    }

    // Modal IngestionPreviewModal logic
    function openPreviewModal(items) {
        modal.classList.remove('hidden');
        renderModalItems(items);
    }

    function closePreviewModal() {
        modal.classList.add('hidden');
    }

    if (modalCloseBtn) modalCloseBtn.addEventListener('click', () => {
        closePreviewModal();
        triggerLoopScenarioB();
    });
    if (modalCancelBtn) modalCancelBtn.addEventListener('click', () => {
        closePreviewModal();
        triggerLoopScenarioB();
    });

    function renderModalItems(items) {
        // Group by Date / Day
        const groups = {};
        items.forEach((item, index) => {
            item._enabled = true; // default checked
            item._id = index;
            const dateKey = item.start_date || 'Fecha por definir';
            if (!groups[dateKey]) groups[dateKey] = [];
            groups[dateKey].push(item);
        });

        // Sort items inside each date group by start_time
        Object.keys(groups).forEach(date => {
            groups[date].sort((a, b) => {
                const tA = a.start_time || '99:99';
                const tB = b.start_time || '99:99';
                return tA.localeCompare(tB);
            });
        });

        let html = '';
        Object.keys(groups).forEach(dateStr => {
            html += `
                <div class="ingestion-day-group">
                    <div class="ingestion-day-title">🗓️ ${dateStr}</div>
                    <div class="ingestion-items-list">
            `;

            groups[dateStr].forEach(item => {
                const icon = getItemIcon(item.type);
                const timeBadge = item.start_time ? `🕒 ${item.start_time}` : `[Hora Por Definir]`;
                const locationStr = item.location_query || item.data?.direccion || item.data?.address || '';
                const notesStr = item.notes || item.data?.reserva || item.data?.confirmation_code || '';

                html += `
                    <div class="ingestion-item-card" id="item-card-${item._id}">
                        <input type="checkbox" class="ingestion-item-checkbox" checked onchange="toggleItemCheck(${item._id}, this.checked)" />
                        <div class="ingestion-item-main">
                            <div class="ingestion-item-top">
                                <span class="ingestion-item-badge">${icon} ${getTypeLabel(item.type)}</span>
                                <span class="ingestion-time-badge">${timeBadge}</span>
                            </div>
                            <div class="ingestion-item-title" id="title-val-${item._id}">${escapeHtml(item.title || 'Evento')}</div>
                            <div class="ingestion-item-meta">
                                ${locationStr ? `<span>📍 ${escapeHtml(locationStr)}</span>` : ''}
                                ${notesStr ? `<span>🔖 ${escapeHtml(notesStr)}</span>` : ''}
                            </div>
                            <div class="ingestion-inline-edit hidden" id="edit-form-${item._id}">
                                <input type="text" class="ingestion-inline-input" value="${escapeHtml(item.title || '')}" placeholder="Título" onchange="updateItemField(${item._id}, 'title', this.value)" />
                                <div style="display:flex; gap:6px;">
                                    <input type="text" class="ingestion-inline-input" value="${escapeHtml(item.start_date || '')}" placeholder="YYYY-MM-DD" onchange="updateItemField(${item._id}, 'start_date', this.value)" style="flex:1;" />
                                    <input type="text" class="ingestion-inline-input" value="${escapeHtml(item.start_time || '')}" placeholder="HH:mm" onchange="updateItemField(${item._id}, 'start_time', this.value)" style="flex:1;" />
                                </div>
                            </div>
                        </div>
                        <button type="button" class="ingestion-edit-btn" title="Editar" onclick="toggleItemEdit(${item._id})">✏️</button>
                    </div>
                `;
            });

            html += `</div></div>`;
        });

        modalBody.innerHTML = html;
        updateSelectedCount();
    }

    window.toggleItemCheck = function(id, checked) {
        const item = processedItems.find(i => i._id === id);
        if (item) item._enabled = checked;
        updateSelectedCount();
    };

    window.toggleItemEdit = function(id) {
        const editForm = document.getElementById(`edit-form-${id}`);
        if (editForm) editForm.classList.toggle('hidden');
    };

    window.updateItemField = function(id, field, value) {
        const item = processedItems.find(i => i._id === id);
        if (item) {
            item[field] = value;
            if (field === 'title') {
                const tEl = document.getElementById(`title-val-${id}`);
                if (tEl) tEl.innerText = value;
            }
        }
    };

    function updateSelectedCount() {
        const enabledCount = processedItems.filter(i => i._enabled).length;
        if (selectedCountEl) selectedCountEl.innerText = enabledCount;
    }

    // Confirm Ingestion (Paso 6)
    if (modalConfirmBtn) {
        modalConfirmBtn.addEventListener('click', () => {
            const selectedItems = processedItems.filter(i => i._enabled);
            if (selectedItems.length === 0) {
                alert('Selecciona al menos un elemento para agregar al lienzo.');
                return;
            }

            closePreviewModal();

            // Dispatch to Canvas via ViantrypCopilot bridge
            if (window.ViantrypCopilot && typeof window.ViantrypCopilot.onApplyBatchActions === 'function') {
                window.ViantrypCopilot.onApplyBatchActions(selectedItems);
            } else if (window.ViantrypCopilot && typeof window.ViantrypCopilot.onApplyAction === 'function') {
                selectedItems.forEach(item => window.ViantrypCopilot.onApplyAction(item));
            }

            // Reset inputs
            selectedFiles = [];
            renderFileList();
            textInput.value = '';

            // Trigger Loop UX Scenario A
            triggerLoopScenarioA(selectedItems.length);
        });
    }

    // Loop UX Handlers (Paso 7)
    function triggerLoopScenarioA(count) {
        showLoopUx(`¡Listo! ${count} elementos agregados con éxito al lienzo. ¿Deseas agregar alguna otra reserva?`, [
            {
                label: '➕ Sí, agregar más',
                action: () => {
                    loopUxEl.classList.add('hidden');
                    resetMode();
                }
            },
            {
                label: '👍 No por ahora',
                action: () => closeDrawer()
            }
        ]);
    }

    function triggerLoopScenarioB() {
        showLoopUx('Entendido, descartamos esa información. ¿Quieres probar cargando otro archivo o texto?', [
            {
                label: '📄 Cargar Archivo',
                action: () => {
                    loopUxEl.classList.add('hidden');
                    selectMode('file');
                }
            },
            {
                label: '✍️ Pegar Texto',
                action: () => {
                    loopUxEl.classList.add('hidden');
                    selectMode('text');
                }
            }
        ]);
    }

    function showLoopUx(msg, options) {
        loopUxEl.classList.remove('hidden');
        loopMsgEl.innerHTML = escapeHtml(msg);
        loopOptionsEl.innerHTML = '';

        options.forEach(opt => {
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'trypai-loop-pill';
            btn.innerText = opt.label;
            btn.onclick = opt.action;
            loopOptionsEl.appendChild(btn);
        });

        const thread = document.getElementById('trypai-chat-thread');
        if (thread) thread.scrollTop = thread.scrollHeight;
    }

    // Utilities
    function getTypeLabel(type) {
        const map = { flight: 'Vuelo', alojamiento: 'Hotel', hotel: 'Hotel', actividad: 'Actividad', activity: 'Actividad', transporte: 'Transporte', transport: 'Transporte', comida: 'Comida', caja: 'Nota', note: 'Nota' };
        return map[type] || 'Elemento';
    }

    function getItemIcon(type) {
        const map = { flight: '✈️', alojamiento: '🏨', hotel: '🏨', actividad: '🎟️', activity: '🎟️', transporte: '🚗', transport: '🚗', comida: '🍽️', caja: '📝', note: '📝' };
        return map[type] || '📍';
    }

    function escapeHtml(str) {
        if (!str) return '';
        return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
    }
});
</script>