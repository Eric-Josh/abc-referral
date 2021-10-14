<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use App\Http\Controllers\NotificationController;

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
    return view('welcome');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {

        $notifications = auth()->user()->unreadNotifications;
        
        return view('dashboard', compact('notifications'));

    })->name('dashboard');
    Route::post('/mark-as-read/{id}', [NotificationController::class, 'markasread'])->name('markNotification');
    Route::post('/mark-all-as-read', [NotificationController::class, 'markallasread'])->name('markAllNotification');
});
require __DIR__.'/auth.php';
