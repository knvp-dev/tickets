<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TodosController extends Controller
{
	public function show($ticket_id){
		return Todo::where('ticket_id',)
	}

   	public function complete(Todo $todo){
   		$todo->complete();
   	}

   	public function uncomplete(Todo $todo){
   		$todo->uncomplete();
   	}
}
