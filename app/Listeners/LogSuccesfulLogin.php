<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

// use App\sysLog;

class LogSuccesfulLogin
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  Login  $event
     * @return void
     */
    public function handle(Login $event){
        
		/*
		This method will be executed everytime that the user login or 
		if the user selected remember me it will also be executed everytime the session expires
		and the remember me token is used.
		that's why is removed from here to the controller method.
		*/
		
		/*
		$user = $event->user;
		$user->lastLogin = date('Y-m-d H:i:s');
		$user->save();
		*/
		// Log the user login event.
		//logAction("Login",2,9, $user->id);
			 
    }
}
