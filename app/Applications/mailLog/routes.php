<?php



$arguments = array(
	'applications'=>'mailLog',
	// applicationKey:secret this secret is defined on the applications table
	// this key must be unique per application.
	'middleware'=>['web','auth','applicationKey:m@ilL0gk3y','permissions'],
	'namespace' => 'App\Applications\mailLog\Controllers',
	'prefix'=>'/dashboard/mailLogs'
);



Route::group($arguments, function() {
	
	// The first route must be the name of the application('folder') or must match the 
	// folder( aka route ) on the application information on the framework.
 	Route::get('/','mailLogController@index')->name('mailLogs');
	Route::get('mailDetail/{id}','mailLogController@mailDetail')->name('viewEmailLog');
	Route::get('resendEmail/{id}','mailLogController@resendEmail')->name('resendEmail');
	Route::post('ajaxMailLogs/','mailLogController@ajaxMailLogs')->name('ajaxMailLogs');
	 
});



