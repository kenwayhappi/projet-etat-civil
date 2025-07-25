<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Http\Requests\SettingsRequest;
use App\Models\Center;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('center')->whereIn('role', ['supervisor', 'agent'])->get();
        $centers = Center::all();
        return view('admin.users.index', compact('users', 'centers'));
    }

    public function store(UserRequest $request)
    {
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);
        $user = User::create($data);

        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
            'center_id' => $user->center_id,
            'center_name' => $user->center ? $user->center->name : null,
            'message' => 'Utilisateur créé avec succès.'
        ], 201);
    }

    public function update(UserRequest $request, User $user)
    {
        $data = $request->validated();
        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }
        $user->update($data);

        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
            'center_id' => $user->center_id,
            'center_name' => $user->center ? $user->center->name : null,
            'message' => 'Utilisateur mis à jour avec succès.'
        ]);
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return response()->json(['message' => 'Vous ne pouvez pas supprimer votre propre compte.'], 403);
        }
        $user->delete();

        return response()->json([
            'id' => $user->id,
            'message' => 'Utilisateur supprimé avec succès.'
        ]);
    }

    public function settings()
    {
        $user = Auth::user();
        return view('admin.settings.index', compact('user'));
    }

    public function updateSettings(SettingsRequest $request)
    {
        $user = Auth::user();
        $data = $request->validated();

        if (!Hash::check($data['current_password'], $user->password)) {
            return back()->withErrors(['current_password' => 'L\'ancien mot de passe est incorrect.'])->withInput();
        }

        $updateData = [
            'name' => $data['name'],
            'email' => $data['email'],
        ];

        if (!empty($data['password'])) {
            $updateData['password'] = Hash::make($data['password']);
        }

        $user->update($updateData);

        return redirect()->route('admin.settings')->with('success', 'Vos informations ont été mises à jour avec succès.');
    }
}
