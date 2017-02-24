<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Events\MessageSent;
use App\Ticket;
use App\User;
use App\Message;
use Auth;

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
        $ticket->assignUser(Auth::user());
        return Ticket::whereId($ticket->id)->with(['category','status','priority','users'])->first();
    }

    public function update(Request $request){
        Ticket::whereId($request->id)->update($this->prepareRequestForUpdate($request)->toArray());
    }

    public function messages(Ticket $ticket){
        return Message::where('ticket_id',$ticket->id)->with('user')->orderBy('created_at','ASC')->get();
    }

    public function addMessage(Ticket $ticket, Request $request){
        $this->guardForUnAssignedUsers($ticket);
        $message = $ticket->addMessage($request->message);
        $data = Message::whereId($message->id)->with('user')->first();
        event(new MessageSent($data));
        return $data;
    }

    public function assignedUsers(Ticket $ticket){
        return $ticket->users;
    }

    public function assignUserToTicket(Ticket $ticket, User $user){
        $this->guardForUnAssignedUsers($ticket);
        $ticket->assignUser($user);
    }

    public function unAssignUserFromTicket(Ticket $ticket, User $user){
        $this->guardForUnAssignedUsers($ticket);
        $ticket->unAssignUser($user);
    }

    public function completeTicket(Ticket $ticket){
        $this->guardForUnAssignedUsers($ticket);
        $ticket->complete();
    }

    public function uncompleteTicket(Ticket $ticket){
        $this->guardForUnAssignedUsers($ticket);
        $ticket->uncomplete();
    }

    public function archive(Ticket $ticket){
        $this->guardForUnAssignedUsers($ticket);
        $ticket->archive();
    }

    public function unarchive(Ticket $ticket){
        $this->guardForUnAssignedUsers($ticket);
        $ticket->unarchive();
    }

    public function remove(Ticket $ticket){
        $this->guardForUnAssignedUsers($ticket);
        $ticket->delete();
    }

    public function prepareRequestForUpdate($data){
        unset($data['category']);
        unset($data['status']);
        unset($data['priority']);
        unset($data['users']);
        return $data;
    }

    protected function guardForUnAssignedUsers($ticket){
        if(!Auth::user()->isAssignedToTicket($ticket)){
            abort(403, 'Unauthorized');
        }
    }
}
