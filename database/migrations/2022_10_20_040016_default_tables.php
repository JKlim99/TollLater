<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaction', function(Blueprint $table){
            $table->id();
            $table->integer('card_id')->nullable();
            $table->string('type', 10);
            $table->float('paid', 10, 2)->nullable();
            $table->float('charged', 10, 2)->nullable();
            $table->float('amount', 10, 2);
            $table->string('car_plate_no', 20)->nullable();
            $table->integer('toll_station_id')->nullable();
            $table->string('station_type', 20);
            $table->integer('user_id');
            $table->timestamps();
        });

        Schema::create('card', function(Blueprint $table){
            $table->id();
            $table->integer('user_id')->nullable();
            $table->integer('card_serial_no');
            $table->string('batch_no', 15);
            $table->string('status', 10)->nullable();
            $table->timestamps();
        });

        Schema::create('user', function(Blueprint $table){
            $table->id();
            $table->string('fullname', 151);
            $table->string('ic_no', 14)->unique();
            $table->string('email', 151)->unique();
            $table->string('mobile_no', 15);
            $table->string('secret_key', 60);
            $table->string('hash', 50);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('car_plate_number', function(Blueprint $table){
            $table->id();
            $table->integer('user_id');
            $table->string('car_plate_number', 50);
        });

        Schema::create('toll_station', function(Blueprint $table){
            $table->id();
            $table->string('name', 151);
            $table->string('highway', 151);
            $table->string('type', 30);
            $table->float('price', 10, 2)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('bill', function(Blueprint $table){
            $table->id();
            $table->integer('card_id');
            $table->integer('user_id');
            $table->float('amount', 10, 2);
            $table->date('due_date');
            $table->string('status', 10)->default('unpaid');
            $table->timestamps();
        });

        Schema::create('payment', function(Blueprint $table){
            $table->id();
            $table->integer('bill_id');
            $table->integer('user_id');
            $table->string('ref_id', 50);
            $table->float('amount', 10, 2);
            $table->string('status', 10)->default('processing') ;
            $table->timestamps();
        });

        Schema::create('closed_station_price', function(Blueprint $table){
            $table->id();
            $table->integer('toll_station_id');
            $table->integer('exit_id');
            $table->float('price', 10, 2)->default(0.00);
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
        Schema::dropIfExists('transaction');
        Schema::dropIfExists('card');
        Schema::dropIfExists('user');
        Schema::dropIfExists('car_plate_number');
        Schema::dropIfExists('toll_station');
        Schema::dropIfExists('bill');
        Schema::dropIfExists('payment');
        Schema::dropIfExists('closed_station_price');
    }
};
