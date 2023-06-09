<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\Job\StoreJobRequest;
use App\Http\Requests\API\Job\UpdateJobRequest;
use App\Http\Resources\Jobs\JobResource;
use App\Models\Jobs;
use App\Traits\HttpResponses;
use Illuminate\Database\QueryException;

class JobsController extends Controller
{
    use HttpResponses;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $jobs = Jobs::all();
        return JobResource::collection($jobs);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreJobRequest $request)
    {
        $job = Jobs::create($request->validated());
        if(!$job){
            return $this->errorResponse('Something went wrong', 500);
        }
        return new JobResource($job);
    }

    /**
     * Display the specified resource.
     */
    public function show(Jobs $job)
    {
        return new JobResource($job);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateJobRequest $request, Jobs $job)
    {
        $job->update($request->validated());
        return new JobResource($job);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Jobs $job)
    {
        try{
            $job->destroy($job->id);
            return response(null, 204);
        }
        catch(QueryException $e){
            return $this->errorResponse('Cant delete a job that has one or more employees', 500);
        }
    }
}
