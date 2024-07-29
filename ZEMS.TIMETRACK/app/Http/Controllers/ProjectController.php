<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ProjectController extends Controller
{

    public function index()
    {
        Carbon::setLocale('ru');

        $user = Auth::user();

        $projects = Project::with(['tasks', 'user'])
            ->where('author', $user->id)
            ->get()
            ->map(function ($project) {
                $createdAt = Carbon::parse($project->created_at);
                $whenMustDone = Carbon::parse($project->when_must_done);

                $project->created_at_formatted = $createdAt->format('d.m.Y H:i');
                $project->when_must_done = $whenMustDone->format('d.m.Y H:i');

                $project->time_difference = Carbon::now()->diffForHumans($whenMustDone, [
                    'parts' => 3,
                    'join' => true,  
                    'syntax' => Carbon::DIFF_ABSOLUTE, 
                ]);

                $project->tasks->map(function ($task) {
                    $task->created_at_formatted = Carbon::parse($task->created_at)->format('d.m.Y H:i');
                    $task->when_must_done = $task->when_must_done ? Carbon::parse($task->when_must_done)->format('d.m.Y H:i') : null;
                    $task->when_done = $task->when_done ? Carbon::parse($task->when_done)->format('d.m.Y H:i') : null;
                    return $task;
                });

                return $project;
            });

        return Inertia::render('Projects/Index', [
            'title' => 'Projects',
            'projects' => $projects,
            'userId' => Auth::id()

        ]);
    }

    public function store(Request $request)
    {
        Project::create(
            $request->validate([
                'name' => 'required',
                'author' => 'required',
                'description' => 'required',
                'when_must_done' => 'required',
            ])
        );

        return redirect()->route('projects.index');
    }

    public function edit(Project $project)
    {
        if ($project->author != Auth::id()) {
            abort(403, 'Ошибка доступа');
        }

        return Inertia::render('Projects/Edit', [
            'title' => 'Edit Projects:',
            'project' => $project
        ]);
    }

    public function update(Request $request, Project $project)
    {
        $project->update(
            $request->validate([
                'name' => 'required',
                'description' => 'required',
                'when_must_done' => 'required',
            ])
        );

        return redirect()->route('projects.index');
    }
    public function done(Project $project)
    {
        $project->is_done = !$project->is_done;
        $project->when_done = $project->is_done ? Carbon::now() : null;
        $project->save();

        // Обновление статуса всех задач проекта
        foreach ($project->tasks as $task) {
            app(TaskController::class)->done($task);
        }

        return redirect()->back();
    }

    public function destroy(Project $project)
    {
        $project->delete();
        return redirect()->back();
    }
}
