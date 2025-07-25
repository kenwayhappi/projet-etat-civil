@extends('layouts.dashboard')

@section('title', 'Centres d\'État Civil')

@section('content')
<div class="container">
    <h1 class="page-title">Centres d'état civil</h1>
    <p class="page-subtitle">Gérez les centres d'état civil de votre système</p>

    <!-- Filtres et recherche -->
    <div class="mb-4">
        <div class="row g-3">
            <div class="col-md-3">
                <label for="region_filter" class="form-label">Filtrer par région</label>
                <select id="region_filter" class="form-control">
                    <option value="">Toutes les régions</option>
                    @foreach ($regions as $region)
                        <option value="{{ $region->id }}">{{ $region->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label for="department_filter" class="form-label">Filtrer par département</label>
                <select id="department_filter" class="form-control">
                    <option value="">Tous les départements</option>
                    @foreach ($departments as $department)
                        <option value="{{ $department->id }}" data-region-id="{{ $department->region_id }}">{{ $department->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label for="search_name" class="form-label">Rechercher par nom</label>
                <div class="input-group">
                    <input type="text" id="search_name" class="form-control" placeholder="Entrez le nom du centre...">
                    <button type="button" id="search_button" class="btn btn-primary btn-sm rounded-pill">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
            <div class="col-md-3 d-flex align-items-end">
                <button class="btn-modern btn-primary-modern" data-bs-toggle="modal" data-bs-target="#addCenterModal">
                    <i class="fas fa-plus me-2"></i> Ajouter un Centre
                </button>
            </div>
        </div>
    </div>

    <!-- Statistiques -->
    <div class="row g-4 mb-4">
        <div class="col-lg-3 col-md-6">
            <div class="stats-card">
                <div class="stats-icon primary">
                    <i class="fas fa-building"></i>
                </div>
                <div class="stats-number">{{ $centers->count() }}</div>
                <div class="stats-label">Total des centres</div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="stats-card success">
                <div class="stats-icon success">
                    <i class="fas fa-map-marker-alt"></i>
                </div>
                <div class="stats-number">{{ $regions->count() }}</div>
                <div class="stats-label">Régions</div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="stats-card warning">
                <div class="stats-icon warning">
                    <i class="fas fa-map"></i>
                </div>
                <div class="stats-number">{{ $departments->count() }}</div>
                <div class="stats-label">Départements</div>
            </div>
        </div>
    </div>

    <!-- Tableau des centres -->
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Nom</th>
                <th>Département</th>
                <th>Région</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="centers-table">
            @foreach ($centers as $center)
                <tr data-id="{{ $center->id }}">
                    <td>{{ $center->name }}</td>
                    <td>{{ $center->department->name }}</td>
                    <td>{{ $center->department->region->name }}</td>
                    <td>
                        <button class="btn btn-primary btn-sm rounded-pill edit-center" data-id="{{ $center->id }}" data-name="{{ $center->name }}" data-department-id="{{ $center->department_id }}">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-info btn-sm rounded-pill detail-center" data-id="{{ $center->id }}">
                            <i class="fas fa-info-circle"></i>
                        </button>
                        <button class="btn btn-danger btn-sm rounded-pill delete-center" data-id="{{ $center->id }}">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Modal pour ajouter/éditer un centre -->
    <div class="modal fade" id="addCenterModal" tabindex="-1" aria-labelledby="addCenterModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addCenterModalLabel">Ajouter un Centre</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="centerForm">
                        @csrf
                        <input type="hidden" id="center_id" name="id">
                        <div class="mb-3">
                            <label for="center_name" class="form-label">Nom du Centre <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="center_name" name="name" required>
                            <div id="center_name_error" class="text-danger mt-1"></div>
                        </div>
                        <div class="mb-3">
                            <label for="department_id" class="form-label">Département <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <select class="form-control" id="department_id" name="department_id" required>
                                    <option value="">Sélectionner un département</option>
                                    @foreach ($departments as $department)
                                        <option value="{{ $department->id }}">{{ $department->name }} ({{ $department->region->name }})</option>
                                    @endforeach
                                </select>
                                <button type="button" class="btn btn-primary btn-sm rounded-pill" data-bs-toggle="modal" data-bs-target="#addDepartmentModal">
                                    <i class="fas fa-plus me-1"></i> Ajouter
                                </button>
                            </div>
                            <div id="department_id_error" class="text-danger mt-1"></div>
                        </div>
                        <button type="submit" class="btn btn-primary rounded-pill">Enregistrer</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal pour ajouter/éditer une région -->
    <div class="modal fade" id="addRegionModal" tabindex="-1" aria-labelledby="addRegionModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addRegionModalLabel">Ajouter une Région</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="regionForm">
                        @csrf
                        <input type="hidden" id="region_id" name="id">
                        <div class="mb-3">
                            <label for="region_name" class="form-label">Nom de la Région <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="region_name" name="name" required>
                            <div id="region_name_error" class="text-danger mt-1"></div>
                        </div>
                        <button type="submit" class="btn btn-primary rounded-pill">Enregistrer</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal pour ajouter/éditer un département -->
    <div class="modal fade" id="addDepartmentModal" tabindex="-1" aria-labelledby="addDepartmentModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addDepartmentModalLabel">Ajouter un Département</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="departmentForm">
                        @csrf
                        <input type="hidden" id="department_id" name="id">
                        <div class="mb-3">
                            <label for="department_name" class="form-label">Nom du Département <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="department_name" name="name" required>
                            <div id="department_name_error" class="text-danger mt-1"></div>
                        </div>
                        <div class="mb-3">
                            <label for="region_id" class="form-label">Région <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <select class="form-control" id="region_id" name="region_id" required>
                                    <option value="">Sélectionner une région</option>
                                    @foreach ($regions as $region)
                                        <option value="{{ $region->id }}">{{ $region->name }}</option>
                                    @endforeach
                                </select>
                                <button type="button" class="btn btn-primary btn-sm rounded-pill" data-bs-toggle="modal" data-bs-target="#addRegionModal">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                            <div id="region_id_error" class="text-danger mt-1"></div>
                        </div>
                        <button type="submit" class="btn btn-primary rounded-pill">Enregistrer</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal pour les détails du centre -->
    <div class="modal fade" id="centerDetailsModal" tabindex="-1" aria-labelledby="centerDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="centerDetailsModalLabel">Détails du Centre</h5>
                    <button type="button" class="btn-close" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p><strong>Nom du centre :</strong> <span id="detail_center_name"></span></p>
                    <p><strong>Département :</strong> <span id="detail_department"></span></p>
                    <p><strong>Région :</strong> <span id="detail_region"></span></p>
                    <p><strong>Nombre de cadres :</strong> <span id="detail_supervisor_count"></span></p>
                    <p><strong>Cadres :</strong></p>
                    <ul id="detail_supervisors"></ul>
                    <p><strong>Nombre d'agents :</strong> <span id="detail_agent"></span></p>
                    <p><strong>Agents :</strong></p>
                    <ul id="detail_agents"></ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary rounded-pill" data-bs-dismiss="modal">Fermer</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal pour supprimer un centre -->
    <div class="modal fade" id="deleteCenter" tabindex="-1" aria-labelledby="deleteCenterModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteCenterModalLabel">Supprimer un Centre</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Êtes-vous sûr de vouloir supprimer ce centre et tous les utilisateurs associés (cadres et agents) ? Cette action est irréversible.</p>
                    <form id="deleteCenterForm">
                        @csrf
                        @method('DELETE')
                        <input type="hidden" id="delete_center_id" name="id">
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
    .btn-info {
        background-color: #17a2b8;
        border-color: #17a2b8;
    }
    .btn-info:hover {
        background-color: #138496;
        border-color: #138496;
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
    .stats-icon.warning { background: rgba(245, 158, 11, 0.1); color: #f59e0b; }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const centerModal = new bootstrap.Modal(document.getElementById('addCenterModal'));
    const regionModal = new bootstrap.Modal(document.getElementById('addRegionModal'));
    const departmentModal = new bootstrap.Modal(document.getElementById('addDepartmentModal'));
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteCenter'));
    const detailsModal = new bootstrap.Modal(document.getElementById('centerDetailsModal'));
    const successToast = new bootstrap.Toast(document.getElementById('successToast'));
    const errorToast = new bootstrap.Toast(document.getElementById('errorToast'));

    function showToast(type, message) {
        const toast = type === 'success' ? successToast : errorToast;
        toast._element.querySelector('.toast-body').textContent = message;
        toast.show();
    }

    // Gestion du formulaire d'ajout/modification de centre
    document.getElementById('centerForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const centerId = document.getElementById('center_id').value;
        const url = centerId ? `/admin/centers/${centerId}` : '/admin/centers';
        const method = centerId ? 'PUT' : 'POST';

        const data = {
            name: document.getElementById('center_name').value,
            department_id: document.getElementById('department_id').value,
            _token: document.querySelector('meta[name="csrf-token"]').content,
        };

        if (centerId) {
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
            const center = response.data;
            const row = `
                <tr data-id="${center.id}">
                    <td>${center.name}</td>
                    <td>${center.department}</td>
                    <td>${center.region}</td>
                    <td>
                        <button class="btn btn-primary btn-sm rounded-pill edit-center" data-id="${center.id}" data-name="${center.name}" data-department-id="${center.department_id}">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-info btn-sm rounded-pill detail-center" data-id="${center.id}">
                            <i class="fas fa-info-circle"></i>
                        </button>
                        <button class="btn btn-danger btn-sm rounded-pill delete-center" data-id="${center.id}">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `;
            if (centerId) {
                document.querySelector(`tr[data-id="${centerId}"]`).outerHTML = row;
            } else {
                document.getElementById('centers-table').insertAdjacentHTML('beforeend', row);
            }
            showToast('success', response.data.message);
            centerModal.hide();
            document.getElementById('centerForm').reset();
            document.getElementById('center_id').value = '';
            document.getElementById('addCenterModalLabel').textContent = 'Ajouter un Centre';
        })
        .catch(error => {
            console.error(error.response);
            const errors = error.response.data.errors || {};
            document.getElementById('center_name_error').textContent = errors.name ? errors.name[0] : '';
            document.getElementById('department_id_error').textContent = errors.department_id ? errors.department_id[0] : '';
            showToast('error', error.response.data.message || 'Erreur lors de l\'enregistrement du centre.');
        });
    });

    // Gestion du formulaire d'ajout/modification de région
    document.getElementById('regionForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        const regionId = document.getElementById('region_id').value;
        const url = regionId ? `/admin/regions/${regionId}` : '/admin/regions';
        const method = regionId ? 'PUT' : 'POST';

        axios({
            method: method,
            url: url,
            data: formData,
            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
        })
        .then(response => {
            const regionSelect = document.getElementById('region_id');
            const regionFilter = document.getElementById('region_filter');
            if (!regionId) {
                regionSelect.insertAdjacentHTML('beforeend', `<option value="${response.data.id}">${response.data.name}</option>`);
                regionFilter.insertAdjacentHTML('beforeend', `<option value="${response.data.id}">${response.data.name}</option>`);
            } else {
                const option = regionSelect.querySelector(`option[value="${regionId}"]`);
                const filterOption = regionFilter.querySelector(`option[value="${regionId}"]`);
                option.textContent = response.data.name;
                filterOption.textContent = response.data.name;
            }
            showToast('success', response.data.message);
            regionModal.hide();
            document.getElementById('regionForm').reset();
            document.getElementById('region_id').value = '';
            document.getElementById('addRegionModalLabel').textContent = 'Ajouter une Région';
        })
        .catch(error => {
            const errors = error.response.data.errors;
            document.getElementById('region_name_error').textContent = errors.name ? errors.name[0] : '';
            showToast('error', error.response.data.message || 'Erreur lors de l\'enregistrement de la région.');
        });
    });

    // Gestion du formulaire d'ajout/modification de département
    document.getElementById('departmentForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        const departmentId = document.getElementById('department_id').value;
        const url = departmentId ? `/admin/departments/${departmentId}` : '/admin/departments';
        const method = departmentId ? 'PUT' : 'POST';

        axios({
            method: method,
            url: url,
            data: formData,
            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
        })
        .then(response => {
            const departmentSelect = document.getElementById('department_id');
            const departmentFilter = document.getElementById('department_filter');
            if (!departmentId) {
                departmentSelect.insertAdjacentHTML('beforeend', `<option value="${response.data.id}" data-region-id="${response.data.region_id}">${response.data.name} (${response.data.region_name})</option>`);
                departmentFilter.insertAdjacentHTML('beforeend', `<option value="${response.data.id}" data-region-id="${response.data.region_id}">${response.data.name} (${response.data.region_name})</option>`);
            } else {
                const option = departmentSelect.querySelector(`option[value="${departmentId}"]`);
                const filterOption = departmentFilter.querySelector(`option[value="${departmentId}"]`);
                option.textContent = `${response.data.name} (${response.data.region_name})`;
                option.dataset.regionId = response.data.region_id;
                filterOption.textContent = `${response.data.name} (${response.data.region_name})`;
                filterOption.dataset.regionId = response.data.region_id;
            }
            showToast('success', response.data.message);
            departmentModal.hide();
            document.getElementById('departmentForm').reset();
            document.getElementById('department_id').value = '';
            document.getElementById('addDepartmentModalLabel').textContent = 'Ajouter un Département';
        })
        .catch(error => {
            const errors = error.response.data.errors;
            document.getElementById('department_name_error').textContent = errors.name ? errors.name[0] : '';
            document.getElementById('region_id_error').textContent = errors.region_id ? errors.region_id[0] : '';
            showToast('error', error.response.data.message || 'Erreur lors de l\'enregistrement du département.');
        });
    });

    // Gestion des clics sur les boutons du tableau
    document.getElementById('centers-table').addEventListener('click', function(e) {
        if (e.target.closest('.edit-center')) {
            const button = e.target.closest('.edit-center');
            const id = button.dataset.id;
            const name = button.dataset.name;
            const departmentId = button.dataset.departmentId;

            document.getElementById('center_id').value = id;
            document.getElementById('center_name').value = name;
            document.getElementById('department_id').value = departmentId;
            document.getElementById('addCenterModalLabel').textContent = 'Modifier le Centre';
            centerModal.show();
        }

        if (e.target.closest('.detail-center')) {
            const button = e.target.closest('.detail-center');
            const id = button.dataset.id;

            axios.get(`/admin/centers/${id}`)
                .then(response => {
                    const center = response.data;
                    document.getElementById('detail_center_name').textContent = center.name;
                    document.getElementById('detail_department').textContent = center.department;
                    document.getElementById('detail_region').textContent = center.region;
                    document.getElementById('detail_supervisor_count').textContent = center.supervisor_count;
                    document.getElementById('detail_agent').textContent = center.agent_count;

                    const supervisorsList = document.getElementById('detail_supervisors');
                    const agentsList = document.getElementById('detail_agents');
                    supervisorsList.innerHTML = center.supervisors.length ? center.supervisors.map(name => `<li>${name}</li>`).join('') : '<li>Aucun cadre</li>';
                    agentsList.innerHTML = center.agents.length ? center.agents.map(name => `<li>${name}</li>`).join('') : '<li>Aucun agent</li>';

                    detailsModal.show();
                })
                .catch(error => {
                    showToast('error', 'Erreur lors de la récupération des détails du centre.');
                });
        }

        if (e.target.closest('.delete-center')) {
            const button = e.target.closest('.delete-center');
            const id = button.dataset.id;

            document.getElementById('delete_center_id').value = id;
            deleteModal.show();
        }
    });

    // Gestion de la suppression
    document.getElementById('deleteCenterForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const centerId = document.getElementById('delete_center_id').value;
        const url = `/admin/centers/${centerId}`;

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
            showToast('error', error.response.data.message || 'Erreur lors de la suppression du centre.');
        });
    });

    // Gestion des filtres
    const regionFilter = document.getElementById('region_filter');
    const departmentFilter = document.getElementById('department_filter');
    const searchName = document.getElementById('search_name');
    const searchButton = document.getElementById('search_button');

    function filterCenters() {
        const regionId = regionFilter.value;
        const departmentId = departmentFilter.value;
        const name = searchName.value.trim();

        // Filtrer les départements en fonction de la région sélectionnée
        const departmentOptions = departmentFilter.querySelectorAll('option:not([value=""])');
        departmentOptions.forEach(option => {
            option.style.display = regionId ? (option.dataset.regionId === regionId ? '' : 'none') : '';
        });

        // Mettre à jour l'URL avec les paramètres de filtre
        const url = new URL(window.location);
        url.searchParams.set('region_id', regionId || '');
        url.searchParams.set('department_id', departmentId || '');
        url.searchParams.set('search_name', name || '');
        window.location = url;
    }

    regionFilter.addEventListener('change', function() {
        departmentFilter.value = ''; // Réinitialiser le filtre de département
        filterCenters();
    });

    departmentFilter.addEventListener('change', filterCenters);

    // Déclencher la recherche sur clic du bouton
    searchButton.addEventListener('click', filterCenters);

    // Déclencher la recherche sur pression de la touche "Entrée"
    searchName.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            filterCenters();
        }
    });
});
</script>
@endpush
@endsection
