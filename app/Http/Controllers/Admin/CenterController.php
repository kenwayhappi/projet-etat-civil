<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CenterRequest;
use App\Models\Center;
use App\Models\Department;
use App\Models\Region;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CenterController extends Controller
{
    public function index(Request $request)
    {
        $query = Center::with('department.region');

        // Appliquer les filtres
        if ($request->has('region_id') && $request->region_id) {
            $query->whereHas('department', function ($q) use ($request) {
                $q->where('region_id', $request->region_id);
            });
        }

        if ($request->has('department_id') && $request->department_id) {
            $query->where('department_id', $request->department_id);
        }

        if ($request->has('search_name') && $request->search_name) {
            $query->where('name', 'like', '%' . $request->search_name . '%');
        }

        $centers = $query->get();
        $departments = Department::with('region')->get();
        $regions = Region::all();

        return view('admin.centers.index', compact('centers', 'departments', 'regions'));
    }

    public function store(CenterRequest $request)
    {
        $center = Center::create($request->validated());

        return response()->json([
            'id' => $center->id,
            'name' => $center->name,
            'department' => $center->department->name,
            'region' => $center->department->region->name,
            'department_id' => $center->department_id,
            'message' => 'Centre créé avec succès.'
        ]);
    }

    public function update(CenterRequest $request, Center $center)
    {
        $center->update($request->validated());

        return response()->json([
            'id' => $center->id,
            'name' => $center->name,
            'department' => $center->department->name,
            'region' => $center->department->region->name,
            'department_id' => $center->department_id,
            'message' => 'Centre mis à jour avec succès.'
        ]);
    }

    public function destroy(Center $center)
    {
        $center->delete();

        return response()->json([
            'id' => $center->id,
            'message' => 'Centre et utilisateurs associés supprimés avec succès.'
        ]);
    }

    public function show(Center $center)
    {
        $supervisors = User::where('center_id', $center->id)->where('role', 'supervisor')->get();
        $agents = User::where('center_id', $center->id)->where('role', 'agent')->get();

        return response()->json([
            'id' => $center->id,
            'name' => $center->name,
            'department' => $center->department->name,
            'region' => $center->department->region->name,
            'supervisor_count' => $supervisors->count(),
            'agent_count' => $agents->count(),
            'supervisors' => $supervisors->pluck('name'),
            'agents' => $agents->pluck('name'),
        ]);
    }

    public function storeRegion(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $region = Region::create(['name' => $request->name]);

        return response()->json([
            'id' => $region->id,
            'name' => $region->name,
            'message' => 'Région créée avec succès.'
        ]);
    }

    public function updateRegion(Request $request, Region $region)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $region->update(['name' => $request->name]);

        return response()->json([
            'id' => $region->id,
            'name' => $region->name,
            'message' => 'Région mise à jour avec succès.'
        ]);
    }

    public function storeDepartment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'region_id' => 'required|exists:regions,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $department = Department::create([
            'name' => $request->name,
            'region_id' => $request->region_id,
        ]);

        return response()->json([
            'id' => $department->id,
            'name' => $department->name,
            'region_id' => $department->region_id,
            'region_name' => $department->region->name,
            'message' => 'Département créé avec succès.'
        ]);
    }

    public function updateDepartment(Request $request, Department $department)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'region_id' => 'required|exists:regions,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $department->update([
            'name' => $request->name,
            'region_id' => $request->region_id,
        ]);

        return response()->json([
            'id' => $department->id,
            'name' => $department->name,
            'region_id' => $department->region_id,
            'region_name' => $department->region->name,
            'message' => 'Département mis à jour avec succès.'
        ]);
    }
}
