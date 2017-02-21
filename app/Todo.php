<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Ticket;

class Todo extends Model
{

    protected $guarded = [];

    public function ticket(){
    	return $this->belongsTo(Ticket::class);
    }

    public function complete(){
    	$this->completed = 1;
    	$this->save();
    }

    public function uncomplete(){
    	$this->completed = 0;
    	$this->save();
    }
}
