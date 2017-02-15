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
    	return view('pages.tickets');
    }

    public function show(Ticket $ticket){
        return view('pages.ticket-detail', compact('ticket'));
    }

    public function store(Request $request){
        Ticket::create($request[0]);
    }

    public function tickets(){
        return Ticket::whereArchived(0)->with(['category','status','priority','customer','users'])->orderBy('created_at','ASC')->get();
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
        return 'Assigned';
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
}
