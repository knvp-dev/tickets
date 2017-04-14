<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Ticket;
use App\User;

class Todo extends Model
{
    protected $guarded = [];

    protected $with = ['resolver'];

    public function ticket(){
    	return $this->belongsTo(Ticket::class);
    }

    public function complete(){
    	$this->completed = 1;
        $this->completed_by = auth()->id();
    	$this->save();
    }

    public function uncomplete(){
    	$this->completed = 0;
        $this->completed_by = null;
    	$this->save();
    }

    public function author(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function resolver(){
        return $this->hasOne(User::class, 'id', 'completed_by');
    }
}
