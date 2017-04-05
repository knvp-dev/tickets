<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\traits\InteractsWithStripe;

use App\Subscription;

class SubscriptionTest extends TestCase
{

	use DatabaseMigrations, InteractsWithStripe;

    /** @test */
    function it_subscibes_a_user(){
    	$user = $this->makeSubscribedUser(['stripe_active' => false]);

    	$user = $user->fresh();

    	$this->assertTrue($user->isSubscibed());

    	try{
    		 $subscription = $user->subscription()->retrieveStripeSubscription();
    	} catch(Exception $e){
    		$this->fail('Expected to see a Stripe subscription, but did not.');
    	}
    }

    /** @test */
    function it_cancels_a_users_subscription(){
        $user = $this->makeSubscribedUser();

        $user->subscription()->cancel();

        $stripeSubscription = $user->subscription()->retrieveStripeSubscription();

        $this->assertNotNull($stripeSubscription->canceled_at);

        $this->assertFalse(!! $user->isSubscibed());
        $this->assertNotNull($user->subscription_end_at);
    }

    protected function makeSubscribedUser($overrides = []){
        $user = create('App\User', $overrides);

        $user->subscription()->create($this->getBasicPlan(), $this->getStripeToken());

        return $user;
    }
}
