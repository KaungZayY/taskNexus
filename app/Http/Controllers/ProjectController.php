<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectDeleteRequest;
use App\Http\Requests\ProjectRequest;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function create()
    {
        return view('projects.create-project');
    }

    public function store(ProjectRequest $request)
    {
        $validated = $request->validated();
        try {
            Project::create([
                'project_name' => $validated['project_name'],
                'description' => $validated['description'],
                'start_date' => $validated['start_date'],
                'end_date' => $validated['end_date'],
                'created_by' => auth()->id(),
            ]);
            
            return redirect()->route('dashboard')->banner('New project created successfully.');

        } 
        catch (\Exception $e) 
        {
            return redirect()->route('dashboard')->dangerBanner('An Error Occured');
        }
    }

    public function edit(Project $project)
    {
        return view('projects.edit-project',compact('project'));
    }

    public function update(ProjectRequest $request, Project $project)
    {
        try{
            $project->update($request->validated());
            return redirect()->route('dashboard')->banner('Project Detail Updated.');
        }
        catch(\Exception $e){
            return redirect()->route('dashboard')->dangerBanner('Cannot Update the Project Detail');
        }
    }

    public function delete(Project $project)
    {
        return view('projects.delete-project',compact('project'));
    }

    public function destroy(ProjectDeleteRequest $request, Project $project)
    {
        $validated = $request->validated();
        $project_name = $validated['project_name'];
        if($project_name != $project->project_name){
            return redirect()->route('projects.delete',$project)->withErrors([
                'project_name' => 'The project name does not match.'
            ]);
        }
        
        try {
            $project->delete();
            return redirect()->route('dashboard')->banner('Project Deleted');
        } catch (\Exception $e) {
            return redirect()->route('dashboard')->dangerBanner('Cannot Delete the Project');
        }
    }

    public function detail(Project $project)
    {
        return view('projects.details-project',compact('project'));
    }
}
