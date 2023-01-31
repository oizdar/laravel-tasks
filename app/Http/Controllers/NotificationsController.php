<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessTaskReminder;
use App\Repository\TasksRepository;
use Illuminate\Http\Response;
use OpenApi\Attributes as OA;

class NotificationsController extends Controller
{
    public function __construct(private readonly TasksRepository $taskRepository)
    {
    }

    /**
     * @return Response
     */
    #[OA\Post(
        path: '/send-notifications/tasks',
        operationId: 'send-task-notifications',
        summary: 'Prepare and sends all notifications',
        tags: ['Notifications'],
        responses: [
            new OA\Response(
                response: '204',
                description: 'successful operation',
            )
        ]
    )]
    public function sendNotifications()
    {
        $tasks = $this->taskRepository->getTasksForReminding();
        foreach ($tasks as $task) {
            ProcessTaskReminder::dispatch($task);
        }

        return response()->noContent();
    }
}
