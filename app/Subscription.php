<?php 
namespace App;

use Carbon\Carbon;
use Stripe\Customer;
use Stripe\Subscription as StripeSubscription;
use Stripe\Plan as StripePlan;

class Subscription
{
	protected $user;

	public function __construct(User $user){
		$this->user = $user;
	}

	public function create(Plan $plan, $token){

		$customer = Customer::create([
            'email' => $this->user->email,
            'source' => $token,
            'plan' => $plan->name
        ]);

        $subscriptionId = $customer->subscriptions->data[0]->id;

        $this->user->activate($customer->id, $subscriptionId, $plan);
	}

	public function cancel($atPeriodEnd = true){
		$customer = $this->retrieveStripeCustomer();
		$subscription = $customer->cancelSubscription(['at_period_end' => $atPeriodEnd]);
		$endDate = Carbon::createFromTimestamp($subscription->current_period_end);
		$this->user->deactivate($endDate);
	}

	public function cancelImmediately(){
		return $this->cancel(false);
	}

	public function resume(){
		$subscription = $this->retrieveStripeSubscription();
		$subscription->plan = $this->user->stripe_plan;

		$subscription->save();

		$this->user->activate();
	}

	public function retrieveStripeCustomer(){
		return Customer::retrieve($this->user->stripe_id);
	}

	public function retrieveStripeSubscription(){
		return StripeSubscription::retrieve($this->user->stripe_subscription);
	}

	public function retrieveStripePlan(){
		return StripePlan::retrieve($this->user->stripe_plan);
	}
}