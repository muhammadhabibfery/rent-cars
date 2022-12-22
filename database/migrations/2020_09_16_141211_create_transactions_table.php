<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->id();
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->string('invoice_number', 31)->unique()->index();
            $table->dateTime('start_date')->index();
            $table->dateTime('finish_date')->index();
            $table->dateTime('return_date')->nullable();
            $table->integer('duration');
            $table->integer('total_late')->nullable();
            $table->bigInteger('payment_amount');
            $table->bigInteger('total_price');
            $table->bigInteger('penalty_amount')->nullable();
            $table->enum('status', ['DP', 'COMPLETED'])->index();
            $table->integer('created_by');
            $table->integer('updated_by')->nullable();
            $table->integer('deleted_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
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
