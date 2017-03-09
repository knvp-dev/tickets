<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Ticket;
use App\User;
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
       return Ticket::notArchived()->withRelations()->orderBy('created_at','ASC')->get();
    }

    /**
     * Fetch a ticket by id
     * @param  int $ticket_id
     * @return Ticket  
     */
    public function show($ticket_id){
        return Ticket::whereId($ticket_id)->withRelations()->first();
    }

    /**
     * Create a new ticket, assign author to the ticket
     * @param  Request $request New Ticket data
     * @return Ticket           Return the newly created ticket
     */
    public function store(Request $request){
        $ticket = Ticket::create($request->ticket);
        $ticket->assignUser(Auth::user());
        return Ticket::whereId($ticket->id)->withRelations()->first();
    }

    /**
     * Update a ticket's information
     * @param  Request $request
     */
    public function update(Request $request){
        Ticket::whereId($request->id)->update($this->prepareRequestForUpdate($request)->toArray());
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
    public function assignUser(Ticket $ticket, User $user){
        $this->guardForUnAssignedUsers($ticket);
        $ticket->assignUser($user);
    }

    /**
     * Unassign a user from a ticket
     * @param  Ticket $ticket
     * @param  User   $user
     */
    public function unAssignUser(Ticket $ticket, User $user){
        $this->guardForUnAssignedUsers($ticket);
        $ticket->unAssignUser($user);
    }

    /**
     * Complete a ticket
     * @param  Ticket $ticket
     */
    public function complete(Ticket $ticket){
        $this->guardForUnAssignedUsers($ticket);
        $ticket->complete();
    }

    /**
     * Uncomplete a ticket
     * @param  Ticket $ticket
     */
    public function uncomplete(Ticket $ticket){
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
     * Delete a ticket
     * @param  Ticket $ticket
     */
    public function delete(Ticket $ticket){
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
     * Make sure the authenticated user is assigned to the ticket
     * @param  Ticket $ticket
     */
    protected function guardForUnAssignedUsers($ticket){
        if(!Auth::user()->isAssignedToTicket($ticket)){
            abort(403, 'Unauthorized');
        }
    }
}
