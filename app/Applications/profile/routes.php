<?php



$arguments = array(
	'applications'=>'profile',
	// applicationKey:secret this secret is defined on the applications table
	// this key must be unique per application.
	'middleware'=>['web','auth','applicationKey:##user$$profile##','permissions'],
	'namespace' => 'App\Applications\profile\Controllers',
	'prefix'=>'/dashboard/profile'
);



Route::group($arguments, function() {
	
	Route::get('/','profileController@index')->name('profile');
 	Route::put('saveProfile','profileController@saveProfile')->name('saveProfile');
	Route::put('updatePassword','profileController@updatePassword')->name('updatePassword');
	Route::put('updateProfilePicture','profileController@updateProfilePicture')->name('updateProfilePicture');
		 
});