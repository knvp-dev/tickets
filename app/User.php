<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Ticket;
use App\Message;

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
    
}
