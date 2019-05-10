<?php

// Routes for webManager Application

$arguments = array(
	'applications'=>'webManager',
	// applicationKey:secret this secret is defined on the applications table
	// this key must be unique per application.
	'middleware'=>['web','auth','applicationKey:webManager$superk3y','permissions'  ], 
	'namespace' => 'App\Applications\webManager\Controllers',
	'prefix'=>'/dashboard/webManager'
);
 
Route::group( $arguments , function() {
	
	// Main app view
    Route::get('/','webManagerController@index')->name('webManager');;
	
	// Users
	Route::get('users','webManagerController@users')->name('users');
	Route::get('users/addUser','webManagerController@addUser')->name('addUser');
	Route::get('users/userDetail/{id}','webManagerController@userDetail')->name('userDetail');
	Route::post('users/addUserToGroup','webManagerController@addUserToGroup')->name('addUserToGroup');
	Route::post('users/removeUserFromGroup','webManagerController@removeUserFromGroup')->name('removeUserFromGroup');
	Route::get('users/editUser/{id}','webManagerController@editUser')->name('editUser');
	Route::post('users/updateUser','webManagerController@updateUser')->name('updateUser');
	Route::post('users/saveUser','webManagerController@saveUser')->name('saveUser');
	
	// Groups
	Route::get('groups','webManagerController@groups')->name('groups');
	Route::get('groups/addGroup','webManagerController@addGroup')->name('addGroup');
	Route::get('groups/editGroup/{id}','webManagerController@editGroup')->name('editGroup');
	Route::get('groups/deleteGroup/{id}','webManagerController@deleteGroup')->name('deleteGroup');
	Route::get('groups/viewGroup/{id}','webManagerController@viewGroup')->name('viewGroup');
	Route::post('groups/addApplicationToGroup','webManagerController@addApplicationToGroup')->name('addApplicationToGroup');
	Route::post('groups/removeApplicationFromGroup','webManagerController@removeApplicationFromGroup')->name('removeApplicationFromGroup');
	Route::post('groups/updateGroup','webManagerController@updateGroup')->name('updateGroup');
	Route::post('groups/saveGroup','webManagerController@saveGroup')->name('saveGroup');
	
	// Applications
	Route::get('applications','webManagerController@applications')->name('applications');
	Route::get('applications/addApplication','webManagerController@addApplication')->name('addApplication');
	Route::post('applications/saveApplication','webManagerController@saveApplication')->name('saveApplication');
	Route::post('applications/updateApplication','webManagerController@updateApplication')->name('updateApplication');
	Route::get('applications/viewApplication/{id}','webManagerController@viewApplication')->name('viewApplication');
	Route::get('applications/editApplication/{id}','webManagerController@editApplication')->name('editApplication');
	Route::get('applications/deleteApplication/{id}','webManagerController@deleteApplication')->name('deleteApplication');
	Route::post('applications/addGroupToApplication','webManagerController@addGroupToApplication')->name('addGroupToApplication');
	Route::post('applications/removeGroupFromApplication','webManagerController@removeGroupFromApplication')->name('removeGroupFromApplication');
	
	// Permissions
	Route::get('permissions','webManagerController@permissions')->name('permissions');
	Route::post('users/permissions','webManagerController@userPermissions')->name('user.permissions');
	Route::post('users/permissions/update','webManagerController@updatePermissionsUser')->name('user.permissions.update');
	Route::post('groups/permissions','webManagerController@groupPermissions')->name('group.permissions');
	Route::post('groups/permissions/update','webManagerController@updatePermissionsGroup')->name('group.permissions.update');
	Route::get('permissions/viewRoute/{id}','webManagerController@viewRoute')->name('permissions.view');
	Route::get('permissions/addRoute','webManagerController@addRoute')->name('permissions.addRoute');
	Route::post('permissions/saveRoute','webManagerController@saveRoute')->name('permission.route.save');
	Route::get('permissions/syncRoutes','webManagerController@syncRoutes')->name('permissions.syncRoutes');
	Route::get('permissions/editRoute/{id}','webManagerController@editPermission')->name('permissions.edit');
	Route::post('permissions/updateRoute','webManagerController@updateRoute')->name('permission.route.update');
	Route::get('permissions/deleteRoute/{id}','webManagerController@deletePermission')->name('permissions.delete');
	
	
	// Categories
	Route::get('/viewCategories','webManagerController@categories')->name('categories');
	Route::get('/editCategory/{id}','webManagerController@editCategory')->name('categories.editRecord');
	Route::get('/deleteCategory/{id}','webManagerController@deleteCategory')->name('categories.deleteRecord');
	Route::get('/addCategory','webManagerController@addCategory')->name('categories.addRecord');
	Route::post('/saveCategory','webManagerController@saveCategory')->name('categories.saveRecord');
	Route::post('/updateCategory','webManagerController@updateCategory')->name('categories.updateRecord');
	
	
			 
});