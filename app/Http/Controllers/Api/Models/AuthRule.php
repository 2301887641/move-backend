<?php

namespace App\Http\Controllers\Api\Models;

use Illuminate\Database\Eloquent\Model;

class AuthRule extends Model
{
    protected $fillable = ['name', 'rule', 'role', 'status', 'parent_id'];

    /**
     * 获取权限树
     * @param $data
     * @param int $parent_id
     * @param int $level
     * @return array
     */
    public function get_tree($data, $parent_id = 0, $level = 0)
    {
        static $arr = array();
        foreach ($data as $d) {
            if ($d['parent_id'] == $parent_id) {
                $d['level'] = $level;
                $arr[] = $d;
                $this->get_tree($data, $d['id'], $level + 1);
            }
        }
        return $arr;
    }

    public function getMenu()
    {
        $data = self::where(["parent_id" => 0])->select("icon","name as text","parent_id")->get();
        $arr=[];
        return $data;die;
        foreach($this->loopFunc($data) as $item){
            $item();
        }
        return $arr;
    }

    public function loopFunc($data)
    {
        foreach($data as $item){
            yield function() use($item){
                return $this->get_tree($item);
            };
        }
    }

}
