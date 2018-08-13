<?php

namespace Tests\Feature;

use App\Task;
use App\User;
use Tests\TestCase;

class TaskControllerTest extends TestCase
{
    protected $user;

    public function setUp()
    {
        parent::setUp();
        $this->user = factory(User::class)->create();
        $token = \JWTAuth::fromUser($this->user);
        $this->defaultHeaders = [
            'HTTP_Authorization' => 'Bearer ' . $token,
            'HTTP_Accept' => 'application/json',
            'HTTP_Content-Type' => 'application/json',
        ];
    }

    /**
     * @test
     * @group tasks
     */
    public function getTask()
    {
        $task = factory(Task::class)->create([
            'user_id' => $this->user->id,
        ]);
        $this->get('api/tasks/' . $task->id)->assertJson([
            'data' => $task->toArray(),
        ]);
    }

    /**
     * @test
     * @group tasks
     */
    public function getTasks()
    {
        $tasks = factory(Task::class, 5)->create([
            'user_id' => $this->user->id,
        ]);
        $this->get('api/tasks')->assertJson([
            'data' => $tasks->toArray(),
        ]);
    }

    /**
     * @test
     * @group tasks
     */
    public function storeTask()
    {
        $this
            ->postJson('api/tasks', [
                'content' => str_random(191),
            ])
            ->assertSuccessful();
    }

    /**
     * @test
     * @group tasks
     */
    public function updateTask()
    {
        $task = factory(Task::class)->create([
            'user_id' => $this->user->id,
        ]);
        $content = str_random(191);
        $this
            ->putJson('api/tasks/' . $task->id, [
                'content' => $content,
            ])
            ->assertSuccessful();
        $updatedTask = Task::find($task->id);
        $this->assertTrue($updatedTask->content === $content);
    }

    /**
     * @test
     * @group tasks
     */
    public function deleteTask()
    {
        $task = factory(Task::class)->create([
            'user_id' => $this->user->id,
        ]);
        $this->delete('api/tasks/' . $task->id)
            ->assertSuccessful();
        $this->assertTrue(Task::where('id', $task->id)->get()->count() === 0);
    }
}
