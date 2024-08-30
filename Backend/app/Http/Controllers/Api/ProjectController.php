<?php

namespace App\Http\Controllers\Api;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProjectResource;
use App\Http\Controllers\Api\BaseController;

class ProjectController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $projects = Project::all();
        return $this->sendResponse(['Projects'=> ProjectResource::collection($projects)], "Projects retrived successfuly");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            "name"=> ["required", "string"],
             "description"=> ["nullable", "string"],
        ]);

        if($validator->fails()){
          return $this->sendError("Validation Errors.", $validator->errors());
        }

        $project = new Project();
        $project->name = $request->input('name');
        $project->description = $request->input('description');
        $project->save();

        if($request->has('users')){
            $user_id = $request->input('users');
            $project->users()->attach($user_id);
        }
 
        return $this->response(["Project"=> new ProjectResource($project)], "Project Created Successfuly");

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        $project = Project::find($id);

        if(!$project){
            return $this->sendError("Project not found", ['error'=>'Project not found']);
        }
         $project->load('users');
        return $this->sendResponse(["Project"=> new ProjectResource($project)], "project retrived successfully");

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            "name"=> ["required", "string"],
             "description"=> ["nullable", "string"],
        ]);

        if($validator->fails()){
          return $this->sendError("Validation Errors.", $validator->errors());
        }

        $project = Project::find($id);
        $project->name = $request->input('name');
        $project->description = $request->input('description');
        $project->save();

        if($request->has('users')){
            $user_id = $request->input('users');
            $project->users()->sync($user_id);
        }
        else{
            $project->users()->detach();
        }

        return $this->sendResponse(['Project'=> new ProjectResource($project), "project retrived successfully"]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        $project = Project::find($id);
        $project->delete();

        return $this->sendResponse([], "project deleted successfully");
    }
}
