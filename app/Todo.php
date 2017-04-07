<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Ticket;
use App\User;

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

    public function author(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function scopeCompletedByUser($query, User $user){
        return $query->where('completed_by', $user->id);
    }

    public function scopeIsCompleted($query){
        return $query->where('completed',1);
    }
}
