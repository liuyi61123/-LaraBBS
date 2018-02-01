<?php

namespace App\Providers;

use App\Models\Topic;
use App\Observers\TopicObserver;
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
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
