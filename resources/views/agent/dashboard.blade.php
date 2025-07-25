@extends('layouts.agent')

@section('title', 'Tableau de bord - Agent')

@section('content')
<div class="container">
    <h1 class="page-title">Tableau de bord Agent</h1>
    <p class="page-subtitle">Bienvenue, {{ auth()->user()->name }}</p>

    <!-- Statistiques -->
    <div class="row g-4 mb-4">
        <div class="col-lg-3 col-md-6">
            <div class="stats-card">
                <div class="stats-icon primary">
                    <i class="fas fa-building"></i>
                </div>
                <div class="stats-number">{{ $center ? 1 : 0 }}</div>
                <div class="stats-label">Centre associé</div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="stats-card success">
                <div class="stats-icon success">
                    <i class="fas fa-file-alt"></i>
                </div>
                <div class="stats-number">{{ $created_acts }}</div>
                <div class="stats-label">Actes créés</div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="stats-card warning">
                <div class="stats-icon warning">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stats-number">{{ $validated_acts }}</div>
                <div class="stats-label">Actes validés</div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="stats-card danger">
                <div class="stats-icon danger">
                    <i class="fas fa-exclamation-circle"></i>
                </div>
                <div class="stats-number">{{ $pending_acts }}</div>
                <div class="stats-label">Actes en attente</div>
            </div>
        </div>
    </div>

    <!-- Boutons d'action -->
    <div class="action-buttons">
        <a href="{{ route('agent.acts.births') }}" class="btn-modern btn-primary-modern">
            <i class="fas fa-file-alt me-2"></i> Gérer les actes de naissance
        </a>
        <a href="{{ route('agent.acts.marriages') }}" class="btn-modern btn-primary-modern">
            <i class="fas fa-heart me-2"></i> Gérer les actes de mariage
        </a>
        <a href="{{ route('agent.acts.deaths') }}" class="btn-modern btn-primary-modern">
            <i class="fas fa-cross me-2"></i> Gérer les actes de décès
        </a>
        <a href="{{ route('agent.acts.divorces') }}" class="btn-modern btn-primary-modern">
            <i class="fas fa-gavel me-2"></i> Gérer les actes de divorce
        </a>
    </div>

    <!-- Informations du centre -->
    <div class="card">
        <div class="card-header">Votre centre</div>
        <div class="card-body">
            @if ($center)
                <p><strong>Nom :</strong> {{ $center->name }}</p>
                <p><strong>Département :</strong> {{ $center->department->name }}</p>
                <p><strong>Région :</strong> {{ $center->department->region->name }}</p>
            @else
                <p>Aucun centre associé.</p>
            @endif
        </div>
    </div>

    <!-- Toast pour les notifications -->
    <div class="toast-container position-fixed bottom-0 end-0 p-3">
        <div id="successToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header bg-success text-white">
                <strong class="me-auto">Succès</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body"></div>
        </div>
        <div id="errorToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header bg-danger text-white">
                <strong class="me-auto">Erreur</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body"></div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .form-control, .btn {
        border: 1px solid #ced4da;
        transition: all 0.3s ease;
    }
    .form-control:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 0.2rem rgba(37, 99, 235, 0.25);
    }
    .btn-primary {
        background-color: #2563eb;
        border-color: #2563eb;
    }
    .btn-primary:hover {
        background-color: #1d4ed8;
        border-color: #1d4ed8;
    }
    .rounded-pill {
        border-radius: 50rem;
    }
    .toast-container {
        z-index: 1055;
    }
    .stats-card {
        background: white;
        border-radius: 0.75rem;
        padding: 1.5rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    .stats-icon {
        width: 48px;
        height: 48px;
        border-radius: 0.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }
    .stats-icon.primary { background: rgba(37, 99, 235, 0.1); color: #2563eb; }
    .stats-icon.success { background: rgba(16, 185, 129, 0.1); color: #10b981; }
    .stats-icon.warning { background: rgba(245, 158, 11, 0.1); color: #f59e0b; }
    .stats-icon.danger { background: rgba(239, 68, 68, 0.1); color: #ef4444; }
    .stats-number { font-size: 1.75rem; font-weight: 700; color: #1f2937; }
    .stats-label { font-size: 0.875rem; color: #6b7280; }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const successToast = new bootstrap.Toast(document.getElementById('successToast'));
    const errorToast = new bootstrap.Toast(document.getElementById('errorToast'));

    function showToast(type, message) {
        const toast = type === 'success' ? successToast : errorToast;
        toast._element.querySelector('.toast-body').textContent = message;
        toast.show();
    }

    @if (session('success'))
        showToast('success', '{{ session('success') }}');
    @endif
    @if (session('error'))
        showToast('error', '{{ session('error') }}');
    @endif
});
</script>
@endpush
@endsection
