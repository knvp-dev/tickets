<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Events\MessageSent;
use App\Ticket;
use App\User;
use App\Message;

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

    public function messages(Ticket $ticket){
        return Message::where('ticket_id',$ticket->id)->with('user')->orderBy('created_at','ASC')->get();
    }

    public function addMessage(Ticket $ticket, Request $request){
        $message = $ticket->addMessage($request->message);
        $data = Message::whereId($message->id)->with('user')->first();
        event(new MessageSent($data));
    }

    public function assignedUsers(Ticket $ticket){
        return $ticket->users;
    }

    public function assignUserToTicket(Ticket $ticket, User $user){
        $ticket->assignUser($user);
    }

    public function unAssignUserFromTicket(Ticket $ticket, User $user){
        $ticket->unAssignUser($user);
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

    public function remove(Ticket $ticket){
        $ticket->delete();
    }

    public function prepareRequestForUpdate($data){
        unset($data['category']);
        unset($data['status']);
        unset($data['priority']);
        unset($data['users']);
        return $data;
    }
}
