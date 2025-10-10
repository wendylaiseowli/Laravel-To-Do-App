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

    // public function show(Project $project, Task $task){
    //     return view('task', compact('project', 'task'));
    // }

    public function showEdit(Project $project, Task $task){
        return view('edittask', compact('project', 'task'));
    }

    public function edit(Taskrequest $request, Project $project, Task $task){
        $validated= $request->validated();
        if($validated['status']==='completed'){
            $validated['completed_at']=now();
        }else{
            $validated['completed_at']=null;
        }
        $task->update($validated);
        return redirect()->route('show.project', $project->id);
    }

    public function delete(Project $project, Task $task){
        $task->delete();
        return redirect()->route('show.project', $project->id);
    }
}
