<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;
use App\Team;
use App\Ticket;

class TicketMembersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($categoryId, Ticket $ticket)
    {
        $teamMembers = $ticket->team->members()->whereNotIn('id', $ticket->members->pluck('id'))->get();
        return view('pages.ticket.members', compact('teamMembers', 'ticket'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Ticket $ticket, User $user)
    {
        $ticket->assignMember($user);
        return back()->with('message', $user->name . ' was added to this ticket');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Ticket $ticket, User $user)
    {
        $ticket->unAssignMember($user);
        return back();
    }

}
