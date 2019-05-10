<?php 

$arguments=array(

	'applications'=>'treeCharts', 
	// applicationKey:secret this secret is defined on the applications table
	// this key must be unique per application.
	'middleware'=>['web','auth','applicationKey:treeCharts%^Vpy','permissions' ],
	'namespace' => 'App\Applications\treeCharts\Controllers',
	'prefix'=>'/dashboard/treeCharts'
	);


	Route::group($arguments, function() { 

		Route::get('/','treeChartsController@index')->name('treeCharts');
		Route::get('/viewChart/{id}','treeChartsController@view')->name('treeCharts.view');
		Route::get('/addChart','treeChartsController@addChart')->name('treeCharts.addChart');
		Route::get('/edit/{id}','treeChartsController@edit')->name('treeCharts.edit');
		Route::post('/update','treeChartsController@update')->name('treeCharts.update');
		Route::post('/save','treeChartsController@save')->name('treeCharts.save');
		Route::get('/delete/{id}','treeChartsController@delete')->name('treeCharts.delete');
		Route::get('/chartJson/{id}','treeChartsController@chartJson')->name('treeCharts.chartJson');
		
		// Nodes
		Route::get('/manageNodes/{id}','treeChartsController@viewChartNodes')->name('treeCharts.nodes');
		Route::post('/addNode','treeChartsController@saveNode')->name('treeCharts.addNode');
		Route::post('/deleteNode','treeChartsController@deleteNode')->name('treeCharts.deleteNode');
		Route::post('/editNode','treeChartsController@editNode')->name('treeCharts.editNode');
		Route::post('/updateNode','treeChartsController@updateNode')->name('treeCharts.updateNode');
		Route::post('/updateColor','treeChartsController@updateColor')->name('treeCharts.updateColor');
		
	});

