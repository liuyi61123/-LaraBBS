<?php

namespace App\Providers;

use App\Models\Topic;
use App\Models\Reply;
use App\Models\Link;
use App\Observers\TopicObserver;
use App\Observers\ReplyObserver;
use App\Observers\LinkObserver;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //时间格式转换
        \Carbon\Carbon::setLocale('zh');

        //数据库默认字符串长度191
        Schema::defaultStringLength(191);

        // 为 topic 模型注册观察者
        Topic::observe(TopicObserver::class);

        // 为 reply 模型注册观察者
        Reply::observe(ReplyObserver::class);

        // 为 Link 模型注册观察者
        Link::observe(LinkObserver::class);

       //  \Horizon::auth(function ($request) {
       //     // 是否是站长
       //     return \Auth::user()->hasRole('Founder');
       // });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if (app()->isLocal()) {
           $this->app->register(\VIACreative\SudoSu\ServiceProvider::class);
       }
    }
}
