<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProjectResource;
use App\JsonResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Project;

class ProjectController extends Controller
{
    use JsonResponseTrait;
    //show the projects by year and major
    public function Get_All() {
        $projects = Project::all();
        return ProjectResource::collection($projects);
        
    }
    public function show_projects(Request $request){
        $validator = Validator::make($request->all(), [
            'year_id' => 'required|between:1,5',
            'major_id' => 'required_if:year_id,4,5'
        ]);

        if ($validator->fails()) {
            return $this->JsonResponseWithData("Validation failed", $validator->errors(), 400);
        }

        if ($request->year_id >= 4) {
            // For years 4 and 5, show projects by major
            $projects = Project::where('year_id', $request->year_id)
                             ->where('major_id', $request->major_id)
                             ->get();
                if(!$projects){
                    return $this->JsonResponseWithData("No projects found", null, 404);
                }
            return $this->JsonResponseWithData("Projects for year {$request->year_id} and major {$request->major_id}", $projects, 200);
        } else {
            // For years 1 and 3, show all projects for that year
            $projects = Project::where('year_id', $request->year_id)->get();
            if(!$projects){
                return $this->JsonResponseWithData("No projects found", null, 404);
            }
            return $this->JsonResponseWithData("Projects for year {$request->year_id}", $projects, 200);
        }
    }

    // add the project by admin base on the year and major
    public function add_project(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'year_id' => 'required|between:1,5',
            'major_id' => 'required_if:year_id,4,5'
        ]);

        if ($validator->fails()) {
            return $this->JsonResponseWithData("Validation failed", $validator->errors(), 400);
        }

        $project = Project::create([
            'name' => $request->name,
            'description' => $request->description,
            'year_id' => $request->year_id,
            'major_id' => $request->major_id
        ]);

        return $this->JsonResponse("Project added successfully", 200);
    }
    // delete the project by admin
    public function delete_project($project_id){
        $project = Project::where('id',$project_id);

        if(!$project){
            return $this->JsonResponse("Project not found", 404);
        }

        $project->delete();
        return $this->JsonResponse("Project deleted successfully", 200);
    }



}
