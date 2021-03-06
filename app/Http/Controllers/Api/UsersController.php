<?php

namespace App\Http\Controllers\Api;

use Auth;
use App\Models\Image;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\Api\UserRequest;
use App\Transformers\UserTransformer;

class UsersController extends Controller
{
    public function store(UserRequest $request)
    {
        $verifyData = \Cache::get($request->verification_key);

        if (!$verifyData) {
            return $this->response->error('验证码已失效', 422);
        }

        if (!hash_equals($verifyData['code'], $request->verification_code)) {
            // 返回401
            return $this->response->errorUnauthorized('验证码错误');
        }

        $user = User::create([
            'name' => $request->name,
            'phone' => $verifyData['phone'],
            'password' => bcrypt($request->password),
        ]);

        // 清除验证码缓存
        \Cache::forget($request->verification_key);

        return $this->response->item($user, new UserTransformer())
            ->setMeta([
                'access_token' => \Auth::guard('api')->fromUser($user),
                'token_type' => 'Bearer',
                'expires_in' => \Auth::guard('api')->factory()->getTTL() * 60
            ])
            ->setStatusCode(201);
    }

    /**
     * 获取用户信息
     *
     * @return \Dingo\Api\Http\Response
     */
    public function me(){
        return $this->response->item($this->user(), new UserTransformer());
    }

    /**
     * 获取制定信息
     *
     * @return \Dingo\Api\Http\Response
     */
    public function show(User $user){
        return $this->response->item($user, new UserTransformer());
    }

    /**
     * 修改个人信息
     * @return array
     */
    public function update(UserRequest $request){
        $user = $this->user();

        $attributes = $request->only(['name', 'email', 'introduction','avatar']);

        // if ($request->avatar_image_id) {
        //     $image = Image::find($request->avatar_image_id);
        //
        //     $attributes['avatar'] = $image->path;
        // }
        $user->update($attributes);

        return $this->response->item($user, new UserTransformer());
    }
    /**
     * 将小程序的用户信息保存到数据库
     * @param Request $request
     * @return
     */
    public function weapp(Request $request){
        $userInfo = $request->userInfo;
        $user = Auth::guard('api')->user();

        $user->name = $userInfo['nickName'];
        $user->avatar = $userInfo['avatarUrl'];
        $user->save();

        return $this->response->item($user, new UserTransformer());
    }
}
