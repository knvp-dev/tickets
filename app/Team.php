<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\User;

class Team extends Model
{

	protected $fillable = ['title','owner_id'];

	public function owner(){
		return $this->hasOne(User::class, 'id', 'owner_id');
	}

    public function members(){
    	return $this->belongsToMany(User::class);
    }

    public function tickets(){
        return $this->hasMany(Ticket::class);
    }

    public function categories(){
        return $this->hasMany(Category::class);
    }

    public function addMember(User $user){
    	return $this->members()->sync([$user->id], false);
    }

    public function addTicket($ticket){
        return $this->tickets()->create($ticket);
    }

    public function invitations(){
        return $this->hasMany(Invitation::class);
    }

}
