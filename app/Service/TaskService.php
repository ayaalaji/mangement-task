<?php

namespace App\Service;

use Exception;
use Carbon\Carbon;
use App\Models\Task;
use PhpParser\Node\Stmt\TryCatch;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class TaskService{
    public function getAllTask(array $data)
    {
        try
        {
            if(Auth::user()->role == 'admin' ||Auth::user()->role == 'manager'){
                $query =Task::query(); 
                if(isset($data['priority'])){
                    $query->where('priority', $data['priority']);
                } 
                if (isset($data['status'])) {
                    $query->where('status', $data['status']);
                }   
                $allTasks = $query->get();
                return $allTasks;
            } else{
                return false;
            }
            
        }catch(Exception $e) {
            Log::error('Error Updated Task: ' . $e->getMessage());
            throw new Exception('ther is something wrong'. $e->getMessage());
        }    
        
    }
    public function addtask(array $data)
    {
        try{
            $user = Auth::user();
            return Task::create([
                'title'=>$data['title'],
                'priority'=>$data['priority'],
                'description'=>$data['description'],
                'assigned_to' =>$data['assigned_to'] ,
                'user_id' => (int)$data['user_id'],
                'added_by' => $user->role, //who is added the task
            ]);
        }catch(Exception $e){
            Log::error('Error creating Task: ' . $e->getMessage());
            throw new Exception('ther is something wrong'. $e->getMessage());
        }
    }

    public function oneTask(Task $task)
    {
        try{
            $user = Auth::user();
            if ($user->role == 'admin' || $user->role == 'manager') 
            {
               return $task;
            }
            if ($user->role == 'user') {
                if ($task->user_id == $user->id) {
                    return $task;
                } else {
                    return false;
                }
            }
        }catch(Exception $e) {
            Log::error('Error Updated Task: ' . $e->getMessage());
            throw new Exception('ther is something wrong'. $e->getMessage());
        }   
    }

    public function updateTask(array $data,Task $task)
    {
        try{
            $user = Auth::user();
            if($user->role=='admin' || $user->role=='manager')
            {
                $taskUpdate =array_filter([
                    'title'=>$data['title'] ?? $task->title,
                    'priority'=>$data['priority']?? $task->priority,
                    'description'=>$data['description']?? $task->description,
                    'assigned_to' =>isset($data['assigned_to']) ?$data['assigned_to'] : $task->assigned_to,
                    'user_id' => isset($data['user_id']) ? (int)$data['user_id'] : $task->user_id,
                ]);
                Log::info('Task Update Data: ', $taskUpdate);
                $task->update($taskUpdate);
                return $task;
            }
        } catch(Exception $e) {
            Log::error('Error Updated Task: ' . $e->getMessage());
            throw new Exception('ther is something wrong'. $e->getMessage());
        }   
    }
    public function userUpdateTask(array $data,Task $task)
    {
        try{
            $user = Auth::user();
            if($user->role == 'user')
            {
                $userUpdateTask = array_filter([
                    'status' =>$data['status'] ?? $task->status
                ]);
                $task->update($userUpdateTask);
                return $task;
            } else{
                return false;
            }
        }catch(Exception $e){
            Log::error('Error Updated Task: ' . $e->getMessage());
            throw new Exception('ther is something wrong'. $e->getMessage());
        }
    }

    public function deleteTask(Task $task) 
    {
        try{
            $user =Auth::user();
            if($user->role=='admin'){
                $task->delete();
                return $task;
            }
            if($user->role=='manager'){
                if ($task->added_by == 'manager') {
                    $task->delete();
                    return $task;
                } else {
                    return false;
                }
            }
            if($user->role=='user'){
                return false;
            }

        }catch(Exception $e) {
            Log::error('Error Updated Task: ' . $e->getMessage());
            throw new Exception('ther is something wrong'. $e->getMessage());
        }   
        
    }
}