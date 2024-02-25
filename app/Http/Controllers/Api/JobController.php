<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\ApplyJobModel;
use App\Models\JobModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Validator;

class JobController extends Controller
{
    public function index(){
        $data = JobModel::where('created_by',auth()->user()->id)->get();
        return response()->json([
            'result' => $data,
            'status' => true,
            'message' => 'Jobs List',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
            $input = $request->all();
            $validator =  Validator::make($input,[
                'title' => 'required',
                'company_name' => 'required',
                'location' => 'required',
                'description' => 'required',
                'application_instruments' => 'required'
            ]);
            if($validator->fails()){
                return $this->sendError($validator->errors());
            }
            $data = JobModel::Create([
                'title' => $input['title'],
                'company_name' => $input['company_name'],
                'location' => $input['location'],
                'description' => $input['description'],
                'application_instruments' => $input['application_instruments'],
                'created_by' => auth()->user()->id,
            ]);
            return response()->json([
                'result' => $data,
                'status' => true,
                'message' => 'Created Successfully',
            ]);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
            $input = $request->all();
            $validator =  Validator::make($input,[
                'title' => 'required',
                'company_name' => 'required',
                'location' => 'required',
                'description' => 'required',
                'application_instruments' => 'required'
            ]);
            if($validator->fails()){
                return $this->sendError($validator->errors());
            }
            $data = JobModel::find($id);
               if(auth()->user()->id === $data->created_by ){
                $data->update([
                    'title' => $input['title'],
                    'company_name' => $input['company_name'],
                    'location' => $input['location'],
                    'description' => $input['description'],
                    'application_instruments' => $input['application_instruments'],
                ]);
                return response()->json([
                    'status' => true,
                    'message' => 'Updated Successfully',
                ]);
               }
               else{
                return response()->json([
                    'status' => false,
                    'message' => 'Only Creator Can update their list',
                ], 400);
               }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $job = JobModel::find($id);
        if (!$job) {
            return response()->json(['status' => false, 'message' => 'Job not found'], 404);
        }
        $job->delete();
        return response()->json(['status' => true, 'message' => 'Job soft deleted']);
    }
    public function search(Request $request){
        $keyword = $request->input('keyword');
        $location = $request->input('location');
        $company_name = $request->input('company_name');

        $jobs = JobModel::where('title', 'like', "%$keyword%")
                        ->orWhere('location','like',"%$location%")
                        ->orWhere('company_name','like',"%$company_name%")
            ->get();
            return response()->json([
                'result' => $jobs,
                'status' => true,
                'message' => 'Search results for jobs',
            ]);
    }
        public function applyForJob(Request $request){
            $input = $request->all();
            $validator =  Validator::make($input,[
                'user_id' => 'required|exists:users,id',
                'job_id' => 'required|exists:job_models,id',
                'resume' => 'required',
                'cover_letter' => 'required',
            ]);
            if($validator->fails()){
                return $this->sendError($validator->errors());
            }
            $jobApply = ApplyJobModel::create([
                'user_id' => $request->user_id,
                'job_id' => $request->job_id,
                'resumes' => $request->resume,
                'cover_letter' => $request->cover_letter,
            ]);
            return response()->json([
                'result' => $jobApply,
                'status' => true,
                'message' => 'Job application submitted successfully',
            ]);
        }

        public function Joblisting(){
            $data = JobModel::get();
            return response()->json([
                'result' => $data,
                'status' => true,
                'message' => 'Jobs List',
            ]);
        }

        public function sendError($message) {
            $message = $message->all();
            $response['error'] = "validation_error";
            $response['message'] = implode('',$message);
            $response['status'] = "0";
            return response()->json($response, 400);
        }
}
