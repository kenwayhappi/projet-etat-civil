<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use App\Models\Act;
use App\Models\Center;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;

class DocumentController extends Controller
{
    public function births(Request $request)
    {
        $query = Act::where('type', 'birth');
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }
        $acts = $query->get();
        Log::info('Chargement des actes de naissance pour le superviseur', ['count' => $acts->count(), 'status' => $request->status ?? 'all']);
        return view('supervisor.documents.births', compact('acts'));
    }

    public function marriages(Request $request)
    {
        $query = Act::where('type', 'marriage');
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }
        $acts = $query->get();
        Log::info('Chargement des actes de mariage pour le superviseur', ['count' => $acts->count(), 'status' => $request->status ?? 'all']);
        return view('supervisor.documents.marriages', compact('acts'));
    }

    public function deaths(Request $request)
    {
        $query = Act::where('type', 'death');
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }
        $acts = $query->get();
        Log::info('Chargement des actes de décès pour le superviseur', ['count' => $acts->count(), 'status' => $request->status ?? 'all']);
        return view('supervisor.documents.deaths', compact('acts'));
    }

    public function divorces(Request $request)
    {
        $query = Act::where('type', 'divorce');
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }
        $acts = $query->get();
        Log::info('Chargement des actes de divorce pour le superviseur', ['count' => $acts->count(), 'status' => $request->status ?? 'all']);
        return view('supervisor.documents.divorces', compact('acts'));
    }

    public function validateAct(Request $request, Act $act)
    {
        Log::info('Validation de l\'acte par le superviseur', ['act_id' => $act->id, 'type' => $act->type]);
        $act->update([
            'status' => 'validated',
            'validated_by' => auth()->user()->id,
        ]);
        return response()->json(['message' => 'Acte validé avec succès', 'act' => $act], 200);
    }

    public function rejectAct(Request $request, Act $act)
    {
        Log::info('Rejet de l\'acte par le superviseur', ['act_id' => $act->id, 'type' => $act->type]);
        $act->update(['status' => 'rejected']);
        return response()->json(['message' => 'Acte rejeté avec succès', 'act' => $act], 200);
    }

    public function generatePdf(Act $act)
    {
        if ($act->status !== 'validated') {
            return redirect()->back()->with('error', 'Seuls les actes validés peuvent être imprimés.');
        }

        $view = match ($act->type) {
            'birth' => 'pdf.birth',
            'marriage' => 'pdf.marriage',
            'death' => 'pdf.death',
            'divorce' => 'pdf.divorce',
            default => abort(404, 'Type d\'acte non supporté'),
        };

        // Récupérer le centre et le superviseur
        $center = Center::find($act->center_id);
        $supervisor = $act->validated_by ? User::find($act->validated_by) : null;

        Log::info('Génération du PDF pour l\'acte', [
            'act_id' => $act->id,
            'type' => $act->type,
            'center_id' => $act->center_id,
            'supervisor_id' => $act->validated_by
        ]);

        $pdf = Pdf::loadView($view, compact('act', 'center', 'supervisor'));
        return $pdf->download("acte-{$act->type}-{$act->id}.pdf");
    }
}
