<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Services\AdminService;
use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
class AdminController extends Controller
{
    private $request;
    private $adminService;
    public function __construct(Request $request,AdminService $adminService)
    {
        $this->request=$request;
        $this->adminService=$adminService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data=$this->adminService->index($this->request,$this->pageInfo());
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
    public function store()
    {
        $rule=[
            "name"=>"required|unique:users",
            "email"=>"required|unique:users",
            "password"=>"required|min:6"
        ];
        $message=[
            "name.required"=>"请输入用户名",
            "name.unique"=>"用户名已重复",
            "email.required"=>"请填写邮箱",
            "email.unique"=>"邮箱名重复",
            "password.required"=>"请输入密码",
            "password.min"=>"密码长度不能小于6位"
        ];
        $data=$this->request->input();
        $validator=Validator::make($data,$rule,$message);
        if($validator->fails()){
            return $this->failed($validator->errors()->first());
        }
        if($this->adminService->store($data)){
            return $this->success("添加用户成功");
        }
        return $this->failed("添加用户失败");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return $this->success("",$this->adminService->findById($id));
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
            "name"=>"required|unique:users,name,".$id,
            "email"=>"required|unique:users,email,".$id
        ];
        $message=[
            "name.required"=>"请输入用户名",
            "name.unique"=>"用户名已重复",
            "email.required"=>"请填写邮箱",
            "email.unique"=>"邮箱名重复"
        ];
        $data=$this->request->input();
        // 如果传递密码
        if(!empty($data["password"])){
            $rule["password"]="required|min:6";
            $message["password.required"]="请输入密码";
            $message["password.min"]="密码长度不能小于6位";
        }
        $validator=Validator::make($data,$rule,$message);
        if($validator->fails()){
            return $this->failed($validator->errors()->first());
        }
        if($this->adminService->update($id,$data)){
            return $this->success("修改用户成功");
        }
        return $this->failed("修改用户失败");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $adminId=config("core.admin.id");
        $user=$this->request->user();
        if(empty($user['id'])){
            return $this->failed("当前用户验证失败");
        }
        if($id==$user["id"] || ($id == $adminId)){
            return $this->failed("不能删除当前用户!!");
        }
        if($this->adminService->destroy($id)){
            return $this->success("删除用户成功");
        }
        return $this->failed("删除用户失败!!");
    }

    /**
     * 获取用户列表
     * @return array
     */
    public function userList()
    {
        return $this->success("",$this->adminService->userList());
    }
}
