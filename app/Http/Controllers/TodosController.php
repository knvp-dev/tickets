<?php

namespace App\Http\Controllers;

use App\Ticket;
use Illuminate\Http\Request;

class TodosController extends Controller
{
	public function show(Ticket $ticket){
		return $ticket->todos;
	}

	public function store(Ticket $ticket, Request $request){
		$ticket->addTodo($request->todo);
	}

	public function delete(Ticket $ticket, Request $request){
		$ticket->removeTodo($request->todo);
	}

   	public function complete(Todo $todo){
   		$todo->complete();
   	}

   	public function uncomplete(Todo $todo){
   		$todo->uncomplete();
   	}
}
