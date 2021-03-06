<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Filters\TicketFilters;
use App\Ticket;
use App\User;
use App\Team;
use App\Category;
use App\Priority;
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
        $tickets = Ticket::forTeam()
                         ->filter($filters)
                         ->orderBy('created_at', 'desc');

        if ($category->exists) $tickets = $category->tickets();

        $tickets = $tickets->paginate(10);

        $team = Team::whereId(session('team_id'))
                     ->first();

        return view('pages.ticket.index', compact('team', 'tickets'));
    }

    /**
     * Fetch a ticket by id
     * @param  int $ticket_id
     * @return Ticket  
     */
    public function show($categoryId, Ticket $ticket){
        return view('pages.ticket.detail', [
            'ticket' => $ticket,
            'user' => auth()->user()
        ]);
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
            'slug' => sluggify(request('title')),
            'owner_id' => auth()->id(),
            'priority_id' => request('priority_id'),
            'category_id' => request('category_id')
        ];
        
        $ticket = $team->addticket($new_ticket);
        $ticket->assignMember(auth()->user());
        return back()->with('message', 'Ticket created successfully!');
    }

    public function edit(Ticket $ticket){
        $categories = Category::all();
        $priorities = Priority::all();
        return view('pages.ticket.edit', compact('ticket', 'categories', 'priorities'));
    }

    /**
     * Update a ticket's information
     * @param  Request $request
     */
    public function update(Ticket $ticket){
        $this->validate(request(), [
            'title' => 'required',
            'description' => 'required'
        ]);

        $ticket->update(request()->all());
        return redirect($ticket->path());
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
        $ticket->messages()->delete();
        $ticket->todos()->delete();
        $ticket->delete();
        return redirect('/tickets');
    }
}
