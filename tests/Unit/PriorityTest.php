<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Priority;
use App\Ticket;

class PriorityTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(){
    	parent::setUp();
    	$this->priority = factory(Priority::class)->create();
    }

    /** @test */
    public function it_can_have_many_tickets(){
    	$ticket_1 = factory(Ticket::class)->create();
		$ticket_2 = factory(Ticket::class)->create();

		$ticket_1->setPriority($this->priority);
		$ticket_2->setPriority($this->priority);

		$this->assertCount(2, $this->priority->tickets);
    }
}
