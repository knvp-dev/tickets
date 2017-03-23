<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

use App\Ticket;
use App\Todo;

class ParticipateInTicketTest extends TestCase
{
	use DatabaseMigrations;
    
    public function setUp(){
    	parent::setUp();
    	$this->owner = create('App\User');
    	$this->team = create('App\Team', ['owner_id' => $this->owner->id]);
    	$this->ticket = create('App\Ticket', ['owner_id' => $this->owner->id]);
    	$this->ticket->assignUser($this->owner);
    }

    /** @test */
    function a_ticket_owner_can_assign_other_team_members_to_a_ticket(){
    	$this->loginAsOwner();
    	$this->addTeamMember();
    	$this->get('/ticket/' . $this->ticket->id . '/assign/' . $this->new_team_member->id);
    	$this->assertCount(2, $this->ticket->users);
    }

    /** @test */
    function a_ticket_owner_can_complete_a_ticket(){
    	$this->loginAsOwner();
    	$this->get('/ticket/' . $this->ticket->id . '/complete');
    	$this->assertEquals(1, Ticket::first()->completed);
    }

    /** @test */
    function a_ticket_owner_can_delete_a_ticket(){
    	$this->loginAsOwner();
    	$this->delete('/ticket/' . $this->ticket->id . '/delete');
    	$this->assertCount(0, Ticket::all());
    }

    /** @test */
    function a_ticket_member_can_not_delete_a_ticket(){
    	$this->expectException('\Exception');
    	$this->loginAsMember();
    	$response = $this->delete('/ticket/' . $this->ticket->id . '/delete');
    }

    /** @test */
    function a_ticket_member_can_add_a_todo_to_a_ticket(){
    	$this->loginAsMember();
    	$todo = make('App\Todo');
    	$this->post('/ticket/' . $this->ticket->id . '/todo/save', $todo->toArray());
    	$this->assertCount(1, $this->ticket->todos);
    }

    /** @test */
    function a_ticket_member_can_add_a_message_to_a_ticket(){
    	$this->loginAsMember();
    	$message = make('App\Message');
    	$this->post('/ticket/' . $this->ticket->id . '/messages/create', $message->toArray());
    	$this->assertCount(1, $this->ticket->messages);
    }

    /** @test */
    function a_ticket_member_can_complete_a_todo(){
    	$this->loginAsMember();
    	$todo = create('App\Todo', ['ticket_id' => $this->ticket->id]);
    	$this->get('/ticket/' . $this->ticket->id . '/todo/' . $todo->id . '/complete');
    	$this->assertEquals(1, Todo::first()->completed);
    }

    /** @test */
    function a_ticket_member_can_delete_a_todo(){
    	$this->loginAsMember();
    	$todo = create('App\Todo', ['ticket_id' => $this->ticket->id]);
    	$this->delete('/ticket/' . $this->ticket->id . '/todo/' . $todo->id . '/delete');
    	$this->assertCount(0, $this->ticket->todos);
    }

    protected function addTeamMember(){
    	$this->new_team_member = create('App\User');
    	$this->team->addMember($this->new_team_member);
    }

    function loginAsMember(){
    	$this->member = create('App\User');
    	$this->team->addMember($this->member);
    	$this->ticket->assignUser($this->member);
    	$this->be($this->member);
    }

    function loginAsOwner(){
    	$this->be($this->owner);
    }
}
