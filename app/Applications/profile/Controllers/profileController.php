<?php


namespace App\Applications\profile\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\User;
use Auth;
use Validator;
use Hash;

class ProfileController extends Controller{
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
		$users = User::find($userId);
		
        return view('profile::profile',['user'=>$users]);
		
    }
	
	public function saveProfile(Request $post){
		
		 
		$userId = Auth::getUser()->id;
		
		
		$validator = Validator::make(
			$post->All()
		,[
			'name' => 'required|string', 
			'initial'=>'nullable|alpha_num |max:10',
			'lastname'=>'required|alpha',
			'lastname2'=>'nullable|alpha',
			'gender'=>'required|alpha|max:1',
			'phone'=>'nullable|alpha_dash',
			'mobile'=>'nullable|alpha_dash' 
			
		] )->validate();
		
		$user = User::find($userId);
		
		$user->name = $post['name'];
		$user->initial = $post['initial'];
		$user->lastname = $post['lastname'];
		$user->lastname2 = $post['lastname2'];
		$user->gender = $post['gender'];
		$user->phone = $post['phone'];
		$user->mobile = $post['mobile'];
		$user->birthDate = mysqlDate(strtotime($post['birthdate']));
		$user->save();
		
 		addNotification('Some random notification sended to user after some change made to the account. The intention is to be 
		long and test the space of the notification area.
		', $userId);
		
		logAction('User updated [ ' .$user->name .' '. $user->lastname. ' ]', 9, 4, $userId );
		$post->session()->flash('alert-success', 'Profile successfully updated');
		return redirect()->route('profile');
		
		
	}
	
	function updatePassword(Request $post){
		$userId = Auth::getUser()->id;
		$user = User::find($userId);
		
		$validator = Validator::make(
			$post->All()
		,[
			'currentPassword' => 'required|string', 
			'password' => 'required|string|min:8|confirmed' 
			 
		] )->validate();
		
		if( Hash::check($post['password'], $user->password) ){
			// Current password Match !
			$user->password = Hash::make($post['password']);
			$user->save();

			$post->session()->flash('alert-success', 'Password successfully updated');
			Auth::login($user);
			return redirect()->route('profile');
			
		} else{
			// Password does not match
			$post->session()->flash('alert-error', 'Invalid current password');
			return redirect()->route('profile');
		}
		
		
	}
	
	
	function updateProfilePicture(Request $post){
 
		$validator = Validator::make(
			$post->All()
		,[
			'profilePicture' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
			 
		] )->validate();
		
		$userId = Auth::getUser()->id;
		$user = User::find($userId);
		$usernewId = $userId *2;
		$pictureName = ucwords(strtolower($user->name)) . ucwords(strtolower($user->lastname)). $usernewId ;
		
		if ($post->hasFile('profilePicture')) {
			$image = $post->file('profilePicture');
			$name = $pictureName .'.'.$image->getClientOriginalExtension();
			$destinationPath = public_path('/images/profilePicture');
			$image->move($destinationPath, $name);
			//$this->save();
			 
			$user->profilePicture = '/images/profilePicture/'. $name;
			$user->save();
			 
			$post->session()->flash('alert-success', 'Image Upload successfully');
			return redirect()->route('profile');
		}
		
		
		
	}
	
	 
	
}

?>