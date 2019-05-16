<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAnalysesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('analyses', function (Blueprint $table) {
            // $table->bigIncrements('id');
            // $table->integer('stock_id');
            // $table->string('month_year')->nullable();
            // $table->integer('mf_increase')->nullable()->default(0);
            // $table->integer('mf_decrease')->nullable()->default(0);
            // $table->integer('mf_removed')->nullable()->default(0);
            // $table->integer('mf_new')->nullable()->default(0);
            // $table->integer('mfh_increase')->nullable()->default(0);
            // $table->integer('mfh_decrease')->nullable()->default(0);
            // $table->integer('mfh_removed')->nullable()->default(0);
            // $table->integer('mfh_new')->nullable()->default(0);

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
        Schema::dropIfExists('analyses');
    }
}
