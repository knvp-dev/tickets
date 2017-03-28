<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Team;

class TeamMembersController extends Controller
{

	public function index(Team $team){
		return view('pages.team.members', compact('team'));
	}

    public function store(Team $team){
    	$team->addMember(request('member'));
    }
}
