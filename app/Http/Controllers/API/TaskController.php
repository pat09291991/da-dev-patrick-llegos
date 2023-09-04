<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

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
}
