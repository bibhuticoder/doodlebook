<?php

use Illuminate\Http\Request;

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
    return file_get_contents(public_path().'/index.html'); 
});

Route::get('/getImage', function (Request $request) {
    $filepath = public_path().'\storage\\' . $request->query('type') . 's\\' . $request->query('filename');
    $headers = array(
        'Content-Type'                 => array_last(explode($request->query('filename'), '.')),
        "Access-Control-Allow-Origin"  => '*',
        "Access-Control-Allow-Method"  => 'GET, HEAD, POST',
        "Access-Control-Max-Age"       => 86400
    );
    return response()->download($filepath, $request->query('filename'), $headers);
});