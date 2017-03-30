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
    	$this->team = create('App\Team', ['owner_id' => $this->owner->id, 'size' => 3]);
    	$this->team->addMember($this->owner);
    	session(['team_id' => $this->team->id]);
    }

    /** @test */
    function a_team_member_can_add_a_ticket(){
    	$this->loginAsMember();
    	$ticket = make('App\Ticket', ['owner_id' => auth()->id(), 'team_id' => session('team_id')]);
    	$this->post('/ticket/save', $ticket->toArray());
    	$this->assertCount(1, $this->team->tickets);
    }

    /** @test */
    function a_team_owner_can_assign_new_members_to_his_team(){
    	$this->loginAsOwner();
    	$new_member = create('App\User');
    	$this->post('/team/' . $this->team->id . '/members/add', ['member' => $new_member]);
    	$this->assertCount(2, $this->team->members);
    }

    /** @test */
    function a_team_has_a_maximum_size(){

        $this->loginAsOwner();

        $user1 = $this->newUser();
        $user2 = $this->newUser();
        
        $this->team->addMember($user1);
        $this->team->addMember($user2);

        $invitation = make('App\Invitation', ['team_id' => $this->team->id]);
        $this->post('/team/' . $this->team->id . '/invitation/create', $invitation->toArray());

        $this->assertCount(0, $this->team->invitations);
    }

    /** @test */
    function a_team_owner_can_remove_members_from_his_team(){
        $this->loginAsOwner();
        $user = $this->newUser();
        $this->team->addMember($user);
        $this->get('/team/' . $this->team->id . '/members/' . $user->id . '/remove');
        $this->assertCount(1, $this->team->members);
    }

    /** @test */
    function a_team_member_can_not_assign_new_members_to_the_team(){
    	$this->loginAsMember();
    	$new_member = create('App\User');
    	$this->post('/team/' . $this->team->id . '/members/add', ['member' => $new_member]);
    	$this->assertCount(2, $this->team->members);
    }

    /** @test */
    function a_team_owner_can_invite_people_to_his_team(){
        $this->loginAsOwner();
        $invitation = make('App\Invitation');
        $this->post('/team/' . $this->team->id .'/invitation/create', $invitation->toArray());
        $this->assertCount(1, $this->team->invitations);
    }

    /** @test */
    function a_team_owner_can_cancel_an_invitation(){
        $this->loginAsOwner();
        $invitation = create('App\Invitation', ['team_id' => $this->team->id]);
        $this->get('/team/'. $this->team->id .'/invitation/' . $invitation->id . '/cancel');
        $this->assertCount(0, $this->team->invitations);
    }

    /** @test */
    function a_invited_user_can_accept_an_invitation(){
        $this->login();
        $invitation = create('App\Invitation', ['team_id' => $this->team->id, 'email' => $this->user->email]);
        $this->post('/invitation/accept', ['invitation_id' => $invitation->id]);
        $this->assertCount(2, $this->team->members);
    }

    protected function login(){
        $this->user = create('App\User');
        $this->be($this->user);
    }

    protected function loginAsMember(){
    	$this->member = create('App\User');
    	$this->team->addMember($this->member);
    	$this->be($this->member);
    }

    protected function loginAsOwner(){
    	$this->be($this->owner);
    }

    public function newUser(){
        return create('App\User');
    }
}
