@extends('layouts.supervisor')

@section('title', 'Validation des Actes de Mariage')

@section('content')
<div class="container">
    <h1 class="page-title">Validation des Actes de Mariage</h1>
    <p class="page-subtitle">Validez ou rejetez les actes de mariage en attente</p>

    <!-- Tableau des actes en attente -->
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Nom de l'Époux</th>
                <th>Nom de l'Épouse</th>
                <th>Date de Mariage</th>
                <th>Statut</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="acts-table">
            @foreach ($acts as $act)
                <tr data-id="{{ $act->id }}">
                    <td>{{ $act->details['spouse1_name'] ?? '-' }}</td>
                    <td>{{ $act->details['spouse2_name'] ?? '-' }}</td>
                    <td>{{ isset($act->details['marriage_date']) ? \Carbon\Carbon::parse($act->details['marriage_date'])->format('d/m/Y') : '-' }}</td>
                    <td>
                        <span class="badge bg-{{ $act->status === 'pending' ? 'warning' : ($act->status === 'validated' ? 'success' : 'danger') }}">
                            {{ ucfirst($act->status) }}
                        </span>
                    </td>
                    <td>
                        <button class="btn btn-success btn-sm rounded-pill validate-act" data-id="{{ $act->id }}">
                            <i class="fas fa-check"></i> Valider
                        </button>
                        <button class="btn btn-danger btn-sm rounded-pill reject-act" data-id="{{ $act->id }}">
                            <i class="fas fa-times"></i> Rejeter
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

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
    .btn-success {
        background-color: #28a745;
        border-color: #28a745;
    }
    .btn-success:hover {
        background-color: #218838;
        border-color: #1e7e34;
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
    const successToast = new bootstrap.Toast(document.getElementById('successToast'));
    const errorToast = new bootstrap.Toast(document.getElementById('errorToast'));

    function showToast(type, message) {
        const toast = type === 'success' ? successToast : errorToast;
        toast._element.querySelector('.toast-body').textContent = message;
        toast.show();
    }

    // Gestion des clics sur les boutons du tableau
    document.getElementById('acts-table').addEventListener('click', function(e) {
        if (e.target.closest('.validate-act')) {
            const button = e.target.closest('.validate-act');
            const id = button.dataset.id;
            console.log('Validation de l\'acte:', id);

            button.disabled = true;
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Validation...';

            axios.post(`/supervisor/documents/${id}/validate`, {
                _token: document.querySelector('meta[name="csrf-token"]').content
            }, {
                headers: {
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                console.log('Réponse validation:', response);
                try {
                    if (response.status === 200) {
                        const act = response.data.act;
                        const row = document.querySelector(`tr[data-id="${id}"]`);
                        if (!row) {
                            throw new Error(`Ligne avec data-id="${id}" non trouvée`);
                        }
                        row.querySelector('.badge').className = 'badge bg-success';
                        row.querySelector('.badge').textContent = 'Validated';
                        row.querySelector('td:last-child').innerHTML = '<span>-</span>';
                        showToast('success', response.data.message);
                        button.disabled = false;
                        button.innerHTML = '<i class="fas fa-check"></i> Valider';
                    } else {
                        throw new Error('Réponse inattendue du serveur: statut ' + response.status);
                    }
                } catch (err) {
                    console.error('Erreur dans le bloc .then:', err);
                    showToast('error', 'Erreur côté client lors de la mise à jour de l\'interface.');
                    button.disabled = false;
                    button.innerHTML = '<i class="fas fa-check"></i> Valider';
                }
            })
            .catch(error => {
                console.error('Erreur lors de la requête AJAX:', error.response?.data || error.message);
                showToast('error', error.response?.data?.message || 'Erreur lors de la validation de l\'acte.');
                button.disabled = false;
                button.innerHTML = '<i class="fas fa-check"></i> Valider';
            });
        }

        if (e.target.closest('.reject-act')) {
            const button = e.target.closest('.reject-act');
            const id = button.dataset.id;
            console.log('Rejet de l\'acte:', id);

            button.disabled = true;
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Rejet...';

            axios.post(`/supervisor/documents/${id}/reject`, {
                _token: document.querySelector('meta[name="csrf-token"]').content
            }, {
                headers: {
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                console.log('Réponse rejet:', response);
                try {
                    if (response.status === 200) {
                        const act = response.data.act;
                        const row = document.querySelector(`tr[data-id="${id}"]`);
                        if (!row) {
                            throw new Error(`Ligne avec data-id="${id}" non trouvée`);
                        }
                        row.querySelector('.badge').className = 'badge bg-danger';
                        row.querySelector('.badge').textContent = 'Rejected';
                        row.querySelector('td:last-child').innerHTML = '<span>-</span>';
                        showToast('success', response.data.message);
                        button.disabled = false;
                        button.innerHTML = '<i class="fas fa-times"></i> Rejeter';
                    } else {
                        throw new Error('Réponse inattendue du serveur: statut ' + response.status);
                    }
                } catch (err) {
                    console.error('Erreur dans le bloc .then:', err);
                    showToast('error', 'Erreur côté client lors de la mise à jour de l\'interface.');
                    button.disabled = false;
                    button.innerHTML = '<i class="fas fa-times"></i> Rejeter';
                }
            })
            .catch(error => {
                console.error('Erreur lors de la requête AJAX:', error.response?.data || error.message);
                showToast('error', error.response?.data?.message || 'Erreur lors du rejet de l\'acte.');
                button.disabled = false;
                button.innerHTML = '<i class="fas fa-times"></i> Rejeter';
            });
        }
    });
});
</script>
@endpush
@endsection
