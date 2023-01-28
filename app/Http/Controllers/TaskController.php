<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use OpenApi\Annotations as OA;

/**
 * @OA\Tag (
 *     name="Tasks",
 *     description="Everything about your Tasks",
 * )
 */
class TaskController extends Controller
{

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
     *          @OA\JsonContent(ref="#/components/schemas/Task")
     *      )
     *  )
     *
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreTaskRequest $request)
    {

        $task = new Task([
            'title' => $request->title,
            'description' => $request->description,
            'due_date' => $request->dueDate,
        ]);
        $task->save();

        return (new TaskResource($task))->response()->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
