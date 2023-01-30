<?php

namespace App\Repository;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Task;
use Illuminate\Http\Exceptions\HttpResponseException;

class TaskRepository
{
    public function storeTask(StoreTaskRequest $taskRequest): Task
    {
        $task = new Task();
        $task->create($taskRequest->validated());

        return $task;
    }

    public function updateTask(UpdateTaskRequest $taskRequest, int $taskId): Task
    {
        /** @var Task $task */
        $task = Task::findOrFail($taskId);

        if($task->completed) {
            throw new HttpResponseException(response()->json([
                'message'   => 'Cannot update completed task',
            ])->setStatusCode(400));
        }

        $task->fill($taskRequest->validated())->save();
        return $task;
    }

    public function deleteTask(int $taskId): void
    {
        /** @var Task $task */
        $task = Task::findOrFail($taskId);

        if($task->completed) {
            throw new HttpResponseException(response()->json([
                'message'   => 'Cannot delete completed task',
            ])->setStatusCode(400));
        }

        $task->delete();
    }
}
