<?php



$arguments = array(
	'applications'=>'notifications',
	// applicationKey:secret this secret is defined on the applications table
	// this key must be unique per application.
	'middleware'=>['web','auth','applicationKey:n0t1fic2ti0ns@pp','permissions'],
	'namespace' => 'App\Applications\notifications\Controllers',
	'prefix'=>'/dashboard/notifications'
);



Route::group($arguments, function() {
	
	Route::get('/','notificationController@index')->name('notifications');
	
	
	//update notification.
	Route::get('/updateNotification/{id?}','notificationController@updateNotification')->name('updateNotification');
	Route::get('/acknowledgeNotification/{id}','notificationController@acknowledgeNotification')->name('acknowledgeNotification');
	Route::get('/removeNotification/{id}','notificationController@removeNotification')->name('removeNotification');
		 
});