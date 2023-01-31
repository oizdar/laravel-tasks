<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetTasksRequest;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Resources\TaskResource;
use App\Http\Resources\TaskResourceForCollection;
use App\Models\Task;
use App\Repository\TasksRepository;
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

    public function __construct(private readonly TasksRepository $taskRepository)
    {
    }

    /**
     * @OA\Get(
     *      path="/tasks",
     *      operationId="index",
     *      tags={"Tasks"},
     *      summary="Display a listing of the resource.",
     *      security={{"passport": {}}},
     *      @OA\Parameter(
     *          description="Filter by completed flag",
     *          in="query",
     *          name="completed",
     *          example="false",
     *          @OA\Schema(type="boolean")
     *      ),
     *     @OA\Parameter(
     *          description="Filter by due_date",
     *          in="query",
     *          name="due_date[from]",
     *          @OA\Schema(type="string", example="2023-05-05")
     *      ),
     *     @OA\Parameter(
     *          description="Filter by due_date",
     *          in="query",
     *          name="due_date[to]",
     *          @OA\Schema(type="string", example="2023-05-05")
     *      ),
     *      @OA\Parameter(
     *          description="Filter by due_date",
     *          in="query",
     *          name="order_by[completed]",
     *          @OA\Schema(type="enum", enum={"asc","desc"})
     *      ),
     *     @OA\Parameter(
     *          description="Filter by due_date",
     *          in="query",
     *          name="order_by[due_date]",
     *          @OA\Schema(type="enum", enum={"asc","desc"})
     *      ),
     *     @OA\Parameter(
     *          description="Filter by due_date",
     *          in="query",
     *          name="order_by[activities]",
     *          @OA\Schema(type="enum", enum={"asc","desc"})
     *      ),
     *      @OA\Response(
     *          response="200",
     *          description="successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(property="data", type="array",
     *                  @OA\Items(ref="#/components/schemas/TaskResourceForCollection")
     *              ),
     *              @OA\Property(property="links", type="object", ref="#/components/schemas/Links"),
     *              @OA\Property(property="meta", type="object", example="{}"),
     *          )
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
     * @param GetTasksRequest $request
     * @return AnonymousResourceCollection
     */
    public function index(GetTasksRequest $request)
    {
        return TaskResourceForCollection::collection(Task::filter($request->validated())
            ->sort($request->validated())
            ->paginate(25));
    }

    /**
     *  @OA\Post(
     *      path="/tasks",
     *      operationId="store",
     *      tags={"Tasks"},
     *      summary="Store a newly created resource in storage.",
     *      security={{"passport": {}}},
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
     *      security={{"passport": {}}},
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
     *              @OA\Property(property="data", type="object",  ref="#/components/schemas/TaskResource")
     *          )
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
     *      security={{"passport": {}}},
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
     *      security={{"passport": {}}},
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
