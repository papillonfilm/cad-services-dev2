<?php


namespace App\Applications\adIntegration\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

// Models
use App\Applications\logs\Models\sysLog;
use App\User;
use DB;
use Session;
// use Config;

class AdIntegrationController extends Controller{
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
		
		$localUsers = User::get();
		$totalLocalUsers = $localUsers->count();
		$enableAccounts = User::where('accountEnable','=','1')->get();
		$totalActiveAccounts = $enableAccounts->count();
		$disableAccounts = User::where('accountEnable','=','0')->get();
		$totalDisableAccounts = $disableAccounts->count();
		
		 
		// Get information from ActiveDirectory.
		$adUsersArray = $this->ldapUsers();
		$totalAdUsers = count($adUsersArray);
		
		// lets create an array of usernames to be compared
		// LocalUsers
		$localUsersArray = array();
		foreach($localUsers as $key=>$value){
			$localUsersArray[] = $value->username;
		}
		
		$ldapUsersArray = array();
		foreach($adUsersArray as $aduser){
			$username = str_replace('@pbcgov.org','',$aduser['email']);
			$ldapUsersArray[] = $username;
		}
		 
		$totalAdUsersInSystem = User::whereIn('username',$ldapUsersArray)->count();
		$totalAdUsersNotInSystem = $totalAdUsers - $totalAdUsersInSystem ; 
		$totalInSystemButNotAd = User::whereNotIn('username',$ldapUsersArray)->where('accountEnable','=','1')->count();
		
        return view('adIntegration::home',[
			'totalLocalUsers'=>$totalLocalUsers,
			'totalActiveAccounts'=>$totalActiveAccounts,
			'totalDisableAccounts'=>$totalDisableAccounts,
			'totalAdUsers'=>$totalAdUsers,
			'totalAdUsersInSystem'=>$totalAdUsersInSystem,
			'totalAdUsersNotInSystem'=>$totalAdUsersNotInSystem,
			'totalInSystemButNotAd'=>$totalInSystemButNotAd
		
		]);
    }
	
	public function localUsers(){
		
		$localUsers = User::get();
		return view('adIntegration::localUsers',['users'=>$localUsers]);
		
	}
	
	public function enableAccounts(){
		
		$localUsers = User::where('accountEnable','=','1')->get();
		return view('adIntegration::enableAccounts',['users'=>$localUsers]);
		
	}
	
	public function adDisableAccount($id){
		
		$user = User::find($id);
		$user->accountEnable = 0;
		$user->save();
		logAction('Disable Account [ ' . $user->name . ' ' . $user->lastname .' ]' ,9);
		Session()->flash('alert-success', 'User account disable.');
		return redirect()->route('enableAccounts' );
		 		
	}
	
	public function disableAccounts(){
		
		$localUsers = User::where('accountEnable','=','0')->get();
		return view('adIntegration::disableAccounts',['users'=>$localUsers]);
		 		
	}
	
	public function adEnableAccount($id){
		
		$user = User::find($id);
		$user->accountEnable = 1;
		$user->save();
		
		logAction('Enable Account [ '.$user->name . ' ' . $user->lastname .' ]',9);
		Session()->flash('alert-success', 'User account enable.');
		return redirect()->route('disableAccounts' );
		
	}
	
	public function adUsers(){
		
		$adUsersArray = $this->ldapUsers();
		return view("adIntegration::adUsers", [ 'users'=>$adUsersArray ] );
		
	}
	
	public function importedAdUsers(){
		
		$adUsersArray = $this->ldapUsers();
	
		$ldapUsersArray = array();
		foreach($adUsersArray as $aduser){
			$username = str_replace('@pbcgov.org','',$aduser['email']);
			$ldapUsersArray[] = $username;
		}
		
		$users = User::whereIn('username',$ldapUsersArray)->get();
		return view("adIntegration::importedAdUsers", [ 'users'=>$users ] );
		
	}
	
	public function adNotImported(){
		
		$adUsersArray = $this->ldapUsers();
		$ldapUsersArray = array();
		foreach($adUsersArray as $aduser){
			$username = str_replace('@pbcgov.org','',$aduser['email']);
			$ldapUsersArray[] = strtolower($username);
		}
		
		 
		
		$totalAdUsersInSystem = User::whereIn('username',$ldapUsersArray)->get();
		
		$adUsersInSystem = $totalAdUsersInSystem->toArray();
		
		
		// To be able to get all the users from AD excluding the ones in our local database
		// we need to create an array index of the 2 arrays.
		// the index will be created to map the user id to the users row id
		// then we do an array diff and get all the ids from the ad and then we run 
		// those and get our final result.
		$userArray= array();
		
		foreach($totalAdUsersInSystem as $usersIn){
			$userArray[] = $usersIn['username'];
		}
		
		 
		// we get all the username of AD users that are not in the system.
		$adUsersArrayIndex = array_diff($ldapUsersArray, $userArray );
		
		$users = array();
		$not = array();
		foreach($adUsersArray as $key=>$value){
			
			$element = in_array($value['username'], $adUsersArrayIndex );
			if($element){
				// element found we add it to our users array
				$users[] = $value;
			}
			
		}
			
		return view("adIntegration::adNotImported", [ 'users'=>$users ] );
	}
	
	public function usersNotInAd(){
		
		$adUsersArray = $this->ldapUsers();
		 
		$ldapUsersArray = array();
		foreach($adUsersArray as $aduser){
			// $username = trim(strtolower(str_replace('@pbcgov.org','',$aduser['email'])));
			$ldapUsersArray[] = $aduser['username'];
		}
 
		$users = User::whereNotIn('username',$ldapUsersArray)->where('accountEnable','=','1')->get();
		return view("adIntegration::usersNotInAd", [ 'users'=>$users ] );
		
	}
	
	public function adDisableAccountFromAd($id){
		$user = User::find($id);
		$user->accountEnable = 0;
		$user->save();
		logAction('Disable Account [ ' . $user->name . ' ' . $user->lastname .' ]' ,9);
		Session()->flash('alert-success', 'User account disable.');
		return redirect()->route('usersNotInAd' );
	}
	
	public function importAdUser($username){
		
		$email = $username . '@pbcgov.org';
		
		$userInfo = $this->ldapUser($email);
	
		if($userInfo){
			// we have a result
			$user = new User;
			$user->name = $userInfo['name'];
			$user->initial = $userInfo['initial'];
			$user->lastname = $userInfo['lastname'];
			$user->email = $userInfo['email'];
			$user->password='';
			$user->username = $userInfo['username'];
			$user->phone = $userInfo['phone'];
			$user->profilePicture = config('vidalFramework.userDefaultProfilePicture');
			$user->accountEnable = 1;
			$user->accountActivated = 1;
			$user->save();
			
			logAction('User [ '.$userInfo['name']. ' '. $userInfo['lastname'] .' ] Imported from AD',1,4);
			
			Session()->flash('alert-success', 'User ' .$userInfo['name']. ' ' .$userInfo['lastname'].' sucessfully imported');
			return redirect()->route('adNotImported' );
			
		}else{
			// boom no user found..
			Session()->flash('alert-error', 'Unable to import user, [Not found on AD]');
			return redirect()->route('adNotImported' );
		}
		
	}
	
	/*
	* Function to search for all users in the ldap/AD
	*
	*
	*/
	public function ldapUsers(){
		// get all ldap users..
			 
		// Get varaibles from config..
		$ldapURL = config('ldap.server') ;
		$ldaprdn  = config('ldap.ldaprdn');
		$ldappass = config('ldap.ldappass');
	
		$ldapconn = ldap_connect($ldapURL );
		
		if(!$ldapconn){
			return false; 
		}
		
		if ($ldapconn) {
			$ldapbind = @ldap_bind($ldapconn, $ldaprdn, $ldappass);
		}
			
		// Variables to request from ldap 		
		$attrs = array("description", "name", "mail","givenName","sn","telephoneNumber","initials","ou");
	
		// May tweak for diferent ldap integration.
		$result = ldap_search($ldapconn, 'ou=Users,ou=CAD,ou=Enterprise,DC=PBCGOV,DC=ORG',"(&(|(memberOf=CN=CAD-Users,OU=Groups,OU=Users,OU=CAD,OU=Enterprise,DC=pbcgov,DC=org)(memberOf=CN=CAD-ALL COURT ADMINISTRATION,OU=Distribution Lists,OU=Users,OU=CAD,OU=Enterprise,DC=pbcgov,DC=org))(!(sAMAccountName=CAD-*))(!(sAMAccountName=2*)) (!(sAMAccountName=\$*)))", $attrs);
		$info = ldap_get_entries($ldapconn, $result);
	 
	 
		foreach($info as $key=>$value ){
			$name = $info[$key]['givenname'][0];
			$lastname = $info[$key]['sn'][0];
			
			if(isset( $info[$key]['mail'][0])){
				$email = $info[$key]['mail'][0];
			}else{
				$email = '';
			}
			
			if(isset($info[$key]['telephonenumber'][0])){
				$phone = $info[$key]['telephonenumber'][0];
			}else{
				$phone = '';
			}
			
			if(isset($info[$key]['initials'][0] )){
				$initial = $info[$key]['initials'][0];
			}else{
				$initial = '';
			}
			
			
			
			if(trim($email) != ''){
				$username = trim(strtolower(str_replace('@pbcgov.org','',$email))); 
				
				$activeDirectoryUsers[]  = array(
					'name'=>$name,
					'initial'=>$initial,
					'lastname'=>$lastname,
					'email'=>$email,
					'phone'=>$phone,
					'username'=>$username
				);
				
				
			}
			
		}
		
		return $activeDirectoryUsers;
		
		
	}
	
	/*
	* Function to search for a particular user in the ldap/AD
	*
	*
	*/
	
	public function ldapUser($email){
		// get all ldap users..
			 
		// Get varaibles from config..
		$ldapURL = config('ldap.server') ;
		$ldaprdn  = config('ldap.ldaprdn');
		$ldappass = config('ldap.ldappass');
	
		$ldapconn = ldap_connect($ldapURL );
		
		if(!$ldapconn){
			return false; 
		}
		
		if ($ldapconn) {
			$ldapbind = @ldap_bind($ldapconn, $ldaprdn, $ldappass);
		}
			
		// Variables to request from ldap 		
		$attrs = array("description", "name", "mail","givenName","sn","telephoneNumber","initials","ou");
	
		// May tweak for diferent ldap integration.
		$result = ldap_search($ldapconn, 'ou=Users,ou=CAD,ou=Enterprise,DC=PBCGOV,DC=ORG',"(&(|(memberOf=CN=CAD-Users,OU=Groups,OU=Users,OU=CAD,OU=Enterprise,DC=pbcgov,DC=org)(memberOf=CN=CAD-ALL COURT ADMINISTRATION,OU=Distribution Lists,OU=Users,OU=CAD,OU=Enterprise,DC=pbcgov,DC=org))(mail=$email)   )", $attrs);
		$info = ldap_get_entries($ldapconn, $result);
	 
	 
		foreach($info as $key=>$value ){
			$name = $info[$key]['givenname'][0];
			$lastname = $info[$key]['sn'][0];
			
			if(isset( $info[$key]['mail'][0])){
				$email = $info[$key]['mail'][0];
			}else{
				$email = '';
			}
			
			if(isset($info[$key]['telephonenumber'][0])){
				$phone = $info[$key]['telephonenumber'][0];
			}else{
				$phone = '';
			}
			
			if(isset($info[$key]['initials'][0] )){
				$initial = $info[$key]['initials'][0];
			}else{
				$initial = '';
			}
			
			
			
			if(trim($email) != ''){
				$username = trim(strtolower(str_replace('@pbcgov.org','',$email))); 
				
				$activeDirectoryUsers  = array(
					'name'=>$name,
					'initial'=>$initial,
					'lastname'=>$lastname,
					'email'=>$email,
					'phone'=>$phone,
					'username'=>$username
				);
				
				
			}
			
		}
		
		return $activeDirectoryUsers;
		
		
	}
	
	
}

?>