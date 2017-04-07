<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Filters\TicketFilters;
use App\Ticket;
use App\User;
use App\Team;
use App\Category;
use Auth;

class TicketsController extends Controller
{

    protected $team;

    public function __construct(){
    	$this->middleware(['auth', 'teamsession']);
    }

    /**
     * Return all tickets that are not archived and order by date of creation
     * @return Collection
     */
    public function index(Category $category, TicketFilters $filters){
        $tickets = Ticket::forTeam()->filter($filters);
        if ($category->exists) $tickets = $category->tickets();
        $tickets = $tickets->get();
        $team = Team::whereId(session('team_id'))->first();
        $categories = Category::forTeam()->get();
        return view('pages.ticket.index', compact('team', 'tickets', 'categories', 'category'));
    }

    /**
     * Fetch a ticket by id
     * @param  int $ticket_id
     * @return Ticket  
     */
    public function show($categoryId, Ticket $ticket){
        $ticket = Ticket::whereId($ticket->id)->withRelations()->first();
        return view('pages.ticket.detail', compact('ticket'));
    }

    /**
     * Create a new ticket, assign author to the ticket
     * @param  Request $request New Ticket data
     * @return Ticket           Return the newly created ticket
     */
    public function store(){

        $this->validate(request(),[
            'title' => 'required',
            'priority_id' => 'required',
            'category_id' => 'required'
            ]);

        $team = Team::whereId(session('team_id'))->first();
        
        $new_ticket = [
        'title' => request('title'),
        'slug' => $this->prettyUrl(request('title')),
        'owner_id' => auth()->id(),
        'priority_id' => request('priority_id'),
        'category_id' => request('category_id')
        ];
        
        $ticket = $team->addticket($new_ticket);
        $ticket->assignMember(auth()->user());
        return back()->with('message', 'Ticket created successfully!');
    }

    /**
     * Update a ticket's information
     * @param  Request $request
     */
    public function update(){
        Ticket::whereId($request->id)->update(request()->all());

        return back();
    }

    /**
     * Return all users assigned to a ticket
     * @param  Ticket $ticket
     * @return User
     */
    public function members($categoryId, Ticket $ticket){
        return $ticket->members;
    }

    /**
     * Complete a ticket
     * @param  Ticket $ticket
     */
    public function complete(Ticket $ticket){
        $ticket->complete();
        return back();
    }

    /**
     * Uncomplete a ticket
     * @param  Ticket $ticket
     */
    public function uncomplete(Ticket $ticket){
        $ticket->uncomplete();
        return back();
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
    public function destroy(Ticket $ticket){
        $ticket->delete();
        return redirect('/tickets');
    }

    protected function prettyUrl($string) {
        //Lower case everything
        $string = strtolower($string);
        //Make alphanumeric (removes all other characters)
        $string = preg_replace("/[^a-z0-9_\s-]/", "", $string);
        //Clean up multiple dashes or whitespaces
        $string = preg_replace("/[\s-]+/", " ", $string);
        //Convert whitespaces and underscore to dash
        $string = preg_replace("/[\s_]/", "-", $string);
        return $string;
    }
}
