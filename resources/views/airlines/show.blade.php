@extends('layouts.app')

@section('title', 'Viantryp - Ver Aerolínea')

@section('content')
    <x-header />

    <!-- Main Content -->
    <main class="main-content">
        <div class="airline-detail-container">
            <div class="airline-header">
                <div class="airline-logo-large">
                    @if($airline->logo_path)
                        <img src="{{ asset('storage/' . $airline->logo_path) }}" alt="{{ $airline->name }} logo" class="logo-large">
                    @else
                        <div class="no-logo-large">✈️</div>
                    @endif
                </div>
                <div class="airline-info-large">
                    <h1>{{ $airline->name }}</h1>
                    <p class="carrier-code">Código: {{ $airline->carrier_code }}</p>
                    <div class="airline-actions">
                        <a href="{{ route('airlines.edit', $airline) }}" class="btn-primary">
                            <i class="fas fa-edit"></i>
                            Editar
                        </a>
                        <a href="{{ route('airlines.index') }}" class="btn-secondary">
                            <i class="fas fa-arrow-left"></i>
                            Volver al Listado
                        </a>
                    </div>
                </div>
            </div>

            <div class="airline-details">
                <div class="detail-section">
                    <h3>Información General</h3>
                    <div class="detail-grid">
                        <div class="detail-item">
                            <label>Nombre:</label>
                            <span>{{ $airline->name }}</span>
                        </div>
                        <div class="detail-item">
                            <label>Código de Transportista:</label>
                            <span>{{ $airline->carrier_code }}</span>
                        </div>
                        <div class="detail-item">
                            <label>Fecha de Creación:</label>
                            <span>{{ $airline->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                        <div class="detail-item">
                            <label>Última Actualización:</label>
                            <span>{{ $airline->updated_at->format('d/m/Y H:i') }}</span>
                        </div>
                    </div>
                </div>

                @if($airline->logo_path)
                <div class="detail-section">
                    <h3>Logo</h3>
                    <div class="logo-display">
                        <img src="{{ asset('storage/' . $airline->logo_path) }}" alt="{{ $airline->name }} logo" class="logo-display-image">
                    </div>
                </div>
                @endif
            </div>
        </div>
    </main>
@endsection

@push('styles')
<style>
    :root {
        --ink: #1f2a44;
        --blue-700: #0ea5e9;
        --blue-600: #38bdf8;
        --blue-300: #93c5fd;
        --blue-100: #e0f2fe;
        --sky-50: #f0f9ff;
        --stone-100: #f5f7fa;
        --stone-300: #e2e8f0;
        --stone-400: #cbd5e1;
        --slate-600: #475569;
        --slate-500: #64748b;
        --success: #10b981;
        --danger: #ef4444;
        --shadow-soft: 0 10px 30px rgba(0,0,0,0.06);
        --shadow-hover: 0 14px 40px rgba(0,0,0,0.08);
        --radius: 16px;
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Poppins', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: linear-gradient(180deg, #e6f3fb 0%, #f7fbff 60%);
        color: var(--ink);
        letter-spacing: 0.1px;
    }

    .main-content {
        max-width: 1000px;
        margin: 0 auto;
        padding: 2rem;
    }

    .airline-detail-container {
        background: white;
        border-radius: var(--radius);
        box-shadow: var(--shadow-soft);
        border: 1px solid var(--stone-300);
        overflow: hidden;
    }

    .airline-header {
        background: var(--sky-50);
        padding: 2rem;
        display: flex;
        align-items: center;
        gap: 2rem;
        border-bottom: 1px solid var(--stone-300);
    }

    .airline-logo-large {
        width: 120px;
        height: 120px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        overflow: hidden;
        background: white;
        border: 2px solid var(--stone-300);
    }

    .logo-large {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .no-logo-large {
        font-size: 3rem;
        color: var(--slate-500);
    }

    .airline-info-large {
        flex: 1;
    }

    .airline-info-large h1 {
        font-size: 2rem;
        font-weight: 700;
        color: var(--ink);
        margin-bottom: 0.5rem;
    }

    .carrier-code {
        font-size: 1.1rem;
        color: var(--slate-600);
        font-weight: 600;
        margin-bottom: 1.5rem;
    }

    .airline-actions {
        display: flex;
        gap: 1rem;
    }

    .btn-primary,
    .btn-secondary {
        padding: 0.75rem 1.5rem;
        border: none;
        border-radius: 8px;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        box-shadow: var(--shadow-soft);
    }

    .btn-primary {
        background: var(--blue-700);
        color: white;
    }

    .btn-primary:hover {
        background: var(--blue-600);
        transform: translateY(-1px);
        box-shadow: var(--shadow-hover);
    }

    .btn-secondary {
        background: #f1f5f9;
        color: var(--slate-600);
        border: 1px solid var(--stone-300);
    }

    .btn-secondary:hover {
        background: #e2e8f0;
    }

    .airline-details {
        padding: 2rem;
    }

    .detail-section {
        margin-bottom: 2rem;
    }

    .detail-section h3 {
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--ink);
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 1px solid var(--stone-300);
    }

    .detail-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1rem;
    }

    .detail-item {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }

    .detail-item label {
        font-weight: 600;
        color: var(--slate-600);
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .detail-item span {
        font-size: 1rem;
        color: var(--ink);
        padding: 0.5rem;
        background: var(--sky-50);
        border-radius: 6px;
        border: 1px solid var(--stone-300);
    }

    .logo-display {
        display: flex;
        justify-content: center;
        padding: 2rem;
        background: var(--sky-50);
        border-radius: 12px;
        border: 1px solid var(--stone-300);
    }

    .logo-display-image {
        max-width: 200px;
        max-height: 150px;
        object-fit: contain;
        border-radius: 8px;
        border: 1px solid var(--stone-300);
    }

    @media (max-width: 768px) {
        .main-content {
            padding: 1rem;
        }

        .airline-header {
            flex-direction: column;
            text-align: center;
            gap: 1.5rem;
        }

        .airline-logo-large {
            width: 100px;
            height: 100px;
        }

        .airline-info-large h1 {
            font-size: 1.5rem;
        }

        .airline-actions {
            justify-content: center;
        }

        .detail-grid {
            grid-template-columns: 1fr;
        }

        .logo-display {
            padding: 1rem;
        }

        .logo-display-image {
            max-width: 150px;
            max-height: 100px;
        }
    }
</style>
@endpush
