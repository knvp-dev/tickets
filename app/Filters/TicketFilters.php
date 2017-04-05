<?php 

namespace App\Filters;

use App\Filters\Filters;

class TicketFilters extends Filters{

	protected $filters = ['by', 'order'];

	public function by($member){
		$member = \App\User::whereName($member)->firstOrFail();
		return $this->builder->where('owner_id', $member->id);
	}

	public function order($order){
		return $this->builder->orderBy('created_at', $order);
	}
}