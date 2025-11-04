@extends('layouts.app')

@section('title', 'Viantryp - Nueva Aerolínea')

@section('content')
    <x-header />

    <!-- Main Content -->
    <main class="main-content">
        <div class="form-container">
            <div class="form-header">
                <h1>Nueva Aerolínea</h1>
                <p>Agrega una nueva aerolínea al sistema</p>
            </div>

            <form action="{{ route('airlines.store') }}" method="POST" enctype="multipart/form-data" class="airline-form">
                @csrf

                <div class="form-group">
                    <label for="name">Nombre de la Aerolínea *</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required>
                    @error('name')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="carrier_code">Código de Transportista *</label>
                    <input type="text" id="carrier_code" name="carrier_code" value="{{ old('carrier_code') }}" required maxlength="10">
                    @error('carrier_code')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="logo">Logo de la Aerolínea</label>
                    <input type="file" id="logo" name="logo" accept="image/*">
                    <small class="form-hint">Formatos permitidos: JPEG, PNG, JPG, GIF. Tamaño máximo: 2MB</small>
                    @error('logo')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-actions">
                    <a href="{{ route('airlines.index') }}" class="btn-secondary">Cancelar</a>
                    <button type="submit" class="btn-primary">Crear Aerolínea</button>
                </div>
            </form>
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
        max-width: 800px;
        margin: 0 auto;
        padding: 2rem;
    }

    .form-container {
        background: white;
        border-radius: var(--radius);
        box-shadow: var(--shadow-soft);
        border: 1px solid var(--stone-300);
        overflow: hidden;
    }

    .form-header {
        background: var(--sky-50);
        padding: 2rem;
        border-bottom: 1px solid var(--stone-300);
        text-align: center;
    }

    .form-header h1 {
        font-size: 1.75rem;
        font-weight: 700;
        color: var(--ink);
        margin-bottom: 0.5rem;
    }

    .form-header p {
        color: var(--slate-500);
        font-size: 1rem;
    }

    .airline-form {
        padding: 2rem;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-group label {
        display: block;
        margin-bottom: 0.5rem;
        color: var(--slate-600);
        font-weight: 600;
        font-size: 0.95rem;
    }

    .form-group input[type="text"],
    .form-group input[type="file"] {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid var(--stone-300);
        border-radius: 8px;
        font-size: 1rem;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }

    .form-group input[type="text"]:focus,
    .form-group input[type="file"]:focus {
        outline: none;
        border-color: var(--blue-700);
        box-shadow: 0 0 0 3px rgba(14, 165, 233, 0.1);
    }

    .form-hint {
        display: block;
        margin-top: 0.25rem;
        color: var(--slate-500);
        font-size: 0.85rem;
    }

    .error-message {
        display: block;
        margin-top: 0.25rem;
        color: var(--danger);
        font-size: 0.85rem;
        font-weight: 500;
    }

    .form-actions {
        display: flex;
        gap: 1rem;
        justify-content: flex-end;
        margin-top: 2rem;
        padding-top: 1.5rem;
        border-top: 1px solid var(--stone-300);
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
        display: inline-block;
        text-align: center;
    }

    .btn-primary {
        background: var(--blue-700);
        color: white;
        box-shadow: var(--shadow-soft);
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

    @media (max-width: 768px) {
        .main-content {
            padding: 1rem;
        }

        .form-container {
            margin: 0;
        }

        .form-header,
        .airline-form {
            padding: 1.5rem;
        }

        .form-actions {
            flex-direction: column;
        }

        .btn-primary,
        .btn-secondary {
            width: 100%;
        }
    }
</style>
@endpush
