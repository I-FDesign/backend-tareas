<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Task;
use App\User;

use Illuminate\Support\Facades\Auth;

class TasksController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
    }

    public function getTasks() {
        $tasks = Auth::user()->tasks;
        return response()->json(['tasks' => $tasks]);
    }

    public function getTask($task_id) {
        $task = Auth::user()->tasks->where('id', $task_id)->first();

        return response()->json(['task' => $task]);
    }

    public function newTask(Request $request) {
        $task = new Task();

        $task->title = $request['title'];
        $task->priority = $request['priority'];
        $task->expiration = strtotime($request['expiration']);
        $task->expirationHour = $request['expirationHour'];

        $user = auth()->user();

        $user->tasks()->save($task);

        return response()->json(['ok' => $task->title], 201);
    }

    public function editTask(Request $request, $task_id) {
        $task = auth()->user()->tasks->where('id', $task_id)->first();

        $task->update([
            'title' => $request['title'],
            'priority' => $request['priority'],
            'expiration' => strtotime($request['expiration']),
            'expirationHour' => $request['expirationHour']
        ]);

        return response()->json(['message' => 'Tarea actualizada correctamente!']);
    }

    public function deleteTask($task_id) {

        $task = auth()->user()->tasks->where('id', $task_id)->first();

        $task->delete();

        return response()->json(['message' => 'La tarea se ha eliminado correctamente'], 200);
    }
}
