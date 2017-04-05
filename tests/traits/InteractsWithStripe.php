<?php 
namespace Tests\traits;
use Stripe\Token;
use App\Plan;

trait InteractsWithStripe {

	protected function getStripeToken(){
		return Token::create([
    		'card' => [
    			'number' => '4242424242424242',
    			'exp_month' => 1,
    			'exp_year' => 2025,
    			'cvc' => 123
    		]
    	])->id;
	}

    protected function getBasicPlan(){
        return new Plan(['name' => 'Basic']);
    }
}