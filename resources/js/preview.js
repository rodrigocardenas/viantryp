function downloadPDF() {
    const tripId = window.tripId || null;
    const token = window.shareToken || '';

    if (!tripId) {
        showNotification('Error', 'No se puede descargar el PDF de este viaje.', 'error');
        return;
    }

    // Show loading state - try both button selectors
    const pdfBtn = document.querySelector('.btn-pdf') || document.querySelector('.public-btn-print');
    const originalText = pdfBtn.innerHTML;
    pdfBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Generando PDF...';
    pdfBtn.disabled = true;

    try {
        // Create a temporary link to trigger download
        const link = document.createElement('a');
        const url = token ? `/trips/${tripId}/pdf?token=${token}` : `/trips/${tripId}/pdf`;
        link.href = url;
        link.download = 'itinerario.pdf';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);

        showNotification('PDF generado', 'El PDF del itinerario se está descargando.', 'success');

        // Reset button
        pdfBtn.innerHTML = originalText;
        pdfBtn.disabled = false;
    } catch (error) {
        console.error('PDF download error:', error);
        showNotification('Error', 'No se pudo generar el PDF.', 'error');

        // Reset button
        pdfBtn.innerHTML = originalText;
        pdfBtn.disabled = false;
    }
}

async function shareTrip() {
    const tripId = window.tripId || null;

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

// Header hide on scroll functionality
let lastScrollTop = 0;
const header = document.getElementById('previewStickyHeader');

window.addEventListener('scroll', function() {
    const scrollTop = window.pageYOffset || document.documentElement.scrollTop;

    if (scrollTop > lastScrollTop && scrollTop > 100) {
        // Scrolling down and past 100px
        header.classList.add('hidden');
    } else {
        // Scrolling up or at top
        header.classList.remove('hidden');
    }

    lastScrollTop = scrollTop;
});

// Hotel Gallery Carousel Functionality
function initializeHotelCarousels() {
    const carousels = document.querySelectorAll('.hotel-gallery');

    carousels.forEach(carousel => {
        const track = carousel.querySelector('.hotel-gallery-track');
        const slides = carousel.querySelectorAll('.hotel-gallery-slide');
        const prevBtn = carousel.querySelector('.hotel-gallery-prev');
        const nextBtn = carousel.querySelector('.hotel-gallery-next');
        const indicators = carousel.querySelectorAll('.hotel-gallery-indicator');

        if (!track || slides.length === 0) return;

        let currentIndex = 0;

        function updateCarousel() {
            // Update slide positions using transform
            track.style.transform = `translateX(-${currentIndex * 100}%)`;

            // Update active slide opacity
            slides.forEach((slide, index) => {
                slide.classList.toggle('active', index === currentIndex);
            });

            // Update indicators
            indicators.forEach((indicator, index) => {
                indicator.classList.toggle('active', index === currentIndex);
            });

            // Update button states
            if (prevBtn) prevBtn.style.opacity = currentIndex === 0 ? '0.5' : '1';
            if (nextBtn) nextBtn.style.opacity = currentIndex === slides.length - 1 ? '0.5' : '1';
        }

        function nextSlide() {
            if (currentIndex < slides.length - 1) {
                currentIndex++;
                updateCarousel();
            }
        }

        function prevSlide() {
            if (currentIndex > 0) {
                currentIndex--;
                updateCarousel();
            }
        }

        function goToSlide(index) {
            if (index >= 0 && index < slides.length) {
                currentIndex = index;
                updateCarousel();
            }
        }

        // Event listeners
        if (prevBtn) prevBtn.addEventListener('click', prevSlide);
        if (nextBtn) nextBtn.addEventListener('click', nextSlide);

        indicators.forEach((indicator, index) => {
            indicator.addEventListener('click', () => goToSlide(index));
        });

        // Touch/swipe support
        let startX = 0;
        let isDragging = false;

        carousel.addEventListener('touchstart', (e) => {
            startX = e.touches[0].clientX;
            isDragging = true;
        });

        carousel.addEventListener('touchmove', (e) => {
            if (!isDragging) return;
            const currentX = e.touches[0].clientX;
            const diff = startX - currentX;

            if (Math.abs(diff) > 50) {
                if (diff > 0 && currentIndex < slides.length - 1) {
                    nextSlide();
                } else if (diff < 0 && currentIndex > 0) {
                    prevSlide();
                }
                isDragging = false;
            }
        });
    });
}

function initializeActivityCarousels() {
    const carousels = document.querySelectorAll('.activity-gallery');

    carousels.forEach(carousel => {
        const track = carousel.querySelector('.activity-gallery-track');
        const slides = carousel.querySelectorAll('.activity-gallery-slide');
        const prevBtn = carousel.querySelector('.activity-gallery-prev');
        const nextBtn = carousel.querySelector('.activity-gallery-next');
        const indicators = carousel.querySelectorAll('.activity-gallery-indicator');

        if (!track || slides.length === 0) return;

        let currentIndex = 0;

        function updateCarousel() {
            // Update slide positions using transform
            track.style.transform = `translateX(-${currentIndex * 100}%)`;

            // Update active slide opacity
            slides.forEach((slide, index) => {
                slide.classList.toggle('active', index === currentIndex);
            });

            // Update indicators
            indicators.forEach((indicator, index) => {
                indicator.classList.toggle('active', index === currentIndex);
            });

            // Update button states
            if (prevBtn) prevBtn.style.opacity = currentIndex === 0 ? '0.5' : '1';
            if (nextBtn) nextBtn.style.opacity = currentIndex === slides.length - 1 ? '0.5' : '1';
        }

        function nextSlide() {
            if (currentIndex < slides.length - 1) {
                currentIndex++;
                updateCarousel();
            }
        }

        function prevSlide() {
            if (currentIndex > 0) {
                currentIndex--;
                updateCarousel();
            }
        }

        function goToSlide(index) {
            if (index >= 0 && index < slides.length) {
                currentIndex = index;
                updateCarousel();
            }
        }

        // Event listeners
        if (prevBtn) prevBtn.addEventListener('click', prevSlide);
        if (nextBtn) nextBtn.addEventListener('click', nextSlide);

        indicators.forEach((indicator, index) => {
            indicator.addEventListener('click', () => goToSlide(index));
        });

        // Touch/swipe support
        let startX = 0;
        let isDragging = false;

        carousel.addEventListener('touchstart', (e) => {
            startX = e.touches[0].clientX;
            isDragging = true;
        });

        carousel.addEventListener('touchmove', (e) => {
            if (!isDragging) return;
            const currentX = e.touches[0].clientX;
            const diff = startX - currentX;

            if (Math.abs(diff) > 50) {
                if (diff > 0 && currentIndex < slides.length - 1) {
                    nextSlide();
                } else if (diff < 0 && currentIndex > 0) {
                    prevSlide();
                }
                isDragging = false;
            }
        });
    });
}

function showHotelGallery(images, startIndex = 0, hotelName = 'Hotel') {
    currentHotelImages = images;
    currentImageIndex = startIndex;

    const modalHtml = `
        <div id="hotelGalleryModal" class="hotel-gallery-modal-overlay" style="
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.9);
            z-index: 10000;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 10000;
            font-family: 'Poppins', sans-serif;
        ">
            <div class="hotel-gallery-modal" style="
                position: relative;
                max-width: 90vw;
                max-height: 90vh;
                background: white;
                border-radius: 12px;
                overflow: hidden;
                box-shadow: 0 25px 50px rgba(0, 0, 0, 0.5);
            ">
                <div class="gallery-modal-header" style="
                    padding: 1rem 1.5rem;
                    background: var(--primary-dark, #1f2a44);
                    color: white;
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                ">
                    <h3 style="margin: 0; font-size: 1.2rem;">${hotelName} - Galería de Fotos</h3>
                    <button onclick="closeHotelGallery()" style="
                        background: none;
                        border: none;
                        color: white;
                        font-size: 1.5rem;
                        cursor: pointer;
                        padding: 0.25rem;
                        border-radius: 4px;
                        transition: background 0.3s ease;
                    " onmouseover="this.style.background='rgba(255,255,255,0.1)'" onmouseout="this.style.background='none'">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="gallery-modal-body" style="
                    position: relative;
                    background: black;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    min-height: 400px;
                ">
                    <img id="galleryMainImage" src="${currentHotelImages[currentImageIndex]}" alt="Hotel image" style="
                        max-width: 100%;
                        max-height: 70vh;
                        object-fit: contain;
                    ">
                    ${currentHotelImages.length > 1 ? `
                        <button onclick="prevHotelImage()" class="gallery-nav-btn" style="
                            position: absolute;
                            left: 1rem;
                            top: 50%;
                            transform: translateY(-50%);
                            background: rgba(0, 0, 0, 0.7);
                            color: white;
                            border: none;
                            width: 50px;
                            height: 50px;
                            border-radius: 50%;
                            cursor: pointer;
                            font-size: 1.2rem;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            transition: background 0.3s ease;
                        " onmouseover="this.style.background='rgba(0,0,0,0.9)'" onmouseout="this.style.background='rgba(0,0,0,0.7)'">
                            <i class="fas fa-chevron-left"></i>
                        </button>
                        <button onclick="nextHotelImage()" class="gallery-nav-btn" style="
                            position: absolute;
                            right: 1rem;
                            top: 50%;
                            transform: translateY(-50%);
                            background: rgba(0, 0, 0, 0.7);
                            color: white;
                            border: none;
                            width: 50px;
                            height: 50px;
                            border-radius: 50%;
                            cursor: pointer;
                            font-size: 1.2rem;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            transition: background 0.3s ease;
                        " onmouseover="this.style.background='rgba(0,0,0,0.9)'" onmouseout="this.style.background='rgba(0,0,0,0.7)'">
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    ` : ''}
                </div>
                <div class="gallery-modal-footer" style="
                    padding: 1rem 1.5rem;
                    background: #f8f9fa;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    gap: 0.5rem;
                ">
                    <span id="galleryImageCounter" style="
                        font-size: 0.9rem;
                        color: #6c757d;
                    ">${currentImageIndex + 1} de ${currentHotelImages.length}</span>
                </div>
            </div>
        </div>
    `;

    document.body.insertAdjacentHTML('beforeend', modalHtml);

    // Add keyboard navigation
    document.addEventListener('keydown', handleGalleryKeydown);
}

function closeHotelGallery() {
    const modal = document.getElementById('hotelGalleryModal');
    if (modal) {
        modal.remove();
    }
    document.removeEventListener('keydown', handleGalleryKeydown);
}

function showActivityGallery(images, startIndex = 0, activityName = 'Actividad') {
    currentActivityImages = images;
    currentActivityImageIndex = startIndex;

    const modalHtml = `
        <div id="activityGalleryModal" class="activity-gallery-modal-overlay" style="
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.9);
            z-index: 10000;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 10000;
            font-family: 'Poppins', sans-serif;
        ">
            <div class="activity-gallery-modal" style="
                position: relative;
                max-width: 90vw;
                max-height: 90vh;
                background: white;
                border-radius: 12px;
                overflow: hidden;
                box-shadow: 0 25px 50px rgba(0, 0, 0, 0.5);
            ">
                <div class="gallery-modal-header" style="
                    padding: 1rem 1.5rem;
                    background: var(--primary-dark, #1f2a44);
                    color: white;
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                ">
                    <h3 style="margin: 0; font-size: 1.2rem;">${activityName} - Galería de Fotos</h3>
                    <button onclick="closeActivityGallery()" style="
                        background: none;
                        border: none;
                        color: white;
                        font-size: 1.5rem;
                        cursor: pointer;
                        padding: 0.25rem;
                        border-radius: 4px;
                        transition: background 0.3s ease;
                    " onmouseover="this.style.background='rgba(255,255,255,0.1)'" onmouseout="this.style.background='none'">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="gallery-modal-body" style="
                    position: relative;
                    background: black;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    min-height: 400px;
                ">
                    <img id="activityGalleryMainImage" src="${currentActivityImages[currentActivityImageIndex]}" alt="Activity image" style="
                        max-width: 100%;
                        max-height: 70vh;
                        object-fit: contain;
                    ">
                    ${currentActivityImages.length > 1 ? `
                        <button onclick="prevActivityImage()" class="gallery-nav-btn" style="
                            position: absolute;
                            left: 1rem;
                            top: 50%;
                            transform: translateY(-50%);
                            background: rgba(0, 0, 0, 0.7);
                            border: none;
                            border-radius: 50%;
                            width: 50px;
                            height: 50px;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            color: white;
                            font-size: 1.2rem;
                            cursor: pointer;
                            transition: background 0.3s ease;
                        " onmouseover="this.style.background='rgba(0,0,0,0.9)'" onmouseout="this.style.background='rgba(0,0,0,0.7)'">
                            <i class="fas fa-chevron-left"></i>
                        </button>
                        <button onclick="nextActivityImage()" class="gallery-nav-btn" style="
                            position: absolute;
                            right: 1rem;
                            top: 50%;
                            transform: translateY(-50%);
                            background: rgba(0, 0, 0, 0.7);
                            border: none;
                            border-radius: 50%;
                            width: 50px;
                            height: 50px;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            color: white;
                            font-size: 1.2rem;
                            cursor: pointer;
                            transition: background 0.3s ease;
                        " onmouseover="this.style.background='rgba(0,0,0,0.9)'" onmouseout="this.style.background='rgba(0,0,0,0.7)'">
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    ` : ''}
                </div>
                <div class="gallery-modal-footer" style="
                    padding: 1rem 1.5rem;
                    background: #f8f9fa;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    gap: 0.5rem;
                ">
                    <span id="activityGalleryImageCounter" style="
                        font-size: 0.9rem;
                        color: #6c757d;
                    ">${currentActivityImageIndex + 1} de ${currentActivityImages.length}</span>
                </div>
            </div>
        </div>
    `;

    document.body.insertAdjacentHTML('beforeend', modalHtml);

    // Add keyboard navigation
    document.addEventListener('keydown', handleActivityGalleryKeydown);
}

function closeActivityGallery() {
    const modal = document.getElementById('activityGalleryModal');
    if (modal) {
        modal.remove();
    }
    document.removeEventListener('keydown', handleActivityGalleryKeydown);
}

function nextActivityImage() {
    currentActivityImageIndex = (currentActivityImageIndex + 1) % currentActivityImages.length;
    updateActivityGalleryImage();
}

function prevActivityImage() {
    currentActivityImageIndex = currentActivityImageIndex === 0 ? currentActivityImages.length - 1 : currentActivityImageIndex - 1;
    updateActivityGalleryImage();
}

function updateActivityGalleryImage() {
    const mainImage = document.getElementById('activityGalleryMainImage');
    const counter = document.getElementById('activityGalleryImageCounter');

    if (mainImage) {
        mainImage.src = currentActivityImages[currentActivityImageIndex];
    }
    if (counter) {
        counter.textContent = `${currentActivityImageIndex + 1} de ${currentActivityImages.length}`;
    }
}

function handleActivityGalleryKeydown(e) {
    if (e.key === 'Escape') {
        closeActivityGallery();
    } else if (e.key === 'ArrowRight') {
        nextActivityImage();
    } else if (e.key === 'ArrowLeft') {
        prevActivityImage();
    }
}

function nextHotelImage() {
    currentImageIndex = (currentImageIndex + 1) % currentHotelImages.length;
    updateGalleryImage();
}

function prevHotelImage() {
    currentImageIndex = currentImageIndex === 0 ? currentHotelImages.length - 1 : currentImageIndex - 1;
    updateGalleryImage();
}

function updateGalleryImage() {
    const mainImage = document.getElementById('galleryMainImage');
    const counter = document.getElementById('galleryImageCounter');

    if (mainImage) {
        mainImage.src = currentHotelImages[currentImageIndex];
    }
    if (counter) {
        counter.textContent = `${currentImageIndex + 1} de ${currentHotelImages.length}`;
    }
}

function handleGalleryKeydown(e) {
    if (e.key === 'Escape') {
        closeHotelGallery();
    } else if (e.key === 'ArrowRight') {
        nextHotelImage();
    } else if (e.key === 'ArrowLeft') {
        prevHotelImage();
    }
}

// Global variables for hotel gallery
let currentHotelImages = [];
let currentImageIndex = 0;

// Global variables for activity gallery
let currentActivityImages = [];
let currentActivityImageIndex = 0;

// Initialize carousels on page load
document.addEventListener('DOMContentLoaded', function() {
    initializeHotelCarousels();
    initializeActivityCarousels();
});
