<?php



$arguments = array(
	'applications'=>'sudo',
	// applicationKey:secret this secret is defined on the applications table
	// this key must be unique per application.
	'middleware'=>['web','auth','applicationKey:sudokey!@#$%^&*()','permissions'],
	'namespace' => 'App\Applications\sudo\Controllers',
	'prefix'=>'/dashboard/sudo'
);



Route::group($arguments, function() {
	
	Route::get('/','sudoController@index')->name('sudo');
	Route::get('sudoInto/{id}','sudoController@sudo')->name('sudoInto');
		 
});