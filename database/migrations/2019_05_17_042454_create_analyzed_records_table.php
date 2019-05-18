<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAnalyzedRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('analyzed_records', function (Blueprint $table) {
            $table->integer('stock_id');
            $table->string('month_year');
            $table->primary(['stock_id', 'month_year']); //composite primary key
            $table->integer('mf_count')->default(0);
            $table->integer('mfh_count')->nullable()->default(0);
            $table->integer('quantity')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('analyzed_records');
    }
}
