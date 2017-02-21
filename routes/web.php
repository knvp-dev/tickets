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
Route::get('/ticket/{ticket_id}', 'TicketsController@show');

Route::post('ticket/save', 'TicketsController@store');
Route::post('ticket/update', 'TicketsController@update');
Route::post('ticket/assign', 'TicketsController@assignUserToTicket');

Route::get('ticket/{ticket}/users', 'TicketsController@assignedUsers');
Route::get('ticket/complete/{ticket}', 'TicketsController@completeTicket');
Route::get('/ticket/uncomplete/{ticket}', 'TicketsController@uncompleteTicket');
Route::get('/ticket/archive/{ticket}', 'TicketsController@archive');
Route::get('/ticket/unarchive/{ticket}', 'TicketsController@unarchive');

Route::get('/ticket/{ticket}/todos', 'TodosController@show');

Route::post('/ticket/{ticket}/todo/save', 'TodosController@store');
Route::delete('/ticket/{ticket}/todo/delete', 'TodosController@delete');

Route::get('/archive', 'ArchiveController@index');
Route::get('/archive/tickets', 'ArchiveController@archivedTickets');

Route::get('/users', function(){
	return App\User::all();
});

Route::get('categories', function(){
	return App\Category::all();
});

Route::get('priorities', function(){
	return App\Priority::all();
});
