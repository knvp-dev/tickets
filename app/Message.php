<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Ticket;

class Message extends Model
{
	protected $guarded = [];

    public function user(){
    	return $this->belongsTo(User::class);
    }

    public function ticket(){
    	return $this->belongsTo(Ticket::class);
    }
}
