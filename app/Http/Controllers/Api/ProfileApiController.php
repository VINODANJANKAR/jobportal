<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Profiles;
use App\Models\Skills;
use App\Models\Experiences;
use App\Models\Qualifications;
use App\Models\Jobs;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Response;
use Illuminate\Database\Eloquent\ModelNotFoundException;


class ProfileApiController extends Controller
{
    public function show(Request $request)
    {
        try {
            $profileId = $request->get('id');

            $query = DB::table('profiles')
                ->leftJoin('skills', 'profiles.skill_id', '=', 'skills.id')
                ->select('profiles.*', 'skills.skill');

            if (!empty($profileId)) {
                $query->where('profiles.id', $profileId); // Add condition if $profileId is not empty
            }

            $profile = $query->get(); // Fetch the results
            return response()->json($profile, 200); // 200 is the HTTP status code
        } catch (\Exception $e) {
            \Log::error('Error creating profile: ' . $e->getMessage());
            return response()->json(['error' => 'An error occurred while creating the profile.'], 500);
        }
    }


    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'first_name'        => 'required|string|max:255',
                'last_name'         => 'required|string|max:255',
                'gender'            => 'required|in:male,female,other',
                'mobile_number'     => 'required|string|unique:profiles,mobile_number|max:15',
                'aadhar_card_no'    => 'nullable|string|unique:profiles,aadhar_card_no|max:12',
                'address'           => 'nullable|string|max:255',
                'city'              => 'nullable|string|max:255',
                'state'             => 'nullable|string|max:255',
                'pin_code'          => 'nullable|string|max:6',
                'skill_id'          => 'nullable|string|exists:skills,id',
                'qualification_id'  => 'nullable|string|exists:qualifications,id',
                'experience_id'     => 'nullable|string|exists:experiences,id',
                'current_salary'    => 'nullable|numeric|min:0',
                'photo'             => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'cv'                => 'nullable|file|mimes:pdf,doc,docx|max:5120',
                'password'          => 'nullable|string|min:8',
                'current_location'  => 'nullable|string|max:255',
                'passing_year'  => 'nullable|string|max:255',

            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            // Handle file upload for photo (if available)
            $photoPath = null;
            if ($request->hasFile('photo')) {
                $photo = $request->file('photo');
                $photoName = Str::random(10) . '.' . $photo->getClientOriginalExtension();
                $photo->storeAs('public/photos', $photoName);
                $photoPath = $photoName; // Store only filename
            }

            // Handle file upload for CV (if available)
            $cvPath = null;
            if ($request->hasFile('cv')) {
                $cv = $request->file('cv');
                $cvName = Str::random(10) . '.' . $cv->getClientOriginalExtension();
                $cv->storeAs('public/cv', $cvName);
                $cvPath = $cvName; // Store only filename
            }

            // Get all request data
            $data = $request->all();
            $data['photo'] = $photoPath;
            $data['cv'] = $cvPath;
            $data['password'] = Hash::make($request->password); // Hash password
            $profile = Profiles::create($data);

            return response()->json([
                'message' => 'Profile created successfully!',
                'profile' => $profile,
            ], 201);
        } catch (Exception $e) {
            \Log::error('Error creating profile: ' . $e->getMessage());
            return response()->json(['error' => 'An error occurred while creating the profile.'], 500);
        }
    }
}
