@extends('layouts.dashboard')

@section('title', 'Gestion des Utilisateurs')

@section('content')
<div class="container">
    <h1 class="page-title">Gestion des utilisateurs</h1>
    <p class="page-subtitle">Gérez les utilisateurs du système d'état civil (superviseurs et agents)</p>

    <!-- Filtres et recherche -->
    <div class="mb-4">
        <div class="row g-3">
            <div class="col-md-4">
                <label for="role_filter" class="form-label">Filtrer par rôle</label>
                <select id="role_filter" class="form-control">
                    <option value="">Tous les rôles</option>
                    <option value="supervisor">Superviseur</option>
                    <option value="agent">Agent</option>
                </select>
            </div>
            <div class="col-md-4">
                <label for="search_name" class="form-label">Rechercher par nom</label>
                <input type="text" id="search_name" class="form-control" placeholder="Entrez le nom...">
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <button class="btn-modern btn-primary-modern" data-bs-toggle="modal" data-bs-target="#addUserModal">
                    <i class="fas fa-plus me-2"></i> Ajouter un Utilisateur
                </button>
            </div>
        </div>
    </div>

    <!-- Statistiques -->
    <div class="row g-4 mb-4">
        <div class="col-lg-3 col-md-6">
            <div class="stats-card">
                <div class="stats-icon primary">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stats-number">{{ $users->count() }}</div>
                <div class="stats-label">Total des utilisateurs</div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="stats-card success">
                <div class="stats-icon success">
                    <i class="fas fa-user-shield"></i>
                </div>
                <div class="stats-number">{{ $users->where('role', 'supervisor')->count() }}</div>
                <div class="stats-label">Superviseurs</div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="stats-card info">
                <div class="stats-icon info">
                    <i class="fas fa-user"></i>
                </div>
                <div class="stats-number">{{ $users->where('role', 'agent')->count() }}</div>
                <div class="stats-label">Agents</div>
            </div>
        </div>
    </div>

    <!-- Tableau des utilisateurs -->
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Nom</th>
                <th>Email</th>
                <th>Rôle</th>
                <th>Centre</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="users-table">
            @foreach ($users as $user)
                <tr data-id="{{ $user->id }}">
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->role }}</td>
                    <td>{{ $user->center ? $user->center->name : '-' }}</td>
                    <td>
                        <button class="btn btn-primary btn-sm rounded-pill edit-user"
                                data-id="{{ $user->id }}"
                                data-name="{{ $user->name }}"
                                data-email="{{ $user->email }}"
                                data-role="{{ $user->role }}"
                                data-center-id="{{ $user->center_id ?? '' }}">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-danger btn-sm rounded-pill delete-user" data-id="{{ $user->id }}">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Modal pour ajouter/éditer un utilisateur -->
    <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addUserModalLabel">Ajouter un Utilisateur</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="userForm">
                        @csrf
                        <input type="hidden" id="user_id" name="id">
                        <div class="mb-3">
                            <label for="user_name" class="form-label">Nom <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="user_name" name="name" required>
                            <div id="user_name_error" class="text-danger mt-1"></div>
                        </div>
                        <div class="mb-3">
                            <label for="user_email" class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="user_email" name="email" required>
                            <div id="user_email_error" class="text-danger mt-1"></div>
                        </div>
                        <div class="mb-3">
                            <label for="user_password" class="form-label">Mot de passe <span class="text-danger" id="password_required">*</span></label>
                            <input type="password" class="form-control" id="user_password" name="password">
                            <div id="user_password_error" class="text-danger mt-1"></div>
                            <small class="form-text text-muted">Requis pour la création, facultatif pour la modification.</small>
                        </div>
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Confirmer le mot de passe <span class="text-danger" id="password_confirmation_required">*</span></label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                            <div id="password_confirmation_error" class="text-danger mt-1"></div>
                        </div>
                        <div class="mb-3">
                            <label for="user_role" class="form-label">Rôle <span class="text-danger">*</span></label>
                            <select class="form-control" id="user_role" name="role" required>
                                <option value="">Sélectionner un rôle</option>
                                <option value="supervisor">Superviseur</option>
                                <option value="agent">Agent</option>
                            </select>
                            <div id="user_role_error" class="text-danger mt-1"></div>
                        </div>
                        <div class="mb-3">
                            <label for="center_id" class="form-label">Centre <span class="text-danger">*</span></label>
                            <select class="form-control" id="center_id" name="center_id" required>
                                <option value="">Sélectionner un centre</option>
                                @foreach ($centers as $center)
                                    <option value="{{ $center->id }}">{{ $center->name }}</option>
                                @endforeach
                            </select>
                            <div id="center_id_error" class="text-danger mt-1"></div>
                        </div>
                        <button type="submit" class="btn btn-primary rounded-pill">Enregistrer</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal pour supprimer un utilisateur -->
    <div class="modal fade" id="deleteUserModal" tabindex="-1" aria-labelledby="deleteUserModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteUserModalLabel">Supprimer un Utilisateur</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Êtes-vous sûr de vouloir supprimer cet utilisateur ? Cette action est irréversible.</p>
                    <form id="deleteUserForm">
                        @csrf
                        @method('DELETE')
                        <input type="hidden" id="delete_user_id" name="id">
                        <button type="submit" class="btn btn-danger rounded-pill">Supprimer</button>
                        <button type="button" class="btn btn-secondary rounded-pill" data-bs-dismiss="modal">Annuler</button>
                    </form>
                </div>
            </div>
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
    .btn-danger {
        background-color: #dc2626;
        border-color: #dc2626;
    }
    .btn-danger:hover {
        background-color: #b91c1c;
        border-color: #b91c1c;
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
    .stats-icon.info { background: rgba(59, 130, 246, 0.1); color: #3b82f6; }
    .stats-number { font-size: 1.75rem; font-weight: 700; color: #1f2937; }
    .stats-label { font-size: 0.875rem; color: #6b7280; }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const userModal = new bootstrap.Modal(document.getElementById('addUserModal'));
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteUserModal'));
    const successToast = new bootstrap.Toast(document.getElementById('successToast'));
    const errorToast = new bootstrap.Toast(document.getElementById('errorToast'));

    function showToast(type, message) {
        const toast = type === 'success' ? successToast : errorToast;
        toast._element.querySelector('.toast-body').textContent = message;
        toast.show();
    }

    // Gestion du formulaire d'ajout/modification
    document.getElementById('userForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const userId = document.getElementById('user_id').value;
        const url = userId ? `/admin/users/${userId}` : '/admin/users';
        const data = {
            name: document.getElementById('user_name').value,
            email: document.getElementById('user_email').value,
            password: document.getElementById('user_password').value,
            password_confirmation: document.getElementById('password_confirmation').value,
            role: document.getElementById('user_role').value,
            center_id: document.getElementById('center_id').value || null,
            _token: document.querySelector('meta[name="csrf-token"]').content,
        };

        if (userId) {
            data._method = 'PUT';
        }

        axios({
            method: 'post',
            url: url,
            data: data,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json'
            }
        })
        .then(response => {
            const user = response.data;
            const row = `
                <tr data-id="${user.id}">
                    <td>${user.name}</td>
                    <td>${user.email}</td>
                    <td>${user.role}</td>
                    <td>${user.center_name || '-'}</td>
                    <td>
                        <button class="btn btn-primary btn-sm rounded-pill edit-user"
                                data-id="${user.id}"
                                data-name="${user.name}"
                                data-email="${user.email}"
                                data-role="${user.role}"
                                data-center-id="${user.center_id || ''}">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-danger btn-sm rounded-pill delete-user" data-id="${user.id}">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `;
            if (userId) {
                document.querySelector(`tr[data-id="${userId}"]`).outerHTML = row;
            } else {
                document.getElementById('users-table').insertAdjacentHTML('beforeend', row);
            }
            showToast('success', response.data.message);
            userModal.hide();
            document.getElementById('userForm').reset();
            document.getElementById('user_id').value = '';
            document.getElementById('addUserModalLabel').textContent = 'Ajouter un Utilisateur';
            document.getElementById('password_required').style.display = 'inline';
            document.getElementById('password_confirmation_required').style.display = 'inline';
        })
        .catch(error => {
            console.error('Erreur:', error.response);
            const errors = error.response.data.errors || {};
            document.getElementById('user_name_error').textContent = errors.name ? errors.name[0] : '';
            document.getElementById('user_email_error').textContent = errors.email ? errors.email[0] : '';
            document.getElementById('user_password_error').textContent = errors.password ? errors.password[0] : '';
            document.getElementById('password_confirmation_error').textContent = errors.password_confirmation ? errors.password_confirmation[0] : '';
            document.getElementById('user_role_error').textContent = errors.role ? errors.role[0] : '';
            document.getElementById('center_id_error').textContent = errors.center_id ? errors.center_id[0] : '';
            showToast('error', error.response.data.message || 'Erreur lors de l\'enregistrement de l\'utilisateur.');
        });
    });

    // Gestion de l'édition
    document.getElementById('users-table').addEventListener('click', function(e) {
        if (e.target.closest('.edit-user')) {
            const button = e.target.closest('.edit-user');
            const id = button.dataset.id;
            const name = button.dataset.name;
            const email = button.dataset.email;
            const role = button.dataset.role;
            const centerId = button.dataset.centerId;

            document.getElementById('user_id').value = id;
            document.getElementById('user_name').value = name;
            document.getElementById('user_email').value = email;
            document.getElementById('user_role').value = role;
            document.getElementById('center_id').value = centerId || '';
            document.getElementById('addUserModalLabel').textContent = 'Modifier l\'Utilisateur';
            document.getElementById('user_password').value = '';
            document.getElementById('password_confirmation').value = '';
            document.getElementById('password_required').style.display = 'none';
            document.getElementById('password_confirmation_required').style.display = 'none';
            userModal.show();
        }

        if (e.target.closest('.delete-user')) {
            const button = e.target.closest('.delete-user');
            const id = button.dataset.id;

            document.getElementById('delete_user_id').value = id;
            deleteModal.show();
        }
    });

    // Gestion de la suppression
    document.getElementById('deleteUserForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const userId = document.getElementById('delete_user_id').value;
        const url = `/admin/users/${userId}`;

        axios({
            method: 'DELETE',
            url: url,
            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
        })
        .then(response => {
            document.querySelector(`tr[data-id="${response.data.id}"]`).remove();
            showToast('success', response.data.message);
            deleteModal.hide();
        })
        .catch(error => {
            console.error('Erreur:', error.response);
            showToast('error', error.response.data.message || 'Erreur lors de la suppression de l\'utilisateur.');
        });
    });

    // Filtre par rôle et recherche par nom
    const roleFilter = document.getElementById('role_filter');
    const searchName = document.getElementById('search_name');
    const tableBody = document.getElementById('users-table');

    function filterUsers() {
        const role = roleFilter.value;
        const name = searchName.value.toLowerCase();

        const rows = tableBody.querySelectorAll('tr');
        rows.forEach(row => {
            const rowRole = row.children[2].textContent;
            const rowName = row.children[0].textContent.toLowerCase();

            const roleMatch = !role || rowRole === role;
            const nameMatch = !name || rowName.includes(name);

            row.style.display = roleMatch && nameMatch ? '' : 'none';
        });
    }

    roleFilter.addEventListener('change', filterUsers);
    searchName.addEventListener('input', filterUsers);
});
</script>
@endpush
@endsection
