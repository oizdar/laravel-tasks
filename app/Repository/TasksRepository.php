<?php

namespace App\Repository;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Jobs\ProcessMailOnTaskCompleted;
use App\Models\Task;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Exceptions\HttpResponseException;

class TasksRepository
{
    public function storeTask(StoreTaskRequest $taskRequest): Task
    {
        $task = new Task($taskRequest->validated());
        $task->user_id = auth()->user()->id ?? 1;

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

        if($task->completed) {
            ProcessMailOnTaskCompleted::dispatch($task);
        }

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

    /**
     * @return Collection<int, Task>
     */
    public function getTasksForReminding()
    {
        $tasks = Task::where('due_date', '<=', new \DateTime('+2 months'))
            ->where('completed', false)
            ->where('reminded', false);

        return $tasks->get();
    }
}
