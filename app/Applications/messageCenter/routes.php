<?php



$arguments = array(
	'applications'=>'messageCenter',
	// applicationKey:secret this secret is defined on the applications table
	// this key must be unique per application.
	'middleware'=>['web','auth','applicationKey:##messageCenter##','permissions'],
	'namespace' => 'App\Applications\messageCenter\Controllers',
	'prefix'=>'/dashboard/messageCenter'
);



Route::group($arguments, function() {
	
	Route::get('/','messageCenterController@index')->name('messageCenter');
	Route::get('addMessage','messageCenterController@addMessage')->name('addMessage');
	Route::get('messageDetail/{id}','messageCenterController@messageDetail')->name('messageDetail');
	Route::get('editMessage/{id}','messageCenterController@editMessage')->name('editMessage');
	
	Route::post('updateMessage','messageCenterController@updateMessage')->name('updateMessage');
	Route::post('saveMessage','messageCenterController@saveMessage')->name('saveMessage');
	Route::get('deleteMessage/{id}','messageCenterController@deleteMessage')->name('deleteMessage');
	Route::post('saveUserMessage','messageCenterController@saveUserMessage')->name('saveUserMessage');
	Route::post('saveGroupMessage','messageCenterController@saveGroupMessage')->name('saveGroupMessage');
	Route::post('saveApplicationMessage','messageCenterController@saveApplicationMessage')->name('saveApplicationMessage');
		 
});