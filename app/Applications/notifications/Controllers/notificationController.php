<?php


namespace App\Applications\notifications\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Notifications ;

use Auth;
 

class notificationController extends Controller{
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
		
		$userId = Auth::getUser()->id;
		
		$notifications = Notifications::where('userId','=',$userId)->orderBy('created_at','desc')->get();
		
        return view('notifications::home',['notification'=>$notifications] );
		
    }
	
	/**
	* updateNotification($id)
	*
	* Function for javascript update
	*/
	 
	public function updateNotification($id=0){
		if(!is_numeric($id)){
			// we validate, but do not give any response.
			return "0";
			
		}
		
		// is numeric, lets do the update.
		$userId = Auth::getUser()->id;
		$notification = Notifications::where('userId','=',$userId)->where('id','=',$id)->first();
		$notification->readed = 1;
		$notification->viewDate =  now();
		$notification->save();
		return "1";
		
	}
	
	
	public function acknowledgeNotification($id){
		if(!is_numeric($id)){
			// we validate, but do not give any response.
			return redirect()->route('notifications')->with('alert-error','Unable to aknowledge notification.');
			
		}
		
		// is numeric, lets do the update.
		$userId = Auth::getUser()->id;
		$notification = Notifications::where('userId','=',$userId)->where('id','=',$id)->first();
		$notification->readed = 1;
		$notification->viewDate =  now();
		$notification->save();
		return redirect()->route('notifications')->with('alert-success','Notification aknowledged');
		
	}
	
	public function removeNotification($id){
		if(!is_numeric($id)){
			// we validate, but do not give any response.
			return redirect()->route('notifications')->with('alert-error','Unable to remove notification.');
			
		}
		
		// is numeric, lets do the update.
		$userId = Auth::getUser()->id;
		$notification = Notifications::where('userId','=',$userId)->where('id','=',$id)->delete();
	 
		return redirect()->route('notifications')->with('alert-success','Notification removed');
		
	}
	
}

?>