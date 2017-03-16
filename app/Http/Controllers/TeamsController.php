<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Team;
use Auth;

class TeamsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $teams = Auth::user()->teams;
        return view('auth.team', compact('teams'));
    }

    public function setActiveTeam(Team $team){
        session(['team_id' => $team->id]);
        return redirect('/');
    }

    public function users(Team $team){
        return $team->users;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(Team::whereTitle($request->title)->exists()){
            return redirect()->back()->withErrors(['A team with this name already exists.']);
        }

        $team = Team::create([
        'title' => $request->title,
        'owner_id' => Auth::user()->id
        ]);

        $team->addUser(Auth::user());

        $this->setActiveTeam($team);

        return redirect('/');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
