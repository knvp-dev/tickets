<?php

namespace App\Http\Controllers;

use App\Ticket;
use App\Todo;
use Illuminate\Http\Request;

class TodosController extends Controller
{
	public function show(Ticket $ticket){
		return $ticket->todos;
	}

	public function store(Ticket $ticket, Request $request){
		$ticket->addTodo($request->todo);
	}

	public function delete(Todo $todo){
		$todo->delete();
	}

   	public function complete(Todo $todo){
   		$todo->complete();
   	}

   	public function uncomplete(Todo $todo){
   		$todo->uncomplete();
   	}
}
