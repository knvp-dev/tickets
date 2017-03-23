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

Route::get('/', 'TicketsController@index')->middleware('ispartofateam');
Route::get('/api/tickets', 'TicketsController@tickets')->middleware('ispartofateam');
Route::get('/ticket/{ticket}', 'TicketsController@show');
Route::post('/team/{team}/ticket/save', 'TicketsController@store');
Route::post('/ticket/update', 'TicketsController@update');
Route::get('/ticket/{ticket}/members', 'TicketsController@assignedUsers');

Route::get('/ticket/{ticket}/messages', 'MessagesController@index');
Route::get('/ticket/{ticket}/todos', 'TodosController@index');
Route::get('/archive/tickets', 'ArchiveController@index');

Route::post('/team/{team}/members/add', 'MembersController@store')->middleware('isownerofteam');

Route::group(['middleware' => 'assigned'], function(){
	Route::get('/ticket/{ticket}/assign/{user}', 'TicketsController@assignUser')
		->middleware('isownerofticket');
	Route::get('/ticket/{ticket}/unassign/{user}', 'TicketsController@unAssignUser')
		->middleware('isownerofticket');
	Route::get('/ticket/{ticket}/complete', 'TicketsController@complete');
	Route::get('/ticket/{ticket}/uncomplete', 'TicketsController@uncomplete');
	Route::get('/ticket/{ticket}/archive', 'TicketsController@archive');
	Route::get('/ticket/{ticket}/unarchive', 'TicketsController@unarchive');
	Route::delete('/ticket/{ticket}/delete', 'TicketsController@delete')
		->middleware('isownerofticket');
	Route::post('/ticket/{ticket}/messages/create', 'MessagesController@store');
	Route::post('/ticket/{ticket}/todo/save', 'TodosController@store');
	Route::get('/ticket/{ticket}/todo/{todo}/complete', 'TodosController@complete');
	Route::get('/ticket/{ticket}/todo/{todo}/uncomplete', 'TodosController@uncomplete');
	Route::delete('/ticket/{ticket}/todo/{todo}/delete', 'TodosController@delete');
});

Route::get('/user', function(){
	return Auth::user();
});

Route::get('/team/{team}/members', 'TeamsController@members');

Route::get('categories', function(){
	return App\Category::all();
});

Route::get('priorities', function(){
	return App\Priority::all();
});

Route::get('/activeteam', 'TeamsController@getActiveTeam');

// TEAM

Route::get('/teams', 'TeamsController@index');
Route::post('/team/create', 'TeamsController@store');

Route::get('/team/choose/{team}', 'TeamsController@setActiveTeam');
