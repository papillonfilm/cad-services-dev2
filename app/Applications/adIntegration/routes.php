<?php



$arguments = array(
	'applications'=>'adIntegration',
	// applicationKey:secret this secret is defined on the applications table
	// this key must be unique per application.
	'middleware'=>['web','auth','applicationKey:ad1nt3grat10n','permissions'],
	'namespace' => 'App\Applications\adIntegration\Controllers',
	'prefix'=>'/dashboard/adIntegration'
);



Route::group($arguments, function() {
	
	Route::get('/','AdIntegrationController@index')->name('adIntegration');
 	Route::get('localUsers','AdIntegrationController@localUsers')->name('localUsers');
 	Route::get('enableAccounts','AdIntegrationController@enableAccounts')->name('enableAccounts');
	Route::get('disableAccounts','AdIntegrationController@disableAccounts')->name('disableAccounts');
	Route::get('adDisableAccount/{id}','AdIntegrationController@adDisableAccount')->name('adDisableAccount');
	Route::get('adDisableAccountFromAd/{id}','AdIntegrationController@adDisableAccountFromAd')->name('adDisableAccountFromAd');
	Route::get('adEnableAccount/{id}','AdIntegrationController@adEnableAccount')->name('adEnableAccount');
	Route::get('adUsers','AdIntegrationController@adUsers')->name('adUsers');
	Route::get('importedAdUsers','AdIntegrationController@importedAdUsers')->name('importedAdUsers');
	Route::get('usersNotInAd','AdIntegrationController@usersNotInAd')->name('usersNotInAd');
	Route::get('adNotImported','AdIntegrationController@adNotImported')->name('adNotImported');
	Route::get('importAdUser/{email}','AdIntegrationController@importAdUser')->name('importAdUser');
	
	
});