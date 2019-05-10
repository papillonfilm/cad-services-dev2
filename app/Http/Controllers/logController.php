<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\sysMailLog;


class logController extends Controller{
    
	
	
	public function trackingImageLog($emailId, $emailHash){
		
		// Google add a = in front of url parameters...
		// Thor fix, nut it may blow later and will be unable to log when the user opens the email.
		$emailId = str_replace('=','',$emailId);
		
		if(is_numeric($emailId)){
			$newHash = md5(config('vidalFramework.preSalt') .$emailId .config('vidalFramework.postSalt') );	
		
			if($newHash == $emailHash){
				// valid user, url not modified..
				// Do the log !!

				$mailLog = sysMailLog::find($emailId);
				$mailLog->userAgent = $_SERVER['HTTP_USER_AGENT'];
				$mailLog->ip = $_SERVER['REMOTE_ADDR'];
				$mailLog->openedTimes = $mailLog->openedTimes + 1;
				$mailLog->lastOpenedDate = mysqlDate(); 
				$mailLog->save();

			}else{
				$mailLog = sysMailLog::find($emailId);
				$mailLog->userAgent = "MD5 Email: $emailHash MD5 Generated $newHash ";
				$mailLog->save();
			}
		}else{
			$newHash = 123;
		}
		
		$headers = ['Content-Type'=> 'image/gif' ];
		return response()->file(public_path('/images/blank.gif') );
		
		
	}
	
	
}