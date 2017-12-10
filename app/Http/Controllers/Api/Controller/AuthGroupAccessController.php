<?php

namespace App\Http\Controllers\Api\Controller;

use App\Http\Controllers\Api\Models\AuthGroupAccess;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class AuthGroupAccessController extends Controller
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
        $query=AuthGroupAccess::query();
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
        $data=$query->select(["id","uid","group_id","created_at","uid as uname","group_id as gname"])->orderBy('created_at',"desc")->paginate(10);
        return $this->success("",$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,AuthGroupAccess $authGroupAccess)
    {
        $rule=[
            "uid"=>"required",
            "group_id"=>"required"
        ];
        $message=[
            "uid.required"=>"请选择用户",
            "group_id.required"=>"请选择角色"
        ];
        $data=$request->input();
        $validator=Validator::make($data,$rule,$message);
        if($validator->fails()){
            return $this->failed($validator->errors()->first());
        }
        try{
            $authGroupAccess->fill($data)->save();
            return $this->success("添加成功");
        }catch(QueryException $e){
            // 1062说明是重复的字段 因为用户名和角色是不允许重复的
             if($e->errorInfo[1]==1062){
                 return $this->failed("不可以添加重复的用户名和角色");
             }
        }
        return $this->failed("添加失败");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data=AuthGroupAccess::where(["id"=>$id])->select(["id","uid","group_id","created_at"])->first();
        return $this->success("",$data);
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
            "uid"=>"required",
            "group_id"=>"required"
        ];
        $message=[
            "uid.required"=>"请选择用户",
            "group_id.required"=>"请选择角色"
        ];
        $data=$request->input();


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
}
