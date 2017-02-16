<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Ticket;

class Todo extends Model
{
    public function ticket(){
    	return $this->belongsTo(Ticket::class);
    }

    public function complete(){
    	$this->complete = 1;
    	$this->save();
    }

    public function uncomplete(){
    	$this->complete = 0;
    	$this->save();
    }
}
