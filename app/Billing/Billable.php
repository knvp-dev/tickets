<?php 
namespace App\Billing;

use Carbon\Carbon;
use App\Subscription;
use App\Billing\Payment;

trait Billable{

	public function activate($customerId = null, $subscriptionId = null, $plan = null){
        return $this->update([
            'stripe_id' => $customerId ?? $this->stripe_id,
            'stripe_subscription' => $subscriptionId ?? $this->stripe_subscription,
            'stripe_active' => true,
            'stripe_plan' => $plan->name ?? $this->stripe_plan,
            'subscription_end_at' => null
        ]);
    }

    public function deactivate($endDate = null){
    	$endDate = $endDate ?: \Carbon\Carbon::now();
    	
        return $this->update([
            'stripe_active' => false,
            'subscription_end_at' => $endDate
        ]);
    }

    public function subscription(){
        return new Subscription($this);
    }

    public static function byStripeId($stripe_id){
        return static::where('stripe_id', $stripe_id)->firstOrFail();
    }

    public function isSubscibed(){
        return !! $this->stripe_active;
    }

    public function isActive(){
    	return $this->isSubscibed() || $this->isOnGracePeriod();
    }

    public function isOnGracePeriod(){
    	if(! $endsAt = $this->subscription_end_at){
    		return false;
    	}

    	return Carbon::now()->lt(Carbon::instance($endsAt));
    }

    public function payments(){
    	return $this->hasMany(Payment::class);
    }
}