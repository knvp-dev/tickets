<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Ticket;
use App\User;
use App\Team;
use Auth;

class TicketsController extends Controller
{

    protected $team;

    public function __construct(){
    	$this->middleware('auth');
    }

    /**
     * Return all tickets that are not archived and order by date of creation
     * @return Collection
     */
    public function index(){
       $team = Team::whereId(session('team_id'))->first();
       return view('pages.ticket.index', compact('team'));
    }

    public function tickets(){
        return Ticket::forTeam()->notArchived()->withRelations()->orderBy('created_at','DESC')->get();
    }

    /**
     * Fetch a ticket by id
     * @param  int $ticket_id
     * @return Ticket  
     */
    public function show(Ticket $ticket){
        $ticket = Ticket::whereId($ticket->id)->withRelations()->first();
        return view('pages.ticket.detail', compact('ticket'));
    }

    /**
     * Create a new ticket, assign author to the ticket
     * @param  Request $request New Ticket data
     * @return Ticket           Return the newly created ticket
     */
    public function store(){
        $team = Team::whereId(session('team_id'))->first();
        $new_ticket = [
            'title' => request('title'),
            'owner_id' => auth()->id(),
            'priority_id' => request('priority'),
            'category_id' => request('category')
        ];
        $ticket = $team->addticket($new_ticket);
        $ticket->assignMember(auth()->user());
        return back();
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
    public function assignedMembers(Ticket $ticket){
        return $ticket->members;
    }

    /**
     * Assign a user to a ticket
     * @param  Ticket $ticket
     * @param  User   $user
     */
    public function assignMember(Ticket $ticket, User $user){
        $ticket->assignMember($user);
    }

    /**
     * Unassign a user from a ticket
     * @param  Ticket $ticket
     * @param  User   $user
     */
    public function unAssignMember(Ticket $ticket, User $user){
        $ticket->unAssignMember($user);
    }

    /**
     * Complete a ticket
     * @param  Ticket $ticket
     */
    public function complete(Ticket $ticket){
        $ticket->complete();
    }

    /**
     * Uncomplete a ticket
     * @param  Ticket $ticket
     */
    public function uncomplete(Ticket $ticket){
        $ticket->uncomplete();
    }

    /**
     * Archive a ticket
     * @param  Ticket $ticket
     */
    public function archive(Ticket $ticket){
        $ticket->archive();
    }

    /**
     * Unarchive a ticket
     * @param  Ticket $ticket
     */
    public function unarchive(Ticket $ticket){
        $ticket->unarchive();
    }

    /**
     * Delete a ticket
     * @param  Ticket $ticket
     */
    public function delete(Ticket $ticket){
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
        unset($data['members']);
        unset($data['owner']);
        return $data;
    }
}
