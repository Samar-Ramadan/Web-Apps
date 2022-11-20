<?php

use Illuminate\Http\Request;


//Samar Ramadan
Route::group(["prefix"=>"v1","namespace"=>"API"],function($route){
Route::group(["prefix"=>"auth"],function(){
    //create new user
    Route::post("register","AuthController@register");
    Route::post("login","AuthController@login")->name('login');
    Route::get("refresh","AuthController@refresh");
});

Route::group(["middleware"=>"auth:api","prefix"=>"auth"],function(){
Route::get("logout","AuthController@logout")->name('logout');
Route::get("user","AuthController@user");
});

Route::group(["middleware"=>"auth:api","prefix"=>"Add"],function(){

   // Route::get('/search_products','ProductController@search');
    Route::get('/','ProductController@index');
    Route::post("/add_product","ProductController@store");
    Route::post('/update_product/{id}','ProductController@update');
    Route::delete('/delete_product/{id}','ProductController@delete');
    Route::get('/show_product/{id}','ProductController@show');

    Route::get('/','FileController@index');
    Route::post('/add_file',"FileController@store");
    //Route::post('/update_file/{id}','FileController@update');
    //Route::delete('/delete_file/{id}','FileController@delete');
   // Route::get('/show_file/{id}','FileController@show');


});
});
