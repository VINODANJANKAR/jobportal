<?php

namespace App\Http\Controllers;

use App\Models\ProfileOfCandidate;
use Illuminate\Http\Request;

class ProfileOfCandidateController extends Controller
{
    // Display a listing of the profiles
    public function index()
    {
        $profiles = ProfileOfCandidate::all();
        return view('profiles.index', compact('profiles'));
    }

    // Show the form for creating a new profile
    public function create()
    {
        return view('profiles.create');
    }

    // Store a newly created profile in the database
    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'gender' => 'required|in:male,female,other',
            'mobile_number' => 'required|unique:profile_of_candidates,mobile_number',
            'aadhar_card_no' => 'required|unique:profile_of_candidates,aadhar_card_no',
            'city' => 'required|string',
            'state' => 'required|string',
            'pin_code' => 'required|string',
            'education' => 'required|string',
            'work_experience' => 'nullable|string',
            'current_salary' => 'required|numeric',
            'photo' => 'nullable|image',
            'skills' => 'nullable|string',
            'cv' => 'nullable|file',
            'password' => 'required|string|min:8',
            'location' => 'nullable|string',
        ]);

        ProfileOfCandidate::create($request->all());

        return redirect()->route('profiles.index')->with('success', 'Profile created successfully!');
    }

    // Display the specified profile
    public function show(ProfileOfCandidate $profile)
    {
        return view('profiles.show', compact('profile'));
    }

    // Show the form for editing the specified profile
    public function edit(ProfileOfCandidate $profile)
    {
        return view('profiles.edit', compact('profile'));
    }

    // Update the specified profile in the database
    public function update(Request $request, ProfileOfCandidate $profile)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'gender' => 'required|in:male,female,other',
            'mobile_number' => 'required|unique:profile_of_candidates,mobile_number,' . $profile->id,
            'aadhar_card_no' => 'required|unique:profile_of_candidates,aadhar_card_no,' . $profile->id,
            'city' => 'required|string',
            'state' => 'required|string',
            'pin_code' => 'required|string',
            'education' => 'required|string',
            'work_experience' => 'nullable|string',
            'current_salary' => 'required|numeric',
            'photo' => 'nullable|image',
            'skills' => 'nullable|string',
            'cv' => 'nullable|file',
            'password' => 'nullable|string|min:8',
            'location' => 'nullable|string',
        ]);

        $profile->update($request->all());

        return redirect()->route('profiles.index')->with('success', 'Profile updated successfully!');
    }

    // Remove the specified profile from the database
    public function destroy(ProfileOfCandidate $profile)
    {
        $profile->delete();

        return redirect()->route('profiles.index')->with('success', 'Profile deleted successfully!');
    }
}
