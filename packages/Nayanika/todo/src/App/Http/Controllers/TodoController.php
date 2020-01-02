<?php
namespace Nayanika\Todo\App\Http\Controllers;
use App\Http\Controllers\Controller;

use Nayanika\Todo\App\Http\Requests\TodoRequest;
use Nayanika\Todo\App\Models\Todo;

class TodoController extends Controller
{   
    /**
     * To view task listing
     */
    public function list()
    {
    	$all_tasks = Todo::list()->get();
    	return view('todo::todo')->with(['tasks'=> $all_tasks, 'submit' => "Add"]);
    }

    /**
     * To store task
     */
    public function store(TodoRequest $request)
    {
        $data = $request->validated();
        if($data)
        {
            $result = Todo::store($data);
            return redirect()->route('Tasks')
            ->with($result['type'], $result['msg']);
        }
        else
        {
            return redirect()->route('Tasks')
                ->with('ErrorMessage', 'Enter valid data.')
                ->withInput();
        }
    }

    /**
     * To fetch task for edit
     */
    public function edit(int $id)
    {   
        $tasks  = Todo::list()->get();
        $task   = Todo::find($id);
        return view('todo::todo')->with(['tasks' => $tasks, 'task' => $task, 'submit' => 'Update']);
    }

    /**
     * To update task
     * @param int id, request
     */
    public function update(TodoRequest $request, int $id)
    {
        $data = $request->validated();
        if($data)
        {
            $result = Todo::updateTask($data['name'], $id);
            return redirect()->route('Tasks')
            ->with($result['type'], $result['msg']);
        }
        else
        {
            return redirect()->route('edit-task', $id)
                ->with('ErrorMessage', 'Enter valid data.')
                ->withInput();
        }
    }

    /**
     * To update task status
     * @param int id, request
     */
    public function updateStatus(int $id)
    {
        if($id)
        {
            $result = Todo::status($id);
            return redirect()->route('Tasks')
            ->with($result['type'], $result['msg']);
        }
        else
        {
            return redirect()->route('Tasks', $id)
                ->with('ErrorMessage', 'Invalid selection.')
                ->withInput();
        }
    }

    /**
     * To delete task
     * @param int id
     */
    public function destroy(int $id)
    {
        if($id)
        {
            $task = Todo::find($id);
            $action = $task->delete();
            if($action)
            {
              $result['msg'] = 'Task deleted successfully.';
              $result['type'] = 'success';
            }
            else
            {
              $result['msg'] = 'There is some error.';
              $result['type'] = 'ErrorMessage';
            }
            return redirect()->route('Tasks')
            ->with($result['type'], $result['msg']);
        }
        else
        {
            return redirect()->route('Tasks', $id)
                ->with('ErrorMessage', 'Invalid selection.')
                ->withInput();
        }
    }

}