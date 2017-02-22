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
    public function ticket_can_be_completed_and_uncompleted(){
        $this->initForTesting();
        $ticket = $this->createSingleTicket();
        $ticket->complete();
        $this->assertTrue(!! $ticket->completed);
        $ticket->uncomplete();
        $this->assertFalse(!! $ticket->completed);
    }

    /** @test */
    public function ticket_can_be_archived_and_unarchived(){
        $this->initForTesting();
        $ticket = $this->createSingleTicket();
        $ticket->archive();
        $this->assertTrue(!! $ticket->archived);
        $ticket->unarchive();
        $this->assertFalse(!! $ticket->archived);
    }

    /** @test */
    public function can_fetch_todos_for_ticket(){
        $this->initForTesting();
        $ticket = $this->createSingleTicket();
        $this->createTodo(1);
        $this->assertCount(1, $ticket->todos);
    }

    /** @test */
    public function can_add_todo_to_ticket(){
        $this->initForTesting();
        $ticket = $this->createSingleTicket();
        $this->createTodo(1);
        $this->assertTrue($ticket->todos()->first()->body == 'todo body');
        $this->assertCount(1, $ticket->todos);
    }

    /** @test */
    public function can_delete_todo_from_ticket(){
        $this->initForTesting();
        $ticket = $this->createSingleTicket();
        $this->createTodo(1);
        $this->createTodo(2);
        $todo = $ticket->todos()->first();
        $response = $this->call('GET','/todo/1/delete');
        $this->assertCount(1, $ticket->todos);
    }

    /** @test */
    public function can_complete_and_uncomplete_todo_for_ticket(){
        $this->initForTesting();
        $ticket = $this->createSingleTicket();
        $this->createTodo(1);
        $todo = $ticket->todos()->first();
        $todo->complete();
        $this->assertTrue(!! $todo->completed);
        $todo->uncomplete();
        $this->assertFalse(!! $todo->completed);
    }

    /** @test */
    public function can_assign_user_to_ticket(){
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
    public function can_fetch_assigned_users_for_ticket(){
        $this->initForTesting();
        $ticket = $this->createSingleTicket();

        $user1 = factory(User::class)->create();
        $user2 = factory(User::class)->create();

        $ticket->assignUser($user1);
        $ticket->assignUser($user2);

        $this->assertCount(2, $ticket->users);
    }

    // HELPER METHODS
    
    protected function createSingleTicket(){
        factory(Ticket::class, 1)->create(['id'=>1]);
        $ticket = Ticket::first();
        return $ticket;
    }

    protected function createTodo($id){
        $todo = new Todo([
            'id' => $id,
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
