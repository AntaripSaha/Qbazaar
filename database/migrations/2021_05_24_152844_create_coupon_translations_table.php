<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCouponTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coupon_translations', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('coupon_id')->unsigned();
            $table->string('locale');
            $table->string('name');
			$table->timestamps();
			
            $table->unique(['coupon_id', 'locale']);
			$table->foreign('coupon_id')->references('id')->on('coupons')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('coupon_translations');
    }
}
