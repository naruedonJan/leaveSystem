<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class BookPlaneTickets extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('book_plane_tickets', function (Blueprint $table) {
            $table->bigIncrements('bp_id');
            $table->integer('bp_emp_id');

            $table->string('bp_go_start_airportName');        
            $table->string('bp_go_end_airportName');
            $table->date('bp_go_date');     
            $table->time('bp_go_time');     

            $table->string('bp_go_con_start_airportName');        
            $table->string('bp_go_con_end_airportName');
            $table->date('bp_go_con_date');
            $table->time('bp_go_con_time');

            $table->string('bp_back_start_airportName');        
            $table->string('bp_back_end_airportName');
            $table->date('bp_back_date');
            $table->time('bp_back_time');

            $table->string('bp_back_con_start_airportName');        
            $table->string('bp_back_con_end_airportName');
            $table->date('bp_back_con_date');
            $table->time('bp_back_con_time');
    
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
        //
    }
}
