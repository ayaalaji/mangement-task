<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Task;
use App\Service\TaskService;
use App\Traits\apiResponseTrait;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    use apiResponseTrait;
    protected $taskService;
    public function __construct(TaskService $taskService)
    {
        $this->taskService=$taskService;
    }
    /**
     * Display all tasks in storage.
     * just admin and manager can see all tasks
     * @param Request $request
     * @return /Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $data = $request->all();
        $tasks = $this->taskService->getAllTask($data);
        if($tasks == false){
            return $this->apiResponse(null,'Unauthorized',400);
        }
        return $this->apiResponse($tasks,'this is all tasks',200);
    }

    /**
     * Store a newly created task.
     * @param StoreTaskRequest $request
     * @return /Illuminate\Http\JsonResponse
     */
    public function store(StoreTaskRequest $request)
    {
        $validatedData = $request->validated();
        $task = $this->taskService->addtask($validatedData);
        return $this->apiResponse($task,'You created task Successfully',201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        $taskOne =$this->taskService->oneTask($task);
        if($taskOne == false){
            return $this->apiResponse(null,'Unauthorized',400);
        }
        return $this->apiResponse($taskOne,'this is your request',200);


    }

    /**
     * Update the specified task.
     * i have 3 role :admin can update all atributes
     * manager can update also all attribute but if admin filled all atrributes
     * he can not update any think
     * @param UpdateTaskRequest $request
     * @param Task $task
     * @return /Illuminate\Http\JsonResponse
     */
    public function update(UpdateTaskRequest $request, Task $task)
    {
        $validatedData = $request->validated();
        $taskUpdate = $this->taskService->updateTask($validatedData,$task);
        return $this->apiResponse($taskUpdate,'You Updated Task Successfully',200);
    }
    /**
     * Update the specified task.
     * the user can update only status
     * @param UpdateTaskRequest $request
     * @param Task $task
     * @return /Illuminate\Http\JsonResponse
     */

    public function updateUser(UpdateTaskRequest $request , Task $task)
    {
        $validatedData = $request->validated();
        $taskUser=$this->taskService->userUpdateTask($validatedData,$task);
        if($taskUser == false){
            return $this->apiResponse(null,'Sorry you can not update this',400);
        }
        return $this->apiResponse($taskUser,'You Updated Task Successfully',200); 
    }

    /**
     * Remove the specified task from storage depending on his role.
     *  @param Task $task
     * @return /Illuminate\Http\JsonResponse
     */
    public function destroy(Task $task)
    {
        $task = $this->taskService->deleteTask($task);
        if($task == false){
            return $this->apiResponse(null,'Unauthorized',400);
        }
        return $this->apiResponse(null,'',204); 
    }
}
