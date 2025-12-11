@props(['trip'])

<!-- Header for authenticated users -->
<div class="header">
    <div class="header-content">
        <div class="logo-container">
            <a href="{{ route('trips.index') }}" class="viantryp-logo">
                <i class="fas fa-route"></i>
                Viantryp
            </a>
        </div>
        <div class="header-right">
            <div class="nav-actions">
                <a href="{{ route('trips.edit', $trip->id) }}" class="btn btn-back">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <button type="button" class="btn btn-share" onclick="shareTrip()">
                    <i class="fas fa-share-alt"></i>
                    Compartir
                </button>
                {{-- <button type="button" class="btn btn-pdf" onclick="downloadPDF()">
                    <i class="fas fa-file-pdf"></i>
                    Descarga PDF
                </button> --}}
            </div>
        </div>
    </div>
</div>

<script>
    async function shareTrip() {
        const tripId = {{ $trip->id ?? 'null' }};

        if (!tripId) {
            showNotification('Error', 'No se puede compartir este viaje.', 'error');
            return;
        }

        try {
            // Show loading state
            const shareBtn = document.querySelector('.btn-share');
            const originalText = shareBtn.innerHTML;
            shareBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Generando enlace...';
            shareBtn.disabled = true;

            // Generate share token via AJAX
            const response = await fetch(`/trips/${tripId}/generate-share-token`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                }
            });

            const data = await response.json();

            if (data.success) {
                // Show share modal with the URL
                showShareModal(data.share_url);

                // Reset button
                shareBtn.innerHTML = originalText;
                shareBtn.disabled = false;
            } else {
                throw new Error(data.message || 'Error al generar el enlace');
            }
        } catch (error) {
            console.error('Share error:', error);
            showNotification('Error', error.message || 'No se pudo generar el enlace de compartición.', 'error');

            // Reset button
            const shareBtn = document.querySelector('.btn-share');
            shareBtn.innerHTML = '<i class="fas fa-share-alt"></i> Compartir';
            shareBtn.disabled = false;
        }
    }
    function showShareModal(shareUrl) {
        // Remove existing modal if present
        const existingModal = document.getElementById('shareModal');
        if (existingModal) {
            existingModal.remove();
        }

        // Create modal HTML
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
                                <input type="text" id="shareUrlInput" value="${shareUrl}" readonly style="
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
                                    background: linear-gradient(135deg, #10b981, #059669);
                                    color: white;
                                    border: none;
                                    border-radius: 8px;
                                    cursor: pointer;
                                    font-weight: 600;
                                    display: flex;
                                    align-items: center;
                                    gap: 0.5rem;
                                    transition: all 0.3s ease;
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
                await navigator.clipboard.writeText(shareUrl);
                copyBtn.innerHTML = '<i class="fas fa-check"></i> ¡Copiado!';
                copyBtn.style.background = 'linear-gradient(135deg, #059669, #047857)';

                // Reset button after 2 seconds
                setTimeout(() => {
                    copyBtn.innerHTML = '<i class="fas fa-copy"></i> Copiar';
                    copyBtn.style.background = 'linear-gradient(135deg, #10b981, #059669)';
                }, 2000);
            } catch (error) {
                // Fallback for older browsers
                urlInput.select();
                document.execCommand('copy');
                copyBtn.innerHTML = '<i class="fas fa-check"></i> ¡Copiado!';
                copyBtn.style.background = 'linear-gradient(135deg, #059669, #047857)';

                setTimeout(() => {
                    copyBtn.innerHTML = '<i class="fas fa-copy"></i> Copiar';
                    copyBtn.style.background = 'linear-gradient(135deg, #10b981, #059669)';
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

    function toggleItemContent(button) {
        const item = button.closest('.timeline-item');
        const content = item.querySelector('.item-content');
        const icon = button.querySelector('i');

        if (content.style.display === 'none') {
            content.style.display = 'block';
            icon.className = 'fas fa-chevron-up';
        } else {
            content.style.display = 'none';
            icon.className = 'fas fa-chevron-down';
        }
    }
</script>
