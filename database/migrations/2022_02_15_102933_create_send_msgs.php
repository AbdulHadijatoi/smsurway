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
        Schema::create('send_msgs', function (Blueprint $table){
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->string('from')->nullable();
            $table->string('to')->nullable();
            $table->text('msg')->nullable();
            $table->string('msg_type')->nullable();
            $table->longText('msg_id')->nullable();
            $table->string('limit')->default(1);
            $table->string('msg_price')->default(0);
            $table->integer('msg_count')->default(1);
            $table->boolean('is_scheduled')->default(0);
            $table->dateTime('sendtime')->nullable();
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
        Schema::dropIfExists('send_msgs');
    }
};
