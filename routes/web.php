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

Route::get('/', [
    'as' => 'home',
    function () {
    return '제 이름은 home 입니다. ';
    }
]);

Route::get('/home', function(){
    return redirect(route('home'));
});
