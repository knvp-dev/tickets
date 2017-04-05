<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ReadTicketsTest extends TestCase
{

	use DatabaseMigrations;

	public function setUp(){
		parent::setUp();
		$this->owner = create('App\User');
    	$this->team = create('App\Team', ['owner_id' => $this->owner->id, 'size' => 3]);
    	$this->team->addMember($this->owner);
    	$this->ticket = create('App\Ticket', ['owner_id' => $this->owner->id, 'team_id' => $this->team->id]);
    	session(['team_id' => $this->team->id]);
	}

    /** @test */
    function a_member_can_view_all_tickets(){
    	$this->loginAsMember();

    	$this->get('/tickets')
    		->assertSee($this->ticket->title);
    }

    /** @test */
    function a_member_can_view_a_specific_ticket(){
    	$this->loginAsMember();

    	$this->get($this->ticket->path())
    		->assertSee($this->ticket->title);
    }

    /** @test */
    function a_member_can_filter_tickets_by_owner(){
    	$this->loginAsMember();

    	$user = create('App\User', ['name' => "JohnDoe"]);
    	$this->team->addMember($user);

    	$ticketByJohn = create('App\Ticket', ['owner_id' => $user->id, 'team_id' => $this->team->id]);
    	$ticketNotByJohn = create('App\Ticket', ['team_id' => $this->team->id]);

    	$this->get('/tickets?by=JohnDoe')
    		->assertSee($ticketByJohn->title)
    		->assertDontSee($ticketNotByJohn->title);
    }

    function loginAsMember(){
    	$this->member = create('App\User');
    	$this->team->addMember($this->member);
    	$this->be($this->member);
    }
}
