<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Ticket;

class ArchiveController extends Controller
{
    /**
     * Fetch all tickets that are archived
     * @return Collection
     */
    public function index(){
    	return Ticket::Archived()->withRelations()->orderBy('updated_at','ASC')->get();
    }

}
