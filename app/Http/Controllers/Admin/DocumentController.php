<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Act;
use App\Models\Center;
use App\Models\Department;
use App\Models\Region;
use App\Models\User;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class DocumentController extends Controller
{
    public function births(Request $request)
    {
        $query = Act::where('type', 'birth');
        $this->applyFilters($query, $request);
        $acts = $query->with('center.department.region')->get();
        $centers = Center::all();
        $departments = Department::all();
        $regions = Region::all();

        return view('admin.documents.births', compact('acts', 'centers', 'departments', 'regions'));
    }

    public function marriages(Request $request)
    {
        $query = Act::where('type', 'marriage');
        $this->applyFilters($query, $request);
        $acts = $query->with('center.department.region')->get();
        $centers = Center::all();
        $departments = Department::all();
        $regions = Region::all();

        return view('admin.documents.marriages', compact('acts', 'centers', 'departments', 'regions'));
    }

    public function deaths(Request $request)
    {
        $query = Act::where('type', 'death');
        $this->applyFilters($query, $request);
        $acts = $query->with('center.department.region')->get();
        $centers = Center::all();
        $departments = Department::all();
        $regions = Region::all();

        return view('admin.documents.deaths', compact('acts', 'centers', 'departments', 'regions'));
    }

    public function divorces(Request $request)
    {
        $query = Act::where('type', 'divorce');
        $this->applyFilters($query, $request);
        $acts = $query->with('center.department.region')->get();
        $centers = Center::all();
        $departments = Department::all();
        $regions = Region::all();

        return view('admin.documents.divorces', compact('acts', 'centers', 'departments', 'regions'));
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

        $center = $act->center;
        $supervisor = $act->validated_by ? User::find($act->validated_by) : null;

        $pdf = Pdf::loadView($view, compact('act', 'center', 'supervisor'));
        return $pdf->download("acte-{$act->type}-{$act->id}.pdf");
    }

    private function applyFilters($query, Request $request)
    {
        if ($request->has('region_id') && $request->region_id) {
            $query->whereHas('center.department', function ($q) use ($request) {
                $q->where('region_id', $request->region_id);
            });
        }

        if ($request->has('department_id') && $request->department_id) {
            $query->whereHas('center', function ($q) use ($request) {
                $q->where('department_id', $request->department_id);
            });
        }

        if ($request->has('center_id') && $request->center_id) {
            $query->where('center_id', $request->center_id);
        }

        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->has('date_from') && $request->has('date_to')) {
            $query->whereBetween('created_at', [$request->date_from, $request->date_to]);
        }
    }
}
