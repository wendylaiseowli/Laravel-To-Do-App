<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Project;
use App\Models\Task;

class TaskTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    // public function test_example(): void
    // {
    //     $response = $this->get('/');

    //     $response->assertStatus(200);
    // }

    public function test_display_create_task_form(){
        $user = User::factory()->create();
        $this->actingAs($user);

        $project = Project::factory()->create([
            'user_id'=> $user->id,
        ]);

        $response = $this->get(route('new.task',$project));

        $response->assertStatus(200);
        $response->assertViewIs('newtask');
    }

    public function test_create_task_successfully_completedAt_not_set(){
        $user = User::factory()->create();
        $this->actingAs($user);

        $project= Project::factory()->create([
            'user_id'=> $user->id,
        ]);

        $data =[
            'title'=>'CRUD function',
            'priority'=>'low',
            'due_date'=>null,
            'description'=>null,
            'status'=>'pending',
            'project_id'=>$project->id,
        ];

        $response= $this->post(route('store.task', $project), $data);
        $response->assertRedirect(route('show.project', $project));
        $response->assertSessionHas('success', 'Task created successfully!');
        
        $this->assertDatabaseHas('tasks',[
            'title'=>'CRUD function',
            'priority'=>'low',
            'due_date'=>null,
            'description'=>null,
            'status'=>'pending',
            'project_id'=>$project->id,
        ]);
    }

    public function test_create_task_successfully_completedAt_set(){
        $user = User::factory()->create();
        $this->actingAs($user);

        $project= Project::factory()->create([
            'user_id'=> $user->id,
        ]);

        $data =[
            'title'=>'CRUD function',
            'priority'=>'low',
            'due_date'=>null,
            'description'=>null,
            'status'=>'completed',
            'project_id'=>$project->id,
        ];

        $response= $this->post(route('store.task', $project), $data);
        $response->assertRedirect(route('show.project', $project));
        $response->assertSessionHas('success', 'Task created successfully!');
        
        $this->assertDatabaseHas('tasks',[
            'title'=>'CRUD function',
            'priority'=>'low',
            'due_date'=>null,
            'description'=>null,
            'status'=>'completed',
            'project_id'=>$project->id,
        ]);
    }

    public function test_requires_validation_when_create_a_task(){
        $user = User::factory()->create();
        $this->actingAs($user);

        $project = Project::factory()->create([
            'user_id'=>$user->id,
        ]);

        $data=[
            'title'=>'',
            'priority'=>'',
            'due_date'=>'2024-01-31',
            'description'=>'',
            'status'=> '',
        ];

        $response = $this->post(route('store.task', $project), $data);
        $response->assertStatus(302);
        $response->assertSessionHasErrors(['title', 'priority', 'due_date', 'status']);
        $this->assertDatabaseCount('tasks', 0);
    }

    public function test_display_edit_task_form(){
        $user = User::factory()->create();
        $this->actingAs($user);

        $project = Project::factory()->create([
            'user_id'=>$user->id,
        ]);

        $task = Task::factory()->create([
            'project_id'=>$project->id,
        ]);

        $response = $this->get(route('show.edit.task', [$project, $task]));
        $response->assertStatus(200);
        $response->assertViewIs('edittask');
    }

    public function test_edit_task_successfully_completedAt_not_set(){
        $user = User::factory()->create();
        $this->actingAs($user);

        $project = Project::factory()->create([
            'user_id'=>$user->id,
        ]);

        $task = Task::factory()->create([
            'project_id'=>$project->id,
        ]);

        $data=[
            'title'=>'automated test',
            'priority'=>'low',
            'due_date'=>null,
            'description'=>null,
            'status'=>'pending',
            'project_id'=>$project->id,
        ];

        $response = $this->put(route('edit.task', [$project, $task]), $data);
        $response->assertStatus(302);
        $response->assertRedirect(route('show.project', $project));
        $response->assertSessionHas('success', 'Task edited successfully!');
        
        $this->assertDatabaseHas('tasks',[
            'title'=>'automated test',
            'priority'=>'low',
            'due_date'=>null,
            'description'=>null,
            'status'=>'pending',
            'project_id'=>$project->id,
        ]);
    }

    public function test_edit_task_successfully_completedAt_set(){
        $user = User::factory()->create();
        $this->actingAs($user);

        $project = Project::factory()->create([
            'user_id'=>$user->id,
        ]);

        $task = Task::factory()->create([
            'project_id'=>$project->id,
        ]);

        $data=[
            'title'=>'automated test',
            'priority'=>'low',
            'due_date'=>null,
            'description'=>null,
            'status'=>'completed',
            'project_id'=>$project->id,
        ];

        $response = $this->put(route('edit.task', [$project, $task]), $data);
        $response->assertStatus(302);
        $response->assertRedirect(route('show.project', $project));
        $response->assertSessionHas('success', 'Task edited successfully!');
        
        $this->assertDatabaseHas('tasks',[
            'title'=>'automated test',
            'priority'=>'low',
            'due_date'=>null,
            'description'=>null,
            'status'=>'completed',
            'project_id'=>$project->id,
        ]);
    }

    public function test_requires_validation_when_edit_a_task(){
        $user = User::Factory()->create();
        $this->actingAs($user);

        $project = Project::Factory()->create([
            'user_id'=> $user->id,
        ]);

        $task = Task::Factory()->create([
            'title'=>'store function',
            'priority'=>'pending',
            'due_date'=>null,
            'description'=>'description',
            'status'=> 'low',
            'project_id'=>$project->id,
        ]);

        $data = [
            'title'=>'',
            'priority'=>'in progress',
            'due_date'=>'2025-01-31',
            'description'=>'',
            'status'=> 'none',
            'project_id'=>$project->id,
        ];

        $response = $this->put(route('edit.task', [$project, $task]), $data);
        $response->assertStatus(302);
        $response->assertSessionHasErrors(['title', 'priority', 'due_date', "status"]);
        
        $this->assertDatabaseMissing('tasks',[
            'title'=>'',
            'priority'=>'in progress',
            'due_date'=>'2025-01-31',
            'description'=>'',
            'status'=> 'none',
            'project_id'=>$project->id,
        ]);
        
        $this->assertDatabaseHas('tasks', [
            'title'=>'store function',
            'priority'=>'pending',
            'due_date'=>null,
            'description'=>'description',
            'status'=> 'low',
            'project_id'=>$project->id,
        ]);
    }

    public function test_delete_task_successfully(){
        $user = User::factory()->create();
        $this->actingAs($user);

        $project = Project::factory()->create([
            'user_id'=> $user->id,
        ]);

        $task = Task::factory()->create([
            'project_id'=>$project->id,
        ]);

        $response = $this->delete(route('delete.task', [$project, $task]));
        $response->assertRedirect(route('show.project', $project));
        $response->assertSessionHas('success', 'Task deleted successfully!');
        $this->assertDatabaseCount('tasks', 0);
    }
}
