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
        $ticket_id = $ticket->id;
        return view('pages.ticket-detail', compact('ticket_id'));
    }

    public function store(Request $request){
        $ticket = Ticket::create($request[0]);
        return Ticket::whereId($ticket->id)->with(['category','status','priority','customer','users','todos'])->first();
    }

    public function detail(Ticket $ticket){
        return Ticket::whereId($ticket->id)->with(['category','status','priority','customer','users','todos'])->first();
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
}
