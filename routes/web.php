<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SchoolController;
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
Route::get('mlogin', [HomeController::class, 'mlogin'])->name('mlogin');
Route::get('mlogin', [HomeController::class, 'mlogin'])->name('login');
Route::post('mauth', [HomeController::class, 'mauth'])->name('mauth');


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
});