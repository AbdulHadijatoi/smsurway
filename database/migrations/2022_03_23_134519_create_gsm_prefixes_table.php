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
        Schema::create('gsm_prefixes', function (Blueprint $table) {
            $table->id();
            $table->string('network_name')->nullable();
            $table->string('network_prefix')->unique();
            // $table->unique(['network_name', 'network_prefix']);
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
        Schema::dropIfExists('gsm_prefixes');
    }
};
