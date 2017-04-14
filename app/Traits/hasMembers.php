<?php 

namespace App\Traits;
use App\User;

trait HasMembers{

	public function members(){
    	return $this->belongsToMany(User::class);
    }

    public function getMembersCountAttribute(){
        return $this->members->count();
    }

    public function assignMember($member){
        $this->members()->sync([$member->id], false);
    }

    public function unAssignMember($member){
        $this->members()->detach($member);
    }
}