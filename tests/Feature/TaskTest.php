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

    public function test_showNew_function_display_create_task_form(){
        $user = User::factory()->create();
        $this->actingAs($user);

        $project = Project::factory()->create([
            'user_id'=> $user->id,
        ]);

        $response = $this->get(route('new.task',$project));

        $response->assertStatus(200);
        $response->assertViewIs('newtask');
    }

    public function test_showNew_function_redirect_login_for_unauthenticate_user(){
        $user = User::factory()->create();

        $project = Project::factory()->create([
            'user_id'=> $user->id,
        ]);

        $response = $this->get(route('new.task',$project));

        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    public function test_store_function_create_task_successfully_completedAt_not_set_when_pending(){
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
            'completed_at'=>null,
        ]);
    }

    public function test_store_function_create_task_successfully_completedAt_set_when_completed(){
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
            'completed_at'=>now(),
        ]);
    }

    public function test_store_function_requires_validation_when_inputs_are_empty_strings(){
        $user = User::factory()->create();
        $this->actingAs($user);

        $project = Project::factory()->create([
            'user_id'=>$user->id,
        ]);

        $response = $this->post(route('store.task', $project), []);
        $response->assertStatus(302);
        $response->assertSessionHasErrors(['title', 'priority', 'status']); //due date can be null
        $this->assertDatabaseCount('tasks', 0);
    }

    public function test_store_function_requires_validation_when_inputs_are_integer(){
        $user = User::factory()->create();
        $this->actingAs($user);

        $project = Project::factory()->create([
            'user_id'=>$user->id,
        ]);

        $data=[
            'title'=> 123,
            'priority'=> 123,
            'due_date'=> 123,
            'description'=>123,
            'status'=>123
        ];

        $response = $this->post(route('store.task', $project), $data);
        $response->assertStatus(302);
        $response->assertSessionHasErrors(['title', 'due_date', 'description', 'priority', 'status']);
        $this->assertDatabaseCount('tasks', 0);
    }

    public function test_store_function_requires_validation_when_title_exceed_character(){
        $user = User::factory()->create();
        $this->actingAs($user);

        $project = Project::factory()->create([
            'user_id'=>$user->id,
        ]);

        $title= str_repeat('2', 161);

        $data=[
            'title'=> $title,
        ];

        $response = $this->post(route('store.task', $project), $data);
        $response->assertStatus(302);
        $response->assertSessionHasErrors(['title']);
        $this->assertDatabaseCount('tasks', 0);
    }

    public function test_store_function_requires_validation_when_due_date_is_pass_date(){
        $user = User::factory()->create();
        $this->actingAs($user);

        $project = Project::factory()->create([
            'user_id'=>$user->id,
        ]);

        $data=[
            'due_date'=> now()->subDays(2),
        ];

        $response = $this->post(route('store.task', $project), $data);
        $response->assertStatus(302);
        $response->assertSessionHasErrors(['due_date']);
        $this->assertDatabaseCount('tasks', 0);
    }

    public function test_store_function_create_task_successfully_when_due_date_is_today(){
        $user = User::factory()->create();
        $this->actingAs($user);

        $project = Project::factory()->create([
            'user_id'=>$user->id,
        ]);

        $title= str_repeat('1', 160);
        $data=[
            'title'=>$title,
            'priority'=>'low',
            'due_date'=>now(),
            'description'=>'test',
            'status'=>'pending',
            'project_id'=>$project->id,
        ];

        $response = $this->post(route('store.task', $project), $data);
        $response->assertStatus(302);
        $response->assertSessionHas('success', 'Task created successfully!');
        $this->assertDatabaseHas('tasks',[
            'title'=>$title,
            'priority'=>'low',
            'due_date'=>now()->toDateTimeString(),
            'description'=>'test',
            'status'=>'pending',
            'project_id'=>$project->id,
        ]);
    }

    public function test_store_function_redirect_login_for_unauthenticate_user(){
        $user = User::factory()->create();

        $project = Project::factory()->create([
            'user_id'=>$user->id,
        ]);

        $response = $this->post(route('store.task', $project), []);
        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    public function test_showEdit_function_display_edit_task_form(){
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

    public function test_showEdit_function_redirect_login_for_unauthenticate_user(){
        $user = User::factory()->create();

        $project = Project::factory()->create([
            'user_id'=>$user->id,
        ]);

        $task = Task::factory()->create([
            'project_id'=>$project->id,
        ]);

        $response = $this->get(route('show.edit.task', [$project, $task]));
        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    public function test_showEdit_function_not_found(){
        $user = User::factory()->create();
        $anotherUser = User::factory()->create();
        $this->actingAs($user);

        $project = Project::factory()->create([
            'user_id'=>$user->id,
        ]);

        $anotherproject = Project::factory()->create([
            'user_id'=>$anotherUser->id,
        ]);

        $task = Task::factory()->create([
            'project_id'=>$project->id,
        ]);

        $anothertask = Task::factory()->create([
            'project_id'=>$anotherproject->id,
        ]);

        $response = $this->get(route('show.edit.task', [$project, $anothertask]));
        $response->assertStatus(404);
    }

    public function test_edit_function_edit_task_successfully_completedAt_not_set_when_pending_to_pending(){
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
            'completed_at'=> null,
        ]);
    }

    public function test_edit_function_edit_task_successfully_completedAt_not_set_when_completed_to_pending(){
        $user = User::factory()->create();
        $this->actingAs($user);

        $project = Project::factory()->create([
            'user_id'=>$user->id,
        ]);

        $task = Task::factory()->create([
            'project_id'=>$project->id,
            'status'=> 'completed',
            'completed_at'=>now(),
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
            'completed_at'=> null,
        ]);
    }

    public function test_edit_function_edit_task_successfully_completedAt_set_when_completed_to_completed(){
        $user = User::factory()->create();
        $this->actingAs($user);

        $project = Project::factory()->create([
            'user_id'=>$user->id,
        ]);

        $task = Task::factory()->create([
            'project_id'=>$project->id,
            'status'=> 'completed',
            'completed_at'=>now(),
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
            'completed_at'=> now()->toDateTimeString(),
        ]);
    }

    public function test_edit_function_edit_task_successfully_completedAt_set_when_pending_to_completed(){
        $user = User::factory()->create();
        $this->actingAs($user);

        $project = Project::factory()->create([
            'user_id'=>$user->id,
        ]);

        $task = Task::factory()->create([
            'project_id'=>$project->id,
            'status'=> 'pending',
            'completed_at'=>null,
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
            'completed_at'=> now()->toDateTimeString(),
        ]);
    }

    public function test_edit_function_edit_task_successfully_completedAt_set_when_due_date_is_today(){
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
            'due_date'=> now(),
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
            'due_date'=> now(),
            'description'=>null,
            'status'=>'completed',
            'project_id'=>$project->id,
            'completed_at'=> now(),
        ]);
    }

    public function test_edit_function_requires_validation_when_inputs_are_empty_strings(){
        $user = User::Factory()->create();
        $this->actingAs($user);

        $project = Project::Factory()->create([
            'user_id'=> $user->id,
        ]);

        $task = Task::factory()->create([
            'project_id'=>$project->id,
            'title'=>'store function',
            'priority'=>'pending',
            'due_date'=>null,
            'description'=>'description',
            'status'=> 'low',
        ]);

        $response = $this->put(route('edit.task', [$project, $task]), []);
        $response->assertStatus(302);
        $response->assertSessionHasErrors(['title', 'priority', 'status']);
        
        $this->assertDatabaseHas('tasks', [
            'title'=>'store function',
            'priority'=>'pending',
            'due_date'=>null,
            'description'=>'description',
            'status'=> 'low',
            'project_id'=>$project->id,
        ]);
    }

    public function test_edit_function_requires_validation_when_inputs_are_ineteger(){
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
            'title'=>12,
            'priority'=>12,
            'due_date'=>123,
            'description'=>12,
            'status'=> 12,
            'project_id'=>$project->id,
        ];

        $response = $this->put(route('edit.task', [$project, $task]), $data);
        $response->assertStatus(302);
        $response->assertSessionHasErrors(['title', 'priority', 'due_date', 'status', 'description']);
        
        $this->assertDatabaseMissing('tasks',[
            'title'=>12,
            'priority'=>12,
            'due_date'=> 123,
            'description'=>12,
            'status'=> 12,
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

    public function test_edit_function_requires_validation_when_title_reach_max_characters(){
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

        $title = str_repeat('1', 161);

        $data = [
            'title'=> $title,
            'priority'=>'low',
            'due_date'=> now(),
            'description'=>'',
            'status'=> 'pending',
            'project_id'=>$project->id,
        ];

        $response = $this->put(route('edit.task', [$project, $task]), $data);
        $response->assertStatus(302);
        $response->assertSessionHasErrors(['title']);
        
        $this->assertDatabaseMissing('tasks',[
            'title'=> $title,
            'priority'=>'low',
            'due_date'=> now(),
            'description'=> null,
            'status'=> 'pending',
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

    public function test_edit_function_requires_validation_when_due_date_is_pass(){
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
            'title'=>'tell',
            'priority'=>'high',
            'due_date'=>'2025-01-31',
            'description'=>'',
            'status'=> 'completed',
            'project_id'=>$project->id,
        ];

        $response = $this->put(route('edit.task', [$project, $task]), $data);
        $response->assertStatus(302);
        $response->assertSessionHasErrors(['due_date']);
        
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

    public function test_edit_function_redirect_login_for_unauthenticate_user(){
        $user = User::Factory()->create();

        $project = Project::Factory()->create([
            'user_id'=> $user->id,
        ]);

        $task = Task::Factory()->create([
            'project_id'=>$project->id,
        ]);

        $response = $this->put(route('edit.task', [$project, $task]), []);
        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    public function test_edit_function_no_task_found(){
        $user = User::Factory()->create();
        $anotheruser = User::Factory()->create();
        $this->actingAs($user);

        $project = Project::Factory()->create([
            'user_id'=> $user->id,
        ]);

        $anotherproject = Project::Factory()->create([
            'user_id'=> $anotheruser->id,
        ]);

        $anothertask = Task::Factory()->create([
            'project_id'=>$anotherproject->id,
        ]);

        $data = [
            'title'=>'edit',
            'priority'=>'high',
            'due_date'=>now(),
            'description'=>'',
            'status' => 'pending',
        ];

        $response = $this->put(route('edit.task', [$project, $anothertask]), $data);
        $response->assertStatus(404);
    }

    public function test_delete_function_delete_task_successfully(){
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

    public function test_delete_function_not_found(){
        $user = User::factory()->create();
        $anotherUser = User::factory()->create();
        $this->actingAs($user);

        $project = Project::factory()->create([
            'user_id'=> $user->id,
        ]);

        $anotherproject = Project::factory()->create([
            'user_id'=> $anotherUser->id,
        ]);

        $anothertask = Task::factory()->create([
            'project_id'=>$anotherproject->id,
        ]);

        $response = $this->delete(route('delete.task', [$project, $anothertask]));
        $response->assertStatus(404);
        $this->assertDatabaseCount('tasks', 1);
    }

    public function test_delete_function_redirect_login_for_unauthenticate_user(){
        $user = User::factory()->create();


        $project = Project::factory()->create([
            'user_id'=> $user->id,
        ]);

        $task = Task::factory()->create([
            'project_id'=>$project->id,
        ]);

        $response = $this->delete(route('delete.task', [$project, $task]));
        $response->assertStatus(302);
        $response->assertRedirect('/login');
        $this->assertDatabaseCount('tasks', 1);
    }
}
