<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePortfoliosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('portfolios', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('mf_id')->nullable();
            $table->string('month_year')->nullable();
            $table->integer('stock_id')->unsigned();
            $table->decimal('quantity',12,2)->nullable()->default(0);
            $table->timestamps();
            $table->foreign('stock_id')
            ->references('id')
            ->on('stocks')
            ->onUpdate('RESTRICT')
            ->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('portfolios');
    }
}
