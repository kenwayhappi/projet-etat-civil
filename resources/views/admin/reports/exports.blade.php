@extends('layouts.dashboard')

@section('title', 'Exports')

@section('content')
    <div class="container">
        <h1 class="page-title">Exports</h1>
        <p class="page-subtitle">Téléchargez les données des actes</p>

        <a href="{{ route('admin.reports.export.excel') }}" class="btn btn-primary-modern">
            <i class="fas fa-download"></i> Exporter en Excel
        </a>

        <table class="table table-bordered table-striped mt-4">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Type</th>
                    <th>Statut</th>
                    <th>Centre</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($acts as $act)
                    <tr>
                        <td>{{ $act->id }}</td>
                        <td>{{ ucfirst($act->type) }}</td>
                        <td>{{ ucfirst($act->status) }}</td>
                        <td>{{ $act->center->name ?? '-' }}</td>
                        <td>{{ $act->created_at->format('d/m/Y') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
