@extends('layouts.supervisor')

@section('title', 'Gestion des Agents')

@section('content')
<div class="container">
    <h1 class="page-title">Gestion des Agents</h1>
    <p class="page-subtitle">Gérez les agents de votre centre</p>

    <!-- Filtres et recherche -->
    <div class="mb-4">
        <div class="row g-3">
            <div class="col-md-4">
                <label for="search_name" class="form-label">Rechercher par nom ou email</label>
                <div class="input-group">
                    <input type="text" id="search_name" class="form-control" placeholder="Entrez le nom ou email...">
                    <button type="button" id="search_button" class="btn btn-primary btn-sm rounded-pill">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <button class="btn-modern btn-primary-modern" data-bs-toggle="modal" data-bs-target="#addAgentModal">
                    <i class="fas fa-plus me-2"></i> Ajouter un Agent
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
                <div class="stats-number">{{ $agents->count() }}</div>
                <div class="stats-label">Total des agents</div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="stats-card success">
                <div class="stats-icon success">
                    <i class="fas fa-building"></i>
                </div>
                <div class="stats-number">1</div>
                <div class="stats-label">Centre</div>
            </div>
        </div>
    </div>

    <!-- Tableau des agents -->
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Nom</th>
                <th>Email</th>
                <th>Centre</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="agents-table">
            @foreach ($agents as $agent)
                <tr data-id="{{ $agent->id }}">
                    <td>{{ $agent->name }}</td>
                    <td>{{ $agent->email }}</td>
                    <td>{{ $center->name }}</td>
                    <td>
                        <button class="btn btn-primary btn-sm rounded-pill edit-agent" data-id="{{ $agent->id }}" data-name="{{ $agent->name }}" data-email="{{ $agent->email }}">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-danger btn-sm rounded-pill delete-agent" data-id="{{ $agent->id }}">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Modal pour ajouter/éditer un agent -->
    <div class="modal fade" id="addAgentModal" tabindex="-1" aria-labelledby="addAgentModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addAgentModalLabel">Ajouter un Agent</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="agentForm">
                        @csrf
                        <input type="hidden" id="agent_id" name="id">
                        <div class="mb-3">
                            <label for="name" class="form-label">Nom <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name" required>
                            <div id="name_error" class="text-danger mt-1"></div>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="email" name="email" required>
                            <div id="email_error" class="text-danger mt-1"></div>
                        </div>
                        <div class="mb-3" id="password_field">
                            <label for="password" class="form-label">Mot de passe <span class="text-danger">*</span></label>
                            <input type="password" class="form-control" id="password" name="password">
                            <div id="password_error" class="text-danger mt-1"></div>
                        </div>
                        <div class="mb-3" id="password_confirmation_field">
                            <label for="password_confirmation" class="form-label">Confirmer le mot de passe</label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                            <div id="confirmation_error" class="text-danger mt-1"></div>
                        </div>
                        <button type="submit" class="btn btn-primary rounded-pill">Enregistrer</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal pour supprimer un agent -->
    <div class="modal fade" id="deleteAgentModal" tabindex="-1" aria-labelledby="deleteAgentModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteAgentModalLabel">Supprimer un Agent</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Êtes-vous sûr de vouloir supprimer cet agent ? Cette action est irréversible.</p>
                    <form id="deleteAgentForm">
                        @csrf
                        @method('DELETE')
                        <input type="hidden" id="delete_agent_id" name="id">
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
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const addAgentModal = new bootstrap.Modal(document.getElementById('addAgentModal'));
    const deleteAgentModal = new bootstrap.Modal(document.getElementById('deleteAgentModal'));
    const successToast = new bootstrap.Toast(document.getElementById('successToast'));
    const errorToast = new bootstrap.Toast(document.getElementById('errorToast'));

    function showToast(type, message) {
        const toast = type === 'success' ? successToast : errorToast;
        toast._element.querySelector('.toast-body').textContent = message;
        toast.show();
    }

    // Gestion du formulaire d'ajout/modification d'agent
    document.getElementById('agentForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const agentId = document.getElementById('agent_id').value;
        const url = agentId ? `/supervisor/agents/${agentId}` : '/supervisor/agents';
        const method = agentId ? 'PUT' : 'POST';
        const data = {
            name: document.getElementById('name').value,
            email: document.getElementById('email').value,
            password: document.getElementById('password').value,
            password_confirmation: document.getElementById('password_confirmation').value,
            _token: document.querySelector('meta[name="csrf-token"]').content,
        };

        if (agentId) {
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
            const agent = response.data;
            const row = `
                <tr data-id="${agent.id}">
                    <td>${agent.name}</td>
                    <td>${agent.email}</td>
                    <td>{{ $center->name }}</td>
                    <td>
                        <button class="btn btn-primary btn-sm rounded-pill edit-agent" data-id="${agent.id}" data-name="${agent.name}" data-email="${agent.email}">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-danger btn-sm rounded-pill delete-agent" data-id="${agent.id}">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `;
            if (agentId) {
                document.querySelector(`tr[data-id="${agentId}"]`).outerHTML = row;
            } else {
                document.getElementById('agents-table').insertAdjacentHTML('beforeend', row);
            }
            showToast('success', 'Agent enregistré avec succès.');
            addAgentModal.hide();
            document.getElementById('agentForm').reset();
            document.getElementById('agent_id').value = '';
            document.getElementById('addAgentModalLabel').textContent = 'Ajouter un Agent';
            document.getElementById('password_field').style.display = 'block';
            document.getElementById('password_confirmation_field').style.display = 'block';
        })
        .catch(error => {
            const errors = error.response.data.errors || {};
            document.getElementById('name_error').textContent = errors.name ? errors.name[0] : '';
            document.getElementById('email_error').textContent = errors.email ? errors.email[0] : '';
            document.getElementById('password_error').textContent = errors.password ? errors.password[0] : '';
            document.getElementById('confirmation_error').textContent = errors.password_confirmation ? errors.password_confirmation[0] : '';
            showToast('error', error.response.data.message || 'Erreur lors de l\'enregistrement de l\'agent.');
        });
    });

    // Gestion des clics sur les boutons du tableau
    document.getElementById('agents-table').addEventListener('click', function(e) {
        if (e.target.closest('.edit-agent')) {
            const button = e.target.closest('.edit-agent');
            const id = button.dataset.id;
            const name = button.dataset.name;
            const email = button.dataset.email;

            document.getElementById('agent_id').value = id;
            document.getElementById('name').value = name;
            document.getElementById('email').value = email;
            document.getElementById('addAgentModalLabel').textContent = 'Modifier un Agent';
            document.getElementById('password_field').style.display = 'none';
            document.getElementById('password_confirmation_field').style.display = 'none';
            addAgentModal.show();
        }

        if (e.target.closest('.delete-agent')) {
            const button = e.target.closest('.delete-agent');
            const id = button.dataset.id;

            document.getElementById('delete_agent_id').value = id;
            deleteAgentModal.show();
        }
    });

    // Gestion de la suppression
    document.getElementById('deleteAgentForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const agentId = document.getElementById('delete_agent_id').value;

        axios({
            method: 'post',
            url: `/supervisor/agents/${agentId}`,
            data: {
                _method: 'DELETE',
                _token: document.querySelector('meta[name="csrf-token"]').content
            },
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => {
            document.querySelector(`tr[data-id="${agentId}"]`).remove();
            showToast('success', 'Agent supprimé avec succès.');
            deleteAgentModal.hide();
        })
        .catch(error => {
            showToast('error', error.response.data.message || 'Erreur lors de la suppression de l\'agent.');
        });
    });

    // Gestion de la recherche
    const searchName = document.getElementById('search_name');
    const searchButton = document.getElementById('search_button');

    function filterAgents() {
        const name = searchName.value.trim().toLowerCase();
        const rows = document.querySelectorAll('#agents-table tr');

        rows.forEach(row => {
            const nameText = row.children[0].textContent.toLowerCase();
            const emailText = row.children[1].textContent.toLowerCase();
            row.style.display = (nameText.includes(name) || emailText.includes(name)) ? '' : 'none';
        });
    }

    searchButton.addEventListener('click', filterAgents);
    searchName.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            filterAgents();
        }
    });
});
</script>
@endpush
@endsection
