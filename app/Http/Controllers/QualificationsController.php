<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Qualifications;
use Illuminate\Support\Facades\Validator;
use Exception;

class QualificationsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $pageSize = $request->get('page_size', 10); // Default to 10 if no page size is provided
            $query = Qualifications::query();
            if ($request->has('search')) {
                $search = $request->get('search');
                $query->where(function($query) use ($search) {
                    $query->where('qualification', 'LIKE', "%{$search}%");
                });
            }
            $qualifications = $query->paginate($pageSize);
            if ($request->ajax()) {
                $tableHtml = view('masters.qualification._table', compact('qualifications'))->render();
                $paginationHtml = $qualifications->links('pagination::bootstrap-5')->render();

                return response()->json([
                    'qualifications' => $tableHtml,
                    'paginationHtml' => $paginationHtml
                ]);
            }
            return view('masters.qualification.index', compact('qualifications'));
        } catch (Exception $e) {
            return response()->json([
                'error' => 'An error occurred while fetching the user list.',
                'message' => $e->getMessage(),
            ], 500); // Return 500 status code for server errors
        }
    }

    public function create()
    {
        $show = false;
        return view('masters.qualification.form',compact('show'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'qualification' => 'required|string|max:255',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
    
        try {
            
            Qualifications::create($request->all());
            return response()->json([
                'message' => 'qualification created successfully!',
            ]);
        } catch (\Exception $e) {
            // Handle errors
            return response()->json(['error' => 'An error occurred, please try again later.'], 500);
        }
    }

    public function show(string $id)
    {
        $show = true;
        $qualification = Qualifications::findOrFail($id);
        return view('masters.qualification.form',compact('qualification','show'));
    }

    public function edit(string $id)
    {
        try {
            $show = false;
            $qualification = Qualifications::findOrFail($id);
            return view('masters.qualification.form',compact('qualification','show'));
        } catch (ModelNotFoundException $e) {
            return back()->withErrors(['error' => 'qualification not found.']);
        } catch (Exception $e) {
            return back()->withErrors(['error' => 'An error occurred while fetching the qualification for editing: ' . $e->getMessage()]);
        }
    }

    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'qualification' => 'required|string|max:255',
        ]);
    
        try {
            $qualification = Qualifications::findOrFail($id);
            $qualification->update($request->all());
            return response()->json([
                'message' => 'qualification updated successfully!',
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred, please try again later.'], 500);
        }
    }

    public function destroy(string $id)
    {
        try { 
            $qualification = Qualifications::findOrFail($id);
            $qualification->delete();
            return response()->json([
                'message' => 'Qualifications deleted successfully!',
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred, please try again later.'], 500);
        }
    }
}
