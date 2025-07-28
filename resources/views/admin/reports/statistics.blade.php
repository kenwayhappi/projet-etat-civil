@extends('layouts.dashboard')

@section('title', 'Statistiques')

@section('content')
    <div class="container">
        <h1 class="page-title">Statistiques</h1>
        <p class="page-subtitle">Vue d'ensemble des actes enregistrés</p>

        <div class="row g-4">
            <div class="col-md-3">
                <div class="stats-card primary">
                    <div class="stats-icon primary"><i class="fas fa-baby"></i></div>
                    <div class="stats-number">{{ $stats['total_births'] }}</div>
                    <div class="stats-label">Naissances</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card success">
                    <div class="stats-icon success"><i class="fas fa-heart"></i></div>
                    <div class="stats-number">{{ $stats['total_marriages'] }}</div>
                    <div class="stats-label">Mariages</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card danger">
                    <div class="stats-icon danger"><i class="fas fa-cross"></i></div>
                    <div class="stats-number">{{ $stats['total_deaths'] }}</div>
                    <div class="stats-label">Décès</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card warning">
                    <div class="stats-icon warning"><i class="fas fa-user-slash"></i></div>
                    <div class="stats-number">{{ $stats['total_divorces'] }}</div>
                    <div class="stats-label">Divorces</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card pending">
                    <div class="stats-icon warning"><i class="fas fa-hourglass"></i></div>
                    <div class="stats-number">{{ $stats['pending'] }}</div>
                    <div class="stats-label">En attente</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card success">
                    <div class="stats-icon success"><i class="fas fa-check"></i></div>
                    <div class="stats-number">{{ $stats['validated'] }}</div>
                    <div class="stats-label">Validés</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card danger">
                    <div class="stats-icon danger"><i class="fas fa-times"></i></div>
                    <div class="stats-number">{{ $stats['rejected'] }}</div>
                    <div class="stats-label">Rejetés</div>
                </div>
            </div>
        </div>
    </div>
@endsection
