<?php


namespace App\Applications\sudo\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\User;
use App\sysLog;
use Auth;
use Validator;

class SudoController extends Controller{
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
		
		$users = User::orderBy('name','asc')->orderBy('lastname','asc')->get();
        return view('sudo::users',['users'=>$users]);
		
    }
	
	public function sudo($id){
		$validator = Validator::make(
		[
			'id'=>$id
		],[
			'id' => 'required|numeric|max:9999999' 
		] )->validate();
		
		// Deautenticate current
		$user = User::find($id);
		if(!$user){
			return redirect()->route('sudo')->with('alert-error','User not found.');
		}
		// $this->createLog($user);
		$userName = $user->name . ' '  .$user->lastname;
		$actualUser = Auth()->user()->name .' ' .Auth()->user()->lastname;
		logAction("$actualUser > $userName" ,10);
		
		Auth::logout();
		// Authenticate as the selected user aka sudo
		Auth::login($user);
		
		
		return redirect()->route('dashboard');
		
	}
	
	
	public function createLog($user){
		$userName = $user->name . ' '  .$user->lastname;
		
		$actualUser = Auth()->user()->name .' ' .Auth()->user()->lastname;
		
		$log = new sysLog;
		$log->type = 'Info';
		$log->category = 'Sudo';
		$log->url = $_SERVER['REQUEST_URI'];
		$log->userId = Auth::getUser()->id;
		$log->description = " $actualUser > $userName";
		$log->ip = $_SERVER['REMOTE_ADDR'];
		$log->userAgent = $_SERVER['HTTP_USER_AGENT'];
		$log->save();
		return true;
		
	}
	
	
}

?>