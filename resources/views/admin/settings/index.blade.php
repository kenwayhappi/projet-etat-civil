@extends('layouts.dashboard')

@section('title', 'Paramètres')

@section('content')
<div class="container">
    <h1 class="page-title">Paramètres</h1>
    <p class="page-subtitle">Gérez vos informations personnelles</p>

    <div class="stats-card">
        <form action="{{ route('admin.settings.update') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label for="name" class="form-label">Nom</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                @error('name')
                    <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                @error('email')
                    <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label for="current_password" class="form-label">Ancien mot de passe</label>
                <input type="password" name="current_password" id="current_password" class="form-control" required>
                @error('current_password')
                    <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label for="password" class="form-label">Nouveau mot de passe (facultatif)</label>
                <input type="password" name="password" id="password" class="form-control">
                @error('password')
                    <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label for="password_confirmation" class="form-label">Confirmer le nouveau mot de passe</label>
                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
            </div>

            <div class="action-buttons">
                <button type="submit" class="btn-modern btn-primary-modern">
                    <i class="fas fa-save"></i>
                    Enregistrer
                </button>
                <a href="{{ route('admin.dashboard') }}" class="btn-modern btn-secondary-modern">
                    <i class="fas fa-arrow-left"></i>
                    Annuler
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
