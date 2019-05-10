<?php


namespace App\Applications\messageCenter\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

// App Models
use App\Applications\messageCenter\Models\sysMessageCenter;

use App\User;
use App\groups;
use App\applications;
use DB;
use Auth;
use Validator;
class messageCenterController extends Controller{
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
		
		
		$messages = sysMessageCenter::orderBy('created_at')->get();
		
        return view('messageCenter::home',['messages'=>$messages]);
		
    }
	
	public function messagesAjax(Request $post){
		
		 
		
		
	}
	
	 public function addMessage(){
		
		$users = User::orderBy('name')->get();
		$groups = Groups::orderBy('name')->get();
		$applications = Applications::orderBy('name')->get();
		 
        return view('messageCenter::addMessage',['users'=>$users , 'groups'=>$groups, 'applications'=>$applications ]  );
		
    }
	
	public function saveUserMessage(Request $post){
		
		$validator = Validator::make(
			$post->All()
		,[
			'name' => 'required|string', 
			'startDate'=>'required ',
			'endDate'=>'required ',
			'showTimes'=>'required|numeric ',
			'userId'=>'required|numeric',
			'message'=>'required'
		] )->validate();
		
		
		$m = new sysMessageCenter;
		$m->name = $post['name'];
		$m->startDate = mysqlDate(strtotime($post['startDate']));
		$m->userId = $post['userId'];
		$m->message = $post['message'];
		$m->endDate = mysqlDate(strtotime($post['endDate']));
		$m->showTimes = $post['showTimes'];
		$m->createdBy = Auth::getUser()->id;
		$m->save();
		
		$user = User::find($post['userId']);
		$userName = $user->name . ' ' . $user->initial . ' '.$user->lastname . ' '. $user->lastname2;
		
		logAction("Message added for user $userName [ " . $post['name'] .' ] ',9 );
		
		$post->session()->flash('alert-success', 'Message successfully added.');
		return redirect()->route('messageCenter');
		
		
	}
	
	public function messageDetail($id){
		
		if(!is_numeric($id)){
			return redirect()->route('messageCenter')->with('alert-error','Invalid identifier');
		}
		
		
		
		$m = sysMessageCenter::find($id);
		 
		
		if($m->userId != NULL){
			
			$user = User::find($m->userId);
			$target = 'User: '. $user->name . ' '. $user->initial . ' ' . $user->lastname . ' '. $user->lastname2;
		}elseif($m->groupId != NULL){
			$group = groups::find($m->groupId);
			$target = 'Group: '. $group->name;
			
		}elseif($m->applicationId != NULL){
			$application = applications::find($m->applicationId);
			$target = 'Application: ' . $application->name ;
		}else{
			$target = 'Not Found';
		}
		
		
		return view('messageCenter::viewMessage',['message'=>$m, 'target'=>$target  ]  );
		
	}
	
	
	public function editMessage($id){
		if(!is_numeric($id)){
			return redirect()->route('messageCenter')->with('alert-error','Invalid identifier');
		}
		
		$users = User::orderBy('name')->get();
		$groups = Groups::orderBy('name')->get();
		$applications = Applications::orderBy('name')->get();
		
		$m = sysMessageCenter::find($id);
		
		if($m->userId != NULL){
			$target = 1 ;
		}elseif($m->groupId != NULL){
			$target = 2;
		}elseif($m->applicationId != NULL){
			$target = 3;
		}else{
			$target = 0;
		}
		
		
		return view('messageCenter::editMessage',[ 
			'message'=>$m ,
			'target'=>$target,
			'users'=>$users ,
			'groups'=>$groups,
			'applications'=>$applications
		]);
		
	}
	
	public function saveGroupMessage(Request $post){
		
		$validator = Validator::make(
			$post->All()
		,[
			'name' => 'required|string', 
			'startDate'=>'required ',
			'endDate'=>'required ',
			'showTimes'=>'required|numeric ',
			'groupId'=>'required|numeric',
			'message'=>'required'
		] )->validate();
		
		
		$m = new sysMessageCenter;
		$m->name = $post['name'];
		$m->startDate = mysqlDate(strtotime($post['startDate']));
		$m->groupId = $post['groupId'];
		$m->message = $post['message'];
		$m->endDate = mysqlDate(strtotime($post['endDate']));
		$m->showTimes = $post['showTimes'];
		$m->createdBy = Auth::getUser()->id;
		$m->save();
		
		$group = groups::find($post['groupId']);
		$groupName = $group->name;
		
		logAction('Message [ '.$post['name'] ." ] added for Group [ $groupName ]  ",9 );
		
		$post->session()->flash('alert-success', 'Message successfully added.');
		return redirect()->route('messageCenter');
		
		
	}
	
	public function updateMessage(Request $post){
		$validator = Validator::make(
			$post->All()
		,[
			'name' => 'required|string', 
			'startDate'=>'required ',
			'endDate'=>'required ',
			'showTimes'=>'required|numeric',
			'target'=>'required|numeric|min:1|max:3 ',
			'message'=>'required'
		] )->validate();
		
		// we need a number for the target
		
		$m = sysMessageCenter::find($post['id']);
		$m->name = $post['name'];
		$m->startDate = mysqlDate(strtotime($post['startDate']));
		
		
		if($post['target'] ==1){
			if(!is_numeric($post['userId'])){
				return back()->withInput($post->All())->with('alert-error','Please select a User');
			}else{
				$m->userId = $post['userId'];
			}
		}else if ($post['target'] ==2){
			if(!is_numeric($post['groupId'])){
				return back()->withInput($post->All())->with('alert-error','Please select a Group');
			}else{
				$m->groupId = $post['groupId'];
			}
		}else if($post['target'] ==3){
			if(!is_numeric($post['applicationId'])){
				return back()->withInput($post->All())->with('alert-error','Please select an Application');
			}else{
				$m->applicationId = $post['applicationId'];
			}
		}
		
	
		$m->message = $post['message'];
		$m->endDate = mysqlDate(strtotime($post['endDate']));
		$m->showTimes = $post['showTimes'];
		$m->save();
		
		$post->session()->flash('alert-success', 'Message successfully updated.');
		return redirect()->route('messageCenter');

	}

	public function saveMessage(Request $post){
		$validator = Validator::make(
			$post->All()
		,[
			'name' => 'required|string', 
			'startDate'=>'required ',
			'endDate'=>'required ',
			'showTimes'=>'required|numeric',
			'target'=>'required|numeric|min:1|max:3 ',
			'message'=>'required'
		] )->validate();
		
		// we need a number for the target
		
		$m = new  sysMessageCenter ;
		$m->name = $post['name'];
		$m->startDate = mysqlDate(strtotime($post['startDate']));
		
		
		if($post['target'] ==1){
			if(!is_numeric($post['userId'])){
				return redirect()->back()->with('alert-error','Please select a User');
			}else{
				$m->userId = $post['userId'];
			}
		}else if ($post['target'] ==2){
			if(!is_numeric($post['groupId'])){
				return redirect()->back()->with('alert-error','Please select a Group');
			}else{
				$m->groupId = $post['groupId'];
			}
		}else if($post['target'] ==3){
			if(!is_numeric($post['applicationId'])){
				return redirect()->back()->with('alert-error','Please select an Application');
			}else{
				$m->applicationId = $post['applicationId'];
			}
		}
		
	
		$m->message = $post['message'];
		$m->endDate = mysqlDate(strtotime($post['endDate']));
		$m->showTimes = $post['showTimes'];
		$m->createdBy = Auth::getUser()->id;
		$m->save();
		
		$post->session()->flash('alert-success', 'Message successfully added.');
		return redirect()->route('messageCenter');

	}
	
	public function deleteMessage($id){
		
		if(!is_numeric($id)){
			return redirect()->route('messageCenter')->with('alert-error','Invalid identifier');
		}
		
		$m = sysMessageCenter::find($id);
		$m->delete();
		 
		return redirect()->route('messageCenter')->with('alert-success', 'Message successfully deleted.');
		
	}
	
	public function saveApplicationMessage(Request $post){
		
		$validator = Validator::make(
			$post->All()
		,[
			'name' => 'required|string', 
			'startDate'=>'required ',
			'endDate'=>'required ',
			'showTimes'=>'required|numeric ',
			'applicationId'=>'required|numeric',
			'message'=>'required'
		] )->validate();
		
		
		$m = new sysMessageCenter;
		$m->name = $post['name'];
		$m->startDate = mysqlDate(strtotime($post['startDate']));
		$m->applicationId = $post['applicationId'];
		$m->message = $post['message'];
		$m->endDate = mysqlDate(strtotime($post['endDate']));
		$m->showTimes = $post['showTimes'];
		$m->createdBy = Auth::getUser()->id;
		$m->save();
		
		$application = applications::find($post['applicationId']);
		$applicationName = $application->name;
		
		logAction('Message [ '.$post['name'] ." ] added for Application[ $applicationName ]  ",9 );
		
		$post->session()->flash('alert-success', 'Message successfully added.');
		return redirect()->route('messageCenter');
		
		
	}
	
	
 
	
	
}

?>