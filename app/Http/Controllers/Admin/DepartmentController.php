<?php

   namespace App\Http\Controllers\Admin;

   use App\Http\Controllers\Controller;
   use App\Http\Requests\DepartmentRequest;
   use App\Models\Department;
   use App\Models\Region;

   class DepartmentController extends Controller
   {
       public function index()
       {
           $departments = Department::with('region')->get();
           return view('admin.departments.index', compact('departments'));
       }

       public function create()
       {
           $regions = Region::all();
           return view('admin.departments.create', compact('regions'));
       }

       public function store(DepartmentRequest $request)
       {
           Department::create($request->validated());
           return redirect()->route('admin.departments.index')->with('success', 'Département créé avec succès.');
       }

       public function edit(Department $department)
       {
           $regions = Region::all();
           return view('admin.departments.edit', compact('department', 'regions'));
       }

       public function update(DepartmentRequest $request, Department $department)
       {
           $department->update($request->validated());
           return redirect()->route('admin.departments.index')->with('success', 'Département mis à jour avec succès.');
       }

       public function destroy(Department $department)
       {
           $department->delete();
           return redirect()->route('admin.departments.index')->with('success', 'Département supprimé avec succès.');
       }
   }
