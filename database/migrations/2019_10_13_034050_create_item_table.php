<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('sku')->unique();
            $table->string('barcode');
            $table->string('item_name');
            $table->unsignedBigInteger('category_id')->nullable();
            $table->decimal('stock_quantity',10,2)->default(0);
            $table->decimal('reorder_level',10,2)->default(0);
            $table->decimal('critical_level',10,2)->default(0);
            $table->unsignedBigInteger('unit_of_measure_id')->nullable();
            $table->timestamps();
            $table->foreign('category_id')->references('id')->on('category')->onDelete('set null');
            $table->foreign('unit_of_measure_id')->references('id')->on('unit_of_measure')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('item');
    }
}
