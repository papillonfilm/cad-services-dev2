<?php 

$arguments=array(

	'applications'=>'sessions', 
	// applicationKey:secret this secret is defined on the applications table
	// this key must be unique per application.
	'middleware'=>['web','auth','applicationKey:sessionsXWk=7','permissions'],
	'namespace' => 'App\Applications\sessions\Controllers',
	'prefix'=>'/dashboard/sessions'
	);


	Route::group($arguments, function() { 

		Route::get('/','sessionsController@index')->name('sessions');
		Route::get('/logoutUser/{id}','sessionsController@logoutUser')->name('sessionsLogoutUser');
		
		
	});

