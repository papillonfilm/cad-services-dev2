<?php



$arguments = array(
	'applications'=>'logs',
	// applicationKey:secret this secret is defined on the applications table
	// this key must be unique per application.
	'middleware'=>['web','auth','applicationKey:logApiKey123$','permissions'],
	'namespace' => 'App\Applications\logs\Controllers',
	'prefix'=>'/dashboard/logs'
);



Route::group($arguments, function() {
	
	Route::get('/','LogsController@index')->name('logs');
	Route::post('showLogs','LogsController@showLogs')->name('showLogs');
	Route::get('viewLog/{id}','LogsController@viewLog')->name('viewLog');
 	 
});