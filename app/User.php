<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Ticket;
use App\Message;
use App\Todo;
use App\Team;
use App\Role;

class User extends Authenticatable
{
    use Notifiable;

    protected $guarded = [];
    protected $hidden = ['password', 'remember_token',];

    public function tickets(){
    	return $this->belongsToMany(Ticket::class);
    }

    public function messages(){
    	return $this->hasMany(Message::class);
    }

    public function isAssignedToTicket(Ticket $ticket){
        return !! $this->tickets->find($ticket);
    }

    public function isPartOfATeam(){
        return ($this->teams()->count() > 0) ? true : false;
    }

    public function isPartOfTeam(Team $team){
        return !! $this->teams->find($team);
    }

    public function teams(){
        return $this->belongsToMany(Team::class);
    }

    public function role(){
        return $this->hasOne(Role::class, 'id', 'role_id');
    }

    public function setRole(Role $role){
        $this->role_id = $role->id;
        $this->save();
    }
    
}
