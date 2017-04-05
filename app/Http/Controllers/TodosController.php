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
    public function index($categoryId, Ticket $ticket){
    	return $ticket->todos->first();
    }

    /**
     * Add new todo to a ticket
     * @param  Ticket  $ticket
     * @param  Request $request
     * @return Todo
     */
    public function store(Ticket $ticket, Request $request){
        
        $this->validate($request, [
            'body' => 'required'
        ]);

    	$todo = $ticket->addTodo(request()->all());
    	event(new TodoCreated($todo));
    	return back();
    }

    /**
     * Delete todo from a ticket
     * @param  Todo   $todo [description]
     * @return [type]       [description]
     */
    public function destroy(Ticket $ticket, Todo $todo){
    	event(new TodoDeleted($todo));
    	$ticket->todos()->delete($todo);
        return back();
    }

    /**
     * Complete a todo
     * @param  Todo   $todo
     */
    public function complete(Ticket $ticket, Todo $todo){
     $todo->complete();
     event(new TodoStatusChanged($todo));
     return back();
    }

    /**
     * Uncomplete a todo
     * @param  Todo   $todo
     */
    public function uncomplete(Ticket $ticket, Todo $todo){
     $todo->uncomplete();
     event(new TodoStatusChanged($todo));
     return back();
    }
}
