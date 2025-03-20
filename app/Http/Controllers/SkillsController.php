<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Skills;
use Illuminate\Support\Facades\Validator;
use Exception;

class SkillsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $pageSize = $request->get('page_size', 10); // Default to 10 if no page size is provided
            $query = Skills::query();
            if ($request->has('search')) {
                $search = $request->get('search');
                $query->where(function($query) use ($search) {
                    $query->where('skill', 'LIKE', "%{$search}%");
                });
            }
            $skill = $query->paginate($pageSize);
            if ($request->ajax()) {
                $tableHtml = view('masters.skill._table', compact('skill'))->render();
                $paginationHtml = $skill->links('pagination::bootstrap-5')->render();

                return response()->json([
                    'skill' => $tableHtml,
                    'paginationHtml' => $paginationHtml
                ]);
            }
            return view('masters.skill.index', compact('skill'));
        } catch (Exception $e) {
            return response()->json([
                'error' => 'An error occurred while fetching the user list.',
                'message' => $e->getMessage(),
            ], 500); // Return 500 status code for server errors
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $show = false;
        return view('masters.skill.form',compact('show'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'skill' => 'required|string|max:255',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
    
        try {
            
            Skills::create($request->all());
            return response()->json([
                'message' => 'Skill created successfully!',
            ]);
        } catch (\Exception $e) {
            // Handle errors
            return response()->json(['error' => 'An error occurred, please try again later.'], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $show = true;
        $skill = Skills::findOrFail($id);
        return view('masters.skill.form',compact('skill','show'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            $show = false;
            $skill = Skills::findOrFail($id);
            return view('masters.skill.form',compact('skill','show'));
        } catch (ModelNotFoundException $e) {
            return back()->withErrors(['error' => 'User not found.']);
        } catch (Exception $e) {
            return back()->withErrors(['error' => 'An error occurred while fetching the User for editing: ' . $e->getMessage()]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'skill' => 'required|string|max:255',
        ]);
    
        try {
            $skill = Skills::findOrFail($id);
            $skill->update($request->all());
            return response()->json([
                'message' => 'Skill updated successfully!',
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred, please try again later.'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try { 
            $skill = Skills::findOrFail($id);
            $skill->delete();
            return response()->json([
                'message' => 'Skill deleted successfully!',
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred, please try again later.'], 500);
        }
    }
}
