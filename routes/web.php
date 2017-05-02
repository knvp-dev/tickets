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

Route::group(['middleware' => 'assigned'], function(){
	Route::get('/ticket/{ticket}/assign/{user}', 'TicketMembersController@store')
	->middleware('isownerofticket');
	Route::get('/ticket/{ticket}/unassign/{user}', 'TicketMembersController@destroy')
	->middleware('isownerofticket');
	Route::get('/ticket/{ticket}/complete', 'TicketsController@complete');
	Route::get('/ticket/{ticket}/uncomplete', 'TicketsController@uncomplete');
	Route::get('/ticket/{ticket}/archive', 'TicketsController@archive');
	Route::get('/ticket/{ticket}/unarchive', 'TicketsController@unarchive');
	Route::delete('/ticket/{ticket}/delete', 'TicketsController@destroy')
	->middleware('isownerofticket');
	Route::get('/ticket/{ticket}/edit', 'TicketsController@edit');
	Route::post('/ticket/{ticket}/update', 'TicketsController@update');
	Route::post('/ticket/{ticket}/messages/create', 'MessagesController@store');
	Route::post('/ticket/{ticket}/todo/save', 'TodosController@store');
	Route::get('/ticket/{ticket}/todo/{todo}/complete', 'TodosController@complete');
	Route::get('/ticket/{ticket}/todo/{todo}/uncomplete', 'TodosController@uncomplete');
	Route::get('/ticket/{ticket}/todo/{todo}/delete', 'TodosController@destroy');
});


// TEAM

Route::group(['middleware' => 'isownerofteam'], function(){
	Route::get('/team/{team}/members', 'TeamMembersController@index');
	Route::post('/team/{team}/members/add', 'TeamMembersController@store');
	Route::get('/team/{team}/members/{user}/remove', 'TeamMembersController@destroy');
	Route::get('/team/{team}/invitation/{invitation}/cancel', 'InvitationsController@destroy');
	Route::post('/team/{team}/invitation/create', 'InvitationsController@store');
});

Route::group(['middleware' => 'auth'], function(){
	Route::get('/', 'TeamsController@index');
	Route::get('/tickets', 'TicketsController@index')->middleware('ispartofateam');
	Route::get('/tickets/{category}', 'TicketsController@index');
	Route::get('/tickets/{category}/{ticket}', 'TicketsController@show');
	Route::get('/tickets/{category}/{ticket}/members', 'TicketMembersController@index');
	Route::get('/tickets/{category}/{ticket}/messages', 'MessagesController@index');
	Route::get('/tickets/{category}/{ticket}/todos', 'TodosController@index');
	Route::get('/archive/tickets', 'ArchiveController@index');
	Route::post('/ticket/save', 'TicketsController@store');
	Route::post('/ticket/update', 'TicketsController@update');
	Route::get('/teams', 'TeamsController@index');
	Route::post('/team/create', 'TeamsController@store');
	Route::post('/invitation/accept', 'InvitationsController@accept');
	Route::get('/team/choose/{team}', 'TeamsController@setActiveTeam');
	Route::post('/team/{team}/categories/create', 'CategoriesController@store')
	->middleware('ispartofteam');
	Route::post('/subscription', 'SubscriptionsController@store');
	Route::patch('/subscription', 'SubscriptionsController@update');
	Route::delete('/subscription', 'SubscriptionsController@destroy');
	Route::get('/profile', 'ProfileController@index');
	Route::post('/avatar', 'ProfileController@store');
});

Route::post('/stripe/webhook', 'WebhooksController@handle');


