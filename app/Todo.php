<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Ticket;

class Todo extends Model
{
    public function ticket(){
    	return $this->belongsTo(Ticket::class);
    }
}
