<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Task;
use App\Http\Requests\TaskRequest;

class TaskController extends Controller
{
    public function showNew(Project $project){
        return view('newtask', compact('project'));
    }

    public function store(TaskRequest $task, Project $project){
        $validated = $task->validated();
        $validated['project_id']= $project->id;
        if($validated['status']==='completed'){
            $validated['completed_at']=now();
        }else{
            $validated['completed_at']=null;
        }
        Task::create($validated);
        return redirect()->route('show.project', $project->id)->with('success', 'Task created successfully!');
    }

    public function showEdit(Project $project, Task $task){
        if($project->id != $task->project_id){
            abort(404, 'Not Found');
        }

        return view('edittask', compact('project', 'task'));
    }

    public function edit(Taskrequest $request, Project $project, Task $task){
        if($project->id != $task->project_id){
            abort(404, 'Not Found');
        }

        $validated= $request->validated();
        if($validated['status']==='completed'){
            $validated['completed_at']=now();
        }else{
            $validated['completed_at']=null;
        }
        $task->update($validated);
        return redirect()->route('show.project', $project->id)->with('success', 'Task edited successfully!');
    }

    public function delete(Project $project, Task $task){
        if($project->id != $task->project_id){
            abort(404, 'Not Found');
        }
        
        $task->delete();
        return redirect()->route('show.project', $project->id)->with('success', 'Task deleted successfully!');
    }
}
