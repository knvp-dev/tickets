<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Events\TodoCreated;
use App\Events\TodoDeleted;
use App\Events\TodoStatusChanged;

use App\Ticket;
use App\Todo;
use Auth;

class TodosController extends Controller
{
    /**
     * Fetch all todos for a ticket
     * @param  Ticket $ticket
     * @return Todo
     */
    public function index(Ticket $ticket){
    	return $ticket->todos;
    }

    /**
     * Add new todo to a ticket
     * @param  Ticket  $ticket
     * @param  Request $request
     * @return Todo
     */
    public function store(Ticket $ticket, Request $request){
    	$this->guardForUnAssignedUsers($ticket);
    	$todo = $ticket->addTodo($request->todo);
    	event(new TodoCreated($todo));
    	return $todo;
    }

    /**
     * Delete todo from a ticket
     * @param  Todo   $todo [description]
     * @return [type]       [description]
     */
    public function delete(Todo $todo){
    	$this->guardForUnAssignedUsers($todo->ticket);
    	event(new TodoDeleted($todo));
    	$todo->delete();
    }

    /**
     * Complete a todo
     * @param  Todo   $todo
     */
    public function complete(Todo $todo){
     $this->guardForUnAssignedUsers($todo->ticket);
     $todo->complete();
     event(new TodoStatusChanged($todo));
    }

    /**
     * Uncomplete a todo
     * @param  Todo   $todo
     */
    public function uncomplete(Todo $todo){
     $this->guardForUnAssignedUsers($todo->ticket);
     $todo->uncomplete();
     event(new TodoStatusChanged($todo));
    }

    /**
     * Make sure the authenticated user is assigned to the ticket it calls an action for
     * @param  Ticket $ticket
     */
    protected function guardForUnAssignedUsers($ticket){
    if(!Auth::user()->isAssignedToTicket($ticket)){
      abort(403, 'Unauthorized');
    }
}
}
