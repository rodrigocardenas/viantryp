// File Manager Module - Handles file uploads and document management
class FileManager {
    constructor() {
        this.uploadedDocuments = {
            flight: [],
            hotel: [],
            transport: []
        };
    }

    setupFileUploadListeners(currentElementType) {
        // Setup listeners for file inputs
        const fileInputs = document.querySelectorAll('#modal-body input[type="file"]');
        fileInputs.forEach(input => {
            input.addEventListener('change', async (e) => {
                const files = e.target.files;
                if (files.length > 0) {
                    const type = currentElementType; // flight, hotel, transport
                    for (let file of files) {
                        await this.uploadDocument(file, type);
                    }
                }
            });
        });
    }

    async uploadDocument(file, type) {
        console.log('FileManager: uploadDocument called with file:', file.name, 'type:', type);
        const tripId = this.getCurrentTripId();
        console.log('FileManager: tripId result:', tripId);

        // Check if we're in create mode (no trip ID yet)
        if (!tripId) {
            console.log('FileManager: No trip ID found, using temporary upload');
            return this.uploadDocumentTemporarily(file, type);
        }

        const formData = new FormData();
        formData.append('file', file);
        formData.append('type', type);
        formData.append('item_id', 'temp_' + Date.now()); // Temporary ID until element is saved

        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

        try {
            const response = await fetch(`/trips/${tripId}/documents/upload`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                body: formData
            });

            const result = await response.json();

            if (result.success) {
                this.uploadedDocuments[type].push(result.document);
                this.showNotification('Documento Subido', 'El documento se ha subido exitosamente.');
                return true;
            } else {
                this.showNotification('Error', result.message || 'Error al subir el documento.');
                return false;
            }
        } catch (error) {
            console.error('Error uploading document:', error);
            this.showNotification('Error', 'Error al subir el documento.');
            return false;
        }
    }

    async uploadDocumentTemporarily(file, type) {
        console.log('FileManager: Starting temporary upload for:', file.name, 'type:', type);

        const formData = new FormData();
        formData.append('file', file);
        formData.append('type', type);
        formData.append('item_id', 'temp_' + Date.now()); // Temporary ID until element is saved

        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        console.log('FileManager: CSRF token found:', !!csrfToken);

        try {
            console.log('FileManager: Making request to /documents/temp-upload');
            const response = await fetch('/documents/temp-upload', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                body: formData
            });

            console.log('FileManager: Response status:', response.status);
            const result = await response.json();
            console.log('FileManager: Response result:', result);

            if (result.success) {
                // Store temporary file info for later processing
                if (!window.tempUploadedFiles) {
                    window.tempUploadedFiles = [];
                }
                window.tempUploadedFiles.push(result.file_info);

                this.uploadedDocuments[type].push(result.file_info);
                this.showNotification('Documento Subido Temporariamente', 'El documento se guardará cuando guardes el viaje.');
                return true;
            } else {
                console.error('FileManager: Upload failed:', result.message);
                this.showNotification('Error', result.message || 'Error al subir el documento.');
                return false;
            }
        } catch (error) {
            console.error('FileManager: Error uploading document temporarily:', error);
            this.showNotification('Error', 'Error al subir el documento.');
            return false;
        }
    }

    getCurrentTripId() {
        console.log('FileManager: getCurrentTripId() called');
        console.log('FileManager: window.currentTripId =', window.currentTripId);
        console.log('FileManager: current path =', window.location.pathname);

        // First, try to get the ID from the global variable
        if (window.currentTripId) {
            console.log('FileManager: Found trip ID in window.currentTripId:', window.currentTripId);
            return window.currentTripId;
        }

        // Fallback to URL parsing for edit mode
        const currentPath = window.location.pathname;
        const urlParts = currentPath.split('/').filter(part => part !== '');
        console.log('FileManager: URL parts =', urlParts);

        if (urlParts.length >= 2 && urlParts[0] === 'trips' && !isNaN(urlParts[1])) {
             // Assumes URL like /trips/{id}/edit
            console.log('FileManager: Found trip ID in URL:', urlParts[1]);
            return urlParts[1];
        }

        console.log('FileManager: No trip ID found, returning null');
        return null;
    }

    showNotification(title, message, type = 'success') {
        // This should be imported from a notification module
        console.log(`${type.toUpperCase()}: ${title} - ${message}`);
        // For now, use a simple alert or create a proper notification system
        if (typeof showNotification === 'function') {
            showNotification(title, message, type);
        } else {
            alert(`${title}: ${message}`);
        }
    }

    getUploadedDocuments(type) {
        return this.uploadedDocuments[type] || [];
    }

    clearUploadedDocuments(type) {
        if (type) {
            this.uploadedDocuments[type] = [];
        } else {
            this.uploadedDocuments = {
                flight: [],
                hotel: [],
                transport: []
            };
        }
    }

    /**
     * Process temporary files when trip is saved
     */
    async processTempFiles(tripId) {
        if (!window.tempUploadedFiles || window.tempUploadedFiles.length === 0) {
            return;
        }

        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

        for (const tempFile of window.tempUploadedFiles) {
            try {
                const response = await fetch('/documents/process-temp', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        temp_path: tempFile.path,
                        trip_id: tripId,
                        type: tempFile.type,
                        item_id: tempFile.item_id
                    })
                });

                const result = await response.json();

                if (result.success) {
                    console.log('Temp file processed:', tempFile.original_name);
                } else {
                    console.error('Failed to process temp file:', tempFile.original_name);
                }
            } catch (error) {
                console.error('Error processing temp file:', error);
            }
        }

        // Clear temp files after processing
        window.tempUploadedFiles = [];
    }

    /**
     * Get all uploaded documents for trip saving
     */
    getAllUploadedDocuments() {
        const allDocs = [];

        for (const type in this.uploadedDocuments) {
            allDocs.push(...this.uploadedDocuments[type]);
        }

        return allDocs;
    }
}

// Make FileManager globally available
window.FileManager = FileManager;
window.fileManager = new FileManager();

// Make setupFileUploadListeners globally available
window.setupFileUploadListeners = function(currentElementType) {
    return window.fileManager.setupFileUploadListeners(currentElementType);
};

// Auto-initialize if not already done
if (!window.fileManager) {
    window.fileManager = new FileManager();
}

export default FileManager;
