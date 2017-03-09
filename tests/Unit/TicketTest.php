<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

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
    use DatabaseTransactions;

	/** @test */
	public function it_can_fetch_all_tickets(){
        $this->initForTesting();
        $tickets = factory(Ticket::class, 5)->create();
        $this->assertCount(5, $tickets);
    }

    /** @test */
    public function it_can_be_completed_and_uncompleted(){
        $this->initForTesting();
        $ticket = $this->createSingleTicket();
        $ticket->complete();
        $this->assertTrue(!! $ticket->completed);
        $ticket->uncomplete();
        $this->assertFalse(!! $ticket->completed);
    }

    /** @test */
    public function it_can_be_archived_and_unarchived(){
        $this->initForTesting();
        $ticket = $this->createSingleTicket();
        $ticket->archive();
        $this->assertTrue(!! $ticket->archived);
        $ticket->unarchive();
        $this->assertFalse(!! $ticket->archived);
    }

    /** @test */
    public function it_can_be_deleted(){
        $this->initForTesting();
        $ticket1 = $this->createSingleTicket();
        $ticket2 = $this->createSingleTicket(2);
        $ticket1->delete();
        $this->assertCount(1, Ticket::all());
    }

    /** @test */
    public function it_can_add_todo(){
        $this->initForTesting();
        $ticket = $this->createSingleTicket();
        $todo = [
            'ticket_id' => $ticket->id,
            'body' => 'todo body',
            'completed' => 0
        ];
        $ticket->addTodo($todo);
        $this->assertTrue($ticket->todos()->first()->body == 'todo body');
        $this->assertCount(1, $ticket->todos);
    }

    /** @test */
    public function it_can_complete_and_uncomplete_a_todo(){
        $this->initForTesting();
        $ticket = $this->createSingleTicket();
        $newTodo = [
            'ticket_id' => $ticket->id,
            'body' => 'todo body',
            'completed' => 0
        ];
        $ticket->addTodo($newTodo);
        $todo = $ticket->todos()->first();
        $todo->complete();
        $this->assertTrue(!! $todo->completed);
        $todo->uncomplete();
        $this->assertFalse(!! $todo->completed);
    }

    /** @test */
    public function it_can_assign_and_unassign_a_user(){
        $this->initForTesting();
        $ticket = $this->createSingleTicket();

        $user1 = factory(User::class)->create();
        $user2 = factory(User::class)->create();

        $ticket->assignUser($user1);
        $ticket->assignUser($user2);
        $this->assertEquals(2, $ticket->amountOfUsers());

        $ticket->unAssignUser($user1);
        $this->assertEquals(1, $ticket->amountOfUsers());

        $ticket->assignUser($user2);
        $this->assertEquals(1, $ticket->amountOfUsers());
    }

    /** @test */
    public function can_add_messages_to_ticket(){
        $this->initForTesting();
        $ticket = $this->createSingleTicket();
        $message = [
            'user_id' => 1,
            'body' => "message body"
        ];
        $ticket->addMessage($message);
        $this->assertCount(1, $ticket->messages);
        $this->assertEquals($ticket->messages()->first()->body ,"message body");
    }

    protected function createSingleTicket($id = 1){
        return factory(Ticket::class, 1)->create(['id'=>$id])->first();
    }

    protected function initForTesting(){
        factory(User::class, 1)->create();
        factory(Customer::class, 3)->create();
        factory(Category::class, 3)->create();
        factory(Status::class, 3)->create();
        factory(Priority::class, 3)->create();
    }

}
