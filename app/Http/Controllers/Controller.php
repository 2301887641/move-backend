<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Api\Models\AuthRule;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    public function __construct()
    {

    }

    /**
     * 成功信息打印
     * @param $msg
     * @param $data
     * @return array
     */
    protected function success($msg,$data='')
    {
        return [
            "status"=>200,
            "msg"=>$msg,
            "data"=>$data
        ];
    }

    /**
     * 错误信息打印
     * @param $msg
     * @return array
     */
    protected function failed($msg)
    {
        return [
            "status"=>500,
            "msg"=>$msg,
        ];
    }

    /**
     * 获取分页信息
     * @param Request $request
     * @return array
     */
    protected function pageInfo()
    {
        $request=request()->instance();
        $page=$request->get("page")?:1;
        $pageSize=$request->get("pageSize")?:10;
        return collect([
            "page"=>($page-1)*$pageSize,
             "pageSize"=>$pageSize
        ]);
    }
}
