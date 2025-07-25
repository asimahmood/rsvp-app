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

// Route::get('/', function () {
//     return view('welcome');
// });


Route::get('/', function () {
    return view('rsvp');
});
Route::get('/bouncer', function () {
    return view('bouncer');
});

Route::get('/admin', function () {
    return view('admin');
});




Route::view('/rsvp/individual', 'individual');
Route::view('/rsvp/couple', 'couple');
Route::view('/rsvp/vip', 'vip');