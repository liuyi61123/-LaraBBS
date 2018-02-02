<?php

namespace App\Models;

class Reply extends Model
{
    protected $fillable = ['content'];

    /**
     * 回复关联的帖子
     */
    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }

    /**
     * 回复关联的用户
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
