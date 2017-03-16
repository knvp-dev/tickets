<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

use App\Ticket;
use App\User;
use App\Customer;
use App\Category;
use App\Status;
use App\Priority;
use App\Todo;
use App\Message;

class TicketTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(){
        parent::setUp();

        $this->ticket = factory(Ticket::class)->create();
        factory(Todo::class)->create(['ticket_id' => $this->ticket->id]);
        factory(Message::class)->create(['ticket_id' => $this->ticket->id]);
        factory(User::class)->create();
    }

    /** @test */
    public function it_belongs_to_a_team(){
        $this->assertInstanceOf('App\Team', $this->ticket->team);
    }

    /** @test */
    public function it_has_an_owner(){
        $this->assertInstanceOf('App\User', $this->ticket->owner);
    }

    /** @test */
    public function it_has_a_category(){
        $this->assertInstanceOf('App\Category', $this->ticket->category);
    }

    /** @test */
    public function it_has_a_status(){
        $this->assertInstanceOf('App\Status', $this->ticket->status);
    }

    /** @test */
    public function it_has_a_priority(){
        $this->assertInstanceOf('App\Priority', $this->ticket->priority);
    }

    /** @test */
    public function it_can_have_todos(){
        $this->assertInstanceOf('App\Todo', $this->ticket->todos->first());
    }

	/** @test */
    public function it_can_have_messages(){
        $this->assertInstanceOf('App\Message', $this->ticket->messages->first());
    }

}
