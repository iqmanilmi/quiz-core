<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\UserController;



// Route::get('/', [DocumentController::class, 'create']);
// Route::post('/extracteddocument', [DocumentController::class, 'store']);

Route::get('/documents', [DocumentController::class, 'index']) -> name('document.index') ;
Route::get('/upload', [DocumentController::class, 'create'])->name('document.create');
Route::post('/extracteddocument', [DocumentController::class, 'store'])->name('document.store');
Route::delete('/documents/delete={document}', [DocumentController::class, 'destroy'])->name('document.destroy');
Route::put('/documents/rename={document}', [DocumentController::class, 'update'])->name('document.update');


Route::get('/quiz/{document}', [DocumentController::class, 'mcq']) -> name('document.mcq') ;
Route::post('/quiz/{document}', [DocumentController::class, 'answer']) -> name('document.answer') ;



Route::get('/results', [DocumentController::class, 'chooseresult']) -> name('document.selectresult') ;
Route::get('/results/{document}', [DocumentController::class, 'result']) -> name('document.result') ;



Route::get('/', [DashboardController::class, 'index']) -> name('dashboard.index') ;



Route::get ('/login', [UserController::class, 'index']) -> name('user.login');
Route::get ('/register', [UserController::class, 'create']) -> name('user.register');
Route::post ('/register', [UserController::class, 'store']) -> name('user.create');
Route::post('/authenticate', [UserController::class, 'authenticate']) -> name('user.authenticate');
Route::post('/logout', [UserController::class, 'logout']) -> name('user.logout');
Route::get ('/forgetpassword', [UserController::class, 'forget']) -> name('user.forgetpassword');
Route::post('/forgetpassword', [UserController::class, 'sendtoken']) -> name('user.sendToken');
Route::post('/resetpassword', [UserController::class, 'resetPassword']) -> name('user.resetPassword');
Route::get ('/manageprofile', [UserController::class, 'manageProfile']) -> name('user.manage');
Route::get ('/updateprofile', [UserController::class, 'editProfile']) -> name('user.update');
Route::post ('/saveprofile', [UserController::class, 'updateProfile']) -> name('user.updateProfile');
Route::post ('/savepassword', [UserController::class, 'updatePassword']) -> name('user.updatePassword');
// Clear session route (Start over link)
Route::get('/forget-password/clear', [UserController::class, 'clearResetSession'])->name('user.clearResetSession');