<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Team;
use App\User;

class TeamMembersController extends Controller
{

	public function index(Team $team){
		return view('pages.team.members', compact('team'));
	}

    public function store(Team $team){
    	$team->addMember(request('member'));
    }

    public function destroy(Team $team, User $user){
    	// unassign the user from all team tickets
    	foreach($user->tickets as $ticket){
    		if($ticket->team_id == $team->id){
    			$ticket->unAssignMember($user);
    		}
    	}
    	//remove the user from the team
    	$team->removeMember($user);
    	
    	return back();
    }
}
