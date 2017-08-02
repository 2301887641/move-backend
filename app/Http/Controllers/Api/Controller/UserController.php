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
        $oldData=User::paginate(10)->toArray();
        $newData=["data"=>$oldData["data"]];
        return $this->success("",$newData);
        $data=User::where($where)->get(["id","name","created_at"]);
    }
}
