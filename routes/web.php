<?php

use App\Http\Controllers\Public_controller\Public_controller;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;
use App\Mail\contact;
use App\Http\Controllers\Logs\LogsController;
use App\Http\Controllers\OsController;
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



// Route::get('/send-email', function(Request $request) {
//     Mail::send(new contact($request));
// });

Route::get('/', function () {
    return view('welcome');
});

Route::get('generatePdf', [LogsController::class, 'generatePdf']);
Route::get('os-viewPdf', [OsController::class, 'viewPdf']);
Route::get('send-mail/', function () {

    $details = [
        'title' => 'Mail from ItSolutionStuff.com',
        'body' => 'This is for testing email using smtp'
    ];

    return new \App\Mail\sendResponse($details);
})->name('send-mail');
//
Route::get('send_mail_gdf/', function () {

    $details = [
        'title' => 'Mail from ItSolutionStuff.com',
        'body' => 'This is for testing email using smtp'
    ];

    return new \App\Mail\sendResponse($details);
})->name('send-mail');



Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');
