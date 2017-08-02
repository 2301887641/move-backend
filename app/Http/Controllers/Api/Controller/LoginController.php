<?php

namespace App\Http\Controllers\Api\Controller;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
class LoginController extends Controller
{
    /**
     * 生成验证码
     * @return mixed
     */
    public function getCaptcha()
    {
        return Captcha();
    }

    /**
     * 验证码检查
     * @param $captcha
     * @return array
     */
    public function checkCaptcha($captcha)
    {
        if(captcha_check($captcha)){
            return ["status"=>"success"];
        }
        return ["status"=>"failed"];
    }
}
