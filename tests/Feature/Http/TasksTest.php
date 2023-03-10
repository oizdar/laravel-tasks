<?php

namespace Tests\Feature\Http;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use Tests\TestCase;

class TasksTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Passport::actingAs(
            User::factory()->create(),
        );
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_tasks_resource_response()
    {
        Task::factory(10)->create();

        $response = $this->get('/api/tasks');

        $response->assertStatus(200);
        $responseBody = json_decode($response->getContent());
        $this->assertObjectHasAttribute('data', $responseBody);
        $this->assertObjectHasAttribute('links', $responseBody);
        $this->assertObjectHasAttribute('meta', $responseBody);
        $this->assertCount(10, $responseBody->data);
    }

    public function test_tasks_resource_paggination()
    {
        Task::factory(40)->create();
        $response = $this->get('/api/tasks');

        $response->assertStatus(200);
        $responseBody = json_decode($response->getContent());

        $this->assertCount(25, $responseBody->data);
        $this->assertObjectHasAttribute('data', $responseBody);
        $this->assertObjectHasAttribute('id', $responseBody->data[0]);
        $this->assertObjectHasAttribute('title', $responseBody->data[0]);
        $this->assertObjectHasAttribute('due_date', $responseBody->data[0]);
        $this->assertObjectHasAttribute('completed', $responseBody->data[0]);
        $this->assertObjectNotHasAttribute('activities', $responseBody->data[0]);

        $response = $this->get('/api/tasks?page=2');
        $responseBody = json_decode($response->getContent());
        $this->assertCount(15, $responseBody->data);
    }

    public function test_tasks_store()
    {
        $response = $this->postJson('/api/tasks/', [
            'title' => fake()->words(asText: true),
            'description' => fake()->text(500),
        ]);

        $response->assertStatus(201);
        $responseBody = json_decode($response->getContent());

        $this->assertObjectHasAttribute('data', $responseBody);
        $this->assertObjectHasAttribute('id', $responseBody->data);
        $this->assertObjectHasAttribute('title', $responseBody->data);
        $this->assertObjectHasAttribute('due_date', $responseBody->data);
        $this->assertObjectHasAttribute('completed', $responseBody->data);
        $this->assertObjectHasAttribute('activities', $responseBody->data);
        $this->assertFalse($responseBody->data->completed);
    }

    public function test_tasks_store_validation()
    {
        $response = $this->postJson('/api/tasks/', []);

        $response->assertStatus(400);
        $responseBody = json_decode($response->getContent());

        $this->assertObjectHasAttribute('message', $responseBody);
        $this->assertObjectHasAttribute('data', $responseBody);

        $this->assertObjectHasAttribute('title', $responseBody->data);
        $this->assertStringContainsString('The title field is required', $responseBody->data->title[0]);
    }

    public function test_tasks_store_delete()
    {
        $task = Task::factory()->create(['completed' => false]);

        $response = $this->delete('/api/tasks/' . $task->id);
        $response->assertStatus(204);

        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }

    public function test_tasks_patch_impossible_when_completed()
    {
        User::factory(5)->create();
        $task = Task::factory()->notCompleted()->create();

        $response = $this->patchJson('/api/tasks/'. $task->id, ['completed' => true]);

        $response->assertStatus(200);
        $responseBody = json_decode($response->getContent());

        $this->assertObjectHasAttribute('data', $responseBody);
        $this->assertObjectHasAttribute('title', $responseBody->data);
//        $this->assertObjectHasAttribute('description', $responseBody->data);
        $this->assertObjectHasAttribute('due_date', $responseBody->data);
        $this->assertObjectHasAttribute('completed', $responseBody->data);
        $this->assertTrue($responseBody->data->completed);

        $response = $this->patchJson('/api/tasks/'. $task->id, ['title' => fake()->words(asText: true)]);
        $response->assertStatus(400);
        $responseBody = json_decode($response->getContent());

        $this->assertObjectHasAttribute('message', $responseBody);
        $this->assertStringContainsString('Cannot update completed task', $responseBody->message);

        $response = $this->delete('/api/tasks/'. $task->id);
        $response->assertStatus(400);
        $responseBody = json_decode($response->getContent());

        $this->assertObjectHasAttribute('message', $responseBody);
        $this->assertStringContainsString('Cannot delete completed task', $responseBody->message);
    }
}
