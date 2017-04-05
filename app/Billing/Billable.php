<?php 
namespace App\Billing;

use App\Subscription;
use App\Billing\Payment;

trait Billable{

	public function activate($customerId, $subscriptionId){
        return $this->update([
            'stripe_id' => $customerId,
            'stripe_subscription' => $subscriptionId,
            'stripe_active' => true,
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

    public function payments(){
    	return $this->hasMany(Payment::class);
    }
}