<?php 

namespace App\Providers;
 
/**
	This file does the inclusion of all applications in the 
	/app/Applications/ Folder
	
	All routes will be available but a middleware is going to verify if the user
	has access to that routes aka application.
	
**/


class ApplicationServiceProvider extends  \Illuminate\Support\ServiceProvider {
	
	
    public function boot(){
		
			// Read all the app/applications folder to add:
			//   -routes
			//   -views
			//   -migrations
			//
			// Modular Application structure.
			
			$appsFolder = __DIR__ . '/../Applications' ;
			$applications = array_diff(scandir($appsFolder), array('..', '.'));
			
 	
			foreach($applications as $key=>$app){

				/*
				This script is going to load our routes and make the views available
				in a package:view format so we can use the method view( app1::view ); to show the view.
				*/

				// Include the Routes 
				if(file_exists(__DIR__.'/../Applications/'.$app.'/routes.php')) {
					include __DIR__.'/../Applications/'.$app.'/routes.php';
				}

				// Load the Views
				if(is_dir(__DIR__.'/../Applications/'.$app.'/Views')) {
					$this->loadViewsFrom(__DIR__.'/../Applications/'.$app.'/Views', $app);
				}
				
				// Add Migrations -  migrations will be avaialbe to use in php artisan migrate.
				if(is_dir(__DIR__.'/../Applications/'.$app.'/Migrations')) {
					$this->loadMigrationsFrom(__DIR__.'/../Applications/'.$app.'/Migrations', $app);
				}

				
			}
		 
		
    }
	
	
    public function register(){
	
	}
	
	
}

?>