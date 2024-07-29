<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;


class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        Carbon::setLocale('ru');

        $user = Auth::user(); // Получаем текущего пользователя

        // Выбираем задачи, связанные с текущим пользователем
        $tasks = Task::where('author', $user->id)->get()->map(function ($task) {
            $task->when_must_done = Carbon::parse($task->when_must_done)->format('d.m.Y H:i');
            $task->when_done = $task->when_done ? Carbon::parse($task->when_done)->format('d.m.Y H:i') : null;

            $whenMustDone = Carbon::parse($task->when_must_done);

            $task->time_task_difference =  $whenMustDone->diffForHumans(Carbon::now(), [
                'parts' => 3,
                'join' => true,  // объединить части в одну строку
                'syntax' => Carbon::DIFF_ABSOLUTE,  // убрать слова "до" или "после"
            ]);

            return $task;
        });

        // Выбираем проекты, связанные с текущим пользователем
        $projects = Project::where('author', $user->id)->get()->map(function ($project) {
            $project->when_must_done = Carbon::parse($project->when_must_done)->format('d.m.Y H:i');
            $project->when_done = $project->when_done ? Carbon::parse($project->when_done)->format('d.m.Y H:i') : null;

            return $project;
        });
        // dd($projects->time_task_difference);

        // dd($tasks);
        return Inertia::render('Tasks/Index', [
            'title' => 'Tasks',
            'userId' => Auth::id(),
            'tasks' => $tasks,
            'projects' => $projects
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Task::create(
            $request->validate([
                'name' => 'required',
                'project_id' => 'required',
                'author' => 'required|exists:users,id',
                'description' => 'required',
                'when_must_done' => 'required',
            ])
        );
        // dd($request);
        return redirect()->route('tasks.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task)
    {

        if ($task->author != Auth::id()) {
            abort(403, 'Ошибка доступа');
        }

        return Inertia::render('Tasks/Edit', [
            'title' => 'Edit task:',
            'task' => $task
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task)
    {
        $task->update(
            $request->validate([
                'name' => 'required',
                'description' => 'required',
                'when_must_done' => 'required',
            ])
        );
        return redirect()->route('tasks.index');
    }

    public function done(Task $task)
    {
        $task->is_done = !$task->is_done;
        $task->when_done = $task->is_done ? Carbon::now() : null;
        $task->save();
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        $task->delete();
        return redirect()->back();
    }
}
