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
use OpenApi\Attributes as OA;

#[OA\Tag(
    name: 'Tasks',
    description: 'Everything about your Tasks',
)]
class TaskController extends Controller
{
    public function __construct(private readonly TasksRepository $taskRepository)
    {
    }

    /**
     * @param GetTasksRequest $request
     * @return AnonymousResourceCollection
     */
    #[OA\Get(
        path: '/tasks',
        operationId: 'index',
        summary: 'Display a listing of the resource.',
        security: [['passport' => '{}']],
        tags: ['Tasks'],
        parameters: [
            new OA\Parameter(
                name: 'completed',
                description: 'Filter by completed flag',
                in: 'query',
                schema: new OA\Schema(type: 'boolean')
            ),
            new OA\Parameter(
                name: 'due_date[from]',
                description: 'Filter by due_date',
                in: 'query',
                schema: new OA\Schema(type: 'string', example: '2023-02-01')
            ),
            new OA\Parameter(
                name: 'due_date[to]',
                description: 'Filter by due_date',
                in: 'query',
                schema: new OA\Schema(type: 'string', example: '2025-02-01')
            ),
            new OA\Parameter(
                name: 'order_by[completed]',
                description: 'Sort by completed',
                in: 'query',
                schema: new OA\Schema(type: 'enum', enum: ['asc', 'desc'])
            ),
            new OA\Parameter(
                name: 'order_by[due_date]',
                description: 'Sort by due_date',
                in: 'query',
                schema: new OA\Schema(type: 'enum', enum: ['asc', 'desc'])
            ),
            new OA\Parameter(
                name: 'order_by[activities]',
                description: 'Sort by activities',
                in: 'query',
                schema: new OA\Schema(type: 'enum', enum: ['asc', 'desc'])
            )
        ],
        responses: [
            new OA\Response(
                response: '200',
                description: 'successful operation',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'data',
                            type: 'array',
                            items: new OA\Items(ref: '#/components/schemas/TaskResourceForCollection')
                        ),
                        new OA\Property(
                            property: 'links',
                            type: 'array',
                            items: new OA\Items(ref: '#/components/schemas/Links')
                        ),
                        new OA\Property(
                            property: 'meta',
                            type: 'object',
                            example: '{}'
                        )
                    ]
                )
            ),
            new OA\Response(
                response: '400',
                description: 'Bad request. Validation or bussiness logic error.',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'message',
                            type: 'string',
                        ),
                        new OA\Property(
                            property: 'data',
                            type: 'array',
                            items: new OA\Items()
                        ),
                    ]
                )
            )
        ]
    )]
    public function index(GetTasksRequest $request)
    {
        return TaskResourceForCollection::collection(Task::filter($request->validated())
            ->sort($request->validated())
            ->paginate(25));
    }

    /**
     * @return JsonResponse
     */
    #[OA\Post(
        path: '/tasks',
        operationId: 'store',
        summary: 'Store a newly created resource in storage.',
        security: [['passport' => '{}']],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                ref: '#/components/schemas/StoreTaskRequest'
            )
        ),
        tags: ['Tasks'],
        responses: [
            new OA\Response(
                response: '201',
                description: 'successful operation',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'data',
                            ref: '#/components/schemas/Task',
                            type: 'object'
                        ),
                    ]
                )
            ),
            new OA\Response(
                response: '400',
                description: 'Bad request. Validation or bussiness logic error.',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'message',
                            type: 'string',
                        ),
                        new OA\Property(
                            property: 'data',
                            type: 'array',
                            items: new OA\Items()
                        ),
                    ]
                )
            )
        ]
    )]
    public function store(StoreTaskRequest $request)
    {
        $task = $this->taskRepository->storeTask($request);

        return (new TaskResource($task))->response()->setStatusCode(201);
    }

    /**
     * @return TaskResource
     */
    #[OA\Get(
        path: '/tasks/{taskId}',
        operationId: 'show',
        summary: 'Display the specified resource.',
        security: [['passport' => '{}']],
        tags: ['Tasks'],
        parameters: [
            new OA\Parameter(
                name: 'taskId',
                description: 'Task uniqal ID',
                in: 'path',
                schema: new OA\Schema(type: 'integer'),
                example: 1
            ),
        ],
        responses: [
            new OA\Response(
                response: '200',
                description: 'successful operation',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'data',
                            ref: '#/components/schemas/TaskResource',
                            type: 'object'
                        ),
                    ]
                )
            ),
            new OA\Response(
                response: '400',
                description: 'Bad request. Validation or bussiness logic error.',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'message',
                            type: 'string',
                        ),
                        new OA\Property(
                            property: 'data',
                            type: 'array',
                            items: new OA\Items()
                        ),
                    ]
                )
            )
        ]
    )]
    public function show(int $id)
    {
        return new TaskResource(Task::findOrFail($id));
    }


    /**
     * @param UpdateTaskRequest $request
     * @param int $id
     * @return TaskResource
     */
    #[OA\Patch(
        path: '/tasks/{taskId}',
        operationId: 'update',
        summary: 'Update the specified resource in storage.',
        security: [['passport' => '{}']],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                ref: '#/components/schemas/UpdateTaskRequest'
            )
        ),
        tags: ['Tasks'],
        parameters: [
            new OA\Parameter(
                name: 'taskId',
                description: 'Task ID',
                in: 'path',
                schema: new OA\Schema(type: 'integer'),
                example: 1,
            )],
        responses: [
            new OA\Response(
                response: '200',
                description: 'successful operation',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'data',
                            ref: '#/components/schemas/Task',
                            type: 'object'
                        ),
                    ]
                )
            ),
            new OA\Response(
                response: '400',
                description: 'Bad request. Validation or bussiness logic error.',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'message',
                            type: 'string',
                        ),
                        new OA\Property(
                            property: 'data',
                            type: 'array',
                            items: new OA\Items()
                        ),
                    ]
                )
            )
        ]
    )]
    public function update(UpdateTaskRequest $request, int $id)
    {
        $task = $this->taskRepository->updateTask($request, $id);

        return new TaskResource($task);
    }


    /**
     * @param  int  $id
     * @return Response
     */
    #[OA\Delete(
        path: '/tasks/{taskId}',
        operationId: 'destroy',
        summary: 'Remove the specified resource from storage.',
        security: [['passport' => '{}']],
        tags: ['Tasks'],
        parameters: [
            new OA\Parameter(
                name: 'taskId',
                description: 'Task ID',
                in: 'path',
                schema: new OA\Schema(type: 'integer'),
                example: 1,
            )],
        responses: [
            new OA\Response(
                response: '204',
                description: 'successful operation',
            ),
            new OA\Response(
                response: '400',
                description: 'Bad request. Validation or bussiness logic error.',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'message',
                            type: 'string',
                        ),
                        new OA\Property(
                            property: 'data',
                            type: 'array',
                            items: new OA\Items()
                        ),
                    ]
                )
            )
        ]
    )]
    public function destroy(int $id)
    {
        $this->taskRepository->deleteTask($id);
        return response()->noContent();
    }
}
