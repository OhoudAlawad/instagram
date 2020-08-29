<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    if(Auth::check())
        return redirect('/home');
    else
        return view('auth/login');
});

Auth::routes();


Route::group(['middleware' => ['auth']], function() {

    Route::get('user/profile', 'UserController@edit');

    Route::patch('user','UserController@update');

    //Post URLs
    Route::resource('post','PostController');
    Route::get('user/posts','PostController@userPosts');
    Route::get('user/{id}/posts','PostController@userFriendPosts');

    //Like URLs
    Route::resource('like','LikeController');

    //Comment URLs
    Route::resource('comment','CommentController');

    //Users URLS
    Route::get('users','UserController@index');
    Route::get('user_info/{id}','UserController@user_info');
    Route::get('search','UserController@autocomplete');

    //Follow URLS
    Route::resource('follow','FollowController');
    Route::get('user/followers','FollowController@index');

    Route::get('/home', 'PostController@index')->name('home');


});
