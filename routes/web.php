<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MsgController;
use App\Http\Controllers\ReplyController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\ImageController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::resource('/msg', MsgController::class);
// Route::get('/msg', [MsgController::class, 'search'])->name('member.search');
Route::resource('/reply', ReplyController::class);
// Route::resource('/img', memberController::class);
// Route::get('/reply/{id}', [ReplyController::class, 'index'])->name('reply.index');

// Route::resource('/member', MemberController::class);
// Route::get('/member', [MemberController::class, 'index'])->name('member.index');
Route::get('/member/create', [MemberController::class, 'create'])->name('member.create');
// Route::post('/member', [MemberController::class, 'store'])->name('member.store');
// Route::get('/member/{id}', [MemberController::class, 'show'])->name('member.show');
// Route::get('/member/{id}/edit', [MemberController::class, 'edit'])->name('member.edit');
// Route::put('/member/{id}', [MemberController::class, 'update'])->name('member.update');
// Route::delete('/member/{id}', [MemberController::class, 'destroy'])->name('member.destroy');

Route::post('/member/login', [MemberController::class, 'login'])->name('member.login');
Route::get('/member/logout', [MemberController::class, 'logout'])->name('member.logout');
// Route::get('/member/space/{id}', [MemberController::class, 'space'])->name('member.space');
Route::get('/member/{id}', [MemberController::class, 'show'])->name('member.show');
Route::resource('/member', MemberController::class);
Route::resource('/img', ImageController::class);
