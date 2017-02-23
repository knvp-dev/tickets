<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Priority;
use App\Customer;
use App\Category;
use App\Status;
use App\Todo;
use App\Message;

class Ticket extends Model
{
    protected $guarded = [];

    public function users(){
    	return $this->belongsToMany(User::class);
    }

    public function customer(){
    	return $this->belongsTo(Customer::class);
    }

    public function priority(){
    	return $this->hasOne(Priority::class, 'id', 'priority_id');
    }

    public function category(){
    	return $this->hasOne(Category::class, 'id','category_id');
    }

    public function status(){
    	return $this->hasOne(Status::class, 'id','status_id');
    }

    public function todos(){
    	return $this->hasMany(Todo::class);
    }

    public function messages(){
        return $this->hasMany(Message::class);
    }

    public function scopeComplete($query){
    	return $query->where('completed',1);
    }

    public function scopeIncomplete($query){
    	return $query->where('complete',0);
    }

    public function assignUser($user){
        $this->users()->sync([$user->id], false);
    }

    public function unAssignUser($user){
        $this->users()->detach($user);
    }

    public function amountOfUsers(){
        return $this->users()->count();
    }

    public function addTodo($todo){
        $this->todos()->create($todo);
    }

    public function addMessage($message){
        $this->messages()->create($message);
    }

    public function complete(){
        $this->completed = 1;
        $this->save();
    }

    public function uncomplete(){
        $this->completed = 0;
        $this->save();
    }

    public function archive(){
        $this->archived = 1;
        $this->save();
    }

    public function unarchive(){
        $this->archived = 0;
        $this->save();
    }
}
