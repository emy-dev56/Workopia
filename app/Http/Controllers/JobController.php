<?php

namespace App\Http\Controllers;

use App\Models\Job;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class JobController extends Controller
{
    use AuthorizesRequests;
    //@desc Show all job listings
    //@route GET /jobs
    public function index(): View
    {
        $jobs = Job::latest()->paginate(9);

        return view('jobs.index', compact('jobs'));
    }

    //@desc Show create new job form
    //@route GET /jobs/create
    public function create(): View
    {
        return view('jobs.create');
    }

    //@desc save new job listing
    //@route POST /jobs
    public function store(Request $request): RedirectResponse
    {
        $validatedData = $request->validate([
            "title" => "required|string|max:255",
            "description" => "required|string",
            "job_type" => "required|string",
            "remote" => "required|boolean",
            "requirements" => "required|string",
            "benefits" => "required|string",
            "tags" => "required|string",
            "address" => "nullable|string",
            "city" => "required|string",
            "state" => "required|string",
            "zipcode" => "nullable|string",
            "salary" => "required|integer",
            "company_name" => "required|string",
            "company_description" => "nullable|string",
            "company_website" => "nullable|string",
            "contact_phone" => "nullable|string",
            "contact_email" => "required|email",
            "company_logo" => "nullable|file|mimes:jpeg,jpg,png,gif",
        ]);

        $validatedData["user_id"] = Auth::user()->id;

        if ($request->has('company_logo')) {
            $path = $request->file('company_logo')->store('logos', 'public');
            $validatedData['company_logo'] = $path;
        }

        Job::create($validatedData);

        return redirect()->route('jobs.index')->with('success', 'Job created successfully!');
    }

    //@desc Show single job listing
    //@route GET /jobs/{$id}
    public function show(Job $job): View
    {
        return view("jobs.show")->with("job", $job);
    }

    //@desc Show form editing a job listing
    //@route GET /jobs/{$id}/edit
    public function edit(Job $job): View
    {
        $this->authorize('update', $job);
        return view('jobs.edit')->with('job', $job);
    }

    //@desc update a job listing
    //@route PUT /jobs/{$id}
    public function update(Request $request, Job $job): RedirectResponse
    {
        $this->authorize('update', $job);
        $validatedData = $request->validate([
            "title" => "required|string|max:255",
            "description" => "required|string",
            "job_type" => "required|string",
            "remote" => "required|boolean",
            "requirements" => "required|string",
            "benefits" => "required|string",
            "tags" => "required|string",
            "address" => "nullable|string",
            "city" => "required|string",
            "state" => "required|string",
            "zipcode" => "nullable|string",
            "salary" => "required|integer",
            "company_name" => "required|string",
            "company_description" => "nullable|string",
            "company_website" => "nullable|string",
            "contact_phone" => "nullable|string",
            "contact_email" => "required|email",
            "company_logo" => "nullable|file|mimes:jpeg,jpg,png,gif",
        ]);

        if ($request->has('company_logo')) {
            Storage::delete('public/logos/' . $job->company_logo);
            $path = $request->file('company_logo')->store('logos', 'public');
            $validatedData['company_logo'] = $path;
        }

        $job->update($validatedData);

        return redirect()->route('jobs.index')->with('success', 'Job updated successfully!');
    }

    //@desc delete a job listing
    //@route DELETE /jobs/{$id}
    public function destroy(Job $job): RedirectResponse
    {
        $this->authorize('delete', $job);
        if ($job->company_logo) {
            Storage::delete('public/logos/' . $job->company_logo);
        }
        $job->delete();
        if (request()->query('from') === 'dashboard') {
            return redirect()->route('dashboard')->with('success', 'Job deleted successfully!');
        }
        return redirect()->route('jobs.index')->with('success', 'Job deleted successfully!');
    }

    //@desc search jobs
    //@route GET /jobs/search
    public function search(Request $request): View
    {
        $keyword = strtolower($request->input('keyword'));
        $location = strtolower($request->input('location'));

        $query = Job::query();

        if ($keyword) {
            $query->whereRaw('LOWER(title) LIKE ?', ["%$keyword%"])
                ->orWhereRaw('LOWER(description) LIKE ?', ["%$keyword%"])
                ->orWhereRaw('LOWER(tags) LIKE ?', ["%$keyword%"]);
        }

        if ($location) {
            $query->whereRaw('LOWER(address) LIKE ?', ["%$location%"])
                ->orWhereRaw('LOWER(city) LIKE ?', ["%$location%"])
                ->orWhereRaw('LOWER(state) LIKE ?', ["%$location%"])
                ->orWhereRaw('LOWER(zipcode) LIKE ?', ["%$location%"]);
        }

        $jobs = $query->paginate(12);

        return view('jobs.index', compact('jobs'));
    }
}
