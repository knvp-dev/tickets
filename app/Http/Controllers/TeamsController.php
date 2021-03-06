<?php

namespace App\Http\Controllers;

use Auth;
use App\Team;
use App\Invitation;
use Illuminate\Http\Request;

class TeamsController extends Controller
{

    public function __construct(){
        $this->middleware('auth');
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $teams = auth()->user()->teams;
        $invitations = Invitation::whereEmail(auth()->user()->email)->get();
        return view('auth.team', compact('teams', 'invitations'));
    }

    public function setActiveTeam(Team $team){
        session(['team_id' => $team->id]);
        return redirect('/tickets');
    }

    public function getActiveTeam(){
        return session('team_id');
    }

    public function members(Team $team){
        return $team->members;
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required'
        ]);

        if( ! Team::whereTitle($request->title)->exists()){
            $team = Team::create([
                'title' => $request->title,
                'slug' => sluggify($request->title),
                'owner_id' => auth()->id()
                ]);

            $team->addMember(auth()->user());
            $this->setActiveTeam($team);

            $team->categories()->create([
                'name' => 'General',
                'slug' => 'general',
                'color' => '#ce5a5a'
            ]);

            return redirect('/tickets');
        }

        return redirect()->back()->withErrors(['A team with this name already exists.']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
