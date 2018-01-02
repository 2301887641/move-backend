<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Models\AuthGroup;
use App\Http\Controllers\Api\Models\AuthRule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Validator;
use App\Http\Controllers\Controller;

class AuthGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $name=$request->get('name');
        $stime=$request->get('stime');
        $etime=$request->get('etime');
        $query=AuthGroup::query();
        if(!empty($name)){
            $query->where("name",'=',$name);
        }
        if(!empty($stime) && !empty($etime)){
            $query->whereBetween("created_at",[$stime,$etime]);
        }else if(!empty($stime)){
            $query->where('created_at','>=',$stime);
        }else if(!empty($etime)){
            $query->where('created_at','<=',$etime);
        }
        $data=$query->orderBy("id","desc")->paginate(10);
        return $this->success("",$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data=AuthGroup::all(["id","name as text"]);
        return $this->success("",$data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rule=[
            "name"=>"required|unique:auth_groups",
            "permission_id"=>"required"
        ];
        $message=[
            "name.required"=>"请输入角色名",
            "name.unique"=>"角色名重复",
            "permission_id.required"=>"请选择规则"
        ];
        $validator=Validator::make($request->input(),$rule,$message);
        if($validator->fails()){
            return $this->failed($validator->errors()->first());
        }
        // 先转换为数组
        $permission_arr=$request->input("permission_id");
        // 获取权限相关内容
        $permissions=$this->formatterPermissionRule($permission_arr);
        // 然后排序
        sort($permission_arr);
        // 最后的文本
        $finally_permission=implode(",",$permission_arr);
        if(AuthGroup::create([
            "name"=>$request->input("name"),
            "permission_id"=>$finally_permission,
            "permissions"=>json_encode($permissions["rule"]),
            "permissions_name"=>$permissions["name"]
        ])){
            return $this->success("添加角色成功");
        }
        return $this->failed("添加角色失败");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return $this->success("",AuthGroup::select(["id","name","permission_id"])->find($id));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $rule=[
            "name"=>"required|unique:auth_groups,name,".$id,
            "permission_id"=>"required"
        ];
        $message=[
            "name.require"=>"请填写角色名称",
            "name.unique"=>"角色名称重复",
            "permission_id.require"=>"请选择权限"
        ];
        $data=$request->input();
        $validator=Validator::make($data,$rule,$message);
        if($validator->fails()){
            return $this->failed($validator->errors()->first());
        }
        $authRule=AuthGroup::find($id);
        $authRule->name=$data["name"];
        //排序
        sort($data["permission_id"]);
        //转文本
        $finally_permission=implode(",",$data["permission_id"]);
        // 传入的权限和之前的权限对比
        if($finally_permission!=$authRule->permission_id){
            // 获取权限相关内容
            $permissions=$this->formatterPermissionRule($data["permission_id"]);
            $authRule->permission_id=$finally_permission;
            $authRule->permissions=json_encode($permissions["rule"]);
            $authRule->permissions_name=$permissions["name"];
        }
        if($authRule->save()){
            return $this->success("修改成功");
        }
        return $this->failed("修改失败");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(AuthGroup::destroy($id)){
            return $this->success("删除成功");
        }
        return $this->failed("删除失败");
    }

    /**
     * 格式化权限json
     * @param $permission_arr
     * @return array
     */
    public function formatterPermissionRule($permission_arr)
    {
        //获取数据并转成数组
        $data=AuthRule::whereIn("id",$permission_arr)->select(["rule","name"])->get()->toArray();
        //获取name字段数组
        $name_arr=array_column($data,"name");
        //后去rule字段
        $permission=array_column($data,"rule");
        //组合成新的下标数组
        $new_permission=array_fill_keys($permission,1);
        return ["rule"=>$new_permission, "name"=>implode(",",$name_arr)];
    }

    /**
     * 获取角色列表
     * @return array
     */
    public function authGroupList()
    {
        $data=AuthGroup::select(["id","name"])->get();
        return $this->success("",$data);
    }
}
