<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\RegistrationForm;

class SubscriptionsController extends Controller
{
    public function store(RegistrationForm $form){

    	try {
    		$form->save();
    	}catch (\Exception $e) {
            return response()->json(['status' => $e->getMessage()], 422);
        }
    	
    	return back();
    }

    public function update(){
        auth()->user()->subscription()->resume();

        return back();
    }

    public function destroy(){
        auth()->user()->subscription()->cancel();

        return back();
    }
}
