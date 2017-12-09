<?php

namespace App\Http\Controllers\Api\Models;

use Illuminate\Database\Eloquent\Model;

class AuthRule extends Model
{
    protected $fillable = ['name', 'rule', 'role', 'status', 'parent_id','icon','path'];

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

    /**
     * 获取前台要展示的栏目
     * @return array
     */
    public function getMenu()
    {
        $data = self::select("id","icon","name as text","parent_id","role","class")->get();
        $data=$data->toArray();
        return $this->list_to_tree($data, 'id', 'parent_id', 'children', 0);
    }

    /**
     * 获取权限列表树形结构
     * @return array
     */
    public function getPermissions()
    {
        $data = self::select("id","name","parent_id","class")->get();
        $data=$data->toArray();
        return $this->list_to_tree($data, 'id', 'parent_id', 'children', 0);
    }

    /**
     * 把返回的数据集转换成Tree  本函数使用引用传递  修改  数组的索引架构
     *  可能比较难理解     函数中   $reffer    $list[]  $parent 等的信息实际上只是内存中地址的引用
     * @access public
     * @param array $list 要转换的数据集
     * @param string $pid parent标记字段
     * @param string $level level标记字段
     * @return array
     */
    public function list_to_tree($list, $pk = 'id', $pid = 'pid', $child = '_child', $root = 0) {
        // 创建Tree
        $tree = array();
        if (is_array($list)) {
            //创建基于主键的数组引用
            $refer = array();
            foreach ($list as $key => $data) {
                //后期可以去掉 如果没有class字段的话 主要用于前台的显示效果
                if(empty($list[$key]["class"])){
                    unset($list[$key]["class"]);
                }else{
                // 前端返回class为空才正常
                    $list[$key]["class"]='';
                }
                $refer[$data[$pk]] = & $list[$key];
            }
            foreach ($list as $key => $data) {
                // 判断是否存在parent
                $parentId = $data[$pid];
                if ($root == $parentId) {
                    //根节点元素
                    $tree[] = & $list[$key];
                } else {
                    if (isset($refer[$parentId])) {
                        //当前正在遍历的父亲节点的数据
                        $parent = & $refer[$parentId];
                        //把当前正在遍历的数据赋值给父亲类的  children
                        $parent[$child][] = & $list[$key];
                    }
                }
            }
        }
        return $tree;
    }
}
