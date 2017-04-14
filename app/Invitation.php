<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Invitation extends Model
{
	protected $guarded = [];

    public function team(){
    	return $this->belongsTo(Team::class);
    }
}
