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

    // store data into redis for the next 20 seconds
    Redis::setex("user", 20, "testUser");

    return view('welcome');
});

// To test redis
Route::get('/redis', function () {

    // Increments on every page load, refresh included
    $visits = Redis::incr('visits');

    //retrieve the stored user and concatenates value to the visits value
    $result = Redis::get("user") . ' ' . $visits;
    return $result;
});
