@extends('layouts.dashboard')

@section('title', 'Tableau de Bord')

@section('content')
<div class="page-title">Tableau de bord administrateur</div>
<p class="page-subtitle">Vue d'ensemble de votre système d'état civil</p>

<div class="row g-4">
    <div class="col-lg-3 col-md-6">
        <div class="stats-card">
            <div class="stats-icon primary">
                <i class="fas fa-building"></i>
            </div>
            <div class="stats-number">{{ $centersCount }}</div>
            <div class="stats-label">Total des centres</div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="stats-card success">
            <div class="stats-icon success">
                <i class="fas fa-map-marker-alt"></i>
            </div>
            <div class="stats-number">{{ $regionsCount }}</div>
            <div class="stats-label">Régions</div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="stats-card warning">
            <div class="stats-icon warning">
                <i class="fas fa-map"></i>
            </div>
            <div class="stats-number">{{ $departmentsCount }}</div>
            <div class="stats-label">Départements</div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="stats-card danger">
            <div class="stats-icon danger">
                <i class="fas fa-users"></i>
            </div>
            <div class="stats-number">{{ $usersCount }}</div>
            <div class="stats-label">Utilisateurs</div>
        </div>
    </div>
</div>

<div class="action-buttons">
    <a href="{{ route('admin.centers.index') }}" class="btn-modern btn-primary-modern">
        <i class="fas fa-building"></i>
        Gérer les centres
    </a>
    <a href="{{ route('admin.users.index') }}" class="btn-modern btn-secondary-modern">
        <i class="fas fa-users"></i>
        Gérer les utilisateurs
    </a>
</div>
@endsection
