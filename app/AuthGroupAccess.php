<?php

namespace App;

use App\User;

use Illuminate\Database\Eloquent\Model;

class AuthGroupAccess extends Model
{
    /**
     * 允许插入的字段
     * @var array
     */
    protected $fillable=["uid","group_id"];

    /**
     * 使用别名查询当前表中的字段
     * @param $uid
     * @return mixed|string
     */
    public function getUnameAttribute($uid){
        $user=User::find($uid);
        return $user->name;
    }

    /**
     * 使用别名查询组名称
     * @param $gid
     * @return mixed
     */
    public function getGnameAttribute($gid){
        $group=AuthGroup::find($gid);
        return $group->name;
    }
}
