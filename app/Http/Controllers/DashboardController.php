<?php

namespace App\Http\Controllers;

use App\Models\Job;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    //@desc Show dashboard
    //@route GET /dashboard
    public function index(): View
    {
        $user = Auth::user();
        $jobs = Job::where("user_id", $user->id)->with('applicants')->get();
        return view("dashboard.index", compact('user', 'jobs'));
    }
}
