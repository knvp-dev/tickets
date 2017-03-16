<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Message;
use App\User;
use App\Ticket;

class MessageTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(){
    	parent::setUp();
    	$this->message = factory(Message::class)->create();
    }

    /** @test */
    public function it_belongs_to_a_user(){
    	$this->assertInstanceOf(User::class, $this->message->user);
    }

    /** @test */
    public function it_belongs_to_a_ticket(){
    	$this->assertInstanceOf(Ticket::class, $this->message->ticket);
    }
}
