<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Todo;

class TodoTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(){
    	parent::setUp();
    	$this->todo = factory(Todo::class)->create();
    }

    /** @test */
    public function it_can_be_completed(){
    	$this->todo->complete();
    	$this->assertTrue(!! $this->todo->completed);
    }

    /** @test */
    public function it_can_be_uncompleted(){
    	$this->todo->complete();
    	$this->todo->uncomplete();
    	$this->assertFalse(!! $this->todo->completed);
    }
}
