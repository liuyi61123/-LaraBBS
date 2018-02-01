<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests\UserRequest;
use App\Handlers\ImageUploadHandler;
class UsersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['show']]);
    }

    /**
     * 展示用户信息
     */
    public function show(User $user){

        return view('users.show',compact('user'));
    }

    /**
     * 编辑用户信息
     */
    public function edit(User $user){
        $this->authorize('update', $user);

        return view('users.edit',compact('user'));
    }

    /**
     * 修改用户信息
     */
    public function update(UserRequest $request,ImageUploadHandler $uploader, User $user){
        $this->authorize('update', $user);

        $data = $request->all();
        //处理上传的文件
        if($request->avatar){
            $result = $uploader->save($request->avatar,'avatars',$user->id,362);
            if($result){
                $data['avatar'] = $result['path'];
            }
        }

        //更新
        $user->update($data);
        return redirect()->route('users.show', $user->id)->with('success', '个人资料更新成功！');

    }
}
