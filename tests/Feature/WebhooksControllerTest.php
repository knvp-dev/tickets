<?php

namespace Tests\Feature;

use App\Http\Controllers\WebhooksController;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class WebhooksControllerTest extends TestCase
{

	use DatabaseMigrations;

    /** @test */
    function it_converts_a_stripe_event_name_to_a_method_name(){
    	$name = (new WebhooksController)->eventToMethod('customer.subscription.deleted');

    	$this->assertEquals('whenCustomerSubscriptionDeleted', $name);
    }

    /** @test */
    function it_deactivates_a_users_subscription_on_stripe_deleted_event(){
    	$user = create('App\User', [
    		'stripe_active' => true,
    		'stripe_id' => 'fake_stripe_id'
    	]);

    	$this->post('stripe/webhook', [
    		'type' => 'customer.subscription.deleted',
    		'data' => [
    		    'object' => [
    				'customer' => $user->stripe_id
    			]
    		]
    	]);
    	
    	$this->assertFalse(!! $user->fresh()->isSubscibed());
    }
}
