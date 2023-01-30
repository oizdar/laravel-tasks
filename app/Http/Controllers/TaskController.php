<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use App\Repository\TaskRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use OpenApi\Annotations as OA;

/**
 * @OA\Tag (
 *     name="Tasks",
 *     description="Everything about your Tasks",
 * )
 */
class TaskController extends Controller
{

    public function __construct(private readonly TaskRepository $taskRepository)
    {
    }

    /**
     *  @OA\Get(
     *      path="/tasks",
     *      operationId="index",
     *      tags={"Tasks"},
     *      summary="Display a listing of the resource.",
     *      @OA\Response(
     *          response="200",
     *          description="successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(property="data", type="array",
     *                  @OA\Items(ref="#/components/schemas/Task")
     *              ),
     *              @OA\Property(property="links", type="object", ref="#/components/schemas/Links"),
     *              @OA\Property(property="meta", type="object", example="{}"),
     *          )
     *      )
     *  )
     *
     * @return AnonymousResourceCollection
     */
    public function index()
    {
        return TaskResource::collection(Task::query()->paginate(25));
    }

    /**
     *  @OA\Post(
     *      path="/tasks",
     *      operationId="store",
     *      tags={"Tasks"},
     *      summary="Store a newly created resource in storage.",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/StoreTaskRequest")
     *      ),
     *      @OA\Response(
     *          response="201",
     *          description="successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(property="data", type="object", ref="#/components/schemas/Task")
     *          )
     *      )
     *  )
     *
     *
     * @return JsonResponse
     */
    public function store(StoreTaskRequest $request)
    {
        $task = $this->taskRepository->storeTask($request);

        return (new TaskResource($task))->response()->setStatusCode(201);
    }

    /**
     *  @OA\Get(
     *      path="/tasks/{taskId}",
     *      operationId="show",
     *      tags={"Tasks"},
     *      summary="Display the specified resource.",
     *      @OA\Parameter(
     *          description="Task ID",
     *          in="path",
     *          name="taskId",
     *          example="1",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response="200",
     *          description="successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(property="data", type="object", ref="#/components/schemas/Task")
     *          )
     *      )
     *  )
     *
     * @return TaskResource
     */
    public function show(int $id)
    {
        return new TaskResource(Task::findOrFail($id));
    }

    /**
     * @OA\Patch(
     *      path="/tasks/{taskId}",
     *      operationId="update",
     *      tags={"Tasks"},
     *      summary="Update the specified resource in storage.",
     *      @OA\Parameter(
     *          description="Task ID",
     *          in="path",
     *          name="taskId",
     *          example="1",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/UpdateTaskRequest")
     *      ),
     *      @OA\Response(
     *          response="200",
     *          description="successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(property="data", type="object", ref="#/components/schemas/Task")
     *          )
     *      )
     *  )
     *
     *
     *
     *
     * @param UpdateTaskRequest $request
     * @param int $id
     * @return TaskResource
     */
    public function update(UpdateTaskRequest $request, int $id)
    {
        $task = $this->taskRepository->updateTask($request, $id);

        return new TaskResource($task);
    }

    /**
     * @OA\Delete(
     *      path="/tasks/{taskId}",
     *      operationId="destroy",
     *      tags={"Tasks"},
     *      summary="Remove the specified resource from storage.",
     *      @OA\Parameter(
     *          description="Task ID",
     *          in="path",
     *          name="taskId",
     *          example="1",
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\Response(
     *          response="204",
     *          description="successful operation",
     *      ),
     *      @OA\Response(
     *          response="400",
     *          description="Bad request. Validation or bussiness logic error.",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string"),
     *              @OA\Property(property="data", type="array", items={}),
     *          )
     *      )
     *  )
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy(int $id)
    {
        $this->taskRepository->deleteTask($id);
        return response()->noContent();
    }
}
