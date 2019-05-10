<?php


namespace App\Applications\emailCenter\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Applications\emailCenter\Models\userGroup;
use App\Applications\emailCenter\Models\groupApplication;
 
use App\User;
use App\groups;
use App\applications;
use Auth;
use Validator;

class emailCenterController extends Controller{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
	
	 
	
    public function __construct(){

    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
		
		
		$users = User::orderBy('name')->get();
		$groups = Groups::orderBy('name')->get();
		$applications = Applications::orderBy('name')->get();
		 
       
        return view('emailCenter::home' , ['users'=>$users , 'groups'=>$groups, 'applications'=>$applications ] );
		
    }
	
	public function sendMessage(Request $post){
		$validator = Validator::make(
			$post->All()
		,[
			'subject'=>'required ',
			'target'=>'required|numeric|min:1|max:3 ',
			'message'=>'required'
		] )->validate();
		
		
		$userId = Auth::getUser()->id;
		// we need a number for the target
		if($post['target'] ==1){
			if(!is_numeric($post['userId'])){
				return redirect()->back()->with('alert-error','Please select a User');
			}
		}else if ($post['target'] ==2){
			if(!is_numeric($post['groupId'])){
				return redirect()->back()->with('alert-error','Please select a Group');
			}
		}else if($post['target'] ==3){
			if(!is_numeric($post['applicationId'])){
				return redirect()->back()->with('alert-error','Please select an Application');
			}
		}
		 		
		// Now we need to search for the user/group/application to send the email message.
		if($post['target'] == 1){
			// User
			$user = User::find($post['userId']);
			$name = $user->name . ' ' . $user->initial . ' '. $user->lastname . ' '. $user->lastname2;
			$email = $user->email;
			
			$emailVals = array('to'=>$email, 'subject'=>$post['subject'], 'msg'=>$post['message']);
			
			sendEmail($emailVals);
			
			$post->session()->flash('alert-success', 'Message successfully send.');
			return redirect()->route('emailCenter');
			
		}else if ($post['target'] == 2){
			// Group
			$group = userGroup::where('groupId','=',$post['groupId'])->get();
			 
			foreach($group as $g){
			 
				// Send emails to all group members..
				$user = User::find($g->userId);
				$name = $user->name . ' ' . $user->initial . ' '. $user->lastname . ' '. $user->lastname2;
				$email = $user->email;

				$emailVals = array('to'=>$email, 'subject'=>$post['subject'], 'msg'=>$post['message']);

				sendEmail($emailVals);
				
			}
			
			$post->session()->flash('alert-success', 'Message successfully send.');
			return redirect()->route('emailCenter');
				
			 
		}else if($post['target'] == 3){
			// Application
			$groupApplication = groupApplication::where('applicationId','=',$post['applicationId'])->get();
			
			// we got the applications now we need to get the groups that have access to that application.
			$targetUsers = array();
			foreach($groupApplication as $gp){
			
				
				$group =  userGroup::where('groupId','=',$gp->groupId)->get();
				
				foreach($group as $g){
					// now we get the users
					// to eliminate duplicates, because a user can have access to the same applications via 2 groups
					// we are going to create an asociative array and use the userID as the element id ;)
					$targetUsers[$g->userId] = $g->userId;
					
				}
				
				
			}
			
			//run array and send emails..
			foreach($targetUsers as $key=>$value){
				$user = User::find($value);
				$name = $user->name . ' ' . $user->initial . ' '. $user->lastname . ' '. $user->lastname2;
				$email = $user->email;

				$emailVals = array('to'=>$email, 'subject'=>$post['subject'], 'msg'=>$post['message']);
				sendEmail($emailVals);
			}
			 
			$post->session()->flash('alert-success', 'Message successfully send.');
			return redirect()->route('emailCenter');
			 
		}
		
		
		
		
		
	}
	
	 
	
 
	
	
}

?>