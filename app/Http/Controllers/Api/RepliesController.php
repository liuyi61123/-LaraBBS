<?php

namespace App\Http\Controllers\Api;

use Auth;
use App\Models\User;
use App\Models\Topic;
use App\Models\Reply;
use App\Http\Requests\Api\ReplyRequest;
use Illuminate\Http\Request;
use App\Transformers\ReplyTransformer;

class RepliesController extends Controller
{
    /**
     * 话题对应的回复列表
     */
    public function index(Topic $topic)
    {
        $replies = $topic->replies()->orderBy('created_at','desc')->paginate(20);
        return $this->response->paginator($replies, new ReplyTransformer());
    }
    /**
     * 添加回复
     */
    public function store(ReplyRequest $request,Topic $topic,Reply $reply)
    {
        $reply->content = $request->content;
        $reply->topic_id = $topic->id;
        $reply->user_id = $this->user()->id;
        $reply->save();

        return $this->response->item($reply, new ReplyTransformer())
            ->setStatusCode(201);
    }

    /**
     * 回复列表
     */
     public function userIndex(User $user, Request $request)
     {
         $replies = $user->replies()->recent()
             ->paginate(20);

         return $this->response->paginator($replies, new ReplyTransformer());
     }
}
