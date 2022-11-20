<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbProductPriceMissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_product_price_missions', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table-> integer ('product_id') ;
            $table-> double ('discount_value');
            $table-> date ('date') ;
          


           // $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tb_product_price_missions');
    }
}
