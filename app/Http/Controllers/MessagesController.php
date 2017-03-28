<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Events\MessageSent;

use Auth;
use App\Message;
use App\Ticket;

class MessagesController extends Controller
{
	/**
     * Fetch all messages for a ticket
     * @param  Ticket $ticket
     * @return Message
     */
    public function index($categoryId, Ticket $ticket){
        return Message::where('ticket_id',$ticket->id)->with('user')->orderBy('created_at','ASC')->get();
    }

	/**
     * Add a new message to a ticket
     * @param Ticket  $ticket
     * @param Request $request
     * @return  Message
     */
    public function store(Ticket $ticket){

        $this->validate(request(), [
            'body' => 'required'
        ]);

        $new_message = [
            'user_id' => auth()->id(),
            'body' => request('body')
        ];
        
        $message = $ticket->addMessage($new_message);
        event(new MessageSent($message));
        return back();
    }
}
