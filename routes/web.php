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

Auth::routes();

// Tickets
Route::get('/', 'HomeController@index');
Route::get('tickets', 'TicketsController@index');
Route::get('ticket/{ticket_id}', 'TicketsController@show');
Route::post('ticket/save', 'TicketsController@store');
Route::post('ticket/update', 'TicketsController@update');
Route::get('ticket/{ticket}/assign/{user}', 'TicketsController@assignUser');
Route::get('ticket/{ticket}/unassign/{user}', 'TicketsController@unAssignUser');
Route::get('ticket/{ticket}/users', 'TicketsController@assignedUsers');
Route::get('ticket/{ticket}/complete', 'TicketsController@complete');
Route::get('ticket/{ticket}/uncomplete', 'TicketsController@uncomplete');
Route::get('ticket/{ticket}/archive', 'TicketsController@archive');
Route::get('ticket/{ticket}/unarchive', 'TicketsController@unarchive');
Route::get('ticket/{ticket}/delete', 'TicketsController@delete');

Route::get('ticket/{ticket}/messages', 'MessagesController@index');
Route::post('ticket/{ticket}/messages/create', 'MessagesController@store');

Route::get('ticket/{ticket}/todos', 'TodosController@index');
Route::post('ticket/{ticket}/todo/save', 'TodosController@store');
Route::get('todo/{todo}/complete', 'TodosController@complete');
Route::get('todo/{todo}/uncomplete', 'TodosController@uncomplete');
Route::delete('todo/{todo}/delete', 'TodosController@delete');

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
