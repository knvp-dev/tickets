<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasMembers;
use App\User;
use App\Priority;
use App\Customer;
use App\Category;
use App\Status;
use App\Todo;
use App\Message;

class Ticket extends Model
{
    use HasMembers;

    protected $guarded = [];

    protected $with = ['category', 'owner', 'members'];

    public function getRouteKeyName(){
        return 'slug';
    }

    public function path(){
        return "/tickets/" . $this->category->slug . "/" . $this->slug;
    }

    public function owner(){
        return $this->hasOne(User::class, 'id', 'owner_id');
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
    	return $this->hasMany(Todo::class)->latest();
    }

    public function messages(){
        return $this->hasMany(Message::class)->with('user')->latest();
    }

    public function team(){
        return $this->belongsTo(Team::class);
    }

    public function addTodo($todo){
        return $this->todos()->create($todo);
    }

    public function addMessage($message){
        return $this->messages()->create($message);
    }

    public function complete(){
        $this->completed = 1;
        $this->status_id = 2;
        $this->save();
    }

    public function uncomplete(){
        $this->completed = 0;
        $this->status_id = 1;
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

    public function completedTodos(){
        return $this->todos->where('completed', 1);
    }

    public function completedTodosForUser(){
        return $this->todos->where('completed_by', auth()->id());
    }

    public function progressInPercent(){
        $todos_count = $this->todos->count();
        return ($todos_count > 0) ? (count($this->completedTodos()) / $todos_count) * 100 : 0;
    }

    public function belongsToTeam($team_id){
        return ($this->team_id == $team_id) ? true : false;
    }

    public function scopeArchived($query){
        return $query->whereArchived(1);
    }

    public function scopeNotArchived($query){
        return $query->whereArchived(0);
    }

    public function scopeForTeam($query){
        return $query->where('team_id', session('team_id'));
    }

    public function scopeFilter($query, $filters){
        return $filters->apply($query);
    }
}
