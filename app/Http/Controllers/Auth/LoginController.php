<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

use Auth;
use Illuminate\Http\Request;
use Redirect;
use Validator;
use Carbon\Carbon;


class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
	
	public function logout() {
		// Log logout event.
		logAction("Logout",3);
		
		Auth::logout();
		return redirect('/login');
	}
	


	protected function showLoginForm(){
		 
		return view('auth.login');
	}
	
    protected function login(Request $request) {
		
		// lets do some Validation.
		$fieldName = filter_var(request()->get('username'), FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
		if($fieldName == 'username'){
			// validation if user is not using an email address
			$validator = Validator::make($request->all(), [
				'username' => 'required|alpha_num|max:255',
				'password' => 'required',
			],[
				'username.alpha_num'=>'Username must be alpha numeric or a valid email address.'
			])->validate();
		}else{
			// validation with an email address.
			$validator = Validator::make($request->all(), [
				'username' => 'required|email|max:255',
				'password' => 'required',
			])->validate();
		}
		
 		
		$username = trim($request['username']);
		$password= trim($request['password']);
		
		$username = strtolower( $username );
		$username = str_replace('@pbcgov.org','',$username);
		$rememberMe = false;
		if(isset($request['remember'])){
			if($request['remember'] == 1){
				$rememberMe = true;
				session(['rememberMe'=>1]);
				
			}else{
				session(['rememberMe'=>0]);
			}
		}else{
			session(['rememberMe'=>0]);
		}
		

		// vidal implementation...
		$attemptLogin = $this->ldapLogin($username, $password);
		if( $attemptLogin  ){
		// login via ldap with ramiro function...

			$user = \App\User::where('username','=', $username) -> first();
            if (!$user) {
				//Create Account if user does not exists on local database
				$user = new \App\User();
				$user->username = $username;
				$user->password = '';
				$user->name = $attemptLogin['name'];
				$user->initial =$attemptLogin['initials'];
				$user->lastname =$attemptLogin['lastname'];
				$user->profilePicture = config('vidalFramework.userDefaultProfilePicture');
				$user->accountActivated = 1;
				$user->accountEnable = 1;
				$user->email = strtolower( $attemptLogin['email'] );
				$user->phone = $attemptLogin['phone'];
				$user->save();
			}else{
				// User exist on database, we have to check if the account is disabled.
				$today =   new Carbon();
				//dd($today . " > " . $user->enableOnDate);
				
				if($user->accountEnable != 1){
					// account is disable for our friend, deny login..
					
					return redirect()->route('login')->with('alert-error', 'Account is disable, please contact support.') ;
					//  or
				}elseif(    $today <= $user->enableOnDate ){
					
					 
					return redirect()->route('login')->with('alert-error', 'Account will be enable on ' . date("F j, Y g:i A" ,strtotime($user->enableOnDate))) ;
				}elseif( $today >= $user->disableOnDate ){
					
					return redirect()->route('login')->with('alert-error', 'Account is disable, please contact support.') ;
				}
				
				
			}
			
			// parameter true or false will remember users session will not expire user have to logout.
			//$rememberUserToken = config('vidalFramework.rememberUser');
			
			// Perform the login for ther user...
			$this->guard()->login($user, $rememberMe);   
			
			// log login action.
			$user->lastLogin = date('Y-m-d H:i:s');
			$user->save();
			logAction("Login",2,9, $user->id);
			
			// The User is login, now we check if the user has any custom message to display.
			// this function will verify for messages for the user and will create a flash message for them.
			getMessagesFromMessageCenter();
			
            return redirect()->route('dashboard');
		}
		 
		logAction("Invalid login for $username",2);
        // the user doesn't exist in the LDAP server or the password is wrong
		//Session::flash('alert-error', 'Invalid username or password');
		return redirect()->route('login')->with('alert-error', 'Invalid username or password') ;
		
    }

	
	public function ldapLogin($username, $password){
	
		// Get Config values from /config/ldap.php 
		$ldapURL  = config('ldap.server') ;
		$ldaprdn  = config('ldap.ldaprdn');
		$ldappass = config('ldap.ldappass');
	
		$ldapconn = ldap_connect($ldapURL );
		
		if(!$ldapconn){
			return false;	 
		}
		
		if ($ldapconn) {
			$ldapbind = @ldap_bind($ldapconn, $ldaprdn, $ldappass);
		}
			
		$ad = $ldapconn;
		
		// Values to get from ldap 
		$attrs = array("description", "name", "mail","givenName","sn","telephoneNumber","initials");
		
		// May tweak for diferent ldap integration. 
		$result = ldap_search($ad, 'ou=Users,ou=CAD,ou=Enterprise,DC=PBCGOV,DC=ORG',"(&(sAMAccountName=".$username.")(memberof:1.2.840.113556.1.4.1941:=CN=CAD-ICMS-GROUP,OU=Services,OU=CAD,OU=Enterprise,DC=pbcgov,DC=org))", $attrs);
		$info = ldap_get_entries($ad, $result);
			
		$loginString = isset($info[0]['dn']) ? $info[0]['dn'] : null;
		
		if(empty($loginString)){
			return false;
		}else{
			
			$ldaprdn = $loginString;
			$ldappass = $password;
				
			$ldapconn = ldap_connect($ldapURL);
			if(!$ldapconn){
				return false;
			}
				
			ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);
			ldap_set_option($ldapconn, LDAP_OPT_REFERRALS, 0);
		
			if ($ldapconn) {
				$ldapbind = @ldap_bind($ldapconn, $ldaprdn, $ldappass);
				$pass = ($ldapbind) ? 1 : 0;
			}
				
			if(!$pass){
				return false;
			}else{
				// Sucessfully Login !
				
				// Sanitize the Result !
				$name= $info[0]['givenname'][0];
				$lastname = $info[0]['sn'][0];
				$email = $info[0]['mail'][0];
				$phone = $info[0]['telephonenumber'][0];
				
				if(isset($info[0]['initials'][0])){
					$initials = $info[0]['initials'][0];
				}else{
					$initials = '';
				}
				
				 return array('name'=>$name,'lastname'=>$lastname,'email'=>$email,'phone'=>$phone, 'initials'=>$initials);
			}
		}

	}
	
	
}
