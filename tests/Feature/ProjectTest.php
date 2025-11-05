<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Project;
use App\Models\Task;
use Carbon\Carbon;

class ProjectTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_show_all_projects_and_thisWeek_tasks(){
        Carbon::setTestNow(Carbon::parse('2025-11-03')); // Monday fixed date

        $user = User::factory()->create();
        $this->actingAs($user);

        $project = Project::factory()->create([
            'user_id'=>$user->id,
        ]);

        $taskThisWeek = Task::factory()->create([
            'project_id'=>$project->id,
            'status'=>'pending',
            'due_date'=>now()->addDays(1)->toDateString(), //in this week
        ]);

        $taskFutureWeek = Task::factory()->create([
            'project_id'=>$project->id,
            'status'=>'pending',
            'due_date'=>now()->addWeeks(2)->toDateString(), //not in this week
        ]);

        $taskOverdue = Task::factory()->create([
            'project_id'=>$project->id,
            'status'=>'pending',
            'due_date'=>now()->subDays(2)->toDateString(), //overdue
        ]);

        $taskToday = Task::factory()->create([
            'project_id'=>$project->id,
            'status'=>'completed',
            'due_date'=>now()->toDateString(), //today
        ]);

        $response = $this->get(route('show.project', $project) . '?status=pending&due_date=this_week');
        $response->assertStatus(200);
        $response->assertViewIs('project');

        // Verify only the thisWeek task is shown
        $response->assertViewHas('tasks', function ($tasks) use ($taskThisWeek) {
            return $tasks->contains($taskThisWeek)
                && $tasks->count() === 1;
        });
    }

    public function test_show_all_projects_and_today_tasks(){
        Carbon::setTestNow(Carbon::parse('2025-11-03')); // Monday fixed date

        $user = User::factory()->create();
        $this->actingAs($user);

        $project = Project::factory()->create([
            'user_id'=>$user->id,
        ]);

        $taskThisWeek = Task::factory()->create([
            'project_id'=>$project->id,
            'status'=>'pending',
            'due_date'=>now()->addDays(1)->toDateString(), //in this week
        ]);

        $taskFutureWeek = Task::factory()->create([
            'project_id'=>$project->id,
            'status'=>'pending',
            'due_date'=>now()->addWeeks(2)->toDateString(), //not in this week
        ]);

        $taskOverdue = Task::factory()->create([
            'project_id'=>$project->id,
            'status'=>'pending',
            'due_date'=>now()->subDays(2)->toDateString(), //overdue
        ]);

        $taskToday = Task::factory()->create([
            'project_id'=>$project->id,
            'status'=>'completed',
            'due_date'=>now()->toDateString(), //today
        ]);

        $response = $this->get(route('show.project', $project) . '?status=completed&due_date=due_today');
        $response->assertStatus(200);
        $response->assertViewIs('project');

        // Verify only the thisWeek task is shown
        $response->assertViewHas('tasks', function ($tasks) use ($taskToday) {
            return $tasks->contains($taskToday)
                && $tasks->count() === 1;
        });
    }

    public function test_show_all_projects_and_overDue_tasks(){
        Carbon::setTestNow(Carbon::parse('2025-11-03')); // Monday fixed date

        $user = User::factory()->create();
        $this->actingAs($user);

        $project = Project::factory()->create([
            'user_id'=>$user->id,
        ]);

        $taskThisWeek = Task::factory()->create([
            'project_id'=>$project->id,
            'status'=>'pending',
            'due_date'=>now()->addDays(1)->toDateString(), //in this week
        ]);

        $taskFutureWeek = Task::factory()->create([
            'project_id'=>$project->id,
            'status'=>'pending',
            'due_date'=>now()->addWeeks(2)->toDateString(), //not in this week
        ]);

        $taskOverdue = Task::factory()->create([
            'project_id'=>$project->id,
            'status'=>'pending',
            'due_date'=>now()->subDays(2)->toDateString(), //overdue
        ]);

        $taskToday = Task::factory()->create([
            'project_id'=>$project->id,
            'status'=>'completed',
            'due_date'=>now()->toDateString(), //today
        ]);

        $response = $this->get(route('show.project', $project) . '?status=pending&due_date=over_due');
        $response->assertStatus(200);
        $response->assertViewIs('project');

        // Verify only the thisWeek task is shown
        $response->assertViewHas('tasks', function ($tasks) use ($taskOverdue) {
            return $tasks->contains($taskOverdue)
                && $tasks->count() === 1;
        });
    }

    public function test_display_create_project_form()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('new.project'));

        $response->assertStatus(200);
        $response->assertViewIs('newproject');
    }

    public function test_create_project_successfully(){
        $user = User::factory()->create();
        $this->actingAs($user);

        $data =[
            'project_name' => 'test project',
            'description' => 'test project description',
        ];

        $response = $this->post(route('create.project'), $data);

        $response->assertRedirect('/dashboard');
        $response->assertSessionHas('success', 'Project created successfully!');
        
        $this->assertDatabaseHas('projects', [
            'project_name' => 'test project',
            'description' => 'test project description',
            'user_id' => $user->id,
        ]);
    }

    public function test_requires_validation_when_create_a_project(){
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post(route('create.project'), []);
        $response->assertStatus(302);
        $response->assertSessionHasErrors(['project_name']);

        $this->assertDatabaseCount('projects',0); //no record will be created
    }

    public function test_display_edit_project_form(){
        $user = User::factory()->create();
        $this->actingAs($user);

        $project = Project::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->get(route('show.edit.project', $project));
        $response->assertStatus(200);
        $response->assertViewIs('editproject');
    }

    public function test_edit_project_successully(){
        $user = User::factory()->create();
        $this->actingAs($user);

        $project = Project::factory()->create([
            'user_id'=> $user->id,
        ]);

        $data = [
            'project_name' =>'testing project 1',
            'description' =>'testing project description',
        ];

        $response = $this->put(route('edit.project', $project), $data);
        
        $response->assertRedirect('/dashboard');
        $response->assertSessionHas('success', 'Project updated successfully!');

        $this->assertDatabaseHas('projects',[
            'id'=> $project->id,
            'project_name' =>'testing project 1',
            'description' =>'testing project description',
        ]);
    }

    public function test_requires_validation_when_edit_a_project(){
        $user = User::factory()->create();
        $this->actingAs($user);

        $data =[
            'project_name'=>'',
            'description'=>'testing description',
        ];

        $project = Project::factory()->create([
            'user_id'=>$user->id,
            'project_name'=>'test project',
            'description'=> 'project description'
        ]);

        $response = $this->put(route('edit.project', $project), $data);
        $response->assertStatus(302);
        $response->assertSessionHasErrors('project_name');
        
        $this->assertDatabaseMissing('projects',[
            'project_name'=>'',
        ]);
        $this->assertDatabaseHas('projects',[
            'project_name'=>'test project',
            'description'=> 'project description',
        ]);
    }

    public function test_delete_project_successfully(){
        $user = User::factory()->create();
        $this->actingAs($user);

        $project = Project::factory()->create([
            'user_id'=>$user->id,
        ]);

        $project = Task::factory()->create([
            'project_id'=>$project->id,
        ]);

        $response = $this->delete(route('delete.project', $project->id));
        $response->assertRedirect('/dashboard');
        $response->assertSessionHas('success', 'Project deleted successfully!');
        $this->assertDatabaseCount('projects', 0);
        $this->assertDatabaseCount('tasks', 0);
    }

    public function test_user_has_many_project_relationship(){
        $user = User::factory()->create();
        $this->actingAs($user);

        $project1 = Project::factory()->create([
            'user_id'=>$user->id,
        ]);

        $project2 = Project::factory()->create([
            'user_id'=>$user->id,
        ]);

        $response = $this->get('/dashboard', [$project1, $project2]);
        $response->assertStatus(200);
    }
}
