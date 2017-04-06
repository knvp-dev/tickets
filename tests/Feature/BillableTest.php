<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use Carbon\Carbon;

class BillableTest extends TestCase
{
	use DatabaseMigrations;

    /** @test */
    function it_determines_if_a_users_subscription_is_active()
    {
        $user = create('App\User', [
        	'stripe_active' => true,
        	'subscription_end_at' => null
        ]);

        $this->assertTrue($user->isActive());

        $user->update([
        	'stripe_active' => false,
        	'subscription_end_at' => Carbon::now()->addDays(2)
        ]);

        $this->assertTrue($user->isActive());

        $user->update([
        	'stripe_active' => false,
        	'subscription_end_at' => Carbon::now()->subDays(2)
        ]);

        $this->assertFalse($user->isActive());
    }

    /** @test */
    function it_determines_if_the_users_subscription_is_on_a_grace_period(){
    	$user = create('App\User', [
        	'subscription_end_at' => null
        ]);

        $this->assertFalse($user->isOnGracePeriod());

        $user->subscription_end_at = Carbon::now()->addDays(2);

        $this->assertTrue($user->isOnGracePeriod());

        $user->subscription_end_at = Carbon::now()->subDays(2);

        $this->assertFalse($user->isOnGracePeriod());
    }


}
