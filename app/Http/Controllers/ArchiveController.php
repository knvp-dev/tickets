<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Ticket;

class ArchiveController extends Controller
{
    public function index(){
    	return view('pages.archive');
    }

    public function archivedTickets(){
    	return Ticket::whereArchived(1)->with(['category','status','priority','customer','users'])->orderBy('updated_at','ASC')->get();
    }
}
