<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SchoolController;
use App\Http\Controllers\GroupAdminController;

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

Route::get('/', [HomeController::class, 'index'])->name('index');
Route::get('pic', [HomeController::class, 'pic'])->name('pic');
//gsuite登入
Route::get('glogin', [HomeController::class, 'glogin'])->name('glogin');
Route::post('gauth', [HomeController::class, 'gauth'])->name('gauth');
//本機登入
Route::get('login', [HomeController::class, 'login'])->name('login');
Route::post('auth', [HomeController::class, 'auth'])->name('auth');


/**
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});
 */
Route::group(['middleware' => 'auth'], function () {
#登出
Route::post('glogin', [HomeController::class, 'logout'])->name('logout');

Route::get('upload_students/{semester_year?}', [SchoolController::class, 'upload_students'])->name('upload_students');
Route::post('import_excel', [SchoolController::class, 'import_excel'])->name('import_excel');
Route::get('student_type/{semester_year?}', [SchoolController::class, 'student_type'])->name('student_type');
Route::get('edit_student/{student}', [SchoolController::class, 'edit_student'])->name('edit_student');
Route::post('update_student/{student}', [SchoolController::class, 'update_student'])->name('update_student');
Route::post('school_ready', [SchoolController::class, 'school_ready'])->name('school_ready');
});

Route::group(['middleware' => 'group_admin'], function () {
    Route::get('assign_group_admin', [GroupAdminController::class, 'assign_group_admin'])->name('assign_group_admin');
    Route::post('do_assign', [GroupAdminController::class, 'do_assign'])->name('do_assign');
    Route::get('start', [GroupAdminController::class, 'start'])->name('start');
    Route::get('group_admin_unlock/{school}', [GroupAdminController::class, 'group_admin_unlock'])->name('group_admin_unlock');
    Route::get('show_student/{school}', [GroupAdminController::class, 'show_student'])->name('show_student');
    Route::get('show_class/{school}', [GroupAdminController::class, 'show_class'])->name('show_class');
    Route::get('form_class/{school}', [GroupAdminController::class, 'form_class'])->name('form_class');
    Route::get('delete123/{school}', [GroupAdminController::class, 'delete123'])->name('delete123');
    Route::get('delete23/{school}', [GroupAdminController::class, 'delete23'])->name('delete23');
    Route::post('go_form/{school}', [GroupAdminController::class, 'go_form'])->name('go_form');
});