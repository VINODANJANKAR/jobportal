<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Experiences;
use Illuminate\Support\Facades\Validator;
use Exception;

class ExperiencesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $pageSize = $request->get('page_size', 10); // Default to 10 if no page size is provided
            $query = Experiences::query();
            if ($request->has('search')) {
                $search = $request->get('search');
                $query->where(function($query) use ($search) {
                    $query->where('experience', 'LIKE', "%{$search}%");
                });
            }
            $experiences = $query->paginate($pageSize);
            if ($request->ajax()) {
                $tableHtml = view('masters.experience._table', compact('experiences'))->render();
                $paginationHtml = $experiences->links('pagination::bootstrap-5')->render();

                return response()->json([
                    'experiences' => $tableHtml,
                    'paginationHtml' => $paginationHtml
                ]);
            }
            return view('masters.experience.index', compact('experiences'));
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
        return view('masters.experience.form',compact('show'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'experience' => 'required|string|max:255',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
    
        try {
            
            Experiences::create($request->all());
            return response()->json([
                'message' => 'experience created successfully!',
            ]);
        } catch (\Exception $e) {
            // Handle errors
            return response()->json(['error' => 'An error occurred, please try again later.'], 500);
        }
    }

    public function show(string $id)
    {
        $show = true;
        $experience = Experiences::findOrFail($id);
        return view('masters.experience.form',compact('experience','show'));
    }

    public function edit(string $id)
    {
        try {
            $show = false;
            $experience = Experiences::findOrFail($id);
            return view('masters.experience.form',compact('experience','show'));
        } catch (ModelNotFoundException $e) {
            return back()->withErrors(['error' => 'User not found.']);
        } catch (Exception $e) {
            return back()->withErrors(['error' => 'An error occurred while fetching the User for editing: ' . $e->getMessage()]);
        }
    }

    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'experience' => 'required|string|max:255',
        ]);
    
        try {
            $experience = Experiences::findOrFail($id);
            $experience->update($request->all());
            return response()->json([
                'message' => 'experience updated successfully!',
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred, please try again later.'], 500);
        }
    }

    public function destroy(string $id)
    {
        try { 
            $experience = Experiences::findOrFail($id);
            $experience->delete();
            return response()->json([
                'message' => 'Experiences deleted successfully!',
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred, please try again later.'], 500);
        }
    }
}
