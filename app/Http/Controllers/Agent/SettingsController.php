<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class SettingsController extends Controller
{
    /**
     * Affiche la page des paramètres de l'agent.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $user = Auth::user();
        return view('agent.settings', compact('user'));
    }

    /**
     * Met à jour les informations de l'agent.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        try {
            // Mise à jour des informations
            $user->name = $validated['name'];
            $user->email = $validated['email'];

            if (!empty($validated['password'])) {
                $user->password = Hash::make($validated['password']);
            }

            $user->save();

            // Enregistrer dans audit_logs
            \App\Models\AuditLog::create([
                'user_id' => $user->id,
                'action' => 'Modification des paramètres du compte',
                'act_id' => null,
            ]);

            return response()->json([
                'message' => 'Paramètres mis à jour avec succès.',
                'user' => [
                    'name' => $user->name,
                    'email' => $user->email,
                ],
            ], 200);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la mise à jour des paramètres de l\'agent: ' . $e->getMessage());
            return response()->json([
                'message' => 'Erreur lors de la mise à jour des paramètres.',
                'errors' => ['general' => 'Une erreur est survenue. Veuillez réessayer.'],
            ], 500);
        }
    }
}
