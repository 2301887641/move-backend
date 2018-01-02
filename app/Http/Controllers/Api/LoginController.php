<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Services\LoginService;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    private $loginService;

    public function __construct(LoginService $loginService)
    {
        $this->loginService = $loginService;
    }

    /**
     * 生成验证码
     * @return mixed
     */
    public function getCaptcha()
    {
        return $this->loginService->getCaptcha('mini');
    }

    /**
     * 验证码检查
     * @param $captcha
     * @return array
     */
    public function checkCaptcha($captcha)
    {
        return $this->loginService->checkCaptcha($captcha);
    }
}
