<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('customer_id')->nullable();
            $table->string('customer_name');
            $table->string('customer_email');
			$table->string('customer_phone');
			$table->text('shipping_address');
            $table->decimal('sub_total', 10, 2)->unsigned();
            $table->string('shipping_method');
            $table->decimal('shipping_cost', 10, 2)->unsigned();
            $table->bigInteger('coupon_id')->nullable();
            $table->decimal('discount', 10, 2)->unsigned();
            $table->decimal('total', 10, 2)->unsigned();
            $table->string('currency',3)->nullable();
            $table->string('payment_method')->nullable();
            $table->string('payment_status',20);
            $table->string('delivery_status',20);
            $table->bigInteger('delivery_boy_id')->nullable();
            $table->text('note')->nullable();
            $table->string('delivery_type',10)->default('express');
            $table->datetime('delivery_time')->nullable();
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
        Schema::dropIfExists('orders');
    }
}
