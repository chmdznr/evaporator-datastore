<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNewbornDatasTable extends Migration
{
    public function up()
    {
        Schema::create('newborn_datas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('trial_code');
            $table->float('thermal', 15, 6);
            $table->longText('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
