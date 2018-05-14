<?php

namespace App\Http\Controllers\Api;

use Auth;
use App\Models\Reply;
use App\Http\Requests\Api\ReplyRequest;
use Illuminate\Http\Request;
use App\Transformers\ReplyTransformer;

class RepliesController extends Controller
{
    /**
     * 添加回复
     */
    public function store(ReplyRequest $request,Reply $reply)
    {

    }

    /**
     * 回复列表
     */
    public function authIndex(Request $request)
    {
        $replies = Auth::user()->replies()->recent()
            ->paginate(20);

        return $this->response->paginator($replies, new ReplyTransformer());
    }
}
