<?php

Route::group([
    'domain' => config('project.api_domain'),
    'namespace' => 'Api',
    'as' => 'api.',
    ], function () {

    /* api.v1 */
    Route::group([
        'prefix' => 'v1',
        'namespace' => 'v1',
        'as' => 'v1.',
        ], function () {

            /* 환영 메시지 */
            Route::get('/', [
                'as' => 'index',
                'uses' => 'WelcomeController@index',
            ]);

            /* 포럼 API */
            // 아티클
            Route::resource('articles', 'ArticlesController');

            // 태그별 아티클 (중첩 라우트)
            Route::get('tags/{slug}/articles', [
                'as' => 'tags.articles.index',
                'uses' => 'ArticlesController@index',
            ]);

            // 태그
            Route::get('tags', [
                'as' => 'tags.index',
                'uses' => 'ArticlesController@tags',
            ]);

//            // 첨부 파일
//            Route::resource('attachments', 'AttachmentsController', ['only' => ['store', 'destroy']]);
//
//            // 아티클별 첨부 파일
//            Route::resource('articles.attachments', 'AttachmentsController', ['only' => ['index']]);
//
//            // 댓글
//            Route::resource('comments', 'CommentsController', ['only' => ['show', 'update', 'destroy']]);
//
//            // 아티클별 댓글
//            Route::resource('articles.comments', 'CommentsController', ['only' => ['index', 'store']]);
//
//            // 투표
//            Route::post('comments/{comment}/votes', [
//                'as' => 'comments.vote',
//                'uses' => 'CommentsController@vote',
//        ]);

    });

    /* 회원가입 */
    Route::post('auth/register', [
        'as' => 'users.store',
        'uses' => 'UsersController@store',
    ]);

    /**
     * 토큰 요청 및 리프레시
     *
     * 사용자가 확인되면 서버는 클라이언트에게 토큰을 반환한다.
     * 클라이언트는 토큰을 기억해야 한다.
     * 클라이언트는 리소스를 요청할 때 Authorization 헤더에 토큰을 달아서 보낸다.
     *
     * API 서비스는 세션을 유지하지 않기 때문에, 로그아웃이 필요없다.
     * 모든 API 요청은 Authorization 헤더값을 제시해야 하고,
     * 서버는 그 헤더값으로 사용자를 식별하여 인증/권한부여한다.
     */
    Route::post('auth/login', [
        'as' => 'sessions.store',
        'uses' => 'SessionsController@store',
    ]);

    Route::post('auth/refresh', [
        'middleware' => 'jwt.refresh',
        'as' => 'sessions.refresh',
        function () {
        },
    ]);
});