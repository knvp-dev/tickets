<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Ticket;

class ArchiveController extends Controller
{
    public function index(){
    	return view('pages.archive');
    }

    /**
     * Fetch all tickets that are archived
     * @return Collection
     */
    public function archivedTickets(){
    	return Ticket::whereArchived(1)->with(['category','status','priority','users'])->orderBy('updated_at','ASC')->get();
    }
}
