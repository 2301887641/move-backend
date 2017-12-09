<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAuthGroupAccessesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('auth_group_accesses', function (Blueprint $table) {
            $table->increments('id');
            //添加index普通索引
            $table->integer('uid')->index()->unsigned()->comment('用户id');
            $table->integer('group_id')->index()->unsigned()->comment('认证组ID');
            //组合唯一索引  也就是不能两个都相同
            $table->unique(["uid","group_id"]);
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
        Schema::dropIfExists('auth_group_accesses');
    }
}
