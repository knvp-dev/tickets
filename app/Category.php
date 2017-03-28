<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Ticket;
use App\Team;

class Category extends Model
{
	public function getRouteKeyName(){
    	return 'slug';
    }
    
    public function tickets(){
    	return $this->hasMany(Ticket::class);
    }

    public function team(){
    	return $this->hasOne(Team::class);
    }

    public function scopeForTeam($query){
        return $query->where('team_id', session('team_id'));
    }
}
