<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Ticket;

class TicketsController extends Controller
{
    public function __construct(){
    	$this->middleware('auth');
    }

    public function index(){
       return Ticket::whereArchived(0)->with(['category','status','priority','users'])->orderBy('created_at','ASC')->get();
    }

    public function show($ticket_id){
        return Ticket::whereId($ticket_id)->with(['category','status','priority','users'])->first();
    }

    public function store(Request $request){
        $ticket = Ticket::create($request[0]);
        return Ticket::whereId($ticket->id)->with(['category','status','priority','users'])->first();
    }

    public function update(Request $request){
        Ticket::whereId($request->id)->update($this->prepareRequestForUpdate($request)->toArray());
    }

    public function assignedUsers(Ticket $ticket){
        return $ticket->users;
    }

    public function assignUserToTicket(Request $request){
        $ticket = Ticket::whereId($request->ticket_id)->with('users')->first();
        $ticket->users()->detach();
        foreach($request->users as $user){
            $ticket->users()->attach($user['id']);
        }
    }

    public function completeTicket(Ticket $ticket){
        $ticket->complete();
    }

    public function uncompleteTicket(Ticket $ticket){
        $ticket->uncomplete();
    }

    public function archive(Ticket $ticket){
        $ticket->archive();
    }

    public function unarchive(Ticket $ticket){
        $ticket->unarchive();
    }

    public function prepareRequestForUpdate($data){
        unset($data['category']);
        unset($data['status']);
        unset($data['priority']);
        unset($data['users']);
        return $data;
    }
}
