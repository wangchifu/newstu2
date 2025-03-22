<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SchoolController;
use App\Http\Controllers\GroupAdminController;
use App\Http\Controllers\OpenIDController;

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
Route::get('about', [HomeController::class, 'about'])->name('about');
Route::get('teach', [HomeController::class, 'teach'])->name('teach');
Route::get('pic', [HomeController::class, 'pic'])->name('pic');
//gsuite登入
Route::get('glogin', [HomeController::class, 'glogin'])->name('glogin');
Route::post('gauth', [HomeController::class, 'gauth'])->name('gauth');

//openid登入
//Route::get('openid_get', 'OpenIdLoginController@openid_get')->name('openid_get');
Route::get('sso', [OpenIDController::class,'sso'])->name('sso');
Route::get('auth/callback', [OpenIDController::class,'callback'])->name('callback');

Route::get('impersonate/{user}', [HomeController::class, 'impersonate'])->name('impersonate');

//本機登入
Route::get('sys_login', [HomeController::class, 'sys_login'])->name('sys_login');
Route::post('sys_auth', [HomeController::class, 'sys_auth'])->name('sys_auth');
Route::get('sys_user', [HomeController::class, 'sys_user'])->name('sys_user');
Route::get('sys_logout', [HomeController::class, 'sys_logout'])->name('sys_logout');


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
Route::get('school_log', [SchoolController::class, 'school_log'])->name('school_log');
Route::get('test_start', [SchoolController::class, 'test_start'])->name('test_start');
Route::get('test_copy', [SchoolController::class, 'test_copy'])->name('test_copy');
Route::get('test_show_student/{school}', [SchoolController::class, 'test_show_student'])->name('test_show_student');
Route::get('test_form_class/{school}', [SchoolController::class, 'test_form_class'])->name('test_form_class');
Route::post('test_go_form/{school}', [SchoolController::class, 'test_go_form'])->name('test_go_form');
Route::get('test_show_class/{school}', [SchoolController::class, 'test_show_class'])->name('test_show_class');
Route::get('test_form_teacher/{school}', [SchoolController::class, 'test_form_teacher'])->name('test_form_teacher');
Route::post('test_go_form_teacher/{school}', [SchoolController::class, 'test_go_form_teacher'])->name('test_go_form_teacher');
Route::get('test_show_teacher/{school}', [SchoolController::class, 'test_show_teacher'])->name('test_show_teacher');
Route::get('test_form_order/{school}', [SchoolController::class, 'test_form_order'])->name('test_form_order');
Route::post('test_go_form_order/{school}', [SchoolController::class, 'test_go_form_order'])->name('test_go_form_order');
Route::get('test_print/{school}', [SchoolController::class, 'test_print'])->name('test_print');
Route::get('test_print2/{school}', [SchoolController::class, 'test_print2'])->name('test_print2');
Route::get('test_delete123/{school}', [SchoolController::class, 'test_delete123'])->name('test_delete123');
Route::get('test_delete23/{school}', [SchoolController::class, 'test_delete23'])->name('test_delete23');
Route::get('test_delete3/{school}', [SchoolController::class, 'test_delete3'])->name('test_delete3');

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
    Route::get('delete3/{school}', [GroupAdminController::class, 'delete3'])->name('delete3');
    Route::get('delete_all/{group}', [GroupAdminController::class, 'delete_all'])->name('delete_all');
    Route::post('go_form/{school}', [GroupAdminController::class, 'go_form'])->name('go_form');
    Route::get('form_teacher/{school}', [GroupAdminController::class, 'form_teacher'])->name('form_teacher');
    Route::post('go_form_teacher/{school}', [GroupAdminController::class, 'go_form_teacher'])->name('go_form_teacher');
    Route::get('show_teacher/{school}', [GroupAdminController::class, 'show_teacher'])->name('show_teacher');
    Route::get('form_order/{school}', [GroupAdminController::class, 'form_order'])->name('form_order');
    Route::post('go_form_order/{school}', [GroupAdminController::class, 'go_form_order'])->name('go_form_order');
    Route::get('print/{school}', [GroupAdminController::class, 'print'])->name('print');
    Route::get('print2/{school}', [GroupAdminController::class, 'print2'])->name('print2');
    Route::get('export/{school}', [GroupAdminController::class, 'export'])->name('export');
    Route::get('group_log', [GroupAdminController::class, 'group_log'])->name('group_log');
});