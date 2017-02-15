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
use Illuminate\Http\Request;
Auth::routes();

// Tickets
Route::get('/', 'TicketsController@index');
Route::get('tickets', 'TicketsController@tickets');
Route::post('ticket/save', 'TicketsController@store');
Route::get('/ticket/detail/{ticket}', 'TicketsController@detail');
Route::get('/ticket/{ticket}', 'TicketsController@show');
Route::get('ticket/{ticket}/users', 'TicketsController@assignedUsers');
Route::get('ticket/complete/{ticket}', 'TicketsController@completeTicket');
Route::get('/ticket/uncomplete/{ticket}', 'TicketsController@uncompleteTicket');
Route::get('/ticket/archive/{ticket}', 'TicketsController@archive');
Route::get('/ticket/unarchive/{ticket}', 'TicketsController@unarchive');
Route::post('ticket/assign', 'TicketsController@assignUserToTicket');


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
