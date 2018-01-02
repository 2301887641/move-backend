<?php

namespace App\Http\Controllers\Api;

use App\User;
use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
//use Illuminate\Support\Facades\Gate;
class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

//        try{
//            $this->authorize('view', new User);
//        }catch (\Exception $e){
//            return $this->failed("没有权限访问!!");
//        }

//        $user = $request->user();
//        if($user->can('view')){
//            return 1;
//        }
//        return 2;
        $name=$request->get('name');
        $stime=$request->get('stime');
        $etime=$request->get('etime');
        $query=User::query();
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
        $validator=Validator::make($request->input(),$rule,$message);
        if($validator->fails()){
            return $this->failed($validator->errors()->first());
        }
        if(User::create([
            "name"=>$request->input("name"),
            "email"=>$request->input("email"),
            "password"=>bcrypt($request->input("password"))
        ])){
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
        return $this->success("",User::find($id));
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
            "name"=>"required|unique:users,name,".$id,
            "email"=>"required|unique:users,email,".$id
        ];
        $message=[
            "name.required"=>"请输入用户名",
            "name.unique"=>"用户名已重复",
            "email.required"=>"请填写邮箱",
            "email.unique"=>"邮箱名重复"
        ];
        $user=User::find($id);
        // 如果传递密码
        if(!empty($request->input("password"))){
            $rule["password"]="required|min:6";
            $message["password.required"]="请输入密码";
            $message["password.min"]="密码长度不能小于6位";
            $user->password = $request->input("password");
        }

        $validator=Validator::make($request->input(),$rule,$message);
        if($validator->fails()){
            return $this->failed($validator->errors()->first());
        }
        $user->name=$request->input("name");
        $user->email=$request->input("email");
        if($user->save()){
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
    public function destroy(Request $request,$id)
    {
        $adminId=config("core.admin.id");
        $user=$request->user();
        if(empty($user['id'])){
            return $this->failed("当前用户验证失败");
        }
        if($id==$user["id"] || ($id == $adminId)){
            return $this->failed("不能删除当前用户!!");
        }
        if(User::destroy($id)){
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
        $adminId=config("core.admin.id");
        $data=User::where("id","<>",$adminId)->get(["id","name as text"]);
        return $this->success("",$data);
    }
}
