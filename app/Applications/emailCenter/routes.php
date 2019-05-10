<?php



$arguments = array(
	'applications'=>'messageCenter',
	// applicationKey:secret this secret is defined on the applications table
	// this key must be unique per application.
	'middleware'=>['web','auth','applicationKey:##emailCenter##','permissions'],
	'namespace' => 'App\Applications\emailCenter\Controllers',
	'prefix'=>'/dashboard/emailCenter'
);



Route::group($arguments, function() {
	
	Route::get('/','emailCenterController@index')->name('emailCenter');
 	Route::post('sendMessage','emailCenterController@sendMessage')->name('sendMessage');
		 
});