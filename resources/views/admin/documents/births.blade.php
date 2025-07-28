@extends('layouts.dashboard')

@section('title', 'Actes de Naissance')

@section('content')
    <div class="container">
        <h1 class="page-title">Actes de Naissance</h1>
        <p class="page-subtitle">Consultez et filtrez tous les actes de naissance</p>

        <form method="GET" action="{{ route('admin.documents.births') }}" class="mb-4">
            <div class="row g-3">
                <div class="col-md-3">
                    <select name="region_id" class="form-select">
                        <option value="">Toutes les régions</option>
                        @foreach ($regions as $region)
                            <option value="{{ $region->id }}" {{ request('region_id') == $region->id ? 'selected' : '' }}>{{ $region->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="department_id" class="form-select">
                        <option value="">Tous les départements</option>
                        @foreach ($departments as $department)
                            <option value="{{ $department->id }}" {{ request('department_id') == $department->id ? 'selected' : '' }}>{{ $department->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="center_id" class="form-select">
                        <option value="">Tous les centres</option>
                        @foreach ($centers as $center)
                            <option value="{{ $center->id }}" {{ request('center_id') == $center->id ? 'selected' : '' }}>{{ $center->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary-modern w-100">Filtrer</button>
                </div>
            </div>
        </form>

        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Date</th>
                    <th>Centre</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($acts as $act)
                    <tr>
                        <td>{{ $act->id }}</td>
                        <td>{{ $act->details['name'] ?? '-' }}</td>
                        <td>{{ $act->created_at->format('d/m/Y') }}</td>
                        <td>{{ $act->center->name ?? '-' }}</td>
                        <td>{{ ucfirst($act->status) }}</td>
                        <td>
                            @if ($act->status === 'validated')
                                <a href="{{ route('admin.documents.pdf', $act->id) }}" class="btn btn-primary btn-sm" target="_blank">PDF</a>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
