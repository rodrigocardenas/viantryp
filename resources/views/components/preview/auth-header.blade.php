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
                <button type="button" class="btn btn-pdf" onclick="downloadPDF()">
                    <i class="fas fa-file-pdf"></i>
                    Descarga PDF
                </button>
            </div>
        </div>
    </div>
</div>
