<?php

use Illuminate\Database\Seeder;

class DummyDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	factory('App\Status',3)->create();
    	factory('App\Category',3)->create();
    	factory('App\Priority',3)->create();
        factory('App\User',1)->create();
        factory('App\Customer',10)->create();
        factory('App\Ticket', 5)->create();
        factory('App\Todo', 10)->create();
    }
}
