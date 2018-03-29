<?php

namespace App\Http\Controllers\Api;

use Auth;
use App\Models\User;
use Illuminate\Http\Request;

class WeappController extends Controller
{
    /**
     * 将小程序的用户信息保存到数据库
     * @param Request $request
     * @return
     */
    public function user(Request $request){
        $userInfo = $request->userInfo;
        $user = Auth::guard('api')->user();

        if(!$user->name){
            $user->name = $userInfo['nickName'];
            $user->avatar = $userInfo['avatarUrl'];
            $user->save();
        }
        $this->response->created();
    }
}
