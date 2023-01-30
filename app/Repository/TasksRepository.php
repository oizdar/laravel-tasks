<?php

namespace App\Repository;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Task;
use App\Models\User;
use App\Notifications\TaskReminder;
use Illuminate\Http\Exceptions\HttpResponseException;

class TasksRepository
{
    public function storeTask(StoreTaskRequest $taskRequest): Task
    {
        $task = new Task($taskRequest->validated());
        $task->save();

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

    public function sendTaskNotifications()
    {
        $tasks = Task::where('due_date', '<=', new \DateTime('tommorow'))
            ->where('completed', false);

        foreach($tasks as $task) {
            if($task->due_date <= today()->setTimezone($task->user->timezone ?? 'utc')) {
                $task->user->notify(new TaskReminder($task))->locale($task->user->locale);
            }
        }
    }
}
