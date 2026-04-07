<?php
use think\facade\Route;

Route::group('api/auth', function () {
    Route::post('login', 'Auth/login');
    Route::post('logout', 'Auth/logout');
});

Route::group('api', function () {
    Route::get('categories', 'Category/index');
    Route::post('feedbacks', 'Feedback/save');
});

Route::group('api', function () {
    Route::get('stats/dashboard', 'Stats/dashboard');

    Route::get('users', 'UserManage/index');
    Route::get('users/export', 'UserManage/export');
    Route::get('users/:id/export', 'UserManage/exportSingle');
    Route::post('users', 'UserManage/save');
    Route::put('users/:id', 'UserManage/update');
    Route::delete('users/:id', 'UserManage/delete');
    Route::delete('users/batch', 'UserManage/batchDelete');
    Route::post('users/:id/reset-password', 'UserManage/resetPassword');

    Route::get('feedbacks', 'Feedback/index');
    Route::get('feedbacks/export', 'Feedback/export');
    Route::get('feedbacks/:id', 'Feedback/read');
    Route::get('feedbacks/:id/export', 'Feedback/exportSingle');
    Route::put('feedbacks/:id/status', 'Feedback/updateStatus');
    Route::put('feedbacks/:id/notes', 'Feedback/updateNotes');
    Route::delete('feedbacks/:id', 'Feedback/delete');
})->middleware(\app\middleware\JwtAuth::class);
