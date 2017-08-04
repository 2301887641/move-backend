<?php

namespace App\Http\Controllers\Api\Controller;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    /**
     * 用户列表
     * @param Request $request
     * @return array
     */
    public function index(Request $request)
    {
        $name=$request->get('name');
        $where=[];
        if(!empty($name)){
            $where["name"]=$name;
        }
        $data=User::where($where)->paginate(10);
        return $this->success("",$data);
    }

    /**
     * 根据id获取单条数据
     * @param $id
     * @return mixed
     */
    public function read($id) {
        return User::find($id);
    }
}
