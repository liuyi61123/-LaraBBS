<?php

namespace App\Models;

use Auth;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasRoles,Traits\ActiveUserHelper;
    use Notifiable{
         notify as protected laravelNotify;
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'phone', 'email', 'password', 'introduction', 'avatar',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     *  用户关联的帖子
     */
    public function topics()
    {
        return $this->hasMany(Topic::class);
    }

    /**
     * 关联的回复
     */
    public function replies(){
        return $this->hasMany(Reply::class);
    }

    /**
     * 判断是否有操作权限
     */
    public function isAuthorOf($model){
        return  $this->id == $model->user_id;
    }

    /**
     * 自定义通知
     */
    public function notify($instance){
        // 如果要通知的人是当前用户，就不必通知了！
        if ($this->id == Auth::id()) {
            return;
        }
        $this->increment('notification_count');
        $this->laravelNotify($instance);
    }

    /**
     * 用户读取清除通知
     */
    public function markAsRead(){
        $this->notification_count = 0;
        $this->save();
        $this->unreadNotifications->markAsRead();
   }

   /**
    * 密码加密处理
    */
   public function setPasswordAttribute($value)
   {
       // 如果值的长度等于 60，即认为是已经做过加密的情况
       if (strlen($value) != 60) {

           // 不等于 60，做密码加密处理
           $value = bcrypt($value);
       }

       $this->attributes['password'] = $value;
   }

   /**
    * 用户后台上传头像处理
    */
   public function setAvatarAttribute($path)
    {
        // 如果不是 `http` 子串开头，那就是从后台上传的，需要补全 URL
        if ( ! starts_with($path, 'http')) {

            // 拼接完整的 URL
            $path = config('app.url') . "/uploads/images/avatars/$path";
        }

        $this->attributes['avatar'] = $path;
    }
}
