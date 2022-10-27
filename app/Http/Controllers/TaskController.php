<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskStatus;

use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tasks = Task::with('status')->paginate(10);
        return view('tasks.index', compact('tasks'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //$task = Task::with('status')->find($id);
        $updateMode = false;
        $taskStatuses = TaskStatus::all();
        $taskStatusClosed = $taskStatuses->firstWhere('name', 'Closed')->id;

        return view('tasks.form', compact('updateMode', 'taskStatuses', 'taskStatusClosed'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'task_id' => 'nullable',
            'name' => 'required',
            'details' => 'nullable|string',
            'task_status_id' => 'required|integer',
            'date_due' => 'nullable|date|after:date_start',
            'date_start' => 'nullable|date',
        ]);
        
        $task = new Task;
        $task->name = $validatedData['name'];
        $task->details = $validatedData['details'];
        $task->date_due = $validatedData['date_due'];
        $task->date_start = $validatedData['date_start'];
        
        $task->status()->associate($validatedData['task_status_id']);
        
        $task->save();

        session()->flash('message', 'Task Created Successfully.');

        return redirect()->route('tasksold.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        dd("show");
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $task = Task::with('status')->find($id);
        $updateMode = true;
        $taskStatuses = TaskStatus::all();
        $taskStatusClosed = $taskStatuses->firstWhere('name', 'Closed')->id;

        return view('tasks.form', compact('task', 'updateMode', 'taskStatuses', 'taskStatusClosed'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Task $task)
    {
        if ($request->submit != 'cancel')
        {
            $validatedData = $request->validate([
                'task_id' => 'nullable',
                'name' => 'required',
                'details' => 'nullable|string',
                'task_status_id' => 'required|integer',
                'date_due' => 'nullable|date|after:date_start',
                'date_start' => 'nullable|date',
            ]);
            
            $task->name = $validatedData['name'];
            $task->details = $validatedData['details'];
            $task->date_due = $validatedData['date_due'];
            $task->date_start = $validatedData['date_start'];
            
            $task->status()->associate($validatedData['task_status_id']);
            
            $task->save();

            session()->flash('message', 'Task Updated Successfully.');

        }

        return redirect()->route('tasksold.index');    
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Task $task)
    {
        $task->delete();
        session()->flash('message', 'Post Deleted Successfully.');

        $tasks = Task::with('status')->get();
        return view('tasks.index', compact('tasks'));
    }

}
