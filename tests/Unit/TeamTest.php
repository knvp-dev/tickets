<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

use App\Team;
use App\User;

class TeamTest extends TestCase
{
	use DatabaseMigrations;

    protected function setUp(){
    	parent::setUp();
    	$this->team = factory(Team::class)->create();
    }

    /** @test */
    public function it_has_an_owner(){
    	$this->assertInstanceOf(User::class, $this->team->owner);
    }

    /** @test */
    public function it_can_have_many_members(){
    	$this->team->addMember(factory(User::class)->create());
    	$this->team->addMember(factory(User::class)->create());

    	$this->assertCount(2, $this->team->members);
    }
}
