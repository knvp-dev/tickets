<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mail;
use App\Mail\TeamInvitation;
use App\Invitation;
use App\Team;
use App\User;

class InvitationsController extends Controller
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
        //
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
    public function store(Team $team)
    {
       if($team->hasReachedMaximumSize()){
            return back()->withErrors(['Your team has reached the maximum size, upgrade to invite more members to your team!']);
       }

        $this->validate(request(), [
            'email' => 'required|email'
        ]);

        if(Invitation::whereEmail(request('email'))->where('team_id', $team->id)->exists())
        {
            return back()->withErrors(['This email address has already been invited.']);
        }else{
            if($team->members->pluck('email')->contains(request('email'))){
                return back()->withErrors(['This user is already part of your team.']);
            }
        }

        $invitation = Invitation::create([
            'team_id' => $team->id,
            'email' => request('email'),
            'token' => str_random()
        ]);

        $email = new TeamInvitation($invitation);
        Mail::to(request('email'))->send($email);

        return back();
        
    }

    public function accept(){
        $invite = Invitation::whereToken(request('token'))->first();
        $user = User::whereEmail($invite->email)->first();
        $invite->team->addMember($user);
        $invite->delete();
        return back();
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
    public function destroy(Team $team, Invitation $invitation)
    {
        $invitation->delete();
        return back();
    }
}
