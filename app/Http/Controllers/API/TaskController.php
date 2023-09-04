<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Error;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Throwable;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('q', '');
        $statusFilter = $request->query('status', '');
        $sortField = $request->query('sortField', 'id');
        $sortOrder = $request->query('sortOrder', 'desc');
        $sizePerPage = $request->query('sizePerPage', 10);

        $tasks = Task::where(function (Builder $q) use ($search, $statusFilter) {
            if (!empty($search)) {
                $q->where('name', 'LIKE', '%' . $search . '%');
            }

            if (!empty($statusFilter)) {
                $q->where('status', $statusFilter);
            }
        })->orderBy($sortField, $sortOrder);

        $paginatedTasks = $tasks->paginate($sizePerPage);

        $paginatedTasks->getCollection()->transform(function ($task) {
            $task->status = Arr::get(Task::STATUSES, $task->status);

            return $task;
        });

        return response()->json($paginatedTasks);
    }

    public function store(Request $request)
    {
        try {
            $params = $request->validate([
                'name' => 'required|max:255|unique:tasks,name'
            ],[
                "name.unique" => "The task has already been added",
                "name.required" => "The task name is required",
            ]);

            Task::create($params);

            return response()->json("Successfully added new task");
        } catch (Throwable $e) {
            return response()->json(["error" => $e->getMessage()], 400);
        }
    }

    public function show(Task $task)
    {
        $task->status = [
            "value" => $task->status,
            "label" => Arr::get(Task::STATUSES, $task->status)
        ];

        return response()->json($task);
    }

    public function update(Task $task, Request $request)
    {
        try {
            $params = $request->validate([
                'name' => 'required|max:255|unique:tasks,name,' . $task->id,
                'status' => 'required'
            ],[
                "name.unique" => "The task has already been added",
                "name.required" => "The task name is required",
            ]);

            $task->update($params);

            return response()->json("Successfully updated task");
        } catch (Throwable $e) {
            return response()->json(["error" => $e->getMessage()], 400);
        }
    }

    public function destroy($id)
    {

        try {
            $task = Task::where('id', $id)->first();
            if (!$task) {
                throw new Error("Task not found");
            }
            $task->delete();

            return response()->json("Successfully deleted task");
        } catch (Throwable $e) {
            return response()->json(["error" => $e->getMessage()], 400);
        }
    }
}
