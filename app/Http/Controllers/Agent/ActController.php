<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Act;
use App\Models\Center;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;

class ActController extends Controller
{
    public function births(Request $request)
    {
        $query = Act::where('type', 'birth')
            ->where('created_by', Auth::user()->id)
            ->where('center_id', Auth::user()->center_id);

        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $acts = $query->get();
        Log::info('Chargement des actes de naissance pour l\'agent', [
            'user_id' => Auth::user()->id,
            'count' => $acts->count(),
            'status' => $request->status ?? 'all'
        ]);

        return view('agent.acts.births', compact('acts'));
    }

    public function marriages(Request $request)
    {
        $query = Act::where('type', 'marriage')
            ->where('created_by', Auth::user()->id)
            ->where('center_id', Auth::user()->center_id);

        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $acts = $query->get();
        Log::info('Chargement des actes de mariage pour l\'agent', [
            'user_id' => Auth::user()->id,
            'count' => $acts->count(),
            'status' => $request->status ?? 'all'
        ]);

        return view('agent.acts.marriages', compact('acts'));
    }

    public function deaths(Request $request)
    {
        $query = Act::where('type', 'death')
            ->where('created_by', Auth::user()->id)
            ->where('center_id', Auth::user()->center_id);

        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $acts = $query->get();
        Log::info('Chargement des actes de décès pour l\'agent', [
            'user_id' => Auth::user()->id,
            'count' => $acts->count(),
            'status' => $request->status ?? 'all'
        ]);

        return view('agent.acts.deaths', compact('acts'));
    }

    public function divorces(Request $request)
    {
        $query = Act::where('type', 'divorce')
            ->where('created_by', Auth::user()->id)
            ->where('center_id', Auth::user()->center_id);

        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $acts = $query->get();
        Log::info('Chargement des actes de divorce pour l\'agent', [
            'user_id' => Auth::user()->id,
            'count' => $acts->count(),
            'status' => $request->status ?? 'all'
        ]);

        return view('agent.acts.divorces', compact('acts'));
    }

    public function store(Request $request)
    {
        try {
            $user = Auth::user();
            $center_id = $user->center_id;

            if (!$center_id) {
                return response()->json(['message' => 'Aucun centre associé à votre compte.'], 400);
            }

            // Validation des champs
            $validated = $request->validate($this->validationRules($request->type));

            // Construire le champ details à partir des données validées
            $details = $validated['details'];

            // Créer l'acte
            $act = Act::create([
                'type' => $request->type,
                'details' => $details,
                'center_id' => $center_id,
                'created_by' => $user->id,
                'status' => 'pending',
            ]);

            // Enregistrer dans audit_logs
            \App\Models\AuditLog::create([
                'user_id' => $user->id,
                'action' => "Création d'un acte de {$request->type}",
                'act_id' => $act->id,
            ]);

            return response()->json($act, 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Erreur de validation lors de la création : ', $e->errors());
            return response()->json(['message' => 'Erreur de validation', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            Log::error('Erreur serveur lors de la création : ', ['message' => $e->getMessage()]);
            return response()->json(['message' => 'Erreur serveur'], 500);
        }
    }

    public function edit(Act $act)
    {
        if ($act->created_by !== Auth::user()->id || $act->status !== 'pending') {
            abort(403, 'Accès non autorisé');
        }

        $view = match($act->type) {
            'birth' => 'agent.acts.births-edit',
            'marriage' => 'agent.acts.marriages-edit',
            'death' => 'agent.acts.deaths-edit',
            'divorce' => 'agent.acts.divorces-edit',
        };

        return view($view, compact('act'));
    }

    public function update(Request $request, Act $act)
    {
        try {
            if ($act->created_by !== Auth::user()->id || $act->status !== 'pending') {
                return response()->json(['message' => 'Accès non autorisé'], 403);
            }

            // Log des données reçues pour débogage
            Log::info('Données reçues dans ActController@update : ', $request->all());

            // Validation des champs
            $validated = $request->validate($this->validationRules($act->type));

            // Construire le champ details à partir des données validées
            $details = [
                'child_name' => $validated['details']['child_name'] ?? null,
                'birth_date' => $validated['details']['birth_date'] ?? null,
                'birth_place' => $validated['details']['birth_place'] ?? null,
                'father_name' => $validated['details']['father_name'] ?? null,
                'mother_name' => $validated['details']['mother_name'] ?? null,
                'spouse1_name' => $validated['details']['spouse1_name'] ?? null,
                'spouse2_name' => $validated['details']['spouse2_name'] ?? null,
                'marriage_date' => $validated['details']['marriage_date'] ?? null,
                'marriage_place' => $validated['details']['marriage_place'] ?? null,
                'deceased_name' => $validated['details']['deceased_name'] ?? null,
                'death_date' => $validated['details']['death_date'] ?? null,
                'death_place' => $validated['details']['death_place'] ?? null,
                'divorce_date' => $validated['details']['divorce_date'] ?? null,
                'divorce_place' => $validated['details']['divorce_place'] ?? null,
            ];

            // Filtrer les valeurs null pour ne garder que les champs pertinents
            $details = array_filter($details, fn($value) => !is_null($value));

            // Mettre à jour l'acte
            $act->update([
                'details' => $details,
                'status' => 'pending',
            ]);

            // Enregistrer dans audit_logs
            \App\Models\AuditLog::create([
                'user_id' => Auth::user()->id,
                'action' => "Modification d'un acte de {$act->type}",
                'act_id' => $act->id,
            ]);

            return response()->json($act, 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Erreur de validation lors de la modification : ', $e->errors());
            return response()->json(['message' => 'Erreur de validation', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            Log::error('Erreur serveur lors de la modification : ', ['message' => $e->getMessage()]);
            return response()->json(['message' => 'Erreur serveur'], 500);
        }
    }

    public function destroy(Act $act)
    {
        try {
            if ($act->created_by !== Auth::user()->id || $act->status !== 'pending') {
                return response()->json(['message' => 'Accès non autorisé'], 403);
            }

            $type = $act->type;
            $act->delete();

            // Enregistrer dans audit_logs
            \App\Models\AuditLog::create([
                'user_id' => Auth::user()->id,
                'action' => "Suppression d'un acte de {$type}",
                'act_id' => null,
            ]);

            return response()->json(['message' => 'Acte supprimé avec succès'], 200);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la suppression : ', ['message' => $e->getMessage()]);
            return response()->json(['message' => 'Erreur serveur'], 500);
        }
    }

    public function generatePdf(Act $act)
    {
        try {
            if ($act->created_by !== Auth::user()->id || $act->status !== 'validated') {
                return response()->json(['message' => 'Accès non autorisé ou acte non validé'], 403);
            }

            $view = match ($act->type) {
                'birth' => 'pdf.birth',
                'marriage' => 'pdf.marriage',
                'death' => 'pdf.death',
                'divorce' => 'pdf.divorce',
                default => throw new \InvalidArgumentException('Type d\'acte invalide.'),
            };

            // Récupérer le centre et le superviseur
            $center = Center::find($act->center_id);
            $supervisor = $act->validated_by ? User::find($act->validated_by) : null;

            Log::info('Génération du PDF pour l\'acte par l\'agent', [
                'act_id' => $act->id,
                'type' => $act->type,
                'user_id' => Auth::user()->id,
                'center_id' => $act->center_id,
                'supervisor_id' => $act->validated_by
            ]);

            $pdf = Pdf::loadView($view, compact('act', 'center', 'supervisor'));
            return $pdf->download("acte-{$act->type}-{$act->id}.pdf");
        } catch (\Exception $e) {
            Log::error('Erreur lors de la génération du PDF : ', ['message' => $e->getMessage()]);
            return response()->json(['message' => 'Erreur lors de la génération du PDF'], 500);
        }
    }

    private function validationRules($type)
    {
        $rules = [
            'type' => 'required|in:birth,marriage,death,divorce',
        ];

        return match($type) {
            'birth' => array_merge($rules, [
                'details.child_name' => 'required|string|max:255',
                'details.birth_date' => 'required|date_format:Y-m-d',
                'details.birth_place' => 'required|string|max:255',
                'details.father_name' => 'required|string|max:255',
                'details.mother_name' => 'required|string|max:255',
            ]),
            'marriage' => array_merge($rules, [
                'details.spouse1_name' => 'required|string|max:255',
                'details.spouse2_name' => 'required|string|max:255',
                'details.marriage_date' => 'required|date_format:Y-m-d',
                'details.marriage_place' => 'required|string|max:255',
            ]),
            'death' => array_merge($rules, [
                'details.deceased_name' => 'required|string|max:255',
                'details.death_date' => 'required|date_format:Y-m-d',
                'details.death_place' => 'required|string|max:255',
            ]),
            'divorce' => array_merge($rules, [
                'details.spouse1_name' => 'required|string|max:255',
                'details.spouse2_name' => 'required|string|max:255',
                'details.divorce_date' => 'required|date_format:Y-m-d',
                'details.divorce_place' => 'required|string|max:255',
            ]),
            default => throw new \InvalidArgumentException('Type d\'acte invalide.'),
        };
    }

    protected function failedValidation(\Illuminate\Validation\Validator $validator)
    {
        throw new \Illuminate\Validation\ValidationException($validator, response()->json([
            'message' => 'Erreur de validation',
            'errors' => $validator->errors(),
        ], 422));
    }
}
