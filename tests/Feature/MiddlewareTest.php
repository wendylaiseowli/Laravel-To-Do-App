<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Project;

class MiddlewareTest extends TestCase
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

    public function test_user_can_access_own_project(){
        $user = User::factory()->create();
        $this->actingAs($user);

        $project = Project::factory()->create([
            'user_id'=>$user->id,
        ]);

        $response= $this->get(route('show.project', $project));
        $response->assertStatus(200);
        $response->assertViewIs('project');
    }

    public function test_user_cannot_access_others_project(){
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $this->actingAs($user);

        $project = Project::factory()->create([
            'user_id'=>$otherUser->id,
        ]);

        $response= $this->get(route('show.project', $project));
        $response->assertStatus(403);
        $response->assertSeeText('You are not the owner of this project you cant access it!');
    }
}
