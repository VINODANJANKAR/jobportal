<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JobPost;
use App\Models\Company;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Exception;
use Carbon\Carbon;


class JobPostController extends Controller
{
    /**
     * Display a listing of the resource. ..
     */
    public function index(Request $request)
    {
        try {
            $pageSize = $request->get('page_size', 10);
            $query = JobPost::query();

            if ($request->has('search') && !empty($request->search)) {
                $search = $request->get('search');
                $query->where('post_title', 'LIKE', "%{$search}%");
            }

            $jobPosts = $query->paginate($pageSize); // Adjust pagination as needed

            if ($request->ajax()) {
                $html = view('jobposts.partials._table', compact('jobPosts'))->render();
                $pagination = $jobPosts->links('pagination::bootstrap-5')->render();
        
                return response()->json([
                    'jobPosts' => $html,
                    'paginationHtml' => $pagination
                ]);
            }

            return view('jobposts.index', compact('jobPosts'));
        } catch (Exception $e) {
            return back()->withErrors(['error' => 'Error fetching job posts: ' . $e->getMessage()]);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $companies = Company::all();
        return view('jobposts.form', compact('companies'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'post_date' => 'required|date',
            'valid_up_to' => 'required|date|after_or_equal:post_date',
            'recruitment_type' => 'required|in:on_pay_roll,on_contract',
            'post_type' => 'required|in:Regular,Image',
            'upload_image' => $request->post_type === 'Image' ? 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048' : 'nullable',
            'post_title' => $request->post_type === 'Regular' ? 'required|string|max:255' : 'nullable|string|max:255',
            'company_id' => $request->post_type === 'Regular' ? 'required|exists:companies,id' : 'nullable|exists:companies,id',
            'position' => $request->post_type === 'Regular' ? 'required|string|max:255' : 'nullable|string|max:255',
            'total_vacancy' => $request->post_type === 'Regular' ? 'required|integer' : 'nullable|integer',
            'male_vacancy' => $request->post_type === 'Regular' ? 'nullable|integer' : 'nullable|integer',
            'female_vacancy' => $request->post_type === 'Regular' ? 'nullable|integer' : 'nullable|integer',
            'educational_qualification' => $request->post_type === 'Regular' ? 'required|string|max:255' : 'nullable|string|max:255',
            'experience' => $request->post_type === 'Regular' ? 'required|string' : 'nullable|string',
            'age' => $request->post_type === 'Regular' ? 'required|string' : 'nullable|string',
            'additional_info' => $request->post_type === 'Regular' ? 'nullable|string' : 'nullable|string',
            'salary' => $request->post_type === 'Regular' ? 'nullable|string' : 'nullable|string',
            'additional_salary_info' => $request->post_type === 'Regular' ? 'nullable|string' : 'nullable|string',
            'ot_rate_hour' => $request->post_type === 'Regular' ? 'required|numeric' : 'nullable|numeric',
            'shift_timing' => $request->post_type === 'Regular' ? 'required|string|max:255' : 'nullable|string|max:255',
            'facilities' => $request->post_type === 'Regular' ? 'nullable|string' : 'nullable|string',
            'required_documents' => $request->post_type === 'Regular' ? 'nullable|string' : 'nullable|string',
            'interview_details' => $request->post_type === 'Regular' ? 'nullable|string' : 'nullable|string',
            'interview_date_time' => $request->post_type === 'Regular' ? 'nullable|date|after_or_equal:post_date|before_or_equal:valid_up_to' : 'nullable|date',
            'contact_details' =>'required|string|max:255',
            'location_id' => $request->post_type === 'Regular' ? 'required|exists:company_locations,id' : 'nullable|exists:company_locations,id',
        ], [
            'valid_up_to.after_or_equal' => 'The Valid Up To date must be equal to or greater than the Post Date.',
            'upload_image.required' => 'The image is required when post type is Image.',
            'upload_image.image' => 'The uploaded file must be an image.',
            'upload_image.mimes' => 'The image must be of type: jpeg, png, jpg, gif, svg.',
            'upload_image.max' => 'The image size must be less than or equal to 2MB.',
            'interview_date_time.after_or_equal' => 'The Interview Date must be equal to or later than the Post Date.',
            'interview_date_time.before_or_equal' => 'The Interview Date must be equal to or earlier than the Valid Up To date.',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        try {
                $photoPath = null;
                if ($request->hasFile('upload_image')) {
                    $photo = $request->file('upload_image');
                    $photoName = Str::random(10) . '.' . $photo->getClientOriginalExtension();
                    $photo->storeAs('public/jobPost', $photoName);
                    $photoPath = $photoName;
                }

                $data = $request->all();
                $postId = $this->generatePostId();
                $data['post_id'] = $postId;
                $data['upload_image'] = $photoPath;

                // Set the status based on the user's role
                $AuthData = auth()->user(); // Get the role of the authenticated user

                if ($AuthData->role === 'admin') {
                    $data['status'] = 'approved'; // Admins get 'approved' status
                } else {
                    $data['status'] = 'pending'; // Regular users get 'pending' status
                }
                $data['post_by_id'] = $AuthData->id;
                // Create the JobPost
                JobPost::create($data);

            return response()->json(['success' => 'Job post created successfully.'. $postId]);
        } catch (Exception $e) {
            \Log::error('Error creating job post: ' . $e);
            return response()->json(['error' => 'An error occurred while creating the job post.'.  $e . $postId], 500);
        }
    }
    


    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $jobPost = JobPost::findOrFail($id);
            return view('jobposts.show', compact('jobPost'));
        } catch (ModelNotFoundException $e) {
            return back()->withErrors(['error' => 'Job post not found.']);
        } catch (Exception $e) {
            return back()->withErrors(['error' => 'An error occurred while fetching the job post: ' . $e->getMessage()]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        try {
            $jobPost = JobPost::findOrFail($id);
            $companies = Company::all();
            return view('jobposts.form', compact('jobPost', 'companies'));
        } catch (ModelNotFoundException $e) {
            return back()->withErrors(['error' => 'Job post not found.']);
        } catch (Exception $e) {
            return back()->withErrors(['error' => 'An error occurred while fetching the job post for editing: ' . $e->getMessage()]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'post_date' => 'required|date',
            'valid_up_to' => 'required|date|after_or_equal:post_date',
            'recruitment_type' => 'required|in:on_pay_roll,on_contract',
            'post_type' => 'required|in:Regular,Image',
            'upload_image' => $request->post_type === 'Image'? ($request->hasFile('upload_image') ? 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048': 'nullable') : 'nullable',
            'post_title' => $request->post_type === 'Regular' ? 'required|string|max:255' : 'nullable|string|max:255',
            'company_id' => $request->post_type === 'Regular' ? 'required|exists:companies,id' : 'nullable|exists:companies,id',
            'position' => $request->post_type === 'Regular' ? 'required|string|max:255' : 'nullable|string|max:255',
            'total_vacancy' => $request->post_type === 'Regular' ? 'required|integer' : 'nullable|integer',
            'male_vacancy' => $request->post_type === 'Regular' ? 'nullable|integer' : 'nullable|integer',
            'female_vacancy' => $request->post_type === 'Regular' ? 'nullable|integer' : 'nullable|integer',
            'educational_qualification' => $request->post_type === 'Regular' ? 'required|string|max:255' : 'nullable|string|max:255',
            'experience' => $request->post_type === 'Regular' ? 'required|string' : 'nullable|string',
            'age' => $request->post_type === 'Regular' ? 'required|string' : 'nullable|string',
            'additional_info' => $request->post_type === 'Regular' ? 'nullable|string' : 'nullable|string',
            'salary' => $request->post_type === 'Regular' ? 'nullable|string' : 'nullable|string',
            'additional_salary_info' => $request->post_type === 'Regular' ? 'nullable|string' : 'nullable|string',
            'ot_rate_hour' => $request->post_type === 'Regular' ? 'required|numeric' : 'nullable|numeric',
            'shift_timing' => $request->post_type === 'Regular' ? 'required|string|max:255' : 'nullable|string|max:255',
            'facilities' => $request->post_type === 'Regular' ? 'nullable|string' : 'nullable|string',
            'required_documents' => $request->post_type === 'Regular' ? 'nullable|string' : 'nullable|string',
            'interview_details' => $request->post_type === 'Regular' ? 'nullable|string' : 'nullable|string',
            'interview_date_time' => $request->post_type === 'Regular' ? 'nullable|date|after_or_equal:post_date|before_or_equal:valid_up_to' : 'nullable|date',
            'contact_details' => 'required|string|max:255',
            'location_id' => $request->post_type === 'Regular' ? 'required|exists:company_locations,id' : 'nullable|exists:company_locations,id',
        ], [
            'valid_up_to.after_or_equal' => 'The Valid Up To date must be equal to or greater than the Post Date.',
            'upload_image.required' => 'The image is required when post type is Image.',
            'upload_image.image' => 'The uploaded file must be an image.',
            'upload_image.mimes' => 'The image must be of type: jpeg, png, jpg, gif, svg.',
            'upload_image.max' => 'The image size must be less than or equal to 2MB.',
            'interview_date_time.after_or_equal' => 'The Interview Date must be equal to or later than the Post Date.',
            'interview_date_time.before_or_equal' => 'The Interview Date must be equal to or earlier than the Valid Up To date.',
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $jobPost = JobPost::findOrFail($id);
            $userRole = auth()->user()->role;
            $jobPost->update($request->except('upload_image', 'status'));

            if ($userRole === 'admin') {
                $jobPost->status = 'approved'; // Admins get 'approved' status
            } else {
                $jobPost->status = 'pending'; // Regular users get 'pending' status
            }

            if ($request->hasFile('upload_image')) {
              
                if ($jobPost->upload_image) {
                    $imageIsUsedElsewhere = JobPost::where('upload_image', $jobPost->upload_image)
                        ->where('id', '!=', $jobPost->id) // Exclude the current jobPost (self)
                        ->exists();
            
                    if (!$imageIsUsedElsewhere) {
                        Storage::delete('public/jobPost/' . $jobPost->upload_image);
                    }
                }
            
                $photo = $request->file('upload_image');
                $photoName = Str::random(20) . '.' . $photo->getClientOriginalExtension();
                $photo->storeAs('public/jobPost', $photoName);
                $jobPost->upload_image = $photoName;
            }
            
            
            $jobPost->update($request->except('upload_image'));
            return response()->json(['success' => 'Job post updated successfully.']);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Job post not found.'], 404);
        } catch (Exception $e) {
            return response()->json(['error' => 'An error occurred while updating the job post.'], 500);
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $jobPost = JobPost::findOrFail($id);
            if ($jobPost->upload_image) {
                $imageIsUsedElsewhere = JobPost::where('upload_image', $jobPost->upload_image)
                    ->where('id', '!=', $jobPost->id) // Exclude the current jobPost (self)
                    ->exists();
        
                if (!$imageIsUsedElsewhere) {
                    Storage::delete('public/jobPost/' . $jobPost->upload_image);
                }
            }
            $jobPost->delete();
            return response()->json([
                'message' => 'Job post deleted successfully.',
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Job post not found.',
                'message' => $e->getMessage(),
            ], 404); // Return 404 status code if the profile is not found
        } catch (Exception $e) {
            return response()->json([
                'error' => 'An error occurred while deleting the  job post.',
                'message' => $e->getMessage(),
            ], 500); // Return 500 status code for server errors
        }
    }

    public function repost($id)
    {
        try {
            $originalPost = JobPost::findOrFail($id);
            $repost = $originalPost->replicate();
            $repost->post_id = $this->generatePostId();
            $repost->is_repost = true;
            $repost->original_post_id = $originalPost->id;
            $repost->post_date = now();
            $repost->repost_date = now();
            $repost->post_by_id = auth()->user()->id;
            $repost->save();
            return redirect()->route('job-posts.edit', $repost->id);
        } catch (ModelNotFoundException $e) {
            return back()->withErrors(['error' => 'Job post not found.']);
        } catch (Exception $e) {
            return back()->withErrors(['error' => 'An error occurred while reposting the job post: ' . $e->getMessage()]);
        }
    }

    public function updateStatus(Request $request, $id)
    {
        try {
            $jobPost = JobPost::findOrFail($id);
            $status = $request->input('status');
    
            if ($status === 'valid') {
                $jobPost->valid_up_to = now()->addDays(30); // Extend the validity
            } else {
                $jobPost->valid_up_to = now()->subDay(); // Set as expired
            }
    
            $jobPost->save();
    
            return response()->json(['success' => 'Status updated successfully.']);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Job post not found.'], 404);
        } catch (Exception $e) {
            return response()->json(['error' => 'An error occurred while updating the status.'], 500);
        }
    }

    public function generatePostId()
    {
        // Get the current month and year
        $currentMonth = Carbon::now()->format('m'); // Format: 02
        $currentYear = Carbon::now()->format('y'); // Format: 25

        // Check if there are any existing job posts for the current month and year
        $lastPost = JobPost::whereYear('created_at', Carbon::now()->year)
                            ->whereMonth('created_at', Carbon::now()->month)
                            ->orderByDesc('created_at')
                            ->first();  // Get the latest post for the current month/year

        // Extract the counter from the last post_id if available
        if ($lastPost) {
            // Extract the last three digits (post number) and increment it
            $counter = (int)substr($lastPost->post_id, -3) + 1;
        } else {
            // If no posts exist, start the counter at 1
            $counter = 1;
        }

        // Format the counter to ensure it is always three digits (e.g., 001, 002)
        $formattedCounter = str_pad($counter, 3, '0', STR_PAD_LEFT);

        // Create the post_id with year and month as prefix
        $postId = $currentYear . $currentMonth . $formattedCounter;

        return $postId;
    }

    public function approve($id)
    {
        $jobPost = JobPost::find($id);
    
        if ($jobPost && $jobPost->status !== 'approved') {
            $jobPost->status = 'approved';
            $jobPost->save();
            
            return response()->json(['success' => true, 'status' => 'approved']);
        }
    
        return response()->json(['success' => false, 'message' => 'Job post is already approved or not found.']);
    }
}