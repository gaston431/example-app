<?php

namespace App\Http\Controllers;

use App\Mail\JobPosted;
use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class JobController extends Controller
{
    /* public function __construct()
    {
        $this->middleware('auth');
        //$this->middleware('log')->only('index');
        //$this->middleware('subscribed')->except('store');
    } */

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //$jobs = Job::all(); // lazy loading
        //$jobs = Job::with('employer')->get(); // eager loading
        $jobs = Job::with('employer')->latest()->simplePaginate(3);

        return view('jobs.index', [
            'jobs' => $jobs
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('jobs.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //validate
        request()->validate([
            'title' => ['required','string','min:3'],
            'salary' => ['required']
        ]);

        //Job::create(request()->all());
        $job = Job::create([
            'title' => request('title'),
            'salary' => request('salary'),
            'employer_id' => 1,
        ]);

        /* $job = new Job;
        $job->title = request('title');
        $job->salary = request('salary');
        $job->employer_id = 1;
        $job->save(); */

        Mail::to($job->employer->user)->queue(
            new JobPosted($job)
        );

        return redirect('jobs');
    }

    /**
     * Display the specified resource.
     */
    public function show(Job $job)
    {
        /* $job = Arr::first($jobs, function ($job) use ($id){
            return $job['id'] == $id;  
        }); */

        /* $job = Arr::first(Job::all(), fn($job) => $job['id'] == $id); */
        
        //$job = Job::find($id);

        return view('jobs.show', [
            'job' => $job
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Job $job)
    {
        //$job = Job::findOrFail($id);

        return view('jobs.edit', [
            'job' => $job
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Job $job)
    {
        // Gate::authorize('edit-job', $job);

        //validate
        request()->validate([
            'title' => ['required', 'min:3'],
            'salary' => ['required']
        ]);
        //authorize

        //update
        //$job = Job::findOrFail($id);

        $job->update([
            'title' => request('title'),
            'salary' => request('salary'),
        ]);

        //redirect
        return redirect('/jobs');
        //return redirect('/jobs/' . $job->id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Job $job)
    {
        // authorize (On hold...)
        // Gate::authorize('edit-job', $job);

        $job->delete();

        return redirect('/jobs');
    }
}
