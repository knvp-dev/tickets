<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Http\Middleware\IsAssignedToTicket;

Auth::routes();

// Tickets
Route::get('/', 'HomeController@index');
Route::get('tickets', 'TicketsController@index');
Route::get('ticket/{ticket_id}', 'TicketsController@show');
Route::post('ticket/save', 'TicketsController@store');
Route::post('ticket/update', 'TicketsController@update');
Route::get('ticket/{ticket}/assign/{user}', 'TicketsController@assignUser')->middleware('assigned');
Route::get('ticket/{ticket}/unassign/{user}', 'TicketsController@unAssignUser')->middleware('assigned');
Route::get('ticket/{ticket}/users', 'TicketsController@assignedUsers');
Route::get('ticket/{ticket}/complete', 'TicketsController@complete')->middleware('assigned');
Route::get('ticket/{ticket}/uncomplete', 'TicketsController@uncomplete')->middleware('assigned');
Route::get('ticket/{ticket}/archive', 'TicketsController@archive')->middleware('assigned');
Route::get('ticket/{ticket}/unarchive', 'TicketsController@unarchive')->middleware('assigned');
Route::get('ticket/{ticket}/delete', 'TicketsController@delete')->middleware('assigned');

Route::get('ticket/{ticket}/messages', 'MessagesController@index');
Route::post('ticket/{ticket}/messages/create', 'MessagesController@store')->middleware('assigned');

Route::get('ticket/{ticket}/todos', 'TodosController@index');
Route::post('ticket/{ticket}/todo/save', 'TodosController@store')->middleware('assigned');
Route::get('todo/{todo}/complete', 'TodosController@complete')->middleware('assigned');
Route::get('todo/{todo}/uncomplete', 'TodosController@uncomplete')->middleware('assigned');
Route::delete('todo/{todo}/delete', 'TodosController@delete')->middleware('assigned');

Route::get('archive/tickets', 'ArchiveController@index');


Route::get('/user', function(){
	return Auth::user();
});

Route::get('/users', function(){
	return App\User::all();
});

Route::get('categories', function(){
	return App\Category::all();
});

Route::get('priorities', function(){
	return App\Priority::all();
});
