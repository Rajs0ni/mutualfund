<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFundsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('funds', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('Name of the Instrument')->nullable();
            $table->string('ISIN')->nullable();
            $table->string('Industry')->nullable();
            $table->decimal('Quantity',12,2)->nullable()->default(0);
            $table->decimal('Market/Fair', 10,2)->nullable()->default(0);
            $table->string('% to Net Assets')->nullable();
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
        Schema::dropIfExists('funds');
    }
}
