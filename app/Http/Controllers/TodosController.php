<?php

namespace App\Http\Controllers;

use App\Events\TodoCreated;
use App\Events\TodoDeleted;
use App\Events\TodoStatusChanged;

use App\Ticket;
use App\Todo;
use Illuminate\Http\Request;

class TodosController extends Controller
{
	public function show(Ticket $ticket){
		return $ticket->todos;
	}

	public function store(Ticket $ticket, Request $request){
		$todo = $ticket->addTodo($request->todo);
		event(new TodoCreated($todo));
		return $todo;
	}

	public function delete(Todo $todo){
		event(new TodoDeleted($todo));
		$todo->delete();
	}

   	public function complete(Todo $todo){
   		$todo->complete();
   		event(new TodoStatusChanged($todo));
   	}

   	public function uncomplete(Todo $todo){
   		$todo->uncomplete();
   		event(new TodoStatusChanged($todo));
   	}
}
