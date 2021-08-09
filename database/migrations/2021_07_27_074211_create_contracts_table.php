<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContractsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('customer_id');
            $table->date('start_date');
            $table->date('expire_date')->nullable();
            $table->double('prepayment')->nullable();
            $table->double('monthly_payment')->nullable();
            $table->integer('user_quantity')->default(1);
            $table->string('free_trial_duration')->nullable();
            $table->string("monthly_payment_currency")->default("IQD");
            $table->string("prepayment_currency")->default("IQD");
            $table->text('note')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('contracts');
    }
}
