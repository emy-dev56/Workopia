<?php

namespace App\Http\Controllers;

use App\Mail\JobApplied;
use App\Models\Applicant;
use App\Models\Job;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class ApplicantController extends Controller
{
    //@desc apply for a job
    //@route POST /jobs/{job}/apply
    public function store(Request $request, Job $job): RedirectResponse
    {
        $exists = Applicant::where('job_id', $job->id)->where('user_id', Auth::id())->exists();
        if ($exists) {
            return redirect()->back()->with('error', 'You have already applied for this job');
        }
        $validatedData = $request->validate([
            'full_name' => 'required|string|max:255',
            'contact_phone' => 'string|max:255',
            'contact_email' => 'required|string|email|max:255',
            'message' => 'string',
            'location' => 'string',
            'resume' => 'required|file|mimes:pdf|max:2048',
        ]);



        if ($request->hasFile('resume')) {
            $resumePath = $request->file('resume')->store('resumes', 'public');
            $validatedData['resume_path'] = $resumePath;
        }

        $applicant = new Applicant($validatedData);
        $applicant->user_id = Auth::id();
        $applicant->job_id = $job->id;

        $applicant->save();

        Mail::to($job->user->email)->send(new JobApplied($applicant, $job));

        return redirect()->back()->with('success', 'Application Submitted');
    }

    public function destroy(Applicant $applicant): RedirectResponse
    {
        $applicant->delete();
        return redirect()->route('dashboard')->with('success', 'Applicant Deleted');
    }
}
