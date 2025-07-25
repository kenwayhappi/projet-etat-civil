@extends('layouts.agent')

@section('title', 'Paramètres du Compte')

@section('content')
<div class="container">
    <h1 class="page-title">Paramètres du Compte</h1>
    <p class="page-subtitle">Modifiez vos informations personnelles</p>

    <!-- Formulaire de modification des paramètres -->
    <div class="card shadow-sm">
        <div class="card-body">
            <form id="settingsForm" action="{{ route('agent.settings.update') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">Nom <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                    <div id="name_error" class="text-danger mt-1"></div>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                    <div id="email_error" class="text-danger mt-1"></div>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Nouveau mot de passe (facultatif)</label>
                    <input type="password" class="form-control" id="password" name="password">
                    <div id="password_error" class="text-danger mt-1"></div>
                </div>
                <div class="mb-3">
                    <label for="password_confirmation" class="form-label">Confirmer le mot de passe</label>
                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                    <div id="password_confirmation_error" class="text-danger mt-1"></div>
                </div>
                <button type="submit" class="btn btn-primary rounded-pill">Enregistrer</button>
            </form>
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
        <div id="errorToast" class="toast" role="alert" aria-live="assertive">
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
    .card {
        border-radius: 0.75rem;
    }
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

    // Gestion du formulaire de paramètres
    document.getElementById('settingsForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const form = this;
        const submitButton = form.querySelector('button[type="submit"]');
        submitButton.disabled = true;
        submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Enregistrement...';

        const formData = new FormData(form);
        console.log('Données envoyées pour mise à jour:', Object.fromEntries(formData)); // Débogage

        axios.post(form.action, formData, {
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => {
            console.log('Réponse mise à jour:', response.data); // Débogage
            showToast('success', response.data.message);
            submitButton.disabled = false;
            submitButton.innerHTML = 'Enregistrer';
            // Mettre à jour les champs du formulaire avec les nouvelles valeurs
            document.getElementById('name').value = response.data.user.name;
            document.getElementById('email').value = response.data.user.email;
            document.getElementById('password').value = '';
            document.getElementById('password_confirmation').value = '';
        })
        .catch(error => {
            console.log('Erreur mise à jour:', error.response?.data); // Débogage
            const errors = error.response?.data?.errors || {};
            document.getElementById('name_error').textContent = errors.name ? errors.name[0] : '';
            document.getElementById('email_error').textContent = errors.email ? errors.email[0] : '';
            document.getElementById('password_error').textContent = errors.password ? errors.password[0] : '';
            document.getElementById('password_confirmation_error').textContent = errors.password_confirmation ? errors.password_confirmation[0] : '';
            showToast('error', error.response?.data?.message || 'Erreur lors de la mise à jour des paramètres.');
            submitButton.disabled = false;
            submitButton.innerHTML = 'Enregistrer';
        });
    });
});
</script>
@endpush
@endsection
