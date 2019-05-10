<?php

use \App\sysMailLog;
use \App\sysLog;
use \App\MessageCenter;
use \App\User;
use \App\groupApplication;
use \App\Notifications;

//use Illuminate\Support\Facades\Session;

/**
 * Sends emails from anywhere
 *
 * $emailValues is an array of variables to be passed to the email
 * $emailVars = array();
 * $emailVars['to'] = 'rvidal@pbcgov.org';
 * $emailVars['subject'] = 'Test';
 * $emailVars['msg'] = 'Some <b> message </b>.';
 *
 *
 * @param array $array must be an asociative array with ('to'=>$email,'subject'=>'Some Subject','msg'=>'messageBody')
 * @return bool
 *
 */


function sendEmail($emailValues, $returnHTML = false){
	
	// Are we going to track that email ?
	$trackEmail = ( isset($emailValues['trackEmail'])?$emailValues['trackEmail']:true );			

	
	if($returnHTML == true){
		$emailValues['returnHTML'] = true;
		$emailHtml = new \App\Mail\EmailBasic($emailValues);
		return $emailHtml;
	}else{
		
		$sysLog = new sysMailLog;
		$sysLog->email = isset($emailValues['to'])?$emailValues['to']:'';
		$sysLog->name = isset($emailValues['name'])?$emailValues['name']:'';
		$sysLog->message = isset($emailValues['msg'])?$emailValues['msg']:'';
		$sysLog->subject = isset($emailValues['subject'])?$emailValues['subject']:'';
		$sysLog->fromEmail = config('mail.from.address');
		$sysLog->fromName = config('mail.from.name');
		$sysLog->save();

		$lastInsertedId = $sysLog->id ;

		// do we want to track to see if the user open the email ?
		if($trackEmail == true){

				$hash = md5(config('vidalFramework.preSalt') .$lastInsertedId .config('vidalFramework.postSalt') );	
				// Create Tracking Image
				$emailValues['trackingImage'] = "<img src='".url("/")."/timg/$lastInsertedId/$hash' width='0px' height='0px' alt='' border='' /> ";	

		}

		
		$to = $emailValues['to'];
		Mail::to($to)->send(new \App\Mail\EmailBasic($emailValues));
		
		return true;
	}
	
	
	
}


/**
 *  logActions helper function
 *
 *  Log all system actions to keep record for forensic if needed
 *
 * @param string $description Description of the action
 * @param int|string $category Category of the action
 * @param int|string $type Type of action
 * @param int $userId (optional) userId of the user performing the action, if not defined current userId will be used.
 * @return bool true on success false on failure  <br>
 *
 *	Categories
 *		1- Account Creation
 *		2- Login
 *		3 -Logout
 *		4- Recover Password
 *		5- Activation Email
 *		6- Validation Error
 *		7- System process
 *		8- Email Sended
 *		9- User Actions
 *		10- Sudo
 *		
 *
 *	Types	
 *		1-Update
 *		2-Error
 *		3-Hack Attempt
 *		4-Info
 *		5-Debug
 *		6-Login Error
 *		7-Create Account
 *		8-Activate Account
 *		9-Success
 *		10-System Information
 *		
 *		
 */

function logAction($description, $category='User Actions', $type = 'Info', $userId = 0){
	
	
	//Convert Categories for Text.
	if(is_numeric($category)){
		switch($category){
			case 1:
				$category = 'Account Creation';
				break;
			case 2:
				$category = 'Login';
				break;
			case 3:
				$category = 'Logout';
				break;
			case 4:
				$category = 'Recover Password';
				break;
			case 5:
				$category = 'Activation Email';
				break;
			case 6:
				$category = 'Validation Error';
				break;
			case 7:
				$category = 'System Process';
				break;
			case 8:
				$category = 'Email Sended';
				break;
			case 9:
				$category = 'User Actions';
				break;
			case 10:
				$category = 'Sudo';
				break;
			default:
				$category = 'User Actions';
		}
	}
	
	// Convert types to text
	if(is_numeric($type)){
		switch($type){
			case 1:
				$type = 'Update';
				break;
			case 2:
				$type = 'Error';
				break;
			case 3:
				$type = 'Hack Attempt';
				break;
			case 4:
				$type = 'Info';
				break;
			case 5:
				$type = 'Debug';
				break;
			case 6:
				$type = 'Login Error';
				break;
			case 7:
				$type = 'Create Account';
				break;
			case 8:
				$type = 'Activate Account';
				break;
			case 9:
				$type = 'Success';
				break;
			case 10:
				$type = 'System Information';
				break;
			default:
				$type = 'Info';
		}
	}
	
	
	// Get the user id
	if($userId == 0){
		// not defined 
		if(Auth::getUser()){
			$userId = Auth::getUser()->id ;
		}		
	}
	
	$log = new sysLog;
	if($log){
		$log->type = $type;
		$log->category = $category;
		$log->description = $description;
		$log->userId = $userId;
		$log->ip = $_SERVER['REMOTE_ADDR'];
		$log->url = $_SERVER['REQUEST_URI'];
		$log->userAgent = $_SERVER['HTTP_USER_AGENT'];
		$log->save();
		return true;
	}else{
		return false;
	}
	
	
	
	
}

/**
 * Get the applications that the user have access
 *
 * Will get all the applications into an asociative array.
 *
 * @return array Asociative Array with all the applications and [name,iconPath,location,categoryName]
 *
 */

function getApplications(){
	
	$userId = Auth::getUser()->id;
		
		// Get all the categories for the applications that the user have access.
		$categories = DB::table('sysApplications')->
			join('sysGroupApplication','sysApplications.id','=','sysGroupApplication.applicationId')->
			join('sysGroups','sysGroupApplication.groupId','=','sysGroups.id')->
			join('sysUserGroup','sysGroups.id','=','sysUserGroup.groupId')->
			join('sysApplicationCategories','sysApplications.categoryId','=','sysApplicationCategories.id')->
			select('sysApplicationCategories.id', 'sysApplicationCategories.name'  )->
			groupBy('sysApplicationCategories.id')->
			groupBy('sysApplicationCategories.name')-> 
		 
			orderBy('sysApplicationCategories.name')->
			where('sysUserGroup.userId','=',$userId)->get();
		
	
		// now we get all the applications per category..
		$userApplications = array();
		
		foreach($categories as $key=>$value){
			
			$apps = DB::table('sysApplications')->
			join('sysGroupApplication','sysApplications.id','=','sysGroupApplication.applicationId')->
			join('sysGroups','sysGroupApplication.groupId','=','sysGroups.id')->
			join('sysUserGroup','sysGroups.id','=','sysUserGroup.groupId')->
			select('sysApplications.name', 'sysApplications.location', 'sysApplications.iconPath' )->
			groupBy('sysApplications.id')->
			groupBy('sysApplications.name')->
			groupBy('sysApplications.location')->
			groupBy('sysApplications.iconPath')->
			orderBy('sysApplications.name')->
			where('sysApplications.categoryId','=', $value->id)->
			where('sysUserGroup.userId','=',$userId)->get();
			
			// Add the category information
			foreach($apps as $k=>$app){
				
				$userApplications[$value->name][] = array(
				'name'=>$app->name,
				'iconPath'=>$app->iconPath,
				'location'=>$app->location,
				'categoryName'=>$value->name
				
				);
			}
			
			// now we add the information of the Applications
			
			
		}
		//dd($userApplications);
		return $userApplications ;
	 
	
}


/**
 * Get the date formated for mysql inserts
 *
 * @param timestamp $timestamp Create date from submitted timestamp
 *
 * @return string Formated date ("Y-m-d H:i:s");
 *
 */

function mysqlDate($timeStamp = ''){
	if($timeStamp != ''){
		return date("Y-m-d H:i:s", $timeStamp);
	}else{
		return date("Y-m-d H:i:s");
	}
}



/**
 * Get messages for current login user
 *
 * Messages can be directed to a user, group or application
 * if the user have access or is a member he will receive the message.
 * message will be save on session to be flash when page renders.
 *
 * @return bool true
 *
 */
function getMessagesFromMessageCenter(){
	// Get the alerts for the user.
	$flashMessage = array();
	$groupMemberships = array();
	$count = 1;
	$mCenter = '';
	// Verify it the alert is for the user.
	$userId = Auth::getUser()->id;
	$todaysDate = mysqlDate();
	$message = MessageCenter::where('userId','=',$userId)->
		where('startDate','<=',$todaysDate)->
		where('endDate','>=' , $todaysDate)->
		whereRaw("showTimes >= (select count(*) FROM sysLog where userId = $userId and category = 'Login' and created_at >= startDate )" )
		->get();
		//->toSql();
	 
	$totalMessages = $message->count();
	if($totalMessages > 0){
		// we have a message we add the session message to be show
		foreach($message as $m){
			$flashMessage[] = $m->message  ;
			$mCenter = MessageCenter::find($m->id);
			$mCenter->showedTimes = $m->showedTimes +1;
			$mCenter->save();
		}
	} 
	
	
	// get alerts for the group..
	// the the groups that the user is part of.
	$groupMembershipsObject = User::find($userId)->groups()->get();
 	
	foreach($groupMembershipsObject as $gm){
		
		$groupMemberships[] = $gm->id;
	}
	
	$groupMessage = MessageCenter::whereIn('groupId',$groupMemberships)->
		where('startDate','<=',$todaysDate)->
		where('endDate','>=' , $todaysDate)->
		whereRaw("showTimes >= (select count(*) FROM sysLog where userId = $userId and category = 'Login' and created_at >= startDate )" )->
		//toSql(); dd($groupMessage );
		get();
 
	$totalGroupMessages = $groupMessage->count();
	
	if($totalGroupMessages > 0){
		// we have a message we add the session message to be show
		foreach($groupMessage as $m){
			$flashMessage[] = $m->message  ;
			$mCenter = MessageCenter::find($m->id);
			$mCenter->showedTimes = $m->showedTimes +1;
			$mCenter->save();
		}
	} 
	
	
	// get the alerts for the application..
	$applicationMembershipsObject = GroupApplication::whereIn('groupId',$groupMemberships)->get();
	$applicationMessages = array();
	$applicationMemberships = array();
	 foreach($applicationMembershipsObject as $app){
		 $applicationMemberships[] = $app->applicationId;
	 }
	
	$applicationMessages = MessageCenter::whereIn('applicationId',$applicationMemberships)->
		where('startDate','<=',$todaysDate)->
		where('endDate','>=' , $todaysDate)->
		whereRaw("showTimes >= (select count(*) FROM sysLog where userId = $userId and category = 'Login' and created_at >= startDate )" )->
		get();
	
	$totalApplicationMessages = $applicationMessages->count();
	
	if($totalApplicationMessages > 0){
		// we have a message we add the session message to be show
		foreach($applicationMessages as $m){
			$flashMessage[] = $m->message  ;
			$mCenter = MessageCenter::find($m->id);
			$mCenter->showedTimes = $m->showedTimes +1;
			$mCenter->save();
		}
	}
	
	
	
	// Now we walk the array to create the message to be display for the user.
	$userMessage = '';
	if(count($flashMessage) > 1){
		foreach($flashMessage as $key=>$val){
			$num = $key +1;
			$userMessage .= $num ."." .$val . '<br>';
		}
		
		Session::flash('alert-message', $userMessage );

	}elseif(count($flashMessage) > 0){
		// Only 1 message we don't need to enumerate them.
		foreach($flashMessage as $key=>$val){ 
			$userMessage =  $val;
		}
		
		Session::flash('alert-message', $userMessage );
	}
	
	 
	 return true;
	 
	
}

/**
 * Breadcrumb helper function
 *
 * This function will generate the breadcrumb based on the hiercharchy structure of the url
 *
 * @return string generated html with the breadcrumb structure and links.
 * 
 */

function breadcrumb(){
	
	$url = $_SERVER['REQUEST_URI'];
	$arrayElements = explode('/',$url);
	$arrayElements2 = $arrayElements;
	$lastElement =  array_pop($arrayElements2);
	
	while (is_numeric($lastElement)){
		$lastElement = array_pop($arrayElements2);	
		
	}
	
	
	$route = Route::Current();
	$currentPageRoute = $route->getName();
	
	$breadcrumb = '<ol class="breadcrumb">';
	$breadcrumb .= '<li> <i class="fa fa-home"></i></li> ';
	
	 
	
	
	foreach($arrayElements as $element){
		if($element != '' and !is_numeric($element)){
			
			$elementName =  preg_replace('/(?<=\\w)(?=[A-Z])/'," $1", $element);
			$elementName = trim($elementName);
			$elementName = ucwords($elementName);
			
			if($element == $currentPageRoute){
				$breadcrumb .= '<li>';
				$breadcrumb .= "  $elementName  ";
				$breadcrumb .='</li>';
			}else{
				 
				
				$breadcrumb .= '<li>';
			
				if( $element == $lastElement  ){
					$breadcrumb .= " $elementName ";				
				}else{
					if(Route::has($element)){
						$breadcrumb .= "<a href='" . route($element ) . "' > $elementName </a>";
					}
						
					
				}
				$breadcrumb .='</li>';
				
				
			}
			
		}
	}
	$breadcrumb .='</ol>';
 
	
	return $breadcrumb;
}


/**
 * Notifications helper function
 *
 * This function will generate an array with the notifications messages.
 *
 * @return array Asociative array with all the notifications for the current login user.
 *
 */

function notifications(){
	
	// First we need to check if the user have access to the notifications app.
	$notifications = array();
	$apps = getApplications();
	$access = false;
	 
	foreach($apps as $app){
		
		foreach($app as $a){
			if($a['name'] == 'Notifications'){
				$access = true;
				continue;
			}

		}
		
	}
	
	
	if($access == false){
		$notifications['access'] = 0;
		return $notifications;
	}
	
	$notifications['access'] = 1;
	
	// The user have access to the app, so lets get the notifications etc.
	$userId = Auth::getUser()->id;
	$alerts = Notifications::where('userId','=',$userId)->where('readed','=',0)->orderBy('created_at', 'desc')->get();
	
	if($alerts->isEmpty() ){
		$notifications['total'] = 0;
		return $notifications ; 
	}
 
	 
 	$notifications['total'] = $alerts->count();
	
	
	// let's create our array to be returned and use in views etc.
	
	foreach($alerts as $a){

		$notifications['notifications'][] = array(
			'created_at'=>$a->created_at,
			'notification'=>$a->notification,
			'id'=>$a->id
		
		);
		
	}
	
	return $notifications;
	
}

/**
 * addNotification helper function to add new notifications
 *
 * This function will insert new notifications for user.
 *
 * @param string $message Notification message for the user.
 * @param int $userId id of the target user.
 *
 * @returns bool true on success or false on error.
 *
 */

function addNotification($notification,$targetUserId){
	
	if(is_numeric($targetUserId) and $notification !=''){
		$alert = new Notifications;
		$alert->notification = $notification;
		$alert->userId = $targetUserId;
		$alert->save();	
		return true;
	}else{
		return false;
	}
		
}



?>