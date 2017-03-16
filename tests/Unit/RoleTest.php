<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\User;

class RoleTest extends TestCase
{
    use DatabaseMigrations;

	protected function setUp(){
		parent::setUp();
		$this->role = factory('App\Role')->create();
	}

	/** @test */
	public function it_can_have_many_users(){
		$user_1 = factory(User::class)->create();
		$user_2 = factory(User::class)->create();

		$user_1->setRole($this->role);
		$user_2->setRole($this->role);

		$this->assertCount(2, $this->role->users);
	}
}
