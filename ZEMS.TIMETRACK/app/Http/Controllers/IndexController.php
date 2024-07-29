<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class IndexController extends Controller
{
    public function index()
    {
        Carbon::setLocale('ru');

        $user = Auth::user();

        // Выбираем проекты, связанные с текущим пользователем
        $projects = Project::with(['tasks', 'user'])
            ->where('author', $user->id)
            ->get()
            ->map(function ($project) {
                $project->created_at_formatted = Carbon::parse($project->created_at)->format('d.m.Y H:i');
                $whenMustDoneProject = Carbon::parse($project->when_must_done);

                $project->project_done_time_difference = $project->when_done
                    ? Carbon::parse($project->created_at)->diffForHumans(Carbon::parse($project->when_done), [
                        'parts' => 3,
                        'join' => true,
                        'syntax' => Carbon::DIFF_ABSOLUTE,
                    ])
                    : null;

                $project->time_difference = Carbon::now()->diffForHumans($whenMustDoneProject, [
                    'parts' => 3,
                    'join' => true,
                    'syntax' => Carbon::DIFF_ABSOLUTE,
                ]);

                $project->tasks->map(function ($task) use ($whenMustDoneProject) {
                    $task->created_at_formatted = Carbon::parse($task->created_at)->format('d.m.Y H:i');
                    $task->when_must_done = $task->when_must_done ? Carbon::parse($task->when_must_done)->format('d.m.Y H:i') : null;
                    $task->when_done = $task->when_done ? Carbon::parse($task->when_done)->format('d.m.Y H:i') : null;

                    $whenMustDoneTask = Carbon::parse($task->when_must_done);
                    $task->time_difference = Carbon::now()->diffForHumans($whenMustDoneTask, [
                        'parts' => 3,
                        'join' => true,
                        'syntax' => Carbon::DIFF_ABSOLUTE,
                    ]);

                    return $task;
                });

                return $project;
            });

        $data = [
            'title' => 'Home',
            'projects' => $projects,
            'tasks' => $projects->flatMap->tasks,
        ];

        // dd($data);
        return Inertia::render('Home', $data);
    }
}
