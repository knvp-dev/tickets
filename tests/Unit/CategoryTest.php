<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Category;
use App\Ticket;

class CategoryTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(){
    	parent::setUp();
    	$this->category = factory(Category::class)->create();
    }

    /** @test */
    public function it_can_have_many_tickets(){
    	$ticket_1 = factory(Ticket::class)->create();
		$ticket_2 = factory(Ticket::class)->create();

		$ticket_1->setCategory($this->category);
		$ticket_2->setCategory($this->category);

		$this->assertCount(2, $this->category->tickets);
    }
}
