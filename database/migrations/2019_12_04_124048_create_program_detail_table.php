<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProgramDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('program_detail', function (Blueprint $table) {
            $table->bigIncrements('pd_id');
            $table->integer('pd_uid');
            $table->string('pd_name');
            $table->date('pd_date');
            $table->time('pd_start_time');
            $table->time('pd_end_time');
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
        Schema::dropIfExists('program_detail');
    }
}
