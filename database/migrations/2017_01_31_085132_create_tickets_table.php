<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('customer_id');
            $table->string('title');
            $table->text('description');
            $table->integer('status_id')->default(1);
            $table->integer('category_id')->default(1);
            $table->integer('priority_id')->default(1);
            $table->integer('completed')->default(0);
            $table->date('date_closed')->nullable();
            $table->timestamps();
        });

        Schema::create('ticket_user', function(Blueprint $table){
            $table->integer('ticket_id');
            $table->integer('user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tickets');
        Schema::dropIfExists('ticket_user');
    }
}
