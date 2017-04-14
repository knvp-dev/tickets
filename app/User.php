<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Billing\Billable;
use App\Ticket;
use App\Message;
use App\Todo;
use App\Team;

class User extends Authenticatable
{
    use Notifiable, Billable;

    protected $guarded = [];
    protected $hidden = ['password', 'remember_token',];

    protected $dates = [
        'subscription_end_at'
    ];

    public function tickets(){
    	return $this->belongsToMany(Ticket::class);
    }

    public function messages(){
    	return $this->hasMany(Message::class);
    }

    public function isAssignedToTicket($ticket){
        return !! $this->tickets->find($ticket);
    }

    public function isPartOfATeam(){
        return ($this->teams->count() > 0) ? true : false;
    }

    public function isPartOfTeam($team){
        return !! $this->teams->find($team);
    }

    public function teams(){
        return $this->belongsToMany(Team::class);
    }

    public function getTeamsCountAttribute(){
        return $this->teams->count();
    }

    public function ownsTeam(Team $team){
        return ($this->id == $team->owner_id) ? true : false;
    }

    public function ownsTicket(Ticket $ticket){
        return ($this->id == $ticket->owner_id) ? true : false;
    }

    public function todos(){
        return $this->hasMany(Todo::class);
    }

    public function completedTodos(){
        return count(Todo::completedByUser($this)->get());
    }

}
