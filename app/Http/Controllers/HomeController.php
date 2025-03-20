<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Profiles;
use App\Models\Jobs;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $days = $request->input('time_period', 7); // If no input, default to 7 days
        $totalProfiles = Profiles::count();
        $totalJobPosts = Jobs::count();
        $newProfiles = Profiles::where('created_at', '>=', now()->subDays($days))->count();
        $newJobPosts = Jobs::where('created_at', '>=', now()->subDays($days))->count();
        $activeJobPosts = Jobs::where('valid_up_to', '>=', now())->count();
        $expiredJobPosts = Jobs::where('valid_up_to', '<', now())->count();
        if ($request->ajax()) {
            return response()->json([
                'totalProfiles' => $totalProfiles,
                'totalJobPosts' => $totalJobPosts,
                'newJobPosts' => $newJobPosts,
                'newProfiles' => $newProfiles,
                'activeJobPosts' => $activeJobPosts,
                'expiredJobPosts' => $expiredJobPosts,
                'days' => $days
            ]);
        }
    
        return view('home', compact(
            'totalProfiles','totalJobPosts', 'newJobPosts', 'newProfiles','activeJobPosts', 'expiredJobPosts', 'days'
        ));
    }
    

}
