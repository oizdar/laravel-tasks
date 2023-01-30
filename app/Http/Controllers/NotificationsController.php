<?php

namespace App\Http\Controllers;

use App\Repository\TasksRepository;
use Illuminate\Http\Response;
use OpenApi\Annotations as OA;

class NotificationsController extends Controller
{
    public function __construct(private readonly TasksRepository $taskRepository)
    {
    }

    /**
     * @OA\Post(
     *      path="/send-notifications/tasks",
     *      operationId="send-task-notifications",
     *      tags={"Notifications"},
     *      summary="Prepare and sends all notifications",
     *      @OA\Response(
     *          response="204",
     *          description="successful operation",
     *      )
     *  )
     *
     * @return Response
     */
    public function sendNotifications()
    {
        $this->taskRepository->sendTaskNotifications();
        return response();
    }
}
