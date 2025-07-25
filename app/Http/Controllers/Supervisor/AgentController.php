<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Center;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AgentController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $agents = User::where('role', 'agent')
            ->where('center_id', $user->center_id)
            ->get();
        $center = $user->center;

        return view('supervisor.agents.index', compact('agents', 'center'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'agent',
            'center_id' => $user->center_id,
        ]);

        return redirect()->route('supervisor.agents.index')->with('success', 'Agent ajouté avec succès.');
    }

    public function update(Request $request, User $agent)
    {
        $user = Auth::user();
        if ($agent->center_id !== $user->center_id || $agent->role !== 'agent') {
            abort(403, 'Accès non autorisé');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($agent->id)],
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $agent->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password ? Hash::make($request->password) : $agent->password,
        ]);

        return redirect()->route('supervisor.agents.index')->with('success', 'Agent mis à jour avec succès.');
    }

    public function destroy(User $agent)
    {
        $user = Auth::user();
        if ($agent->center_id !== $user->center_id || $agent->role !== 'agent') {
            abort(403, 'Accès non autorisé');
        }

        $agent->delete();

        return redirect()->route('supervisor.agents.index')->with('success', 'Agent supprimé avec succès.');
    }
}
