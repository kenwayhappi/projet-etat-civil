@extends('layouts.agent')

@section('title', 'Actes de Décès')

@section('content')
<div class="container">
    <h1 class="page-title">Actes de Décès</h1>
    <p class="page-subtitle">Gérez les actes de décès de votre centre</p>

    <!-- Bouton pour ajouter un acte -->
    <div class="mb-4">
        <button class="btn-modern btn-primary-modern" data-bs-toggle="modal" data-bs-target="#addDeathModal">
            <i class="fas fa-plus me-2"></i> Ajouter un Acte de Décès
        </button>
    </div>

    <!-- Tableau des actes -->
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Nom du Défunt</th>
                <th>Date de Décès</th>
                <th>Lieu de Décès</th>
                <th>Statut</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="acts-table">
            @foreach ($acts as $act)
                <tr data-id="{{ $act->id }}">
                    <td>{{ $act->details['deceased_name'] ?? '-' }}</td>
                    <td>{{ isset($act->details['death_date']) ? \Carbon\Carbon::parse($act->details['death_date'])->format('d/m/Y') : '-' }}</td>
                    <td>{{ $act->details['death_place'] ?? '-' }}</td>
                    <td>
                        <span class="badge bg-{{ $act->status === 'validated' ? 'success' : ($act->status === 'pending' ? 'warning' : 'danger') }}">
                            {{ ucfirst($act->status) }}
                        </span>
                    </td>
                    <td>
                        @if ($act->status === 'pending')
                            <button class="btn btn-primary btn-sm rounded-pill edit-act" data-id="{{ $act->id }}" data-details='@json($act->details)'>
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-danger btn-sm rounded-pill delete-act" data-id="{{ $act->id }}">
                                <i class="fas fa-trash"></i>
                            </button>
                        @else
                            <span>-</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Modal pour ajouter un acte -->
    <div class="modal fade" id="addDeathModal" tabindex="-1" aria-labelledby="addDeathModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addDeathModalLabel">Ajouter un Acte de Décès</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="deathForm" action="{{ route('agent.acts.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="type" value="death">
                        <div class="mb-3">
                            <label for="deceased_name" class="form-label">Nom du Défunt <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="deceased_name" name="details[deceased_name]" required>
                            <div id="deceased_name_error" class="text-danger mt-1"></div>
                        </div>
                        <div class="mb-3">
                            <label for="death_date" class="form-label">Date de Décès <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="death_date" name="details[death_date]" required>
                            <div id="death_date_error" class="text-danger mt-1"></div>
                        </div>
                        <div class="mb-3">
                            <label for="death_place" class="form-label">Lieu de Décès <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="death_place" name="details[death_place]" required>
                            <div id="death_place_error" class="text-danger mt-1"></div>
                        </div>
                        <button type="submit" class="btn btn-primary rounded-pill">Enregistrer</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal pour modifier un acte -->
    <div class="modal fade" id="editDeathModal" tabindex="-1" aria-labelledby="editDeathModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editDeathModalLabel">Modifier un Acte de Décès</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editDeathForm" method="POST">
                        @csrf
                        @method('POST')
                        <input type="hidden" id="edit_act_id" name="id">
                        <input type="hidden" name="type" value="death">
                        <div class="mb-3">
                            <label for="edit_deceased_name" class="form-label">Nom du Défunt <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="edit_deceased_name" name="details[deceased_name]" required>
                            <div id="edit_deceased_name_error" class="text-danger mt-1"></div>
                        </div>
                        <div class="mb-3">
                            <label for="edit_death_date" class="form-label">Date de Décès <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="edit_death_date" name="details[death_date]" required>
                            <div id="edit_death_date_error" class="text-danger mt-1"></div>
                        </div>
                        <div class="mb-3">
                            <label for="edit_death_place" class="form-label">Lieu de Décès <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="edit_death_place" name="details[death_place]" required>
                            <div id="edit_death_place_error" class="text-danger mt-1"></div>
                        </div>
                        <button type="submit" class="btn btn-primary rounded-pill">Enregistrer</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal pour supprimer un acte -->
    <div class="modal fade" id="deleteActModal" tabindex="-1" aria-labelledby="deleteActModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteActModalLabel">Supprimer un Acte</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Êtes-vous sûr de vouloir supprimer cet acte ? Cette action est irréversible.</p>
                    <form id="deleteActForm" method="POST">
                        @csrf
                        @method('DELETE')
                        <input type="hidden" id="delete_act_id" name="id">
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
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const addActModal = new bootstrap.Modal(document.getElementById('addDeathModal'));
    const editActModal = new bootstrap.Modal(document.getElementById('editDeathModal'));
    const deleteActModal = new bootstrap.Modal(document.getElementById('deleteActModal'));
    const successToast = new bootstrap.Toast(document.getElementById('successToast'));
    const errorToast = new bootstrap.Toast(document.getElementById('errorToast'));

    function showToast(type, message) {
        const toast = type === 'success' ? successToast : errorToast;
        toast._element.querySelector('.toast-body').textContent = message;
        toast.show();
    }

    // Gestion du formulaire d'ajout
    document.getElementById('deathForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const form = this;
        const submitButton = form.querySelector('button[type="submit"]');
        submitButton.disabled = true;
        submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Enregistrement...';

        // Effacer les messages d'erreur précédents
        document.getElementById('deceased_name_error').textContent = '';
        document.getElementById('death_date_error').textContent = '';
        document.getElementById('death_place_error').textContent = '';

        const formData = new FormData(form);
        console.log('Données envoyées pour création:', Object.fromEntries(formData));

        axios.post(form.action, formData, {
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => {
            console.log('Réponse création:', response);
            try {
                if (response.status === 201) {
                    const act = response.data;
                    if (!act || !act.details) {
                        throw new Error('Données de l\'acte invalides dans la réponse');
                    }

                    // Vérifier et formater la date
                    let formattedDate = '-';
                    if (act.details.death_date && typeof moment === 'function') {
                        const parsedDate = moment(act.details.death_date);
                        if (!parsedDate.isValid()) {
                            console.warn('Date de décès invalide:', act.details.death_date);
                        } else {
                            formattedDate = parsedDate.format('DD/MM/YYYY');
                        }
                    }

                    const row = `
                        <tr data-id="${act.id}">
                            <td>${act.details.deceased_name || '-'}</td>
                            <td>${formattedDate}</td>
                            <td>${act.details.death_place || '-'}</td>
                            <td><span class="badge bg-${act.status === 'validated' ? 'success' : (act.status === 'pending' ? 'warning' : 'danger')}">${act.status}</span></td>
                            <td>
                                ${act.status === 'pending' ? `
                                <button class="btn btn-primary btn-sm rounded-pill edit-act" data-id="${act.id}" data-details='${JSON.stringify(act.details)}'>
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-danger btn-sm rounded-pill delete-act" data-id="${act.id}">
                                    <i class="fas fa-trash"></i>
                                </button>
                                ` : '<span>-</span>'}
                            </td>
                        </tr>`;
                    document.getElementById('acts-table').insertAdjacentHTML('beforeend', row);
                    showToast('success', 'Acte créé avec succès.');
                    form.reset();
                    addActModal.hide();
                    submitButton.disabled = false;
                    submitButton.innerHTML = 'Enregistrer';
                } else {
                    throw new Error('Réponse inattendue du serveur: statut ' + response.status);
                }
            } catch (err) {
                console.error('Erreur dans le bloc .then:', err);
                showToast('error', 'Erreur côté client lors de la mise à jour de l\'interface.');
                submitButton.disabled = false;
                submitButton.innerHTML = 'Enregistrer';
            }
        })
        .catch(error => {
            console.error('Erreur lors de la requête AJAX:', error.response?.data || error.message);
            const errors = error.response?.data?.errors || {};
            document.getElementById('deceased_name_error').textContent = errors['details.deceased_name'] ? errors['details.deceased_name'][0] : '';
            document.getElementById('death_date_error').textContent = errors['details.death_date'] ? errors['details.death_date'][0] : '';
            document.getElementById('death_place_error').textContent = errors['details.death_place'] ? errors['details.death_place'][0] : '';
            showToast('error', error.response?.data?.message || 'Erreur lors de la création de l\'acte.');
            submitButton.disabled = false;
            submitButton.innerHTML = 'Enregistrer';
        });
    });

    // Gestion des clics sur les boutons du tableau
    document.getElementById('acts-table').addEventListener('click', function(e) {
        if (e.target.closest('.edit-act')) {
            const button = e.target.closest('.edit-act');
            const id = button.dataset.id;
            const details = JSON.parse(button.dataset.details);
            console.log('Détails pour édition:', details);

            document.getElementById('edit_act_id').value = id;
            document.getElementById('edit_deceased_name').value = details.deceased_name || '';
            document.getElementById('edit_death_date').value = details.death_date || '';
            document.getElementById('edit_death_place').value = details.death_place || '';

            // Effacer les messages d'erreur précédents
            document.getElementById('edit_deceased_name_error').textContent = '';
            document.getElementById('edit_death_date_error').textContent = '';
            document.getElementById('edit_death_place_error').textContent = '';

            editActModal.show();
        }

        if (e.target.closest('.delete-act')) {
            const button = e.target.closest('.delete-act');
            const id = button.dataset.id;
            console.log('ID pour suppression:', id);

            document.getElementById('delete_act_id').value = id;
            deleteActModal.show();
        }
    });

    // Gestion du formulaire de modification
    document.getElementById('editDeathForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const form = this;
        const id = document.getElementById('edit_act_id').value;
        const submitButton = form.querySelector('button[type="submit"]');
        submitButton.disabled = true;
        submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Enregistrement...';

        // Effacer les messages d'erreur précédents
        document.getElementById('edit_deceased_name_error').textContent = '';
        document.getElementById('edit_death_date_error').textContent = '';
        document.getElementById('edit_death_place_error').textContent = '';

        const formData = new FormData(form);
        console.log('Données envoyées pour modification:', Object.fromEntries(formData));

        axios.post(`/agent/acts/${id}`, formData, {
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => {
            console.log('Réponse modification:', response);
            try {
                const act = response.data;
                if (!act || !act.details) {
                    throw new Error('Données de l\'acte invalides dans la réponse');
                }

                const row = document.querySelector(`tr[data-id="${id}"]`);
                if (!row) {
                    throw new Error(`Ligne avec data-id="${id}" non trouvée`);
                }

                // Vérifier et formater la date
                let formattedDate = '-';
                if (act.details.death_date && typeof moment === 'function') {
                    const parsedDate = moment(act.details.death_date);
                    if (!parsedDate.isValid()) {
                        console.warn('Date de décès invalide:', act.details.death_date);
                    } else {
                        formattedDate = parsedDate.format('DD/MM/YYYY');
                    }
                }

                row.innerHTML = `
                    <td>${act.details.deceased_name || '-'}</td>
                    <td>${formattedDate}</td>
                    <td>${act.details.death_place || '-'}</td>
                    <td><span class="badge bg-${act.status === 'validated' ? 'success' : (act.status === 'pending' ? 'warning' : 'danger')}">${act.status}</span></td>
                    <td>
                        ${act.status === 'pending' ? `
                        <button class="btn btn-primary btn-sm rounded-pill edit-act" data-id="${act.id}" data-details='${JSON.stringify(act.details)}'>
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-danger btn-sm rounded-pill delete-act" data-id="${act.id}">
                            <i class="fas fa-trash"></i>
                        </button>
                        ` : '<span>-</span>'}
                    </td>
                `;
                showToast('success', 'Acte modifié avec succès.');
                editActModal.hide();
                submitButton.disabled = false;
                submitButton.innerHTML = 'Enregistrer';
            } catch (err) {
                console.error('Erreur dans le bloc .then:', err);
                showToast('error', 'Erreur côté client lors de la mise à jour de l\'interface.');
                submitButton.disabled = false;
                submitButton.innerHTML = 'Enregistrer';
            }
        })
        .catch(error => {
            console.error('Erreur lors de la requête AJAX:', error.response?.data || error.message);
            const errors = error.response?.data?.errors || {};
            document.getElementById('edit_deceased_name_error').textContent = errors['details.deceased_name'] ? errors['details.deceased_name'][0] : '';
            document.getElementById('edit_death_date_error').textContent = errors['details.death_date'] ? errors['details.death_date'][0] : '';
            document.getElementById('edit_death_place_error').textContent = errors['details.death_place'] ? errors['details.death_place'][0] : '';
            showToast('error', error.response?.data?.message || 'Erreur lors de la modification de l\'acte.');
            submitButton.disabled = false;
            submitButton.innerHTML = 'Enregistrer';
        });
    });

    // Gestion de la suppression
    document.getElementById('deleteActForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const id = document.getElementById('delete_act_id').value;
        const submitButton = this.querySelector('button[type="submit"]');
        submitButton.disabled = true;
        submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Suppression...';

        axios({
            method: 'delete',
            url: `/agent/acts/${id}`,
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => {
            console.log('Réponse suppression:', response.data);
            document.querySelector(`tr[data-id="${id}"]`).remove();
            showToast('success', 'Acte supprimé avec succès.');
            deleteActModal.hide();
            submitButton.disabled = false;
            submitButton.innerHTML = 'Supprimer';
        })
        .catch(error => {
            console.error('Erreur suppression:', error.response?.data);
            showToast('error', error.response?.data?.message || 'Erreur lors de la suppression de l\'acte.');
            submitButton.disabled = false;
            submitButton.innerHTML = 'Supprimer';
        });
    });
});
</script>
@endpush
@endsection
