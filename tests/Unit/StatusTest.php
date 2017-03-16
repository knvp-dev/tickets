<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Status;
use App\Ticket;

class StatusTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(){
    	parent::setUp();
    	$this->status = factory(Status::class)->create();
    }

    /** @test */
    public function it_can_have_many_tickets(){
    	$ticket_1 = factory(Ticket::class)->create();
		$ticket_2 = factory(Ticket::class)->create();

		$ticket_1->setStatus($this->status);
		$ticket_2->setStatus($this->status);

		$this->assertCount(2, $this->status->tickets);
    }
}
