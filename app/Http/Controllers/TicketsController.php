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

    /**
     * Return all tickets that are not archived and order by date of creation
     * @return Collection
     */
    public function index(){
       return Ticket::whereArchived(0)->with(['category','status','priority','users'])->orderBy('created_at','ASC')->get();
    }

    /**
     * Fetch a ticket by id
     * @param  int $ticket_id
     * @return Ticket  
     */
    public function show($ticket_id){
        return Ticket::whereId($ticket_id)->with(['category','status','priority','users','owner'])->first();
    }

    /**
     * Create a new ticket, assign users to the ticket
     * @param  Request $request New Ticket data
     * @return Ticket           Return the newly created ticket
     */
    public function store(Request $request){
        $ticket = Ticket::create($request->ticket);
        $ticket->assignUser(Auth::user());
        return Ticket::whereId($ticket->id)->with(['category','status','priority','users'])->first();
    }

    /**
     * Update a ticket's information
     * @param  Request $request
     */
    public function update(Request $request){
        Ticket::whereId($request->id)->update($this->prepareRequestForUpdate($request)->toArray());
    }

    /**
     * Fetch all messages for a ticket
     * @param  Ticket $ticket
     * @return Message
     */
    public function messages(Ticket $ticket){
        return Message::where('ticket_id',$ticket->id)->with('user')->orderBy('created_at','ASC')->get();
    }

    /**
     * Create new message for a ticket
     * @param Ticket  $ticket
     * @param Request $request
     * @return  Message
     */
    public function addMessage(Ticket $ticket, Request $request){
        $this->guardForUnAssignedUsers($ticket);
        $message = $ticket->addMessage($request->message);
        $data = Message::whereId($message->id)->with('user')->first();
        event(new MessageSent($data));
        return $data;
    }

    /**
     * Return all users assigned to a ticket
     * @param  Ticket $ticket
     * @return User
     */
    public function assignedUsers(Ticket $ticket){
        return $ticket->users;
    }

    /**
     * Assign a user to a ticket
     * @param  Ticket $ticket
     * @param  User   $user
     */
    public function assignUserToTicket(Ticket $ticket, User $user){
        $this->guardForUnAssignedUsers($ticket);
        $ticket->assignUser($user);
    }

    /**
     * Unassign a user from a ticket
     * @param  Ticket $ticket
     * @param  User   $user
     */
    public function unAssignUserFromTicket(Ticket $ticket, User $user){
        $this->guardForUnAssignedUsers($ticket);
        $ticket->unAssignUser($user);
    }

    /**
     * Complete a ticket
     * @param  Ticket $ticket
     */
    public function completeTicket(Ticket $ticket){
        $this->guardForUnAssignedUsers($ticket);
        $ticket->complete();
    }

    /**
     * Uncomplete a ticket
     * @param  Ticket $ticket
     */
    public function uncompleteTicket(Ticket $ticket){
        $this->guardForUnAssignedUsers($ticket);
        $ticket->uncomplete();
    }

    /**
     * Archive a ticket
     * @param  Ticket $ticket
     */
    public function archive(Ticket $ticket){
        $this->guardForUnAssignedUsers($ticket);
        $ticket->archive();
    }

    /**
     * Unarchive a ticket
     * @param  Ticket $ticket
     */
    public function unarchive(Ticket $ticket){
        $this->guardForUnAssignedUsers($ticket);
        $ticket->unarchive();
    }

    /**
     * Remove a ticket
     * @param  Ticket $ticket
     */
    public function remove(Ticket $ticket){
        $this->guardForUnAssignedUsers($ticket);
        $ticket->delete();
    }

    /**
     * Unset all relationship data from request
     * @param  Array $data
     * @return Array
     */
    public function prepareRequestForUpdate($data){
        unset($data['category']);
        unset($data['status']);
        unset($data['priority']);
        unset($data['users']);
        unset($data['owner']);
        return $data;
    }

    /**
     * Make sure the authenticated user is assigned to the ticket it calls an action for
     * @param  Ticket $ticket
     */
    protected function guardForUnAssignedUsers($ticket){
        if(!Auth::user()->isAssignedToTicket($ticket)){
            abort(403, 'Unauthorized');
        }
    }
}
