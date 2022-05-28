<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title')->nullable();
            $table->float('amount',8,2)->default(0);
            $table->dateTime('date_time');
            $table->enum('type', [1, 2])->comment('1 = Expense, 2 = Income');
            $table->integer('account_id');
            $table->integer('category_id');
            $table->integer('paymentmode_id');
            $table->integer('user_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}
