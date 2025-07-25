<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use App\Models\Center;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $centers = Center::where('id', $user->center_id)->with(['department.region'])->get();
        $agents_count = User::where('role', 'agent')->where('center_id', $user->center_id)->count();
        $pending_documents = 0; // À remplacer par une logique réelle pour les documents en attente

        return view('supervisor.dashboard', compact('centers', 'agents_count', 'pending_documents'));
    }

    public function showCenter(Center $center)
    {
        // Vérifier que le centre appartient au superviseur
        if ($center->id !== Auth::user()->center_id) {
            abort(403, 'Accès non autorisé');
        }

        $agents = User::where('role', 'agent')->where('center_id', $center->id)->get();
        return view('supervisor.centers.show', compact('center', 'agents'));
    }
}
