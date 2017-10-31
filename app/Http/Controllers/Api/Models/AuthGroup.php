<?php

namespace App\Http\Controllers\Api\Models;

use Illuminate\Database\Eloquent\Model;

class AuthGroup extends Model
{
    protected $fillable=["name","permission_id","permissions","permissions_name"];

    /**
     * 格式化status字段
     * @param $status
     * @return string
     */
    public function getStatusAttribute($status)
    {
        if($status==1){
            $msg="已启用";
        }else if($status==0){
            $msg="已停用";
        }
        return $msg;
    }
}
