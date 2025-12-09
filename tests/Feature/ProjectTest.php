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
    public function test_show_function_show_all_projects_and_thisWeek_pending_tasks(){
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

    public function test_show_function_show_all_projects_and_today_completed_tasks(){
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

    public function test_show_function_show_all_projects_and_overDue_pending_tasks(){
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

    public function test_show_function_when_there_is_no_task_for_that_project(){
        Carbon::setTestNow(Carbon::parse('2025-11-03')); // Monday fixed date

        $user = User::factory()->create();
        $this->actingAs($user);

        $project = Project::factory()->create([
            'user_id'=>$user->id,
        ]);

        $response = $this->get(route('show.project', $project));
        $response->assertStatus(200);
        $response->assertViewIs('project');

        // Verify only the thisWeek task is shown
        $response->assertViewHas('tasks', function ($tasks) {
            return $tasks->count() === 0;
        });
    }
    public function test_showNew_function_display_create_project_form()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('new.project'));

        $response->assertStatus(200);
        $response->assertViewIs('newproject');
    }

    public function test_showNew_function_redirect_login_for_unauthenticate_user()
    {
        $response = $this->get(route('new.project'));

        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    public function test_create_function_create_project_successfully(){
        $user = User::factory()->create();
        $this->actingAs($user);

        $data =[
            'project_name' => 'test project',
            'description' => '',
        ];

        $response = $this->post(route('create.project'), $data);

        $response->assertRedirect('/dashboard');
        $response->assertSessionHas('success', 'Project created successfully!');
        
        $this->assertDatabaseHas('projects', [
            'project_name' => 'test project',
            'description' => null,
            'user_id' => $user->id,
        ]);
    }

    public function test_create_function_requires_validation_when_empty_input(){
        $user = User::factory()->create();
        $this->actingAs($user);

        $data=[
            'project_name' => '',
            'description' => null,
        ];

        $response = $this->post(route('create.project'), []);
        $response->assertStatus(302);
        $response->assertSessionHasErrors(['project_name']);

        $this->assertDatabaseCount('projects',0); //no record will be created
    }

    public function test_create_function_requires_validation_when_inputs_are_not_string(){
        $user = User::factory()->create();
        $this->actingAs($user);

        $data=[
            'project_name'=>123,
            'description' => 123,
        ];

        $response = $this->post(route('create.project'), $data);
        $response->assertStatus(302);
        $response->assertSessionHasErrors(['project_name', 'description']);

        $this->assertDatabaseCount('projects',0); //no record will be created
    }

    public function test_create_function_requires_validation_when_inputs_exceed_max_characters(){
        $user = User::factory()->create();
        $this->actingAs($user);

        $name = str_repeat('q', 121);
        $description = str_repeat('d',256);

        $data=[
            'project_name'=>$name,
            'description' => $description,
        ];

        $response = $this->post(route('create.project'), $data);
        $response->assertStatus(302);
        $response->assertSessionHasErrors(['project_name', 'description']);

        $this->assertDatabaseCount('projects',0); //no record will be created
    }

    public function test_create_function_redirect_login_for_unauthenticate_user()
    {
        $response = $this->get(route('new.project'));

        $data =[
            'project_name' => 'test project',
            'description' => 'test project description',
        ];

        $response = $this->post(route('create.project'), $data);
        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    public function test_showEdit_function_display_edit_project_form(){
        $user = User::factory()->create();
        $this->actingAs($user);

        $project = Project::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->get(route('show.edit.project', $project));
        $response->assertStatus(200);
        $response->assertViewIs('editproject');
    }

    public function test_showEdit_redirect_login_for_unauthenticate_user(){
        $user = User::factory()->create();
        $project = Project::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->get(route('show.edit.project', $project));
        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    public function test_edit_function_edit_project_successully(){
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

    public function test_edit_function_requires_validation_when_inputs_are_null(){
        $user = User::factory()->create();
        $this->actingAs($user);

        $data =[
            'project_name'=>null,
            'description'=>null,
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
            'project_name'=>null,
        ]);
        $this->assertDatabaseHas('projects',[
            'project_name'=>'test project',
            'description'=> 'project description',
        ]);
    }

    public function test_edit_function_requires_validation_when_inputs_are_not_string(){
        $user = User::factory()->create();
        $this->actingAs($user);

        $data =[
            'project_name'=>125,
            'description'=>126,
        ];

        $project = Project::factory()->create([
            'user_id'=>$user->id,
            'project_name'=>'test project',
            'description'=> 'project description'
        ]);

        $response = $this->put(route('edit.project', $project), $data);
        $response->assertStatus(302);
        $response->assertSessionHasErrors(['project_name', 'description']);
        
        $this->assertDatabaseMissing('projects',[
            'project_name'=>125,
            'description'=>126,
        ]);
        $this->assertDatabaseHas('projects',[
            'project_name'=>'test project',
            'description'=> 'project description',
        ]);
    }

    public function test_edit_function_requires_validation_when_inputs_exceed_max_character(){
        $user = User::factory()->create();
        $this->actingAs($user);

        $name = str_repeat('a', 121);
        $description = str_repeat('2', 256);
        
        $data =[
            'project_name'=>$name,
            'description'=>$description,
        ];

        $project = Project::factory()->create([
            'user_id'=>$user->id,
            'project_name'=>'test project',
            'description'=> 'project description'
        ]);

        $response = $this->put(route('edit.project', $project), $data);
        $response->assertStatus(302);
        $response->assertSessionHasErrors(['project_name', 'description']);
        
        $this->assertDatabaseMissing('projects',[
            'project_name'=>$name,
            'description'=>$description,
        ]);
        $this->assertDatabaseHas('projects',[
            'project_name'=>'test project',
            'description'=> 'project description',
        ]);
    }

    public function test_edit_function_redirect_login_for_unauthenticate_user(){
        $user = User::factory()->create();
        
        $data =[
            'project_name'=>'aa',
            'description'=>'bb',
        ];

        $project = Project::factory()->create([
            'user_id'=>$user->id,
            'project_name'=>'test project',
            'description'=> 'project description'
        ]);

        $response = $this->put(route('edit.project', $project), $data);
        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    public function test_delete_function_delete_project_successfully(){
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

    public function test_delete_function_user_has_many_project_relationship(){
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

    public function test_delete_function_redirect_login_for_unauthenticate_user   (){
        $user = User::factory()->create();

        $project1 = Project::factory()->create([
            'user_id'=>$user->id,
        ]);

        $response = $this->get('/dashboard', [$project1]);
        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }
}
