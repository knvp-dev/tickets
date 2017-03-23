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
    	$todo = $ticket->addTodo(request()->all());
    	event(new TodoCreated($todo));
    	return $todo;
    }

    /**
     * Delete todo from a ticket
     * @param  Todo   $todo [description]
     * @return [type]       [description]
     */
    public function delete($ticketId, Todo $todo){
    	event(new TodoDeleted($todo));
    	$todo->delete();
    }

    /**
     * Complete a todo
     * @param  Todo   $todo
     */
    public function complete($ticketId, Todo $todo){
     $todo->complete();
     event(new TodoStatusChanged($todo));
    }

    /**
     * Uncomplete a todo
     * @param  Todo   $todo
     */
    public function uncomplete($ticketId, Todo $todo){
     $todo->uncomplete();
     event(new TodoStatusChanged($todo));
    }
}
