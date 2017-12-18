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
    public function getMenu($user)
    {
        //获取管理员的id
        $adminId=config("core.admin.id");
        if($user->id!=$adminId){
            $data=$this->getAuthRulesById($user->id);
        }else{
            //直接获取所有
            $data=self::select("id","icon","name as text","parent_id","role","class")->get();
            $data=$data->toArray();
        }
        return $this->list_to_tree($data, 'id', 'parent_id', 'children', 0);
    }

    /**
     * 根据id获取对应的authrules
     * @param $id
     * @return array|\Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getAuthRulesById($id)
    {
        //获取指定id的group_id
        $authGroupAccessIds=AuthGroupAccess::where(["uid"=>$id])->get(["group_id"]);
        if($authGroupAccessIds->isEmpty()){
            return [];
        }
        //存放规则id数组
        $ruleIdArr=[];
        //遍历用户组id 获取rule id
        foreach($this->formatAuthGroupAIds($authGroupAccessIds) as $item){
            if(isset($item->permission_id)){
                $ruleIdArr[]=$item->permission_id;
            }
        }
        //获取选定id的规则
        $ruleIdStr=implode(",",$ruleIdArr);
        $ruleIdArr=explode(",",$ruleIdStr);
        $data=self::select("id","icon","name as text","parent_id","role","class")->whereIn("id",$ruleIdArr)->get();
        $data=$data->toArray();
        //获取选定id的父类的id规则 去除重复的
        $parent_ids=array_unique(array_column($data,"parent_id"));
        //父类id有可能在选定id中存在 所以要取个差集
        $parent_ids=array_diff($parent_ids,array_column($data,"id"));
        $parentData=self::select("id","icon","name as text","parent_id","role","class")->whereIn("id",$parent_ids)->get();
        $parentData=$parentData->toArray();
        //合并数据
        $data=array_merge($data,$parentData);
        return $data;
    }

    /**
     * 获取authGroup id
     * @param $authGroupAccessIds
     * @return \Generator
     */
    private function formatAuthGroupAIds($authGroupAccessIds)
    {
       foreach($authGroupAccessIds as $item){
           yield AuthGroup::getPermissionIdById($item->group_id);
       }
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
