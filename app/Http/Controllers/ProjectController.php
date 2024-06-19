<?php

namespace App\Http\Controllers;

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
            $project = Project::create([
                'project_name' => $validated['project_name'],
                'description' => $validated['description'],
                'start_date' => $validated['start_date'],
                'end_date' => $validated['end_date'],
                'created_by' => auth()->id(),
            ]);
    
            if (!$project) 
            {
                return redirect()->route('dashboard')->warningBanner('Cannot create new project, try again later');
            } else 
            {
                return redirect()->route('dashboard')->banner('New project created successfully.');
            }
        } catch (\Exception $e) 
        {
            return redirect()->route('dashboard')->dangerBanner($e);
        }
    }
}
