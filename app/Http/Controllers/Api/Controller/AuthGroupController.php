<?php

namespace App\Http\Controllers\Api\Controller;

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
        //
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
        return $this->getPermissionRule($permission_arr);

        // 然后排序
        sort($permission_arr);
        // 最后的文本
        $finally_permission=implode(",",$permission_arr);
        if(AuthGroup::create([
            "name"=>$request->input("name"),
            "permission_id"=>$finally_permission
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * 格式化权限json
     * @param $permission_arr
     * @return array
     */
    public function formatterPermissionRule($permission_arr)
    {
        $data=AuthRule::where("parent_id",'>',0)->get(["id","rule"]);
        $arr=[];
        foreach($data as $k=>$v){
            if(in_array($v["id"],$permission_arr)){
                $arr[$v["rule"]]=1;
            }
        }
        return $arr;
    }
}
