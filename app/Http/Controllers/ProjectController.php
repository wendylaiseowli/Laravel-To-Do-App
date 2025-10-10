<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ProjectRequest;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;


class ProjectController extends Controller
{
    public function show(Request $request, Project $project)
    {
        $query = $project->tasks(); // start with the relationship query

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('due_date')) {
            $today = now()->toDateString();

            switch ($request->due_date) {
                case 'due_today':
                    $query->whereDate('due_date', $today);
                    break;

                case 'this_week':
                    $query->whereBetween('due_date', [
                        now()->startOfWeek()->toDateString(),
                        now()->endOfWeek()->toDateString(),
                    ]);
                    break;

                case 'over_due':
                    $query->whereDate('due_date', '<', $today);
                    break;
            }
        }

        $tasks = $query->get();

        return view('project', compact('project', 'tasks'));
    }
    
    public function showNew(){
        return view('newproject');
    }

    public function create(ProjectRequest $request){
        // dd($request->validated());
        $validated= $request->validated();
        $validated['user_id'] = Auth::id();
        Project::create($validated);
        return redirect('/dashboard')->with('success', 'Project created successfully!');
    }

    public function showEdit(Project $project){
        return view('editproject', compact('project'));
    }

    public function edit(ProjectRequest $request, Project $project){
        $project->update($request->validated());
        return redirect('/dashboard')->with('success', 'Project updated successfully!');;
    }

    public function delete(Project $project){
        $project->delete();
        $task= $project->tasks();
        $task->delete();
        return redirect('/dashboard')->with('success', 'Project deleted successfully!');
    }
}
