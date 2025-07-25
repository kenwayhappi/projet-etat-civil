<?php

   namespace App\Http\Controllers\Admin;

   use App\Http\Controllers\Controller;
   use App\Http\Requests\RegionRequest;
   use App\Models\Region;

   class RegionController extends Controller
   {
       public function index()
       {
           $regions = Region::all();
           return view('admin.regions.index', compact('regions'));
       }

       public function create()
       {
           return view('admin.regions.create');
       }

       public function store(RegionRequest $request)
       {
           Region::create($request->validated());
           return redirect()->route('admin.regions.index')->with('success', 'Région créée avec succès.');
       }

       public function edit(Region $region)
       {
           return view('admin.regions.edit', compact('region'));
       }

       public function update(RegionRequest $request, Region $region)
       {
           $region->update($request->validated());
           return redirect()->route('admin.regions.index')->with('success', 'Région mise à jour avec succès.');
       }

       public function destroy(Region $region)
       {
           $region->delete();
           return redirect()->route('admin.regions.index')->with('success', 'Région supprimée avec succès.');
       }
   }
