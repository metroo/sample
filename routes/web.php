<?php

use Illuminate\Support\Facades\Route;

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


Route::group(['middleware' => 'role:developer'], function() {

    Route::group(['prefix' => 'laravel-filemanager'],  function () {
        \UniSharp\LaravelFilemanager\Lfm::routes();
    });
    Route::group(['prefix' => 'filemanager'],  function () {
        \UniSharp\LaravelFilemanager\Lfm::routes();
    });

    Route::get('/admin',  function (){
        return view('admin.app');
    })->name('admin')->middleware('auth');

    Route::group(['prefix'=>'admin'] , function (){
        //Route::post('upload' , 'UploadController@store')->middleware('auth');

        Route::resource('upload' , 'UploadController')->middleware('auth');

        Route::get('updateShortLink', 'ProductController@updateShortLink');
        Route::get('roles', 'PermisController@Permission');
        Route::resource('multimedia/categories' , 'MultimediaController')->middleware('auth');
        Route::get('multimedia/categories/create_sound' , 'MultimediaController@createSound')->middleware('auth');
        Route::resource('schema' , 'SchemaJsonController')->middleware('auth');
        Route::resource('category' , 'CategoryController')->middleware('auth');
        Route::resource('sound' , 'SoundController')->middleware('auth');
        Route::resource('video' , 'VideoController')->middleware('auth');
        Route::resource('picture' , 'PictureController')->middleware('auth');

        //Route::get('pictureList' , 'PictureController@getlist')->middleware('auth');

        Route::resource('product' , 'ProductController')->middleware('auth');
        Route::get('product/{id}/approve' , 'ProductController@approve')->middleware('auth');
        Route::post('product/{id}/notapprove' , 'ProductController@notapprove')->middleware('auth');
        Route::get('mailbox' , 'AdminPanel\MailboxController@index')->middleware('auth');
        Route::post('mailbox/fetch_user' , 'AdminPanel\MailboxController@fetch_user')->middleware('auth');
        Route::post('mailbox/fetch_user_chat/{id}' , 'AdminPanel\MailboxController@fetch_user_chat')->middleware('auth');
        Route::post('mailbox/send_chat' , 'AdminPanel\MailboxController@store')->middleware('auth');
        Route::get('composeAdmin' ,  function (){
            return view('layouts.composeAdmin');
        })->middleware('auth');

        Route::get('files' ,  function (){
            return view('layouts.filemanager.index');
        })->middleware('auth');
        /* Route::get('{index}' , function ($index){
            return view('member.'.$index);
        })->middleware('auth');*/
    });

});

Route::get('?ts={locale}', function ($locale) {
    //if($locale == 'en' || $locale = 'fa')
        App::setLocale($locale);
        dd($locale);

});
Route::get('/lastuser', function () {
    return view('auth/loginold');
})->name('lastuser');
Route::post('/lastusercheck' , 'Auth\LastLoginController@login')->name('loginlastuser');
Route::post('/updatelastuser' , 'Auth\LastLoginController@update')->name('updatelastuser');
Route::post('/ajaxlogin' , 'Auth\LoginController@ajaxLogin')->name('ajaxlogin');


Route::get('/', 'API\ProductFromCity@ShowCity'  )->name('home');

Route::get('/home',  'API\ProductFromCity@ShowCity');

Route::get('/s/{city}/{category}' , 'API\ProductFromCity@ShowCityCategory')->name('cities.cat.show');
Route::post('/s/{city}/{category}/q/' , 'API\ProductFromCity@search')->name('cities.cat.search');
Route::get('/s/{city}/{category}/q/' , 'API\ProductFromCity@search')->name('cities.cat.search');
Route::get('/s/{city}' , 'API\ProductFromCity@ShowCity')->name('cities.show');
Route::get('/s/{city}/q/' , 'API\ProductFromCity@search')->name('cities.search');
Route::get('/v/{prodctId}.html' , 'API\ProductFromCity@ShowDetail')->name('product.show');
Route::get('/v/{prodctId}/q/' , 'API\ProductFromCity@search')->name('product.search');
Route::resource('city' , 'CityController');
Route::get('/test' , 'testController@display_view');

Auth::routes();

//Route::get('/home', 'HomeController@index')->name('home');
Route::get('/dashboard',  function (){
        return view('member.app');
})->name('dashboard')->middleware('auth');

Route::get('product/image/{id}' , 'UploadController@show');

Route::group(['prefix'=>'dashboard'] , function (){
    Route::resource('schema' , 'SchemaJsonController');
    Route::resource('profile' , 'UserController')->middleware('auth');
    Route::resource('product' , 'ProductController')->middleware('auth');
    Route::resource('mailbox' , 'MailboxController')->middleware('auth');
    Route::post('mailbox/fetch_user/{rId}' , 'MailboxController@fetch_user')->middleware('auth');
    Route::post('mailbox/fetch_user_chat/{rId}' , 'MailboxController@fetch_user_chat')->middleware('auth');
    Route::post('mailbox/send_chat_direct/{rId}/{pId}' , 'MailboxController@storeDirect')->middleware('auth');
    Route::post('mailbox/send_chat/{rId}/{pId}' , 'MailboxController@store')->middleware('auth');
    Route::post('mailbox/send_chat/{thread}' , 'MailboxController@storeChatThread')->middleware('auth');
    Route::resource('city' , 'CityController');
    Route::get('categoriesTree/{root}' , 'CategoryController@showTree')->middleware('auth');
    Route::post('product/upload' , 'UploadController@store')->middleware('auth');
    Route::post('product/{id}/upload' , 'UploadController@store')->middleware('auth');
    Route::post('product/fileDelete' , 'UploadController@destroy')->middleware('auth');
    Route::post('product/{id}/fileDelete' , 'UploadController@destroy')->middleware('auth');
    Route::post('product/{id}/fileDeleteUpdate' , 'ProductController@destroyFile')->middleware('auth');
    Route::get('product/image/{id}' , 'UploadController@show');
    Route::get('product/image/{id}/{size}' , 'UploadController@show');
    Route::resource('mailbox' , 'MailboxController')->middleware('auth');
    Route::get('composeAdmin' ,  function (){
        return view('layouts.composeAdmin');
    })->middleware('auth');
    /* Route::get('{index}' , function ($index){
        return view('member.'.$index);
    })->middleware('auth');*/
});


