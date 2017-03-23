<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ParticipateInTeamTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp(){
    	parent::setUp();
    	$this->owner = create('App\User');
    	$this->team = create('App\Team', ['owner_id' => $this->owner->id]);
    	$this->team->addMember($this->owner);
    	session(['team_id' => $this->team->id]);
    }

    /** @test */
    function a_team_member_can_add_a_ticket(){
    	$this->loginAsMember();
    	$ticket = make('App\Ticket', ['owner_id' => auth()->id()]);
    	$this->post('/team/' . $this->team->id . '/ticket/save', $ticket->toArray());
    	$this->assertCount(1, $this->team->tickets);
    }

    /** @test */
    function a_team_owner_can_assign_new_users_to_his_team(){
    	$this->loginAsOwner();
    	$new_member = create('App\User');
    	$this->post('/team/' . $this->team->id . '/members/add', ['member' => $new_member]);
    	$this->assertCount(2, $this->team->members);
    }

    /** @test */
    function a_team_member_can_not_assign_new_users_to_the_team(){
    	$this->loginAsMember();
    	$new_member = create('App\User');
    	$this->post('/team/' . $this->team->id . '/members/add', ['member' => $new_member]);
    	$this->assertCount(2, $this->team->members);
    }

    protected function loginAsMember(){
    	$this->member = create('App\User');
    	$this->team->addMember($this->member);
    	$this->be($this->member);
    }

    protected function loginAsOwner(){
    	$this->be($this->owner);
    }
}
