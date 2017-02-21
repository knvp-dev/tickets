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

class TicketTest extends TestCase
{
    use DatabaseTransactions;

    // TESTS

	/** @test */
	public function can_fetch_all_tickets(){
        $this->initForTesting();
        factory(Ticket::class, 5)->create();
        $tickets = Ticket::all();
        $this->assertCount(5, $tickets);
    }

    /** @test */
    public function ticket_can_be_completed(){
        $this->initForTesting();
        $ticket = $this->createSingleTicket();
        $ticket->complete();
        $this->assertTrue(!! $ticket->completed);
    }

    /** @test */
    public function ticket_can_be_archived(){
        $this->initForTesting();
        $ticket = $this->createSingleTicket();
        $ticket->archive();
        $this->assertTrue(!! $ticket->archived);
    }

    /** @test */
    public function ticket_can_be_uncompleted(){
        $this->initForTesting();
        $ticket = $this->createSingleTicket();
        $ticket->complete();
        $ticket->uncomplete();
        $this->assertFalse(!! $ticket->completed);
    }

    /** @test */
    public function ticket_can_be_unarchived(){
        $this->initForTesting();
        $ticket = $this->createSingleTicket();
        $ticket->archive();
        $ticket->unarchive();
        $this->assertFalse(!! $ticket->archived);
    }

    /** @test */
    public function can_fetch_todos_for_ticket(){
        $this->initForTesting();
        $ticket = $this->createSingleTicket();
        $this->createTodo();
        $this->assertCount(1, $ticket->todos);
    }

    /** @test */
    public function can_add_todo_to_ticket(){
        $this->initForTesting();
        $ticket = $this->createSingleTicket();
        $this->createTodo();
        $this->assertTrue($ticket->todos()->first()->body == 'todo body');
        $this->assertCount(1, $ticket->todos);
    }

    /** @test */
    public function can_delete_todo_from_ticket(){
        $this->initForTesting();
        $ticket = $this->createSingleTicket();
        $this->createTodo();
        $todo = $ticket->todos()->first();
        $response = $this->call('DELETE','/ticket/1/todo/delete', ['todo' => $todo->toArray()]);
        $this->assertCount(0, $ticket->todos);
    }

    /** @test */
    public function can_complete_todo_for_ticket(){
        $this->initForTesting();
        $ticket = $this->createSingleTicket();
        $this->createTodo();
        $todo = $ticket->todos()->first();
        $todo->complete();
        $this->assertTrue(!! $todo->completed);
    }

    /** @test */
    public function can_uncomplete_todo_for_ticket(){
        $this->initForTesting();
        $ticket = $this->createSingleTicket();
        $this->createTodo();
        $todo = $ticket->todos()->first();
        $todo->complete();
        $todo->uncomplete();
        $this->assertFalse(!! $todo->completed);
    }

    // HELPER METHODS
    
    protected function createSingleTicket(){
        factory(Ticket::class, 1)->create(['id'=>1]);
        $ticket = Ticket::first();
        return $ticket;
    }

    protected function createTodo(){
        $todo = new Todo([
            'ticket_id'=> 1,
            'body'=> 'todo body',
            'completed'=> 0
        ]);
        $response = $this->call('POST','/ticket/1/todo/save', ['todo' => $todo->toArray()]);
        $this->assertEquals(200, $response->getStatusCode());
    }

    protected function initForTesting(){
        factory(User::class, 1)->create();
        factory(Customer::class, 3)->create();
        factory(Category::class, 3)->create();
        factory(Status::class, 3)->create();
        factory(Priority::class, 3)->create();
    }

}
