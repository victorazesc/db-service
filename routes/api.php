<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


use App\Http\Controllers\UserController;
use App\Http\Controllers\OsController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FileEntriesController;
use App\Http\Controllers\Public_controller\Public_controller;
use App\Http\Controllers\Clients\ClientsController;
use App\Http\Controllers\Products\ProductsController;
use App\Http\Controllers\Certificates\Certificates;
use App\Http\Controllers\Receipts\ReceiptsController;
use App\Http\Controllers\Services\ServicesController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Notifications\NotificationsController;
use App\Http\Controllers\ImageUploadController;
use App\Http\Controllers\Profiles\ProfilesController;
use App\Http\Controllers\Help\HelpsController;
use App\Http\Controllers\Logs\LogsController;

Route::post('/changeStatus', [Certificates::class, 'changeStatus']);
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/send-email', [Public_controller::class, 'send_mail']);
Route::get('/send-email-gdf', [Public_controller::class, 'send_mail_gdf']);

Route::post('/Auth/login', [AuthController::class, 'login']);
Route::post('/send-help', [HelpsController::class, 'sendHelp']);
Route::post('/Auth/register', [AuthController::class, 'register']);


Route::group(['middleware' => ['apijwt']], function () {

    Route::post('/Auth/logout', [AuthController::class, 'logout']);
    Route::get('/logs', [LogsController::class, 'logs']);
    Route::get('/generatePdf', [LogsController::class, 'generatePdf']);
    Route::post('/deleteFile', [LogsController::class, 'deleteFile']);
    Route::post('/clearLogs', [LogsController::class, 'clearLogs']);
    Route::post('/store-file', [DocumentController::class, 'store']);
    Route::get('/store-file', [DocumentController::class, 'getImages']);
    Route::get('/image-upload', [ImageUploadController::class, 'imageUpload'])->name('image.upload');
    Route::post('/image-upload', [ImageUploadController::class, 'imageUploadPost'])->name('image.upload.post');
    Route::get('/isAuth', [UserController::class, 'index']);
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::get('/users', [UserController::class, 'index']);
    Route::post('/change-password', [UserController::class, 'changePassword']);
    Route::get('/profiles', [ProfilesController::class, 'getProfiles']);
    Route::get('/helps', [HelpsController::class, 'getHelps']);
    Route::get('/os', [OsController::class, 'getOs']);
    Route::get('/os/{os}', [OsController::class, 'index']);
    Route::post('/add-os', [OsController::class, 'addOs']);
    Route::get('/getStatusOs', [OsController::class, 'getStatusOs']);
    Route::get('/get-notifications', [NotificationsController::class, 'getNotifications']);
    Route::get('/get-notifications-list', [NotificationsController::class, 'getNotificationsList']);
    Route::post('/alter-status-notification', [NotificationsController::class, 'alterStatus']);
    Route::get('/getFrequentHelps', [HelpsController::class, 'getFrequentHelps']);

    Route::get('/clients', [ClientsController::class, 'GetClients']);
    Route::get('/client', [ClientsController::class, 'GetClientById']);
    Route::get('/product', [ProductsController::class, 'GetProductById']);
    Route::get('/certificates', [Certificates::class, 'getCertificates']);
    Route::post('/addCertificate', [Certificates::class, 'addCertificate']);
    Route::post('/editCertificate', [Certificates::class, 'editCertificate']);
    Route::post('/deleteCertificate', [Certificates::class, 'deleteCertificate']);

    Route::post('/addClient', [ClientsController::class, 'addClient']);
    Route::post('/add-help', [HelpsController::class, 'addHelp']);
    Route::post('/edit-help', [HelpsController::class, 'editHelp']);
    Route::post('/delete-help', [HelpsController::class, 'deleteHelp']);
    Route::post('/response-help', [HelpsController::class, 'responseHelpQuestion']);
    Route::post('/deletClient', [ClientsController::class, 'deletClient']);
    Route::post('/editClient', [ClientsController::class, 'editClient']);
    Route::post('/editUser', [UserController::class, 'editUser']);
    Route::post('/deleteUser', [AuthController::class, 'deleteUser']);
    Route::post('/deleteOs/{os}', [OsController::class, 'deleteOs']);
    Route::post('/editOs', [OsController::class, 'editOs']);
    Route::post('/resetPassword', [AuthController::class, 'resetPassword']);


    Route::get('/products', [ProductsController::class, 'GetProducts']);
    // Route::get('/product', [ProductsController::class, 'GetProductById']);
    Route::post('/addProduct', [ProductsController::class, 'addProduct']);
    Route::post('/deletProduct', [ProductsController::class, 'deletProduct']);
    Route::post('/deleteService', [ServicesController::class, 'deleteService']);
    Route::post('/editProduct', [ProductsController::class, 'editProduct']);
    Route::post('/editService', [ServicesController::class, 'editService']);

    Route::get('/receipts', [ReceiptsController::class, 'GetReceipts']);
    Route::get('/services', [ServicesController::class, 'GetServices']);
    Route::post('/addService', [ServicesController::class, 'addService']);
});
