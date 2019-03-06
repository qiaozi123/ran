<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CaptchaController extends Controller
{
    public function captcha()
    {
        $captcha['url'] = captcha_src();
        return $this->responseData($captcha);
    }

    public function captchaValidate(Request $request)
    {
        $rules = ['captcha' => 'required|captcha'];
        $validator = Validator::make($request->all(), $rules);
        dd($validator->fails());
        if ($validator->fails()) {
            return $this->responseFailed('验证失败');
        } else {
            return $this->responseSuccess('验证成功');
        }
    }
}
