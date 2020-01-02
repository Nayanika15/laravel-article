<?php

namespace Nayanika\Todo\App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class Todo extends Model
{
  protected $table = 'todos';
  protected $fillable = [
      'name', 'status', 'user_id'
  ];

  /**
   * Defining relationship with user table
   * 
   */
  public function user()
  {
      return $this->belongsTo(User::class);
  }

  /**
   * To view the listing of tasks
   */
 public static function list()
 {	
 	if(auth()->user()->is_admin)
 		{
 			return Todo::select('id','name', 'status', 'user_id');
    }
	else
  	{
  		return Todo:: select('id','name', 'status')
  			->where('user_id', Auth::user()->id);
  	}
	}

  /**
   * To store the tasks
   */
 public static function store($data)
  {  
    $todo = new Todo;
    $todo->name = $data['name'];
    $todo->status = '0';
    $todo->user_id = auth()->user()->id;
    $saved = $todo->save();
    if($saved)
    {
      $result['msg'] = 'Task added successfully.';
      $result['type'] = 'success'; 
    }
    else
    {
      $result['msg'] = 'There is some error.';
      $result['type'] = 'ErrorMessage';
    }

    return $result;
  }

  /**
   * To Update the tasks
   */
  public static function updateTask(string $name, int $id)
  {
    $todo = Todo::find($id);
    $todo->name = $name;
    $saved = $todo->save();
    if($saved)
    {
      $result['msg'] = 'Task updated successfully.';
      $result['type'] = 'success';
    }
    else
    {
      $result['msg'] = 'There is some error.';
      $result['type'] = 'ErrorMessage';
    }

    return $result;
  }

  /**
   * To Update the status
   */
  public static function status(int $id)
  {
    $todo = Todo::find($id);
    $todo->status = '1';
    $saved = $todo->save();
    if($saved)
    {
      $result['msg'] = 'Task status updated successfully.';
      $result['type'] = 'success';
    }
    else
    {
      $result['msg'] = 'There is some error.';
      $result['type'] = 'ErrorMessage';
    }

    return $result;
  }
}
