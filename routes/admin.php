<?php
use Illuminate\Support\Facades\Route;


Route::group(['namespace' => 'Admin'], function () {

	Route::get('/access.get', 'Access@index');


    //Access
	Route::post('/access.addpermissions', [Access::class, 'AddPermissions']);
	Route::post('/access.addroles', [Access::class, 'AddRoles']);
});