<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Ticket;

class Priority extends Model
{
    public function tickets(){
    	return $this->hasMany(Ticket::class);
    }
}
