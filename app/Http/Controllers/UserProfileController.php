<?php

namespace App\Http\Controllers;

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
use Illuminate\Support\Facades\Log;


class UserProfileController extends Controller
{
    //
    public function show(Request $request)
    {
        $show = true;
        $profileId = $request->get('id');
        $profile = DB::table('profiles')
        ->leftJoin('skills', 'profiles.skill_id', '=', 'skills.id')
        ->select('profiles.*', 'skills.skill')
        ->get();

        return response()->json($profile, 200); // 200 is the HTTP status code
    }


    public function store(Request $request)
    {
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
            'password'          => 'required|string|min:8',
            'current_location'  => 'nullable|string|max:255',
            'passing_year'  => 'nullable|string|max:255',

        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        try {
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

    public function getJobListing(Request $request){
        #get Data from Job Table
        try {
            if ($request->has('limit')) {
                $limit = $request->input('limit');
                $jobs = Jobs::with('companies')
                            ->limit($limit)
                            ->get();
            } else {
                $perPage = $request->get('per_page', 10); // Default to 10 items per page
                $jobs = Jobs::with('companies')
                            ->paginate($perPage)
                            ->items(); // Use items() to get only the data
            }
            return response()->json($jobs, 200);
            
        } catch (\Exception $e) {
            Log::error('An error occurred', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
        
            // Return a meaningful response
            return response()->json([
                'success' => false,
                'error' => 'Something went wrong!',
                'details' => $e->getMessage(),
                'line' => $e->getLine(),
            ], 500);
        }
        
    }

    public function getJobDetails(Request $request){
        try {
            //code...

            $job = Jobs::with('companies')
                      ->find($request->id);

            // Return job details
            if(empty($job))
            {
                return response()->json([
                    'status' => true,
                    'data' => $job,
                    'message' => 'No Data Found',
                    'code' => 200,
                ], 200);
            }
            return response()->json([
                'status' => true,
                'data' => $job,
                'message' => 'Job details fetched successfully',
                'code' => 200,
            ], 200);
        } catch (\Exception $e) {
            Log::error('An error occurred', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
        
            // Return a meaningful response
            return response()->json([
                'success' => false,
                'error' => 'Something went wrong!',
                'details' => $e->getMessage(),
                'line' => $e->getLine(),
            ], 500);
        }
    }

    public function userRegister(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'mobile' => 'required|digits:10|unique:users,mobile',
            'password' => 'required|string|min:8|confirmed', // 'confirmed' checks password_confirmation
            'designation' => 'required|string|max:255',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
    
        try {
            // Create and store the user
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->mobile = $request->mobile;
            $user->password = Hash::make($request->password); // Store hashed password
            $user->designation = $request->designation;
            $user->save();
    
            // Return success response
            return response()->json([
                'message' => 'User created successfully!',
                'status' => true,
                'code' => 200
            ]);
        } catch (\Exception $e) {
            // Handle errors
            return response()->json(['error' => 'An error occurred, please try again later.'], 500);
        }
    }
}
