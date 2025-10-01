@extends('layouts.app')

@section('title', 'Viantryp - Editor de Itinerarios')

@section('content')

    <x-header :showActions="true" :backUrl="'#'" :backOnclick="'showUnsavedChangesModal()'" :actions="[
        ['url' => '#', 'text' => 'Guardar', 'class' => 'btn-save', 'icon' => 'fas fa-save', 'onclick' => 'saveTrip()'],
        ['url' => '#', 'text' => 'Vista Previa', 'class' => 'btn-preview', 'icon' => 'fas fa-eye', 'onclick' => 'previewTrip()'],
        ['url' => '#', 'text' => 'Descarga versión PDF', 'class' => 'btn-pdf', 'icon' => 'fas fa-file-pdf', 'onclick' => 'downloadPDF()']
    ]" />

    <!-- New Trip Modal Component -->
    <x-new-trip-modal />

    <div class="editor-container" id="editor-container">
        <!-- Sidebar Component -->
        <x-sidebar />

        <!-- Main Content Area -->
        <div class="editor-main">
            <div class="main-content">
                <!-- Trip Header Component -->
                <x-trip-header :trip="$trip ?? null" />

                <!-- Timeline Component -->
                <x-timeline :trip="$trip ?? null" />

                <!-- Add Day Button -->
                <div class="add-day-section">
                    <button class="btn-add-day" onclick="addNewDay()">
                        <i class="fas fa-plus"></i>
                        Agregar Día
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Element Modal Component -->
    <x-element-modal />

    <!-- Unsaved Changes Modal Component -->
    <x-unsaved-changes-modal />
@endsection

@push('styles')
<style>
    :root {
        --primary-dark: #1f2a44;
        --primary-blue: #0ea5e9;
        --light-blue: #e0f2fe;
        --coral: #FF6B6B;
        --mint: #22c55e;
        --gold: #fbbf24;
        --purple: #a78bfa;
        --orange: #fb923c;
        --light-gray: #F6F9FC;
        --white: #FFFFFF;
        --shadow: rgba(0, 0, 0, 0.08);
        --text-gray: #64748b;
        --border-gray: #e2e8f0;
        --shadow-soft: 0 10px 30px rgba(0,0,0,0.06);
        --shadow-hover: 0 14px 40px rgba(0,0,0,0.08);
        --radius: 16px;
    }

    .editor-container {
        display: flex;
        height: calc(100vh - 80px);
        background: linear-gradient(180deg, #e6f3fb 0%, #f7fbff 60%);
    }

    /* Left Sidebar */
    .editor-sidebar {
        width: 320px;
        background: var(--white);
        border-right: 1px solid var(--border-gray);
        overflow-y: auto;
        box-shadow: var(--shadow-soft);
    }

    .sidebar-content {
        padding: 2rem;
    }

    .sidebar-section h4 {
        color: var(--primary-dark);
        font-size: 1.25rem;
        font-weight: 700;
        margin-bottom: 1.5rem;
        font-family: 'Poppins', sans-serif;
    }

    .element-categories {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .element-category {
        background: var(--white);
        border: 1px solid var(--border-gray);
        border-radius: var(--radius);
        padding: 1.25rem;
        cursor: grab;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 0.5rem;
        box-shadow: var(--shadow-soft);
        position: relative;
        overflow: hidden;
    }

    .element-category::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(14, 165, 233, 0.1), transparent);
        transition: left 0.5s;
    }

    .element-category:hover::before {
        left: 100%;
    }

    .element-category:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1), 0 4px 10px rgba(0, 0, 0, 0.06);
        border-color: var(--primary-blue);
    }

    .element-category:active {
        cursor: grabbing;
        transform: translateY(-1px);
    }

    .category-icon {
        width: 56px;
        height: 56px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.25rem;
        flex-shrink: 0;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .flight-icon { background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); }
    .hotel-icon { background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); }
    .activity-icon { background: linear-gradient(135deg, #10b981 0%, #059669 100%); }
    .transport-icon { background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); }
    .note-icon { background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%); }
    .summary-icon { background: linear-gradient(135deg, #10b981 0%, #059669 100%); }
    .total-icon { background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); }

    .category-info {
        flex: 1;
    }

    .category-info h5 {
        color: var(--primary-dark);
        font-size: 1rem;
        font-weight: 600;
        margin-bottom: 0.25rem;
        font-family: 'Poppins', sans-serif;
    }

    .category-info p {
        color: var(--text-gray);
        font-size: 0.875rem;
        margin: 0;
        line-height: 1.4;
        font-family: 'Poppins', sans-serif;
    }

    /* Special styles for summary and total elements */
    .element-category[data-type="summary"],
    .element-category[data-type="total"] {
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .element-category[data-type="summary"]:hover,
    .element-category[data-type="total"]:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15), 0 8px 16px rgba(0, 0, 0, 0.1);
        border-color: var(--primary-blue);
    }

    .element-category[data-type="summary"]:active,
    .element-category[data-type="total"]:active {
        transform: translateY(-2px);
    }

    /* Main Content */
    .editor-main {
        flex: 1;
        overflow-y: auto;
        background: transparent;
    }

    .main-content {
        padding: 2rem;
        max-width: 1200px;
        margin: 0 auto;
    }

    /* Trip Title */
    .trip-title-section {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 2rem;
    }

    .trip-icon {
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, #0ea5e9, #38bdf8);
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.5rem;
        box-shadow: var(--shadow-soft);
    }

    .trip-title-input {
        flex: 1;
        border: none;
        background: transparent;
        font-size: 2rem;
        font-weight: 700;
        color: var(--primary-dark);
        outline: none;
        padding: 0.5rem 0;
        font-family: 'Poppins', sans-serif;
    }

    .trip-title-input::placeholder {
        color: #adb5bd;
    }

    /* Info Card */
    .info-card {
        background: var(--white);
        border-radius: var(--radius);
        border: 1px solid var(--border-gray);
        margin-bottom: 2rem;
        overflow: hidden;
        box-shadow: var(--shadow-soft);
    }

    .card-header {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        padding: 1.5rem;
        border-bottom: 1px solid var(--border-gray);
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .card-header i {
        color: var(--primary-blue);
        font-size: 1.2rem;
    }

    .card-header h3 {
        color: var(--primary-dark);
        font-size: 1.1rem;
        font-weight: 600;
        margin: 0;
        font-family: 'Poppins', sans-serif;
    }

    .card-content {
        padding: 1.5rem;
    }

    .form-row {
        display: flex;
        gap: 1rem;
        margin-bottom: 1rem;
        align-items: end;
    }

    .form-row:last-child {
        margin-bottom: 0;
    }

    .form-group {
        flex: 1;
    }

    .form-group label {
        display: block;
        color: var(--primary-dark);
        font-weight: 500;
        margin-bottom: 0.5rem;
        font-size: 0.9rem;
        font-family: 'Poppins', sans-serif;
    }

    .form-input {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid var(--border-gray);
        border-radius: 10px;
        font-size: 0.9rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        font-family: 'Poppins', sans-serif;
    }

    .form-input:focus {
        outline: none;
        border-color: var(--primary-blue);
        box-shadow: 0 0 0 3px rgba(14, 165, 233, 0.1);
    }

    .btn-update-dates {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        border: none;
        padding: 0.75rem 1.5rem;
        border-radius: 10px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        display: flex;
        align-items: center;
        gap: 0.5rem;
        white-space: nowrap;
        font-family: 'Poppins', sans-serif;
        box-shadow: 0 2px 8px rgba(16, 185, 129, 0.3);
    }

    .btn-update-dates:hover {
        background: linear-gradient(135deg, #059669 0%, #047857 100%);
        transform: translateY(-1px);
        box-shadow: 0 4px 16px rgba(16, 185, 129, 0.4);
    }

    /* Days Container */
    .days-container {
        display: flex;
        flex-direction: column;
        gap: 2rem;
    }

    .day-card {
        background: var(--white);
        border-radius: var(--radius);
        border: 1px solid var(--border-gray);
        overflow: hidden;
        box-shadow: var(--shadow-soft);
        transition: all 0.3s ease;
    }

    .day-card:hover {
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1), 0 4px 10px rgba(0, 0, 0, 0.06);
    }

    .day-header {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        padding: 1.5rem;
        border-bottom: 1px solid var(--border-gray);
    }

    .day-header h3 {
        color: var(--primary-dark);
        font-size: 1.5rem;
        font-weight: 700;
        margin: 0 0 0.5rem 0;
        font-family: 'Poppins', sans-serif;
    }

    .day-date {
        background: #e0f2fe;
        color: #0f172a;
        padding: 0.5rem 1rem;
        border-radius: 999px;
        font-size: 0.9rem;
        font-weight: 600;
        display: inline-block;
        font-family: 'Poppins', sans-serif;
    }

    .day-content {
        padding: 2rem;
        text-align: center;
        min-height: 200px;
        position: relative;
    }

    .add-element-btn {
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem auto;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        color: white;
        font-size: 1.5rem;
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
    }

    .add-element-btn:hover {
        background: linear-gradient(135deg, #1d4ed8 0%, #1e40af 100%);
        transform: scale(1.05);
        box-shadow: 0 8px 20px rgba(59, 130, 246, 0.4);
    }

    .drag-instruction {
        color: var(--text-gray);
        font-size: 0.9rem;
        margin: 0;
        font-family: 'Poppins', sans-serif;
    }

    /* Add Day Section */
    .add-day-section {
        text-align: center;
        margin-top: 2rem;
    }

    .btn-add-day {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        border: none;
        padding: 1rem 2rem;
        border-radius: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        display: inline-flex;
        align-items: center;
        gap: 0.75rem;
        font-size: 1rem;
        font-family: 'Poppins', sans-serif;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
    }

    .btn-add-day:hover {
        background: linear-gradient(135deg, #059669 0%, #047857 100%);
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(16, 185, 129, 0.4);
    }

    /* Modal */
    .modal {
        display: none;
        position: fixed;
        z-index: 10000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.6);
        backdrop-filter: blur(4px);
    }

    .modal.show {
        display: block !important;
    }

    #new-trip-modal {
        background-color: rgba(0, 0, 0, 0.7);
    }

    #new-trip-modal .modal-content {
        max-width: 500px;
        margin: 10% auto;
    }

    /* New Trip Modal Specific Styles */
    .new-trip-modal {
        max-width: 450px;
        margin: 15% auto;
    }

    .new-trip-modal .modal-header {
        background: var(--white);
        border-bottom: 1px solid var(--border-gray);
        padding: 1.5rem 2rem 1rem 2rem;
    }

    .modal-title {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 1.3rem;
        font-weight: 700;
        color: var(--primary-dark);
        font-family: 'Poppins', sans-serif;
    }

    .modal-title i {
        color: var(--primary-blue);
        font-size: 1.2rem;
    }

    .new-trip-modal .modal-close {
        background: none;
        border: none;
        font-size: 1.5rem;
        cursor: pointer;
        color: var(--text-gray);
        padding: 0.25rem;
        border-radius: 8px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .new-trip-modal .modal-close:hover {
        background: rgba(107, 114, 128, 0.1);
        color: var(--primary-dark);
    }

    .new-trip-modal .modal-body {
        padding: 2rem;
        text-align: center;
    }

    .welcome-section {
        margin-bottom: 2rem;
    }

    .airplane-icon {
        font-size: 3rem;
        margin-bottom: 1rem;
        color: var(--primary-blue);
    }

    .welcome-section h2 {
        color: var(--primary-dark);
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        font-family: 'Poppins', sans-serif;
    }

    .welcome-section p {
        color: var(--text-gray);
        font-size: 1rem;
        margin: 0;
        font-family: 'Poppins', sans-serif;
    }

    .input-section {
        text-align: left;
    }

    .input-section label {
        display: block;
        color: var(--primary-dark);
        font-weight: 600;
        margin-bottom: 0.75rem;
        font-size: 1rem;
        font-family: 'Poppins', sans-serif;
    }

    .trip-name-input {
        width: 100%;
        padding: 1rem;
        border: 2px solid var(--border-gray);
        border-radius: 12px;
        font-size: 1rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-sizing: border-box;
        font-family: 'Poppins', sans-serif;
    }

    .trip-name-input:focus {
        outline: none;
        border-color: var(--primary-blue);
        box-shadow: 0 0 0 3px rgba(14, 165, 233, 0.1);
    }

    .trip-name-input::placeholder {
        color: #adb5bd;
    }

    .new-trip-modal .modal-footer {
        background: var(--white);
        border-top: 1px solid var(--border-gray);
        padding: 1.5rem 2rem;
        display: flex;
        justify-content: space-between;
        gap: 1rem;
    }

    .btn-cancel {
        background: rgba(107, 114, 128, 0.1);
        color: #6b7280;
        border: 1px solid rgba(107, 114, 128, 0.2);
        padding: 0.75rem 1.5rem;
        border-radius: 10px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        font-size: 0.9rem;
        font-family: 'Poppins', sans-serif;
    }

    .btn-cancel:hover {
        background: rgba(107, 114, 128, 0.15);
        border-color: rgba(107, 114, 128, 0.3);
        transform: translateY(-1px);
    }

    .btn-create {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        border: none;
        padding: 0.75rem 1.5rem;
        border-radius: 10px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        font-size: 0.9rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-family: 'Poppins', sans-serif;
        box-shadow: 0 2px 8px rgba(16, 185, 129, 0.3);
    }

    .btn-create:hover {
        background: linear-gradient(135deg, #059669 0%, #047857 100%);
        transform: translateY(-1px);
        box-shadow: 0 4px 16px rgba(16, 185, 129, 0.4);
    }

    .btn-create i {
        font-size: 0.8rem;
    }

    /* Force modal to be visible when it has the show class */
    #new-trip-modal.show {
        display: block !important;
        visibility: visible !important;
        opacity: 1 !important;
    }

    .modal-content {
        background-color: var(--white);
        margin: 5% auto;
        padding: 0;
        border-radius: var(--radius);
        width: 90%;
        max-width: 600px;
        max-height: 80vh;
        overflow-y: auto;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15), 0 8px 16px rgba(0, 0, 0, 0.1);
    }

    .modal-header {
        padding: 1.5rem;
        border-bottom: 1px solid var(--border-gray);
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    }

    .modal-header h3 {
        color: var(--primary-dark);
        font-size: 1.2rem;
        font-weight: 600;
        margin: 0;
        font-family: 'Poppins', sans-serif;
    }

    .modal-close {
        background: none;
        border: none;
        font-size: 1.5rem;
        cursor: pointer;
        color: var(--text-gray);
        padding: 0.25rem;
        border-radius: 8px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .modal-close:hover {
        background: rgba(107, 114, 128, 0.1);
        color: var(--primary-dark);
    }

    .modal-body {
        padding: 1.5rem;
    }

    .modal-footer {
        padding: 1.5rem;
        border-top: 1px solid var(--border-gray);
        display: flex;
        justify-content: flex-end;
        gap: 1rem;
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    }

    /* Timeline Items */
    .timeline-item {
        background: var(--white);
        border: 1px solid var(--border-gray);
        border-radius: var(--radius);
        margin-bottom: 1.5rem;
        overflow: hidden;
        box-shadow: var(--shadow-soft);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .timeline-item:hover {
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1), 0 4px 10px rgba(0, 0, 0, 0.06);
        transform: translateY(-2px);
    }

    .item-header {
        padding: 1.75rem;
        border-bottom: 1px solid var(--border-gray);
        display: flex;
        align-items: center;
        gap: 1.25rem;
        background: linear-gradient(135deg, #ffffff 0%, #fafbfc 100%);
    }

    .item-icon {
        width: 56px;
        height: 56px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.25rem;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .icon-flight { background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); }
    .icon-hotel { background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); }
    .icon-activity { background: linear-gradient(135deg, #10b981 0%, #059669 100%); }
    .icon-transport { background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); }
    .icon-note { background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%); }
    .icon-summary { background: linear-gradient(135deg, #10b981 0%, #059669 100%); }
    .icon-total { background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); }

    .item-info {
        flex: 1;
    }

    .item-type {
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        color: #6b7280;
        margin-bottom: 0.5rem;
        font-family: 'Inter', sans-serif;
    }

    .item-title {
        font-size: 1.125rem;
        font-weight: 600;
        color: #111827;
        margin-bottom: 0.25rem;
        line-height: 1.4;
        font-family: 'Poppins', sans-serif;
    }

    .item-subtitle {
        color: #6b7280;
        font-size: 0.875rem;
        line-height: 1.5;
        font-family: 'Poppins', sans-serif;
    }

    .item-actions {
        display: flex;
        gap: 0.5rem;
    }

    .action-btn {
        padding: 0.5rem;
        border: 1px solid var(--border-gray);
        border-radius: 8px;
        background: rgba(107, 114, 128, 0.1);
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        color: #6b7280;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 36px;
        height: 36px;
        font-family: 'Inter', sans-serif;
    }

    .action-btn:hover {
        background: rgba(107, 114, 128, 0.15);
        border-color: rgba(107, 114, 128, 0.3);
        color: #374151;
        transform: scale(1.05);
    }

    /* Special styling for summary and total elements */
    .timeline-item.summary {
        background: linear-gradient(135deg, #f8fff8 0%, #ffffff 100%);
        border: 2px solid #10b981;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.1);
    }

    .timeline-item.summary .item-icon {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    }

    .timeline-item.total {
        background: linear-gradient(135deg, #fff8f8 0%, #ffffff 100%);
        border: 2px solid #ef4444;
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.1);
    }

    .timeline-item.total .item-icon {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    }

    .summary-update-btn {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        border: none;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-size: 0.8rem;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        margin-left: 0.5rem;
        font-family: 'Inter', sans-serif;
        box-shadow: 0 2px 8px rgba(16, 185, 129, 0.3);
    }

    .summary-update-btn:hover {
        background: linear-gradient(135deg, #059669 0%, #047857 100%);
        transform: translateY(-1px);
        box-shadow: 0 4px 16px rgba(16, 185, 129, 0.4);
    }

    .action-btn.btn-danger:hover {
        background: rgba(239, 68, 68, 0.1);
        border-color: rgba(239, 68, 68, 0.3);
        color: #dc2626;
    }

    /* Element Type Selection */
    .element-type-selection {
        padding: 1rem 0;
    }

    .element-type-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
        gap: 1.25rem;
    }

    .element-type-btn {
        background: var(--white);
        border: 1px solid var(--border-gray);
        border-radius: var(--radius);
        padding: 1.5rem 1rem;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.75rem;
        text-align: center;
        box-shadow: var(--shadow-soft);
        position: relative;
        overflow: hidden;
    }

    .element-type-btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(14, 165, 233, 0.1), transparent);
        transition: left 0.5s;
    }

    .element-type-btn:hover::before {
        left: 100%;
    }

    .element-type-btn:hover {
        border-color: var(--primary-blue);
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        transform: translateY(-3px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1), 0 4px 10px rgba(0, 0, 0, 0.06);
    }

    .element-type-icon {
        width: 56px;
        height: 56px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.25rem;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .element-type-btn span {
        font-weight: 600;
        color: var(--primary-dark);
        font-size: 0.9rem;
        font-family: 'Poppins', sans-serif;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .editor-container {
            flex-direction: column;
        }

        .editor-sidebar {
            width: 100%;
            height: auto;
            max-height: 200px;
        }

        .main-content {
            padding: 1rem;
        }

        .form-row {
            flex-direction: column;
            gap: 0.5rem;
        }

        .btn-update-dates {
            width: 100%;
            justify-content: center;
        }

        .element-type-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .sidebar-content {
            padding: 1.5rem;
        }

        .element-categories {
            gap: 0.75rem;
        }

        .element-category {
            padding: 1rem;
        }

        .category-icon {
            width: 48px;
            height: 48px;
            font-size: 1.1rem;
        }

        .item-header {
            padding: 1.25rem;
            gap: 1rem;
        }

        .item-icon {
            width: 48px;
            height: 48px;
            font-size: 1.1rem;
        }
    }

    /* Checkbox Styles */
    .checkbox-label {
        display: flex;
        align-items: center;
        cursor: pointer;
        font-size: 14px;
        color: var(--primary-dark);
        margin: 10px 0;
        position: relative;
    }

    .checkbox-label input[type="checkbox"] {
        position: absolute;
        opacity: 0;
        cursor: pointer;
    }

    .checkmark {
        height: 18px;
        width: 18px;
        background-color: #fff;
        border: 2px solid #e2e8f0;
        border-radius: 4px;
        margin-right: 10px;
        position: relative;
        transition: all 0.2s ease;
    }

    .checkbox-label input[type="checkbox"]:checked ~ .checkmark {
        background-color: var(--primary-blue);
        border-color: var(--primary-blue);
    }

    .checkmark:after {
        content: "";
        position: absolute;
        display: none;
        left: 5px;
        top: 2px;
        width: 4px;
        height: 8px;
        border: solid white;
        border-width: 0 2px 2px 0;
        transform: rotate(45deg);
    }

    .checkbox-label input[type="checkbox"]:checked ~ .checkmark:after {
        display: block;
    }

    .form-text {
        font-size: 12px;
        color: #64748b;
        margin-top: 5px;
        display: block;
    }
</style>
@endpush

@push('scripts')
<script>
    let currentElementType = null;
    let currentElementData = {};
    let currentDay = null;

    // Initialize the page
    document.addEventListener('DOMContentLoaded', function() {
        // Check if we're on the create route
        const isNewTrip = window.location.pathname.includes('/create');
        console.log('DOM loaded, current path:', window.location.pathname);
        console.log('Is new trip:', isNewTrip);

        const modal = document.getElementById('new-trip-modal');
        const editor = document.getElementById('editor-container');

        console.log('Modal element:', modal);
        console.log('Editor element:', editor);

        if (isNewTrip) {
            // Show modal for new trip
            console.log('Showing new trip modal');
            if (modal) {
                modal.classList.add('show');
                console.log('Modal classes after adding show:', modal.className);
            }
            if (editor) {
                editor.style.display = 'none';
            }
        } else {
            // Hide modal for existing trip
            console.log('Hiding modal, showing editor');
            if (modal) {
                modal.classList.remove('show');
            }
            if (editor) {
                editor.style.display = 'flex';
            }
        }

        // Add event listeners to draggable elements
        const draggableElements = document.querySelectorAll('.element-category');
        draggableElements.forEach(element => {
            element.addEventListener('dragstart', drag);

            // Add click handlers for summary and total elements
            if (element.dataset.type === 'summary') {
                element.addEventListener('click', function(e) {
                    e.preventDefault();
                    handleSummaryClick(element);
                });
            }
            if (element.dataset.type === 'total') {
                element.addEventListener('click', function(e) {
                    e.preventDefault();
                    handleTotalClick(element);
                });
            }
        });

        // Track changes for unsaved changes warning
        let hasUnsavedChanges = false;
        const originalData = collectAllTripItems();

        // Function to check if there are unsaved changes
        function checkForChanges() {
            const currentData = collectAllTripItems();
            const currentTitle = document.getElementById('trip-title').value;
            const currentStartDate = document.getElementById('start-date').value;

            // Compare with original data
            const hasDataChanges = JSON.stringify(currentData) !== JSON.stringify(originalData);
            const hasTitleChanges = currentTitle !== (document.getElementById('trip-title').defaultValue || '');
            const hasDateChanges = currentStartDate !== (document.getElementById('start-date').defaultValue || '');

            hasUnsavedChanges = hasDataChanges || hasTitleChanges || hasDateChanges;
        }

        // Add change listeners to track modifications
        document.getElementById('trip-title').addEventListener('input', function() {
            checkForChanges();
            // Update all summaries and totals when title changes
            updateAllSummaries();
        });
        document.getElementById('start-date').addEventListener('change', checkForChanges);

        // Warn about unsaved changes when leaving the page
        window.addEventListener('beforeunload', function(e) {
            checkForChanges();
            if (hasUnsavedChanges) {
                e.preventDefault();
                e.returnValue = 'Tienes cambios sin guardar. ¿Estás seguro de que quieres salir?';
                return e.returnValue;
            }
        });

        // Handle back button and other navigation
        window.addEventListener('popstate', function(e) {
            checkForChanges();
            if (hasUnsavedChanges) {
                const shouldLeave = confirm('Tienes cambios sin guardar. ¿Estás seguro de que quieres salir?');
                if (!shouldLeave) {
                    history.pushState(null, null, window.location.pathname);
                }
            }
        });
    });

    // Drag and Drop functionality
    function allowDrop(ev) {
        ev.preventDefault();
    }

    function drag(ev) {
        ev.dataTransfer.setData("text", ev.target.dataset.type);
    }

    function drop(ev) {
        ev.preventDefault();
        const elementType = ev.dataTransfer.getData("text");
        const dayElement = ev.currentTarget.closest('.day-card');
        const dayNumber = parseInt(dayElement.dataset.day);

        currentDay = dayNumber;
        currentElementType = elementType;
        currentElementData = { type: elementType, day: dayNumber };

        // Special handling for summary - create directly without modal
        if (elementType === 'summary') {
            handleSummaryClick();
            return;
        }

        const modalTitle = document.getElementById('modal-title');
        const modalBody = document.getElementById('modal-body');

        modalTitle.textContent = `Agregar ${getTypeLabel(elementType)}`;
        modalBody.innerHTML = getElementForm(elementType);

        document.getElementById('element-modal').style.display = 'block';
    }


    function addElementToDay(day) {
        showElementTypeSelection(day);
    }

    function showElementTypeSelection(day) {
        currentDay = day;
        const modal = document.getElementById('element-modal');
        const modalTitle = document.getElementById('modal-title');
        const modalBody = document.getElementById('modal-body');

        modalTitle.textContent = 'Seleccionar Tipo de Elemento';
        modalBody.innerHTML = `
            <div class="element-type-selection">
                <div class="element-type-grid">
                    <button class="element-type-btn" onclick="selectElementType('flight')">
                        <div class="element-type-icon flight-icon">
                            <i class="fas fa-plane"></i>
                        </div>
                        <span>Vuelo</span>
                    </button>
                    <button class="element-type-btn" onclick="selectElementType('hotel')">
                        <div class="element-type-icon hotel-icon">
                            <i class="fas fa-bed"></i>
                        </div>
                        <span>Hotel</span>
                    </button>
                    <button class="element-type-btn" onclick="selectElementType('activity')">
                        <div class="element-type-icon activity-icon">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <span>Actividad</span>
                    </button>
                    <button class="element-type-btn" onclick="selectElementType('transport')">
                        <div class="element-type-icon transport-icon">
                            <i class="fas fa-car"></i>
                        </div>
                        <span>Traslado</span>
                    </button>
                    <button class="element-type-btn" onclick="selectElementType('note')">
                        <div class="element-type-icon note-icon">
                            <i class="fas fa-sticky-note"></i>
                        </div>
                        <span>Nota</span>
                    </button>
                    <button class="element-type-btn" onclick="selectElementType('summary')">
                        <div class="element-type-icon summary-icon">
                            <i class="fas fa-list-check"></i>
                        </div>
                        <span>Resumen</span>
                    </button>
                    <button class="element-type-btn" onclick="selectElementType('total')">
                        <div class="element-type-icon total-icon">
                            <i class="fas fa-dollar-sign"></i>
                        </div>
                        <span>Valor Total</span>
                    </button>
                </div>
            </div>
        `;

        modal.style.display = 'block';
    }

    function selectElementType(type) {
        currentElementType = type;
        currentElementData = { type: type, day: currentDay };

        // Special handling for summary - create directly without modal
        if (type === 'summary') {
            handleSummaryClick();
            closeModal();
            return;
        }

        const modalTitle = document.getElementById('modal-title');
        const modalBody = document.getElementById('modal-body');

        modalTitle.textContent = `Agregar ${getTypeLabel(type)}`;
        modalBody.innerHTML = getElementForm(type);
    }

    function getTypeLabel(type) {
        const labels = {
            'flight': 'Vuelo',
            'hotel': 'Hotel',
            'activity': 'Actividad',
            'transport': 'Traslado',
            'note': 'Nota',
            'summary': 'Resumen de Itinerario',
            'total': 'Valor Total'
        };
        return labels[type] || 'Elemento';
    }

    function getElementForm(type) {
        const forms = {
            'flight': `
                <div class="form-group">
                    <label for="airline">Aerolínea</label>
                    <input type="text" id="airline" class="form-input" placeholder="Ej: Iberia">
                </div>
                <div class="form-group">
                    <label for="flight-number">Número de Vuelo</label>
                    <input type="text" id="flight-number" class="form-input" placeholder="Ej: IB1234">
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="departure-time">Hora de Salida</label>
                        <input type="time" id="departure-time" class="form-input">
                    </div>
                    <div class="form-group">
                        <label for="arrival-time">Hora de Llegada</label>
                        <input type="time" id="arrival-time" class="form-input">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="departure-airport">Aeropuerto de Salida</label>
                        <input type="text" id="departure-airport" class="form-input" placeholder="Ej: Madrid Barajas">
                    </div>
                    <div class="form-group">
                        <label for="arrival-airport">Aeropuerto de Llegada</label>
                        <input type="text" id="arrival-airport" class="form-input" placeholder="Ej: París Charles de Gaulle">
                    </div>
                </div>
                <div class="form-group">
                    <label for="confirmation-number">Número de Confirmación</label>
                    <input type="text" id="confirmation-number" class="form-input" placeholder="Ej: ABC123">
                </div>
            `,
            'hotel': `
                <div class="form-group">
                    <label for="hotel-name">Nombre del Hotel</label>
                    <input type="text" id="hotel-name" class="form-input" placeholder="Ej: Hotel Plaza">
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="check-in">Check-in</label>
                        <input type="time" id="check-in" class="form-input">
                    </div>
                    <div class="form-group">
                        <label for="check-out">Check-out</label>
                        <input type="time" id="check-out" class="form-input">
                    </div>
                </div>
                <div class="form-group">
                    <label for="room-type">Tipo de Habitación</label>
                    <input type="text" id="room-type" class="form-input" placeholder="Ej: Habitación doble">
                </div>
                <div class="form-group">
                    <label for="nights">Noches</label>
                    <input type="number" id="nights" class="form-input" min="1" placeholder="2">
                </div>
            `,
            'activity': `
                <div class="form-group">
                    <label for="activity-title">Título de la Actividad</label>
                    <input type="text" id="activity-title" class="form-input" placeholder="Ej: Visita al Louvre">
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="start-time">Hora de Inicio</label>
                        <input type="time" id="start-time" class="form-input">
                    </div>
                    <div class="form-group">
                        <label for="end-time">Hora de Fin</label>
                        <input type="time" id="end-time" class="form-input">
                    </div>
                </div>
                <div class="form-group">
                    <label for="location">Ubicación</label>
                    <input type="text" id="location" class="form-input" placeholder="Ej: Museo del Louvre, París">
                </div>
                <div class="form-group">
                    <label for="description">Descripción</label>
                    <textarea id="description" class="form-input" rows="3" placeholder="Detalles de la actividad..."></textarea>
                </div>
            `,
            'transport': `
                <div class="form-group">
                    <label for="transport-type">Tipo de Transporte</label>
                    <input type="text" id="transport-type" class="form-input" placeholder="Ej: Taxi, Metro, Bus">
                </div>
                <div class="form-group">
                    <label for="pickup-time">Hora de Recogida</label>
                    <input type="time" id="pickup-time" class="form-input">
                </div>
                <div class="form-group">
                    <label for="pickup-location">Punto de Recogida</label>
                    <input type="text" id="pickup-location" class="form-input" placeholder="Ej: Hotel Plaza">
                </div>
                <div class="form-group">
                    <label for="destination">Destino</label>
                    <input type="text" id="destination" class="form-input" placeholder="Ej: Aeropuerto">
                </div>
            `,
            'note': `
                <div class="form-group">
                    <label for="note-title">Título de la Nota</label>
                    <input type="text" id="note-title" class="form-input" placeholder="Ej: Recordatorios importantes">
                </div>
                <div class="form-group">
                    <label for="note-content">Contenido</label>
                    <textarea id="note-content" class="form-input" rows="4" placeholder="Escribe tu nota aquí..."></textarea>
                </div>
            `,
            'summary': ``,
            'total': `
                <div class="form-group">
                    <label for="total-amount">Precio total del viaje *</label>
                    <input type="number" id="total-amount" class="form-input" placeholder="0" min="0" step="0.01" required>
                </div>
                <div class="form-group">
                    <label for="currency">Moneda *</label>
                    <select id="currency" class="form-input" required>
                        <option value="">Seleccionar moneda</option>
                        <option value="USD">USD - Dólar Estadounidense</option>
                        <option value="EUR">EUR - Euro</option>
                        <option value="CLP">CLP - Peso Chileno</option>
                        <option value="ARS">ARS - Peso Argentino</option>
                        <option value="PEN">PEN - Sol Peruano</option>
                        <option value="COP">COP - Peso Colombiano</option>
                        <option value="MXN">MXN - Peso Mexicano</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="checkbox-label">
                        <input type="checkbox" id="place-at-end">
                        <span class="checkmark"></span>
                        Colocar al final del itinerario
                    </label>
                    <small class="form-text">Si no se marca, se colocará al inicio (después del resumen)</small>
                </div>
                <div class="form-group">
                    <label for="price-breakdown">Desglose del precio (opcional)</label>
                    <textarea id="price-breakdown" class="form-input" rows="4" placeholder="Ej: Vuelos: $500, Hoteles: $800, Actividades: $300, Transporte: $200"></textarea>
                </div>
            `
        };

        return forms[type] || '<p>Formulario no disponible</p>';
    }

    function saveElement() {
        const formData = collectFormData();

        // Validate required fields
        if (!validateForm(formData)) {
            return;
        }

        // If editing existing element
        if (currentElementData && currentElementData.title && currentElementData.title !== '') {
            // Update existing element
            updateExistingElement(formData);
        } else {
            // Create new element
            addElementToDay(formData);
        }

        closeModal();
        showNotification('Elemento Guardado', `${getTypeLabel(currentElementType)} guardado correctamente.`);
    }

    function validateForm(data) {
        // Validate required fields based on element type
        if (data.type === 'total') {
            if (!data.total_amount || data.total_amount === '0') {
                showNotification('Error', 'El precio total es obligatorio.', 'error');
                return false;
            }
            if (!data.currency) {
                showNotification('Error', 'La moneda es obligatoria.', 'error');
                return false;
            }
        }
        return true;
    }

    function updateExistingElement(newData) {
        // Find the existing element to update
        const allItems = document.querySelectorAll('.timeline-item');
        let elementToUpdate = null;

        allItems.forEach(item => {
            const itemData = extractItemDataForDisplay(item);
            if (itemData && itemData.title === currentElementData.title && itemData.type === currentElementData.type) {
                elementToUpdate = item;
            }
        });

        if (elementToUpdate) {
            // Update the element's content
            const titleElement = elementToUpdate.querySelector('.item-title');
            const subtitleElement = elementToUpdate.querySelector('.item-subtitle');

            if (titleElement) {
                titleElement.textContent = getElementTitle(newData);
            }
            if (subtitleElement) {
                subtitleElement.textContent = getElementSubtitle(newData);
            }

            // Update summaries
            updateAllSummaries();
        }
    }

    function fillFormWithData(data) {
        const form = document.getElementById('modal-body');
        const inputs = form.querySelectorAll('input, textarea, select');

        inputs.forEach(input => {
            const fieldName = input.id.replace('-', '_');
            if (data[fieldName] !== undefined) {
                if (input.type === 'checkbox') {
                    input.checked = data[fieldName];
                } else {
                    input.value = data[fieldName];
                }
            }
        });
    }

    function collectFormData() {
        const data = { type: currentElementType, day: currentDay };
        const form = document.getElementById('modal-body');
        const inputs = form.querySelectorAll('input, textarea, select');

        inputs.forEach(input => {
            if (input.type === 'checkbox') {
                data[input.id.replace('-', '_')] = input.checked;
            } else if (input.value.trim()) {
                data[input.id.replace('-', '_')] = input.value.trim();
            }
        });

        return data;
    }

    function addElementToDay(data) {
        // Special handling for total element positioning
        if (data.type === 'total') {
            const daysContainer = document.getElementById('days-container');
            if (!daysContainer) return;

            // Create element
            const elementDiv = createElementDiv(data);

            if (data.place_at_end) {
                // Place at the end of all days
                daysContainer.appendChild(elementDiv);
            } else {
                // Place at the beginning (after summary if exists)
                const firstDay = daysContainer.querySelector('.day-card');
                if (firstDay) {
                    daysContainer.insertBefore(elementDiv, firstDay);
                } else {
                    daysContainer.appendChild(elementDiv);
                }
            }

            // Update summaries after adding element
            updateAllSummaries();
            return;
        }

        const dayCard = document.querySelector(`[data-day="${data.day}"]`);
        if (!dayCard) return;

        const dayContent = dayCard.querySelector('.day-content');

        // Hide the add button and instruction
        const addBtn = dayContent.querySelector('.add-element-btn');
        const instruction = dayContent.querySelector('.drag-instruction');
        if (addBtn) addBtn.style.display = 'none';
        if (instruction) instruction.style.display = 'none';

        // Create element
        const elementDiv = createElementDiv(data);
        dayContent.appendChild(elementDiv);

        // Update summaries after adding element
        updateAllSummaries();
    }

    function createElementDiv(data) {
        const elementDiv = document.createElement('div');
        elementDiv.className = `timeline-item ${data.type}`;
        elementDiv.innerHTML = `
            <div class="item-header">
                <div class="item-icon icon-${data.type}">
                    <i class="${getIcon(data.type)}"></i>
                </div>
                <div class="item-info">
                    <div class="item-type">${getTypeLabel(data.type)}</div>
                    <div class="item-title">${getElementTitle(data)}</div>
                    <div class="item-subtitle">${getElementSubtitle(data)}</div>
                </div>
                <div class="item-actions">
                    <button class="action-btn" onclick="editElement(this)" title="Editar">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="action-btn btn-danger" onclick="deleteElement(this)" title="Eliminar">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        `;

        return elementDiv;
    }

    function getElementTitle(data) {
        switch (data.type) {
            case 'flight':
                return `${data.airline || 'Vuelo'} ${data.flight_number || ''}`.trim();
            case 'hotel':
                return data.hotel_name || 'Hotel';
            case 'activity':
                return data.activity_title || 'Actividad';
            case 'transport':
                return data.transport_type || 'Traslado';
            case 'note':
                return data.note_title || 'Nota';
            case 'summary':
                return data.summary_title || 'Resumen de Itinerario';
            case 'total':
                const currencySymbols = {
                    'USD': '$',
                    'EUR': '€',
                    'CLP': '$',
                    'ARS': '$',
                    'PEN': 'S/',
                    'COP': '$',
                    'MXN': '$'
                };
                const symbol = currencySymbols[data.currency] || data.currency || '$';
                const amount = data.total_amount || '0.00';
                return `${symbol}${parseFloat(amount).toFixed(2)} ${data.currency || 'USD'}`;
            default:
                return 'Elemento';
        }
    }

    function getElementSubtitle(data) {
        switch (data.type) {
            case 'flight':
                return `${data.departure_airport || ''} → ${data.arrival_airport || ''}`.replace(' → ', '');
            case 'hotel':
                return `${data.check_in || ''} - ${data.check_out || ''}`.replace(' - ', '');
            case 'activity':
                return data.location || '';
            case 'transport':
                return `${data.pickup_location || ''} → ${data.destination || ''}`.replace(' → ', '');
            case 'summary':
                return 'Resumen automático del viaje';
            case 'total':
                return data.price_breakdown || 'Precio total del viaje';
            default:
                return '';
        }
    }

    function getIconClass(type) {
        const classes = {
            'flight': 'icon-flight',
            'hotel': 'icon-hotel',
            'activity': 'icon-activity',
            'transport': 'icon-transport',
            'note': 'icon-note',
            'summary': 'icon-summary',
            'total': 'icon-total'
        };
        return classes[type] || 'icon-note';
    }

    function getIcon(type) {
        const icons = {
            'flight': 'fas fa-plane',
            'hotel': 'fas fa-bed',
            'activity': 'fas fa-map-marker-alt',
            'transport': 'fas fa-car',
            'note': 'fas fa-sticky-note',
            'summary': 'fas fa-list-check',
            'total': 'fas fa-dollar-sign'
        };
        return icons[type] || 'fas fa-sticky-note';
    }

    function closeModal() {
        document.getElementById('element-modal').style.display = 'none';
        currentElementType = null;
        currentElementData = {};
        currentDay = null;
    }

    function editElement(button) {
        const itemElement = button.closest('.timeline-item');
        if (!itemElement) return;

        const itemData = extractItemDataForDisplay(itemElement);
        if (!itemData) return;

        // Set current element data for editing
        currentElementType = itemData.type;
        currentElementData = itemData;
        currentDay = itemData.day || 1;

        // Show modal with form
        const modalTitle = document.getElementById('modal-title');
        const modalBody = document.getElementById('modal-body');

        modalTitle.textContent = `Editar ${getTypeLabel(itemData.type)}`;
        modalBody.innerHTML = getElementForm(itemData.type);

        // Fill form with existing data
        fillFormWithData(itemData);

        document.getElementById('element-modal').style.display = 'block';
    }

    function deleteElement(button) {
        if (confirm('¿Estás seguro de que quieres eliminar este elemento?')) {
            button.closest('.timeline-item').remove();
            showNotification('Elemento Eliminado', 'El elemento ha sido eliminado del itinerario.');
            // Update summaries after deletion
            updateAllSummaries();
        }
    }

    function addNewDay() {
        const daysContainer = document.getElementById('days-container');
        const existingDays = daysContainer.querySelectorAll('.day-card');
        const newDayNumber = existingDays.length + 1;

        const dayCard = document.createElement('div');
        dayCard.className = 'day-card';
        dayCard.setAttribute('data-day', newDayNumber);

        const startDate = document.getElementById('start-date').value;
        let dayDate = 'Sin fecha';
        if (startDate) {
            const date = new Date(startDate);
            date.setDate(date.getDate() + newDayNumber - 1);
            dayDate = date.toLocaleDateString('es-ES', {
                weekday: 'long',
                day: 'numeric',
                month: 'long',
                year: 'numeric'
            });
        }

        dayCard.innerHTML = `
            <div class="day-header">
                <h3>Día ${newDayNumber}</h3>
                <p class="day-date">${dayDate}</p>
            </div>
            <div class="day-content" ondrop="drop(event)" ondragover="allowDrop(event)">
                <div class="add-element-btn" onclick="addElementToDay(${newDayNumber})">
                    <i class="fas fa-plus"></i>
                </div>
                <p class="drag-instruction">Arrastra elementos aquí para personalizar este día</p>
            </div>
        `;

        daysContainer.appendChild(dayCard);

        // Update summaries after adding new day
        updateAllSummaries();

        showNotification('Día Agregado', `Día ${newDayNumber} agregado al itinerario.`);
    }

    function updateItineraryDates() {
        const startDateInput = document.getElementById('start-date').value;
        if (!startDateInput) {
            showNotification('Error', 'Por favor selecciona una fecha de inicio.');
            return;
        }

        const startDate = new Date(startDateInput + 'T00:00:00');
        const dayCards = document.querySelectorAll('.day-card');

        dayCards.forEach((card, index) => {
            const currentDate = new Date(startDate);
            currentDate.setDate(startDate.getDate() + index);

            const dateElement = card.querySelector('.day-date');
            const formattedDate = currentDate.toLocaleDateString('es-ES', {
                weekday: 'long',
                day: 'numeric',
                month: 'long',
                year: 'numeric'
            });
            dateElement.textContent = formattedDate;
            dateElement.setAttribute('data-date', currentDate.toISOString().split('T')[0]);
        });

        showNotification('Fechas Actualizadas', 'Las fechas de los días han sido actualizadas.');
        // Update summaries after date changes
        updateAllSummaries();
    }

    function previewTrip() {
        window.open('{{ url("trips/temp/preview") }}', '_blank');
    }

    function downloadPDF() {
        showNotification('PDF', 'Funcionalidad de descarga PDF en desarrollo.');
    }

    function saveTrip() {
        // Collect all trip elements from the days
        const itemsData = collectAllTripItems();

        // Calculate end date based on number of days
        const startDate = document.getElementById('start-date').value;
        let endDate = null;
        if (startDate) {
            const dayCards = document.querySelectorAll('.day-card');
            const startDateObj = new Date(startDate);
            const endDateObj = new Date(startDate);
            endDateObj.setDate(startDateObj.getDate() + dayCards.length - 1);
            endDate = endDateObj.toISOString().split('T')[0];
        }

        const tripData = {
            title: document.getElementById('trip-title').value,
            start_date: startDate,
            end_date: endDate,
            items_data: itemsData
        };

        // Determine if this is a new trip or updating existing
        const currentPath = window.location.pathname;
        const isEditing = currentPath.includes('/edit');
        let url, method, tripId = null;

        console.log('Current path:', currentPath);
        console.log('Is editing:', isEditing);

        if (isEditing) {
            // For editing, extract the trip ID from the URL and use POST with _method override
            const urlParts = currentPath.split('/');
            tripId = urlParts[urlParts.length - 2]; // Get the ID before /edit
            console.log('Extracted trip ID:', tripId);
            console.log('URL parts:', urlParts);

            if (!tripId || isNaN(tripId)) {
                console.error('Invalid trip ID extracted from URL');
                showNotification('Error', 'No se pudo determinar el ID del viaje para editar.');
                return;
            }

            url = '{{ url("trips") }}/' + tripId;
            method = 'POST'; // Use POST with _method override
            tripData._method = 'PATCH'; // Add method override
        } else {
            // For creating, make POST request to /trips
            url = '{{ url("trips") }}';
            method = 'POST';
        }

        // Fallback: if URL generation fails, use relative URLs
        if (!url || url === '{{ url("trips") }}') {
            console.log('URL generation failed, using fallback');
            if (isEditing && tripId) {
                url = '/trips/' + tripId;
            } else {
                url = '/trips';
            }
        }

        console.log('Final URL:', url);
        console.log('Method:', method);

        console.log('Saving trip:', { url, method, tripData });

        // Get CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        console.log('CSRF token found:', !!csrfToken);

        if (!csrfToken) {
            console.error('CSRF token not found!');
            showNotification('Error', 'Token de seguridad no encontrado. Recarga la página.');
            return;
        }

        fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify(tripData)
        })
        .then(response => {
            console.log('Response status:', response.status);
            console.log('Response headers:', response.headers);

            // Log the raw response text for debugging
            return response.text().then(text => {
                console.log('Raw response:', text);

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}, response: ${text}`);
                }

                try {
                    return JSON.parse(text);
                } catch (e) {
                    console.error('Failed to parse JSON response:', text);
                    throw new Error('Invalid JSON response from server');
                }
            });
        })
        .then(data => {
            console.log('Response data:', data);

            if (data.success) {
                showNotification('Viaje Guardado', 'El viaje ha sido guardado exitosamente.');
                window.location.href = '{{ route("trips.index") }}';
            } else {
                showNotification('Error', data.message || 'No se pudo guardar el viaje.');
            }
        })
        .catch(error => {
            console.error('Error saving trip:', error);
            showNotification('Error', 'No se pudo guardar el viaje. Revisa la consola para más detalles.');
        });
    }

    function collectAllTripItems() {
        const items = [];
        const dayCards = document.querySelectorAll('.day-card');

        dayCards.forEach((dayCard, index) => {
            const dayNumber = parseInt(dayCard.dataset.day) || (index + 1);
            const timelineItems = dayCard.querySelectorAll('.timeline-item');

            timelineItems.forEach(item => {
                const itemData = extractItemData(item, dayNumber);
                if (itemData) {
                    items.push(itemData);
                }
            });
        });

        return items;
    }

    function extractItemData(itemElement, dayNumber) {
        const itemType = itemElement.querySelector('.item-type')?.textContent?.toLowerCase();

        if (!itemType) return null;

        const baseData = {
            type: itemType.replace('elemento', '').trim().toLowerCase(),
            day: dayNumber
        };

        // Extract data based on item type
        switch (baseData.type) {
            case 'vuelo':
                return {
                    ...baseData,
                    type: 'flight',
                    airline: itemElement.querySelector('.item-title')?.textContent?.split(' ')[0] || '',
                    flight_number: itemElement.querySelector('.item-title')?.textContent?.split(' ')[1] || '',
                    departure_airport: itemElement.querySelector('.item-subtitle')?.textContent?.split(' → ')[0] || '',
                    arrival_airport: itemElement.querySelector('.item-subtitle')?.textContent?.split(' → ')[1] || ''
                };

            case 'alojamiento':
                return {
                    ...baseData,
                    type: 'hotel',
                    hotel_name: itemElement.querySelector('.item-title')?.textContent || '',
                    check_in: '',
                    check_out: '',
                    room_type: '',
                    nights: 1
                };

            case 'actividad':
                return {
                    ...baseData,
                    type: 'activity',
                    activity_title: itemElement.querySelector('.item-title')?.textContent || '',
                    location: itemElement.querySelector('.item-subtitle')?.textContent || '',
                    start_time: '',
                    end_time: '',
                    description: ''
                };

            case 'traslado':
            case 'transporte':
                return {
                    ...baseData,
                    type: 'transport',
                    transport_type: itemElement.querySelector('.item-title')?.textContent || '',
                    pickup_location: itemElement.querySelector('.item-subtitle')?.textContent?.split(' → ')[0] || '',
                    destination: itemElement.querySelector('.item-subtitle')?.textContent?.split(' → ')[1] || '',
                    pickup_time: ''
                };

            case 'nota':
                return {
                    ...baseData,
                    type: 'note',
                    note_title: itemElement.querySelector('.item-title')?.textContent || '',
                    note_content: itemElement.querySelector('.item-subtitle')?.textContent || ''
                };

            default:
                return {
                    ...baseData,
                    type: 'note',
                    note_title: itemElement.querySelector('.item-title')?.textContent || 'Elemento',
                    note_content: itemElement.querySelector('.item-subtitle')?.textContent || ''
                };
        }
    }

    function createNewTrip() {
        const tripName = document.getElementById('new-trip-name').value.trim();

        if (!tripName) {
            showNotification('Error', 'Por favor ingresa un nombre para el viaje.');
            return;
        }

        // Set the trip title
        document.getElementById('trip-title').value = tripName;

        // Hide modal and show editor
        document.getElementById('new-trip-modal').classList.remove('show');
        document.getElementById('editor-container').style.display = 'flex';

        showNotification('Viaje Creado', `Viaje "${tripName}" creado exitosamente.`);
    }

    function cancelNewTrip() {
        window.location.href = '{{ route("trips.index") }}';
    }

    // Unsaved Changes Modal Functions
    function showUnsavedChangesModal() {
        const modal = document.getElementById('unsaved-changes-modal');
        const changesSummary = document.getElementById('changesSummary');

        // Generate changes summary
        const changes = generateChangesSummary();
        changesSummary.innerHTML = changes;

        modal.style.display = 'block';
    }

    function closeUnsavedModal() {
        const modal = document.getElementById('unsaved-changes-modal');
        modal.style.display = 'none';
    }

    function exitWithoutSaving() {
        window.location.href = '{{ route("trips.index") }}';
    }

    function saveAndExit() {
        saveTrip();
    }

    function generateChangesSummary() {
        const currentData = collectAllTripItems();
        const originalData = []; // This would need to be stored when the page loads

        let changes = '';

        // Check for new items
        const currentItemCount = Object.keys(currentData).length;
        if (currentItemCount > 0) {
            changes += `• Se agregaron ${currentItemCount} elementos al itinerario<br>`;
        }

        // Check for title changes
        const currentTitle = document.getElementById('trip-title').value;
        const originalTitle = document.getElementById('trip-title').defaultValue || '';
        if (currentTitle !== originalTitle) {
            changes += `• Título del viaje modificado<br>`;
        }

        // Check for date changes
        const currentStartDate = document.getElementById('start-date').value;
        const originalStartDate = document.getElementById('start-date').defaultValue || '';

        if (currentStartDate !== originalStartDate) {
            changes += `• Fechas del viaje modificadas<br>`;
        }

        if (!changes) {
            changes = '• Cambios menores en el contenido<br>';
        }

        return changes;
    }

    // Close modal when clicking outside
    window.onclick = function(event) {
        const elementModal = document.getElementById('element-modal');
        const newTripModal = document.getElementById('new-trip-modal');
        const unsavedModal = document.getElementById('unsaved-changes-modal');

        if (event.target === elementModal) {
            closeModal();
        }

        if (event.target === newTripModal) {
            // Don't close new trip modal by clicking outside
        }

        if (event.target === unsavedModal) {
            closeUnsavedModal();
        }
    }

    // Automatic Itinerary Summary Generation
    function generateItinerarySummary() {
        const tripTitle = document.getElementById('trip-title').value.trim() || 'Mi Viaje';
        const startDate = document.getElementById('start-date').value;
        const dayContainers = document.querySelectorAll('.day-card');

        let summary = `<strong>${tripTitle}</strong><br>`;

        if (startDate) {
            const startDateObj = new Date(startDate);
            const endDateObj = new Date(startDate);
            endDateObj.setDate(startDateObj.getDate() + dayContainers.length - 1);

            const formatDate = (date) => {
                return date.toLocaleDateString('es-ES', {
                    day: '2-digit',
                    month: '2-digit',
                    year: 'numeric'
                });
            };

            summary += `<strong>Duración:</strong> ${dayContainers.length} días (${formatDate(startDateObj)} - ${formatDate(endDateObj)})<br><br>`;
        }

        // Group items by day
        const itemsByDay = {};

        // Initialize days
        for (let i = 1; i <= dayContainers.length; i++) {
            itemsByDay[i] = [];
        }

        // Collect all timeline items and group by day
        dayContainers.forEach((dayCard, index) => {
            const dayNumber = index + 1;
            const timelineItems = dayCard.querySelectorAll('.timeline-item');

            timelineItems.forEach(item => {
                if (!item.classList.contains('summary')) {
                    const itemData = extractItemDataForDisplay(item);
                    if (itemData) {
                        itemsByDay[dayNumber].push(itemData);
                    }
                }
            });
        });

        // Generate day-by-day summary
        Object.keys(itemsByDay).forEach(dayNumber => {
            const dayItems = itemsByDay[dayNumber];
            if (dayItems.length > 0) {
                const dayDate = new Date(startDate);
                dayDate.setDate(dayDate.getDate() + parseInt(dayNumber) - 1);

                const formatDayDate = (date) => {
                    return dayDate.toLocaleDateString('es-ES', {
                        weekday: 'long',
                        day: '2-digit',
                        month: '2-digit',
                        year: 'numeric'
                    });
                };

                summary += `<strong>Día ${dayNumber} - ${formatDayDate(dayDate)}</strong><br>`;

                dayItems.forEach(item => {
                    let itemTitle = item.title || 'Sin título';

                    // Special formatting for different item types
                    if (item.type === 'flight') {
                        itemTitle = itemTitle;
                    } else if (item.type === 'hotel') {
                        itemTitle = itemTitle.replace(/\s*\(\d+\s*noche?s?\)/i, '').trim();
                    }

                    summary += `• ${itemTitle}<br>`;
                });

                summary += '<br>';
            }
        });

        // If no items found
        if (Object.values(itemsByDay).every(day => day.length === 0)) {
            summary += '<em>Sin elementos agregados aún</em>';
        }

        // Add total price if exists
        const totalElements = document.querySelectorAll('.timeline-item.total');
        if (totalElements.length > 0) {
            const totalElement = totalElements[0];
            const totalData = extractItemDataForDisplay(totalElement);
            if (totalData && totalData.total_amount && totalData.currency) {
                const price = parseFloat(totalData.total_amount);
                if (!isNaN(price)) {
                    const currencySymbols = {
                        'USD': '$',
                        'EUR': '€',
                        'COP': '$',
                        'MXN': '$'
                    };
                    const symbol = currencySymbols[totalData.currency] || totalData.currency;
                    const formattedPrice = `${symbol}${price.toLocaleString('es-ES', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2,
                        useGrouping: true
                    })}`;
                    summary += `<br><br><strong>💰 Valor Total del Viaje:</strong> ${formattedPrice} ${totalData.currency}`;
                }
            }
        }

        return summary;
    }

    function extractItemDataForDisplay(itemElement) {
        if (!itemElement) return null;

        const baseData = {
            type: itemElement.classList.contains('flight') ? 'flight' :
                  itemElement.classList.contains('hotel') ? 'hotel' :
                  itemElement.classList.contains('activity') ? 'activity' :
                  itemElement.classList.contains('transport') ? 'transport' :
                  itemElement.classList.contains('note') ? 'note' :
                  itemElement.classList.contains('total') ? 'total' : 'unknown'
        };

        switch (baseData.type) {
            case 'flight':
                return {
                    ...baseData,
                    title: itemElement.querySelector('.item-title')?.textContent || '',
                    airline: itemElement.querySelector('.item-title')?.textContent?.split(' ')[1] || '',
                    flight_number: itemElement.querySelector('.item-title')?.textContent?.split(' ')[2] || '',
                    departure_airport: itemElement.querySelector('.item-subtitle')?.textContent?.split(' → ')[0] || '',
                    arrival_airport: itemElement.querySelector('.item-subtitle')?.textContent?.split(' → ')[1] || ''
                };

            case 'hotel':
                return {
                    ...baseData,
                    title: itemElement.querySelector('.item-title')?.textContent || '',
                    hotel_name: itemElement.querySelector('.item-title')?.textContent || ''
                };

            case 'activity':
                return {
                    ...baseData,
                    title: itemElement.querySelector('.item-title')?.textContent || '',
                    activity_title: itemElement.querySelector('.item-title')?.textContent || '',
                    location: itemElement.querySelector('.item-subtitle')?.textContent || ''
                };

            case 'transport':
                return {
                    ...baseData,
                    title: itemElement.querySelector('.item-title')?.textContent || '',
                    transport_type: itemElement.querySelector('.item-title')?.textContent || '',
                    pickup_location: itemElement.querySelector('.item-subtitle')?.textContent?.split(' → ')[0] || '',
                    destination: itemElement.querySelector('.item-subtitle')?.textContent?.split(' → ')[1] || ''
                };

            case 'note':
                return {
                    ...baseData,
                    title: itemElement.querySelector('.item-title')?.textContent || '',
                    note_title: itemElement.querySelector('.item-title')?.textContent || '',
                    note_content: itemElement.querySelector('.item-subtitle')?.textContent || ''
                };

            case 'total':
                const titleText = itemElement.querySelector('.item-title')?.textContent || '';
                const subtitleText = itemElement.querySelector('.item-subtitle')?.textContent || '';

                // Extract amount and currency from title (handles different currency symbols)
                let totalAmount = '0.00';
                let currency = 'USD';

                // Try to extract amount and currency from title
                const amountMatch = titleText.match(/([€$S/])?(\d+(?:\.\d{2})?)\s*([A-Z]{3})?/);
                if (amountMatch) {
                    totalAmount = amountMatch[2];
                    if (amountMatch[3]) {
                        currency = amountMatch[3];
                    }
                }

                return {
                    ...baseData,
                    title: titleText,
                    total_amount: totalAmount,
                    currency: currency,
                    price_breakdown: subtitleText !== 'Precio total del viaje' ? subtitleText : ''
                };

            default:
                return {
                    ...baseData,
                    title: itemElement.querySelector('.item-title')?.textContent || 'Elemento'
                };
        }
    }

    function updateAllSummaries() {
        // Find all summary elements and update them
        const summaryElements = document.querySelectorAll('.timeline-item.summary');
        summaryElements.forEach(summaryElement => {
            updateSummaryElement(summaryElement);
        });

        // Find all total elements and update them
        const totalElements = document.querySelectorAll('.timeline-item.total');
        totalElements.forEach(totalElement => {
            updateTotalElement(totalElement);
        });
    }

    function updateSummaryElement(summaryElement) {
        if (summaryElement && summaryElement.classList.contains('summary')) {
            // Update title
            const tripTitle = document.getElementById('trip-title').value.trim() || 'Mi Viaje';
            const titleElement = summaryElement.querySelector('.item-title');
            if (titleElement) {
                titleElement.textContent = tripTitle;
            }

            // Update content
            const summaryContent = generateItinerarySummary();
            const descriptionElement = summaryElement.querySelector('.item-subtitle');

            if (descriptionElement) {
                descriptionElement.innerHTML = summaryContent;
            }
        }
    }

    function updateTotalElement(totalElement) {
        if (totalElement && totalElement.classList.contains('total')) {
            const itemData = extractItemDataForDisplay(totalElement);

            // If total has manual data, use it
            if (itemData && itemData.total_amount && itemData.currency) {
                const currencySymbols = {
                    'USD': '$',
                    'EUR': '€',
                    'CLP': '$',
                    'ARS': '$',
                    'PEN': 'S/',
                    'COP': '$',
                    'MXN': '$'
                };
                const symbol = currencySymbols[itemData.currency] || itemData.currency || '$';
                const amount = parseFloat(itemData.total_amount);

                // Update the total display
                const titleElement = totalElement.querySelector('.item-title');
                if (titleElement) {
                    titleElement.textContent = `${symbol}${amount.toFixed(2)} ${itemData.currency}`;
                }

                // Update subtitle with price breakdown if available
                const subtitleElement = totalElement.querySelector('.item-subtitle');
                if (subtitleElement && itemData.price_breakdown) {
                    subtitleElement.textContent = itemData.price_breakdown;
                }
                return;
            }

            // Otherwise, calculate total from all items automatically
            const allItems = document.querySelectorAll('.timeline-item:not(.summary):not(.total)');
            let totalAmount = 0;
            let currency = 'USD';

            allItems.forEach(item => {
                const priceText = item.querySelector('.item-title')?.textContent || '';
                const priceMatch = priceText.match(/\$?(\d+(?:\.\d{2})?)\s*([A-Z]{3})?/);
                if (priceMatch) {
                    totalAmount += parseFloat(priceMatch[1] || 0);
                    if (priceMatch[2]) {
                        currency = priceMatch[2];
                    }
                }
            });

            // Update the total display
            const titleElement = totalElement.querySelector('.item-title');
            if (titleElement) {
                titleElement.textContent = `$${totalAmount.toFixed(2)} ${currency}`;
            }
        }
    }

    // Click handlers for summary and total elements
    function handleSummaryClick() {
        // Check if there's already a summary
        const existingSummary = document.querySelector('.timeline-item.summary');

        if (existingSummary) {
            // If summary already exists, remove it
            existingSummary.remove();
            return;
        }

        // Create new summary element at the top
        const summaryElement = createSummaryElement();
        const daysContainer = document.getElementById('days-container');

        if (daysContainer) {
            daysContainer.insertBefore(summaryElement, daysContainer.firstChild);
        }

        // Update the summary content
        updateAllSummaries();
    }

    function handleTotalClick() {
        // Check if there's already a total element
        const existingTotal = document.querySelector('.timeline-item.total');

        if (existingTotal) {
            // If total already exists, remove it
            existingTotal.remove();
            return;
        }

        // Create new total element
        const totalElement = createTotalElement();
        const daysContainer = document.getElementById('days-container');

        if (daysContainer) {
            daysContainer.appendChild(totalElement);
        }
    }

    function createSummaryElement() {
        const tripTitle = document.getElementById('trip-title').value.trim() || 'Mi Viaje';
        const elementDiv = document.createElement('div');
        elementDiv.className = 'timeline-item summary';
        elementDiv.innerHTML = `
            <div class="item-header">
                <div class="item-icon summary-icon">
                    <i class="fas fa-list-check"></i>
                </div>
                <div class="item-info">
                    <div class="item-type">Resumen de Itinerario</div>
                    <div class="item-title">${tripTitle}</div>
                    <div class="item-subtitle">Resumen automático del viaje</div>
                </div>
                <div class="item-actions">
                    <button class="action-btn summary-update-btn" onclick="updateAllSummaries()" title="Actualizar resumen">
                        <i class="fas fa-sync-alt"></i>
                    </button>
                    <button class="action-btn btn-danger" onclick="deleteElement(this)" title="Eliminar">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        `;

        return elementDiv;
    }

    function createTotalElement() {
        const elementDiv = document.createElement('div');
        elementDiv.className = 'timeline-item total';
        elementDiv.innerHTML = `
            <div class="item-header">
                <div class="item-icon total-icon">
                    <i class="fas fa-dollar-sign"></i>
                </div>
                <div class="item-info">
                    <div class="item-type">Valor Total</div>
                    <div class="item-title">$0.00 USD</div>
                    <div class="item-subtitle">Precio total del viaje</div>
                </div>
                <div class="item-actions">
                    <button class="action-btn summary-update-btn" onclick="updateAllSummaries()" title="Actualizar total">
                        <i class="fas fa-sync-alt"></i>
                    </button>
                    <button class="action-btn btn-danger" onclick="deleteElement(this)" title="Eliminar">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        `;

        return elementDiv;
    }
</script>
@endpush
