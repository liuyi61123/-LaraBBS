<?php

use Illuminate\Http\Request;

$api = app('Dingo\Api\Routing\Router');

$api->version('v1', [
    'namespace' => 'App\Http\Controllers\Api',
    'middleware' => ['serializer:array','bindings']
], function($api) {
    $api->group([
        'middleware' => 'api.throttle',
        'limit' => config('api.rate_limits.sign.limit'),
        'expires' => config('api.rate_limits.sign.expires'),
    ], function($api) {
        // 短信验证码
        $api->post('verificationCodes', 'VerificationCodesController@store')
            ->name('api.verificationCodes.store');
        // 用户注册
        $api->post('users', 'UsersController@store')
            ->name('api.users.store');
        // 图片验证码
        $api->post('captchas', 'CaptchasController@store')
            ->name('api.captchas.store');
        // 第三方登录
        $api->post('socials/{social_type}/authorizations', 'AuthorizationsController@socialStore')
            ->name('api.socials.authorizations.store');
        // 登录
        $api->post('authorizations', 'AuthorizationsController@store')
            ->name('api.authorizations.store');
        // 资源推荐
        $api->get('links', 'LinksController@index')
            ->name('api.links.index');
        // 分类帖子
        $api->get('categories', 'CategoriesController@index')
            ->name('api.categories.index');
        //帖子列表
        $api->get('topics', 'TopicsController@index')
        ->name('api.topics.index');
        //指定用户信息
        $api->get('users/{user}', 'UsersController@show')
            ->name('api.users.show');
        //用户发布的帖子
        $api->get('users/{user}/topics', 'TopicsController@userIndex')
            ->name('api.users.topics.index');
        //用户发布的回复
        $api->get('users/{user}/replies', 'RepliesController@userIndex')
            ->name('api.users.replies.index');
        // 话题回复列表
        $api->get('topics/{topic}/replies', 'RepliesController@index')
            ->name('api.topics.replies.index');
        //最新发布的帖子
        $api->get('topics/{num}/new', 'TopicsController@new')
            ->name('api.topics.new');
        //查看文章详情
        $api->get('topics/{topic}', 'TopicsController@show')
            ->name('api.topics.show');

        // 需要 token 验证的接口
        $api->group(['middleware' => 'api.auth'], function($api) {
            // 刷新token
            $api->put('authorizations/current', 'AuthorizationsController@update')
                ->name('api.authorizations.update');
            // 删除token
            $api->delete('authorizations/current', 'AuthorizationsController@destroy')
                ->name('api.authorizations.destroy');
            // 当前登录用户信息
            $api->get('user', 'UsersController@me')
                ->name('api.user.show');
            // 小程序保存用户信息
            $api->post('users/weapp','UsersController@weapp')
                ->name('api.user.weapp');
            // 编辑登录用户信息
            $api->put('user', 'UsersController@update')
                ->name('api.user.update');
            // 图片资源
            $api->post('images', 'ImagesController@store')
                ->name('api.images.store');
            // 发布话题
            $api->post('topics', 'TopicsController@store')
                ->name('api.topics.store');
            // 发布话题
            $api->put('topics/{topic}', 'TopicsController@update')
                ->name('api.topics.update');
            // 删除话题
            $api->delete('topics/{topic}', 'TopicsController@destory')
                ->name('api.topics.destory');
            // 发布回复
            $api->post('topics/{topic}/replies', 'RepliesController@store')
                ->name('api.topics.replies.store');
        });
    });
});
