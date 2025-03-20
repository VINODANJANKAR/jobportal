<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\CompanyLocations;

class CompanyLocationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'company_id' => 'required|exists:companies,id',
            'location' => 'required|string',
            'location_map' => 'nullable|string',
            'city' => 'required|string',
            'address' => 'required|string',
        ]);
    
        // Create the new location
        $location = new CompanyLocations();
        $location->company_id = $validated['company_id'];
        $location->location = $validated['location'];
        $location->location_map = $validated['location_map'];
        $location->city = $validated['city'];
        $location->address = $validated['address'];
        $location->save();
    
        // Return a success response
        return response()->json([
            'message' => 'Location added successfully!',
            'location' => $location
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $company = Company::findOrFail($id);
        $locations = $company->locations()->paginate(10); 
        return view('masters.company.location.index', compact('company', 'locations'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
         $validated = $request->validate([
            'location' => 'required|string',
            'location_map' => 'string',
            'city' => 'required|string',
            'address' => 'required|string',
        ]);
        $location = CompanyLocations::findOrFail($id);
        $location->location = $validated['location'];
        $location->location_map = $validated['location_map'];
        $location->city = $validated['city'];
        $location->address = $validated['address'];
        $location->save();
        return response()->json([
            'message' => 'Location updated successfully!',
            'location' => $location
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $location = CompanyLocations::findOrFail($id);
        $location->delete();
        return response()->json([
            'message' => 'Location deleted successfully!'
        ]);
    }

    public function getAddress(CompanyLocations $location)
    {
        return response()->json(['address' => $location->address]);
    }
}
