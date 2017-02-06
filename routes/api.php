<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/users', function(){
	return App\User::all();
});

Route::get('ticket/{ticket_id}/users', function($ticket_id){
	return App\Ticket::whereId($ticket_id)->with('users')->first();
});

Route::post('ticket/assign', function(Request $request){
	$ticket = App\Ticket::whereId($request['ticket_id'])->with('users')->first();
	$ticket->users()->detach();
	foreach($request['users'] as $user){
		$ticket->users()->attach($user['id']);
	}
	return 'Assigned';
});

Route::get('tickets', function(){
	return App\Ticket::with(['category','status','priority','customer','users'])->orderBy('created_at','ASC')->get();
});

Route::post('ticket/save', function(Request $request){
	$ticket = App\Ticket::create($request[0]);
	return $ticket->whereId($ticket->id)->with(['category','status','priority','customer','users'])->orderBy('created_at','ASC')->first();
});

Route::get('ticket/complete/{ticket_id}', function($ticket_id){
	$ticket = App\Ticket::whereId($ticket_id)->first();
	$ticket->completed = 1;
	$ticket->save();
	return "Ticket completed";
});

Route::get('categories', function(){
	return App\Category::all();
});

Route::get('priorities', function(){
	return App\Priority::all();
});

