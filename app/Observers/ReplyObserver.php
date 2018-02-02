<?php

namespace App\Observers;

use App\Models\Reply;
use App\Notifications\TopicReplied;

// creating, created, updating, updated, saving,
// saved,  deleting, deleted, restoring, restored

class ReplyObserver
{
    public function creating(Reply $reply)
    {
        //输入过滤
        $reply->content = clean($reply->content, 'user_topic_body');
    }

    public function created(Reply $reply)
    {
        $topic = $reply->topic;
        //添加评论后，在帖子回复数量里加一
        $topic->increment('reply_count', 1);

        // 如果评论的作者不是话题的作者，才需要通知
        if ( ! $reply->user->isAuthorOf($topic)) {
            $topic->user->notify(new TopicReplied($reply));
        }
    }

    public function deleted(Reply $reply)
    {
        //删除评论后，在帖子回复数量里减一
        $reply->topic->decrement('reply_count', 1);
    }
}
