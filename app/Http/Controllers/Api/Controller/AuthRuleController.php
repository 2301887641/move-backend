<?php

namespace App\Http\Controllers\Api\Controller;

use App\Http\Controllers\Api\Models\AuthRule;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
class AuthRuleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $authRule=new AuthRule();
        $data=AuthRule::all();
        if(empty($data)){
            $data=[];
            $total=0;
        }else{
            $data=$authRule->get_tree($data);
            $total=count($data);
        }
        return $this->success('',["data"=>$data,"total"=>$total]);
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
    public function store(Request $request,AuthRule $authRule)
    {
        $rule = [
            "name" => "required|unique:auth_rules",
            "rule" => "required",
            "role" => "required"
        ];
        $message = [
            "name.required" => "请填写权限名称",
            "name.unique" => "权限名称不能重复",
            "rule.required" => "请填写对应规则",
            "role.required" => "请填写对应路由",
        ];
        $validator = Validator::make($request->input(), $rule, $message);
        if ($validator->fails()) {
            return $this->failed($validator->first());
        }
        if (!$authRule->fill($request->input())->save()) {
            return $this->failed("添加失败");
        }
        return $this->success("添加成功");
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
}
