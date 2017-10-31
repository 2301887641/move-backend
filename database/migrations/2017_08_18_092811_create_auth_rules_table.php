<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAuthRulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('auth_rules', function (Blueprint $table) {
            $table->increments('id');
            $table->string('rule',100)->comment('规则唯一标识,对应类名.方法 如:User.create'); // jsonb deletes duplicates
            $table->string('name',100)->comment('对应的栏目名称');
            $table->string('role',100)->comment('栏目路由');
            $table->tinyInteger('type')->default(1)->comment('是否开启细规则验证 为1表示condition字段就可以定义规则表达式 如定义{score}>5 and {score}<100 表示用户的分数在5-100之间时这条规则才会通过');
            $table->string('condition',100)->default("")->comment('condition字段里面的内容将会用作正则表达式的规则来配合认证规则来认证用户');
            $table->tinyInteger('status')->default(1)->comment('状态：为1正常，为0禁用');
            $table->integer('parent_id')->default(0)->comment('父类的id');
            $table->string('icon')->default("")->comment("图标");
            $table->string('class')->default("")->comment("控制栏目下拉");
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
        Schema::dropIfExists('auth_rules');
    }
}
