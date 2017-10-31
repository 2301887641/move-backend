<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAuthGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('auth_groups', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique()->comment('用户组中文名称');
            $table->text('permissions')->comment('用户规则json格式 {"delete-post": true, "update-post": true, "publish-post": true}'); // jsonb deletes duplicates
            $table->tinyInteger('status')->default(1)->comment('状态：为1正常，为0禁用');
            $table->string('permission_id')->default('')->comment('规则id列表 需要排序放入 后期修改后需要比对');
            $table->string('permissions_name')->default('')->comment('用户规则列表名称');
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
        Schema::dropIfExists('auth_groups');
    }
}
