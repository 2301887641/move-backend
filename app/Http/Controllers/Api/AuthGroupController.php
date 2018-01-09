<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Services\AuthGroupService;
use Illuminate\Http\Request;
use Validator;
use App\Http\Controllers\Controller;

class AuthGroupController extends Controller
{
    private $authGroupService;
    private $request;
    public function __construct(Request $request,AuthGroupService $authGroupService)
    {
        $this->authGroupService=$authGroupService;
        $this->request=$request;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data=$this->authGroupService->index($this->request,$this->pageInfo());
        return $this->success("",$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data=$this->authGroupService->getAll(["id","name as text"]);
        return $this->success("",$data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store()
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
        $data=$this->request->input();
        $validator=Validator::make($data,$rule,$message);
        if($validator->fails()){
            return $this->failed($validator->errors()->first());
        }
        if($this->authGroupService->store($data)){
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
        return $this->authGroupService->findById($id,["id","name","permission_id"]);
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
    public function update($id)
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
        $data=$this->request->input();
        $validator=Validator::make($data,$rule,$message);
        if($validator->fails()){
            return $this->failed($validator->errors()->first());
        }
        if($this->authGroupService->update($id,$data)){
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
        if($this->authGroupService->destory($id)){
            return $this->success("删除成功");
        }
        return $this->failed("删除失败");
    }


    /**
     * 获取角色列表
     * @return array
     */
    public function authGroupList()
    {
        $data=$this->authGroupService->BaseGetAll(["id","name"]);
        return $this->success("",$data);
    }
}
