<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

use App\Ticket;
use App\Message;
use App\Todo;
use App\Team;
use App\Role;

class UserTest extends TestCase
{
	use DatabaseMigrations;

	protected function setUp(){
		parent::setUp();
		$this->user = factory('App\User')->create();
	}

    /** @test */
    public function it_can_own_a_ticket(){
    	$ticket = factory(Ticket::class)->create(['owner_id' => $this->user->id]);
    	$this->assertEquals($this->user->name, $ticket->owner->name);
    }

    /** @test */
    public function it_can_have_messages(){
    	$message = factory(Message::class)->create(['user_id' => $this->user->id]);
    	$this->assertInstanceOf('App\Message', $this->user->messages->find($message));
    }

    /** @test */
    public function it_can_own_a_team(){
    	$team = factory(Team::class)->create(['owner_id' => $this->user->id]);

    	$this->assertEquals($this->user->name, $team->owner->name);
    	$this->assertInstanceOf('App\User', $team->owner);
    }

    /** @test */
    public function it_can_be_part_of_a_team(){
    	$team = factory(Team::class)->create();
    	$team->addUser($this->user);
    	$this->assertTrue($this->user->isPartOfATeam($team));
    }

    /** @test */
    public function it_can_be_part_of_a_specific_team(){
    	$team = factory(Team::class)->create();
    	$team->addUser($this->user);
    	$this->assertTrue($this->user->isPartOfTeam($team));
    }

    /** @test */
    public function it_can_be_part_of_many_teams(){
    	$team_1 = factory(Team::class)->create();
    	$team_2 = factory(Team::class)->create();

    	$team_1->addUser($this->user);
    	$team_2->addUser($this->user);

    	$this->assertCount(2, $this->user->teams);
    }

    /** @test */
    public function it_can_be_given_a_different_role(){
        $role = factory(Role::class)->create();
        $this->user->setRole($role);
        $this->assertEquals($this->user->role->id, $role->id);
    }

    /** @test */
    public function it_has_a_role(){
        $this->assertInstanceOf(Role::class, $this->user->role);
    }
}
