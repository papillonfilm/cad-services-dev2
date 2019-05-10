<?php

namespace App\Applications\sessions\Controllers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Applications\sessions\Models\sessions;
use App\User;
use DB;

class sessionsController extends Controller{ 

	public function __construct(){

	}

	
	
	public function index(){
		
		// LastActivity is stored as epoch (unix timestamp since 1970-1-1)
		
		// we want to get the valid sessions that means that are the
		// sessions from now - session timeout.
		$time =  time() - (config('session.lifetime')*60); 
		
		
		$sessions = sessions:: join('sysUsers','sysUsers.id','=','sessions.user_id')->
			where('last_activity','>=', $time)->
			orderBy('last_activity','desc')->
			get();
			// groupBy('user_id')->get();
		
		$totalUsers = sessions::where('last_activity','>=', $time)->
			//groupBy('user_id')->
			
			//toSql();
			count(DB::raw('DISTINCT user_id'));
		 
		
		$totalActiveSessions = $sessions->count();
		
			
		return view('sessions::home',['sessions'=>$sessions, 'total'=>$totalActiveSessions, 'totalUsers'=>$totalUsers ]);
		 
		
	}
	
	public function logoutUser($id){
		
		if(!is_numeric($id)){
			return redirect()->route('sessions')->with('alert-error','Invalid User');
		}
		
		// We have to do 2 things to sucessfully logout a user
		// 1- remove or change the remember_token in the sysUsers table
		// 2- remove or change the session on the sessions table.
		
		//dd($id);
		
		// Remove token
		$user = User::where('id','=',$id)->first();
		$user->remember_token = '';
		$user->save();
		
		// Delete session.
		$session = sessions::where('user_id','=',$id)->delete();
		
		
		return redirect()->route('sessions')->with('alert-success','User sucessfully logout');
	}
	
	
	
	
}
