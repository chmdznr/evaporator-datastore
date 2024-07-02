<?php

Route::redirect('/', '/login');
Route::get('/home', function () {
    if (session('status')) {
        return redirect()->route('admin.home')->with('status', session('status'));
    }

    return redirect()->route('admin.home');
});

Auth::routes(['register' => false]);

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'namespace' => 'Admin', 'middleware' => ['auth']], function () {
    Route::get('/', 'HomeController@index')->name('home');
    // Permissions
    Route::delete('permissions/destroy', 'PermissionsController@massDestroy')->name('permissions.massDestroy');
    Route::resource('permissions', 'PermissionsController');

    // Roles
    Route::delete('roles/destroy', 'RolesController@massDestroy')->name('roles.massDestroy');
    Route::resource('roles', 'RolesController');

    // Users
    Route::delete('users/destroy', 'UsersController@massDestroy')->name('users.massDestroy');
    Route::resource('users', 'UsersController');

    // Audit Logs
    Route::resource('audit-logs', 'AuditLogsController', ['except' => ['create', 'store', 'edit', 'update', 'destroy']]);

    // Newborn Cv
    Route::delete('newborn-cvs/destroy', 'NewbornCvController@massDestroy')->name('newborn-cvs.massDestroy');
    Route::post('newborn-cvs/media', 'NewbornCvController@storeMedia')->name('newborn-cvs.storeMedia');
    Route::post('newborn-cvs/ckmedia', 'NewbornCvController@storeCKEditorImages')->name('newborn-cvs.storeCKEditorImages');
    Route::resource('newborn-cvs', 'NewbornCvController');

    // Newborn Data
    Route::delete('newborn-datas/destroy', 'NewbornDataController@massDestroy')->name('newborn-datas.massDestroy');
    Route::post('newborn-datas/media', 'NewbornDataController@storeMedia')->name('newborn-datas.storeMedia');
    Route::post('newborn-datas/ckmedia', 'NewbornDataController@storeCKEditorImages')->name('newborn-datas.storeCKEditorImages');
    Route::resource('newborn-datas', 'NewbornDataController');
});
Route::group(['prefix' => 'profile', 'as' => 'profile.', 'namespace' => 'Auth', 'middleware' => ['auth']], function () {
    // Change password
    if (file_exists(app_path('Http/Controllers/Auth/ChangePasswordController.php'))) {
        Route::get('password', 'ChangePasswordController@edit')->name('password.edit');
        Route::post('password', 'ChangePasswordController@update')->name('password.update');
        Route::post('profile', 'ChangePasswordController@updateProfile')->name('password.updateProfile');
        Route::post('profile/destroy', 'ChangePasswordController@destroy')->name('password.destroyProfile');
    }
});
