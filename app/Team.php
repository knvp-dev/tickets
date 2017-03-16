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

    public function users(){
    	return $this->belongsToMany(User::class);
    }

    public function addUser(User $user){
    	return $this->users()->sync([$user->id], false);
    }

}
