<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::get('/','WelcomeController@index');

Route::get('auth/login',function (){
    $credentials = [
        'email' => 'john@example.com',
        'password' => 'password'
    ];
    if(!auth()->attempt($credentials)){
        return '로그인 정보가 정확하지 않습니다';
    }

    return redirect('protected');
});

Route::get('protected', function (){

   if(!auth()->check()){
       return '누구세요?';
   }
   return '어서오세요. ' . auth()->user()->name;
});

Route::get('auth/logout',function (){
    dump(session()->all());
    auth()->logout();
    return '또 봐요~';
});


Route::get('/foo', function(){
    return view('welcome1')->with([
        'name' => 'Foo',
        'greeting' => '안녕하세요? ',   
    ]);
});


Route::get('/welcome2', function(){
    return view('welcome2')->with([
        'name' => 'Foo',
        'greeting' => '안녕하세요2? ',
    ]);

});

Route::get('/welcome3', function(){
    return view('welcome3')->with([
        'name' => 'Foo',
        'greeting' => '브레이드 주석처리 테스 ',
    ]);

});

Route::get('/welcome4', function() {
    $items = ['apple','banana','tomato', 'perl'];
    return view('welcome4', ['items' => $items]);
});

Route::get('/welcome5', function()
    {
    return view('welcome5');
    //return 'test';
    });

Auth::routes();

Route::get('/home', 'HomeController@index');

Route::resource('articles','ArticlesController');

//DB::listen(
//    function($query){
//        var_dump($query->sql);
//    });

