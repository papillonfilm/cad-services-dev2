<?php


// Namespace for the webManager application.
// This have to be the defined first so the 
// Load of models and laravel classes will load
// inside this namespace.
//namespace App\Http\Controllers\webManager;
namespace App\Applications\webManager\Controllers;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

// Load Models
use App\Applications\webManager\Models\Application;
use App\Applications\webManager\Models\Users;
use App\Applications\webManager\Models\Groups;
use App\Applications\webManager\Models\GroupApplications;
use App\Applications\webManager\Models\ApplicationCategories;
use App\Applications\webManager\Models\UserGroup;
use App\Applications\webManager\Models\accessRoutes;
use App\Applications\webManager\Models\accessUserPermissions;
use App\Applications\webManager\Models\accessGroupPermissions;
use App\Applications\webManager\Models\sysApplicationCategories;
use Auth;
use Session;
use DB;
use Validator;
use Route;

class webManagerController extends Controller{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
	
	 
	
    public function __construct(){
		
		// Code to create sub Navigation on the selected application.
		$subNavigation = array();
		$subNavigation['Web Manager'][] = array(
			'title'=>'Users',
			'link'=>route('users')
		);
		$subNavigation['Web Manager'][] =array(
			'title'=>'Groups',
			'link'=>route('groups')
		);
		$subNavigation['Web Manager'][] =array(
			'title'=>'Applications',
			'link'=>route('applications')
		);
		$subNavigation['Web Manager'][] =array(
			'title'=>'Permissions',
			'link'=>route('permissions')
		);
		$subNavigation['Web Manager'][] =array(
			'title'=>'Categories',
			'link'=>route('categories')
		);
		
		// send the varaible to all the views on this controller.
		view()->share('subNavigation',$subNavigation);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
		
		$totalApps = application::count();
		$totalUsers = users::count();
		$totalGroups = groups::count();
		// Get the Total Applications..
	 
        return view('webManager::home', ['totalApps'=>$totalApps, 'totalUsers'=>$totalUsers, 'totalGroups'=>$totalGroups]);
    }
	
	
	
	public function users(){
		
		$users = users::orderBy('name')->get() ;
		return view('webManager::users', ['users'=>$users]);
	}
	
	public function userDetail( $id){

		$validator = Validator::make(
		[
			'id'=>$id
		],[
			'id' => 'required|numeric|max:999999' 
		] )->validate();
		
		
		$user = users::find($id);
		
		if(!$user){
			return redirect()->route('users')->with('alert-error','User not found.');
		}
 
		$userId = $user->id; // get the user id

		$groups = users::find($id)->groups()->get(); 
		 
		$totalGroups = $groups->count();
		
		$inGroups = array();
		foreach($groups as $k=>$v){
			$inGroups[] = $v->id;
		}
		 
		$notInGroups = groups::whereNotIn('id', $inGroups)->get();
	 
		
	 
		$apps = DB::table('sysApplications')->
			join('sysGroupApplication','sysApplications.id','=','sysGroupApplication.applicationId')->
			join('sysGroups','sysGroupApplication.groupId','=','sysGroups.id')->
			join('sysUserGroup','sysGroups.id','=','sysUserGroup.groupId')->
			select('sysApplications.name', 'sysApplications.location', 'sysApplications.iconPath')->
			groupBy('sysApplications.id')->
			groupBy('sysApplications.name')->
			groupBy('sysApplications.location')->
			groupBy('sysApplications.iconPath')->
			where('sysUserGroup.userId','=',$userId)->get();
	 
		
 		$totalApps = $apps->count();
		
		return view('webManager::userDetail',[
			'user'=>$user,
			'totalGroups'=>$totalGroups,
			'notInGroups'=>$notInGroups,
			'groups'=>$groups,
			'applications'=>$apps,
			'totalApps'=>$totalApps
		]);
		
	}
	
	public function addUser(){
 		
		return view('webManager::addUser' );
	}
	
	public function saveUser(Request $req){
		
		$validator = Validator::make(
			$req->All()
		,[
			'name' => 'required|string', 
			'initial'=>'nullable|alpha_num |max:10',
			'lastname'=>'required|alpha',
			'lastname2'=>'nullable|alpha',
			'gender'=>'required|alpha|max:1',
			'email'=>'required|email',
			'username'=>'required|alpha_num',
			'accountEnable'=>'required|numeric|max:1',
			'accountActivated'=>'required|numeric|max:1',
			'phone'=>'nullable|alpha_dash',
			'mobile'=>'nullable|alpha_dash',
			
		] )->validate();
		
		$user = new users;
		$user->name = $req['name'];
		$user->initial = $req['initial'];
		$user->lastname = $req['lastname'];
		$user->lastname2 = $req['lastname2'];
		$user->gender = $req['gender'];
		$user->email = $req['email'];
		$user->accountEnable = $req['accountEnable'];
		$user->username = $req['username'];
		$user->password = '';
		$user->profilePicture = config('vidalFramework.userDefaultProfilePicture');
		$user->phone = $req['phone'];
		$user->mobile = $req['mobile'];
		$user->save();
		
		logAction('User added [ ' .$user->name .' '. $user->lastname. ' ]',1 );
		$req->session()->flash('alert-success', 'User successfully Added');
		return redirect()->route('users');
		
	}
	
	public function editUser($id){
		$validator = Validator::make(
		[
			'id'=>$id
		],[
			'id' => 'required|numeric|max:999999' 
		] )->validate();
				
		$user = users::find($id);
		
		if(!$user){
			return redirect()->route('users')->with('alert-error','User not found.');
		}
		
		return view('webManager::editUser', ['user'=>$user]);
	}
	
	public function updateUser(Request $req){
		
		$validator = Validator::make(
			$req->All()
		,[
			'name' => 'required|alpha', 
			'initial'=>'nullable|alpha|max:10',
			'lastname'=>'required|alpha',
			'lastname2'=>'nullable|alpha',
			'gender'=>'required|alpha|max:1',
			'email'=>'required|email',
			'accountEnable'=>'required|numeric|max:1',
			'accountActivated'=>'required|numeric|max:1',
			'phone'=>'nullable|alpha_dash',
			'mobile'=>'nullable|alpha_dash',
			'accountEnableDate'=>'required|date|before:2038-12-31',
			'accountDisableDate'=>'required|date|before:2038-12-31',
			
		] )->validate();
		
		
	 
		
		$user = users::find($req->id);
		if(!$user){
			return redirect()->route('users')->with('alert-error','User not found.');
		}
		$user->name = $req['name'];
		$user->initial = $req['initial'];
		$user->lastname = $req['lastname'];
		$user->lastname2 = $req['lastname2'];
		$user->gender = $req['gender'];
		$user->email = $req['email'];
		$user->accountEnable = $req['accountEnable'];
		$user->phone = $req['phone'];
		$user->mobile = $req['mobile'];
		$user->enableOnDate = $req['accountEnableDate'];
		$user->disableOnDate= $req['accountDisableDate'];
		$user->save();
		/* to test notifications.
		for($i = 0 ;$i < 20; $i++){
			addNotification("Some long notification to test the space of the area of the notification that we have to show for the user in the
			header of the page .. Let's see how it goes ..
			", $req->id);	
		}
		*/
		
		logAction('User updated [ ' .$user->name .' '. $user->lastname. ' ]',9 );
		$req->session()->flash('alert-success', 'User successfully updated');
		return redirect()->route('users');
		
	}
	
	public function addUserToGroup(Request $req ){
		//dd($request);
		
		$validator = Validator::make(
			$req->All()
		,[
			'userId' => 'required|numeric|max:20', 
			'groupId'=>'required|numeric|max:20'			
		] )->validate();
		
		$userToGroup = new userGroup;
		$userToGroup->userId=$req['userId'];
		$userToGroup->groupId=$req['groupId'];
		$userToGroup->addedBy=Auth::getUser()->id;
		$userToGroup->save();
		
		$user = Users::find($req['userId']);
		$group = Groups::find($req['groupId']);
		
		logAction('User [ ' .$user->name .' '. $user->lastname. ' ] added to Group [ '. $group->name.' ]',9 );
		 
		$req->session()->flash('alert-success', 'User successfully added to group.');
		return redirect()->route('userDetail',[$req['userId']]);
		
	}
	
	public function removeUserFromGroup(Request $request){
		
		 $validator = Validator::make(
			$request->All()
		,[
			'userId' => 'required|numeric|max:20', 
			'groupId'=>'required|numeric|max:20'			
		] )->validate();
		
		$query = userGroup::where('groupId',$request['groupId'])->where('userId', $request['userId'])->delete();
		
		$user = Users::find($request['userId']);
		$group = Groups::find($request['groupId']);
		
		logAction('User [ ' .$user->name .' '. $user->lastname. ' ] removed from Group [ '. $group->name.' ]',9 );
		
		$request->session()->flash('alert-success', 'User successfully removed from group.');
		return redirect()->route('userDetail',[$request['userId']]);
	}
	
	
	public function applications(){
		$applications = application::orderBy('name')->get() ;
		return view('webManager::applications', ['applications'=>$applications]);
	}
	
	public function addApplicationToGroup(Request $request){
	 
		$validator = Validator::make(
			$request->All()
		,[
			'applicationId' => 'required|numeric|max:20', 
			'groupId'=>'required|numeric|max:20'			
		] )->validate();
		
		
		$groupApplication = new GroupApplications;  
		$groupApplication->groupId = $request['groupId'];
		$groupApplication->applicationId = $request['applicationId'];
		$groupApplication->addedBy = Auth::getUser()->id;
		$groupApplication->save();
		
		$app = Application::find($request['applicationId']);
		$group = Groups::find($request['groupId']);
		
		logAction('Application [ ' .$app->name .  ' ] added to Group [ '. $group->name.' ]',9 );
		
		$request->session()->flash('alert-success', 'Application successfully added.'); 
		return redirect()->route('viewGroup',[$request['groupId']]);
		
	}
	
	public function removeApplicationFromGroup(Request $request){
		
		$validator = Validator::make(
			$request->All()
		,[
			'applicationId' => 'required|numeric|max:20', 
			'groupId'=>'required|numeric|max:20'			
		] )->validate();
			
		
		$app = Application::find($request['applicationId']);
		$group = Groups::find($request['groupId']);
		
		logAction('Application [ ' .$app->name .  ' ] removed From Group [ '. $group->name.' ]',9 );
		
		$query = groupApplications::where('applicationId',$request['applicationId'])->where('groupId', $request['groupId'])->delete();
		$request->session()->flash('alert-success', 'Application successfully removed.');
		return redirect()->route('viewGroup',[$request['groupId']]);
	}
	
	
	public function groups(){
		
		$groups = groups::orderBy('name')->get() ;
		return view('webManager::groups', ['groups'=>$groups]);
		
	}
	
	public function addGroup(){
		
		return view('webManager::addGroup');
		
	}
	
	public function editGroup($id){
		
		$validator = Validator::make(
		[
			'id'=>$id
		],[
			'id' => 'required|numeric|max:999999' 
		] )->validate();
		
		$groups = groups::find($id);
		if(!$groups){
			return redirect()->route('groups')->with('alert-error','Group not found.');
		}
		 
		return view('webManager::editGroup', ['groups'=>$groups]);
		
	}
	
	public function deleteGroup($id){
		
		$validator = Validator::make(
		[
			'id'=>$id
		],[
			'id' => 'required|numeric|max:999999' 
		] )->validate();
		// Before we delete a group we have to check that it does not have any users or applications.
		
		$totalApps = GroupApplications::where('groupId','=',$id)->count();
		$totalUsers = UserGroup::where('groupId','=',$id)->count();
		
		
		if($totalApps  > 0){
			Session::flash('alert-error', 'Group still in use by applications ');
			return redirect()->route('groups');
		}
		
		if($totalUsers > 0){
			Session::flash('alert-error', 'Group still have users. ');
			return redirect()->route('groups');
		}
		
		
		$group = Groups::find($id);
		if(!$group){
			return redirect()->route('groups')->with('alert-error','Group not found.');
		}
		$group->delete();
		
		Session::flash('alert-success', 'Group successfully removed.');
		return redirect()->route('groups');
	}
	
	public function viewGroup($id){
		
		$validator = Validator::make(
		[
			'id'=>$id
		],[
			'id' => 'required|numeric|max:999999' 
		] )->validate();
		
		$groups = groups::find($id);
		if(!$groups){
			return redirect()->route('groups')->with('alert-error','Group not found.');
		}
		//$totalGroups = users::count(); // get the total users in group
		$totalApplications = groups::find($id)->applications()->count(); // total applications in group
		$totalUsers =groups::find($id)->users()->count(); 
	 	
		// get user
		$user = users::find($groups->addedBy);
		$applicationsInGroup = groups::find($id)->applications()->get();
		
		// Get all the apps ID to use them in the next query to get all
		// the applications available not in group
		$appArray = array();
		foreach($applicationsInGroup as $key=>$value){
			$appArray[] = $value->id;
		}
		 
		 
		$applicationsNotInGroup = application::whereNotIn('id', $appArray)->get();
			
		return view('webManager::viewGroup', 
			[
				'group'=>$groups, 
			//	'totalGroups'=>$totalGroups, 
				'totalApplications'=>$totalApplications,
				'totalUsers'=>$totalUsers,
				'applicationsInGroup'=>$applicationsInGroup,
				'applicationsNotInGroup'=>$applicationsNotInGroup,
				'user'=>$user
			]
		);
		
	}
	
	public function updateGroup(Request $request){
		
		$validator = Validator::make(
			$request->All()
		,[
			 
			'name'=>'required|string',
			'permissionPriority'=>'required|numeric',
			'description'=>'nullable|string'
		] )->validate();
		
		 
		$group = Groups::find($request['id']);
		$group->name = $request['name'];
		$group->description = $request['description'];
		$group->permissionPriority = $request['permissionPriority'];
		$group->save();
		
		logAction('Group Updated [ ' .$group->name .  ' ] ',9 );
		
		$request->session()->flash('alert-success', 'Group successfully updated.'); 
		return redirect()->route('groups');
		
	}
	
	public function saveGroup(Request $request){
		
		$validator = Validator::make(
			$request->All()
		,[
			 
			'name'=>'required|string',
			'permissionPriority'=>'required|numeric',
			'description'=>'nullable|string'
		] )->validate();

		$group = new Groups;  
		$group->name = $request['name'];
		$group->permissionPriority = $request['permissionPriority'];
		$group->description = $request['description'];
		$group->addedBy = Auth::getUser()->id;
		$group->save();
		
		logAction('Group Added [ ' .$group->name .  ' ] ',9 );
		
		$request->session()->flash('alert-success', 'Group successfully added.');
		return redirect()->route('groups');
 
	}
	
	// Applications Methods
	
	public function viewApplication(Request $request){
		
		 if(!is_numeric($request['id'])){
			 $request->session()->flash('alert-error', 'Invalid Application identifier.');
			return redirect()->route('applications');
		 }
		
		$appId = $request['id'];
		$app = application::find($appId);
		$totalApplicationsInGroup = application::find($appId)->groups()->count();
		$applicationsInGroup = application::find($appId)->groups()->get();
		
		
		//get all users in application..
		//groups that have app.
		$groups = array();
		foreach($applicationsInGroup as $apg){
			$groups[] = $apg->id;
		}
		
		// Calculate the total unique users that have access to this application.
		$totalUsers  = userGroup::whereIn('groupId',$groups)->groupBy('userId')->select('userId')->get();
		$totalUsers = $totalUsers->toArray();
		$totalUsers = count($totalUsers);
		
		$applicationsNotInGroup =  groups::whereNotIn('id', $groups)->get();
	 
		return view('webManager::viewApplications', 
			[
				'app'=>$app,
				'totalUsers'=>$totalUsers,
				'totalApplicationsInGroup'=>$totalApplicationsInGroup,
				'applicationsNotInGroup'=>$applicationsNotInGroup,
				'applicationsInGroup'=>$applicationsInGroup
			]
	   );
	}
	
	public function addApplication(){
		
		//get all the icons from the public folder images/icons
		$directory = public_path('images/icons/');
		//$icons = Storage::files($directory);
		//dd($directory);
		$icons = array();
		 foreach (glob( $directory ."*.{png,svg,jpg,jpeg}",GLOB_BRACE) as $filename) {
			//echo "$filename size " . filesize($filename) . "\n";
			$iconSrc = str_replace(public_path() . '/', '', $filename);
			$icons[] =  $iconSrc ;
		}
		 
		
		
		
		// Get Application Categories
		$categories = ApplicationCategories::orderBy('name')->get();
		 
		return view('webManager::addApplication', ['icons'=>$icons,'categories'=>$categories]);
		
	}
	
	public function editApplication($id){
		
		$validator = Validator::make(
		[
			'id'=>$id
		],[
			'id' => 'required|numeric|max:999999' 
		] )->validate();
		
		$app = Application::find($id);
		if(!$app){
			return redirect()->route('applications')->with('alert-error','Application not found.');
		}
		
		$categories = ApplicationCategories::orderBy('name')->get();
		//get all the icons from the public folder images/icons
		$directory = public_path('images/icons/');
		//$icons = Storage::files($directory);
		//dd($directory);
		$icons = array();
		 foreach (glob( $directory ."*.{png,svg,jpg,jpeg}",GLOB_BRACE) as $filename) {
			//echo "$filename size " . filesize($filename) . "\n";
			$iconSrc = str_replace(public_path() . '/', '', $filename);
			//$icons[] = $filename; 
			$icons[] =  $iconSrc ;
		}
		//dd(public_path());
		
		
		return view("webManager::editApplication",['app'=>$app, 'icons'=>$icons, 'categories'=>$categories ]);
		
	}
	
	public function saveApplication(Request $request){
		
		$validatedData = $request->validate([
        	'name'=>'required|string',
			'location'=>'required|string',
			'description'=>'nullable|string',
			'appKey'=>'required|string',
			'iconPath'=>'required|string',
			'categoryId'=>'required|numeric'
    	],[
			'name.required'=>'Please enter an application name.',
			'location.required'=>'Please enter an application default route'
		]);
		
		
		$application = new Application;
		$application->name = $request['name'];
		$application->description = $request['description'];
		$application->location = $request['location'];
		$application->appKey = $request['appKey'];
		$application->iconPath = $request['iconPath'];
		$application->author = $request['author'];
		$application->version = $request['version'];
		$application->addedBy = Auth::getUser()->id;
		$application->categoryId = $request['categoryId'];
		$application->save();
		
		logAction('Application added [ ' .$application->name .  ' ] ',9 );
		
		$request->session()->flash('alert-success', 'Application successfully added.');
		return redirect()->route('applications');
		
	}
	
	function updateApplication(Request $request){
		
		$validatedData = $request->validate([
			'id'=>'required|numeric',
        	'name'=>'required|string',
			'location'=>'required|regex:/^[A-Za-z. -]{2,255}$/',
			'description'=>'nullable|string',
			'appKey'=>'required|string',
			'iconPath'=>'required|string',
			'categoryId'=>'required|numeric',
			'author'=>'nullable|string'
    	],[
			'name.required'=>'Please enter an application name.',
			'location.required'=>'Please enter an application default route'
		]);
		
		$app = Application::find($request['id']);
		$app->name = $request['name'];
		$app->description = $request['description'];
		$app->location = $request['location'];
		$app->appKey = $request['appKey'];
		$app->iconPath = $request['iconPath'];
		$app->author = $request['author'];
		$app->version = $request['version'];
		$app->addedBy = Auth::getUser()->id;
		$app->categoryId = $request['categoryId'];
		$app->save();
		
		logAction('Application Edited [ ' .$app->name .  ' ] ',9 );
		
		session()->flash('alert-success', 'Application successfully updated.');  
		return redirect()->route('applications');
		
	}
	
	
	function deleteApplication(Request $request){
		// let's see if the application is not in a group aka in use or asigned before we delete it
		
	 
		if(!is_numeric($request['id'])){
			Session::flash('alert-error', 'Invalid Application Id'); 
			return redirect()->route('applications');
		}
 
		
		 
		$groupApp = groupApplications::where('applicationId','=',$request['id'])->count();
		
		if($groupApp > 0){
			// the application still in use..
			Session::flash('alert-error', 'Application still in use, please remove access first.'); 

			//return $this->applications();
			return redirect()->route('applications');
			
		}else{
			// app not in use we can remove it.
			$app = Application::find($request['id']);
			$app->delete();
			
			logAction('Application Deleted [ ' .$app->name .  ' ] ',9 );
			
			$request->session()->flash('alert-success', 'Application successfully deleted.'); 


			return redirect()->route('applications');
		
		}
	}
	
	
	function addGroupToApplication(Request $request){
		
		$validatedData = $request->validate([
        	'applicationId'=>'required'
       
    	],[
			'applicationId.required'=>'No Application selected.'
		]);
		
		$groupApplication = new GroupApplications;  
		$groupApplication->groupId = $request['groupId'];
		$groupApplication->applicationId = $request['applicationId'];
		$groupApplication->addedBy = Auth::getUser()->id;
		$groupApplication->save();
		
		$group = Groups::find($request['groupId']);
		
		logAction('Application added to Group [ ' .$group->name .  ' ] ',9 );
		
		$request->session()->flash('alert-success', 'Group successfully added.');
		return redirect()->route('viewApplication',[$request['applicationId']]);
		
	}
	
	function removeGroupFromApplication(Request $request){
		
		$validatedData = $request->validate([
        	'applicationId'=>'required',
			'groupId'=>'required'
    	],[
			'applicationId.required'=>'No Application selected.',
			'groupId.required'=>'No Group selected.'
		]);
			
		$group = Groups::find($request['groupId']);
		logAction('Application removed from Group [ ' .$group->name .  ' ] ',9 );
		
		$query = groupApplications::where('applicationId',$request['applicationId'])->where('groupId', $request['groupId'])->delete();
		$request->session()->flash('alert-success', 'Group successfully removed.');
		return redirect()->route('viewApplication',[$request['applicationId']]);
		
	}

	// Permissions.
	function permissions(){
		
		$permissions = accessRoutes::orderBy('applicationName')->get();
		
		return view('webManager::permissions',['permissions'=>$permissions]);
	}
	
	function userPermissions(Request $request){
		
		$validatedData = $request->validate([
			'userId'=>'required|numeric', 
    	],[
			'id.required'=>'Invalid User Id',
		]);
		
		//$userId = Auth::getUser()->id;
		
		$userId = $request['userId'];
		
		$user = Users::find($userId);
		
		
		$accessRoutes = accessRoutes:: 
			orderBy('applicationName')->
			get()->toArray();
		
		$userAccessRoutes = accessUserPermissions::where('userId','=',$userId)->get()->toArray();
		 
		//create an index
		$accessIndex = array();
		foreach($userAccessRoutes as $key=>$access){
			$accessIndex[$access['accessRoutesId']] = $key;
		}
		
		 
		
		foreach($accessRoutes as $key=>$route){
		
				if(isset($accessIndex[$route['id'] ])){
					// user have rule defined.
					$accessRoutes[$key]['hasAccess'] = $userAccessRoutes[ $accessIndex[$route['id'] ]  ]['hasAccess'];
					$accessRoutes[$key]['accessId'] =  $userAccessRoutes[ $accessIndex[$route['id'] ]  ]['id'];
					$accessRoutes[$key]['url'] ="<span id='u".$route['id']."'> ".$route['url']."</span><span id='g".$route['id']."'></span>"; 
				}else{
					$accessRoutes[$key]['hasAccess'] = '';
					//dd($this->getPermissionColor($route['url'], $userId) );
					$accessRoutes[$key]['url'] = $this->getPermissionColor($route['url'], $userId, $route['id']);
				}
			
		}
		
		
		return view( "webManager::userPermissions", ['accessRoutes'=>$accessRoutes, 'user'=>$user ] );
	 
	 
		
	}
	
	function viewRoute($id){
		
		if(!is_numeric($id)){
			return redirect()->back()->with('alert-error', 'Invalid Route id');
		}
		
		$permission = accessRoutes::find($id);
		 
		return view('webManager::viewPermission',['permission'=>$permission]);
		
		
	}
	
	function syncRoutes(){
		
		$routes = Route::getRoutes();
		 
		$accessRoutes = AccessRoutes::orderBy('applicationName')->get()->toArray();
		
		 $userId = Auth::getUser()->id;
		
		 
		
		$systemRoutes = array();
		$counter = 0;
		foreach($routes as $route){
			//dd($route->action['middleware']);
			
			$systemRoutes[$counter]['url'] = $route->uri;
			$systemRoutes[$counter]['sync'] = 0; 
			$systemRoutes[$counter]['add'] = 0;
			$systemRoutes[$counter]['middleware'] = $route->action['middleware'];
			$systemRoutes[$counter]['added'] = '<span class="red">No</span>';
			
			if(is_array($route->action['middleware'])){
				if(in_array('permissions',$route->action['middleware'])){
					// la ruta se esta monitoreando vamos a añadirla.
					$systemRoutes[$counter]['add'] = 1;
					foreach($accessRoutes as $key=>$value ){
				 
						if($value['url'] == $route->uri){
							// route found
							$systemRoutes[$counter]['sync'] = 1; 
							$systemRoutes[$counter]['added'] =' <span class="">N/A</span>';
							 
							unset($accessRoutes[$key]);
							continue;

						}

					}
					
					
					
				}
			}
			
			
			
			$counter ++;
			
		}
		
		
		//dd($systemRoutes);
		foreach($systemRoutes as $key=>$route){
				
				 if( $route['sync'] == 0 and $route['add']==1){
					// Insert into database.
					$insert = new AccessRoutes;
					$insert->url = $route['url'];
					$insert->applicationName = 'Not Defined';
					$insert->addedBy =  $userId;
					$insert->save();
					$systemRoutes[$key]['added'] = ' <span class="green">Yes</span>';
				 }
				
			}
		 
		 
		 return view('webManager::syncRoutes',['syncRoutes'=>$systemRoutes]);
		
		
	}
	
	function getPermissionColor($url, $userId, $routeId =''){
		
				
		$apps = DB::table('sysApplications')->
			join('sysGroupApplication','sysApplications.id','=','sysGroupApplication.applicationId')->
			join('sysGroups','sysGroupApplication.groupId','=','sysGroups.id')->
			join('sysUserGroup','sysGroups.id','=','sysUserGroup.groupId')->
			select('sysGroupApplication.groupId')->
			groupBy('sysGroupApplication.groupId')->
			where('sysUserGroup.userId','=',$userId)->get();
	 
		$groupId = array();
		foreach($apps as $app){
			$groupId[] = $app->groupId;
		}
		
		 $groupHaveAccess = DB::table('accessRoutes')->
			join('accessGroupPermissions','accessRoutes.id','=','accessGroupPermissions.accessRoutesId')->
			join('sysGroups','accessGroupPermissions.groupId','sysGroups.id')->
			select('accessGroupPermissions.hasAccess', 'groupId','accessRoutes.id')->
			where('accessRoutes.url', '=', $url)->
			whereIn('accessGroupPermissions.groupId',$groupId)->
			//where('accessGroupPermissions.hasAccess','=', 1)->
			orderBy('permissionPriority')->
			 //toSql();
			get() ;
		
		
		
		 if($groupHaveAccess){
			 
			 foreach($groupHaveAccess as $groupAccess){
				 //dd($groupAccess);
				 if($groupAccess->hasAccess ===1){
					 $url = "<span class='green' id='u".$groupAccess->id."' > $url </span> 
					 <span id='g".$groupAccess->id."' class='green'>&nbsp; &nbsp; [ ". $this->getGroupName($groupAccess->groupId)." ]</span> ";
					 return $url;
				 }elseif($groupAccess->hasAccess ===0){
					 $url = "<span class='red' id='u".$groupAccess->id."'> $url </span> 
					 <span  id='g".$groupAccess->id."' class='red'> &nbsp; &nbsp; [ ". $this->getGroupName($groupAccess->groupId)." ]</span> ";
				 	 return $url;
				 } else{
					
				 }
				 
				 
			 }
			 
			 
		 }
	
		$url = "<span class='' id='u".$routeId."'> $url </span><span  id='g".$routeId."' class=''></span> "; 			 
		
		return $url;
		
		
	}
	
	function getGroupName($id){
		if(!is_numeric($id)){
			return '';
		}
		
		$group = Groups::find($id);
		if($group){
			$groupName = $group->name;
		}else{
			$groupName = '';
		}
		
		
		return $groupName;
		
		
	}
	
	
	function groupPermissions(Request $request){
		
		$validatedData = $request->validate([
			'groupId'=>'required|numeric', 
    	],[
			'id.required'=>'Invalid User Id',
		]);
		
		//$userId = Auth::getUser()->id;
		
		$groupId = $request['groupId'];
		
		$group = Groups::find($groupId);
		
		
		$accessRoutes = accessRoutes:: 
			orderBy('applicationName')->
			get()->toArray();
		
		$userAccessRoutes = accessGroupPermissions::where('groupId','=',$groupId)->get()->toArray();
		 
		//create an index
		$accessIndex = array();
		foreach($userAccessRoutes as $key=>$access){
			$accessIndex[$access['accessRoutesId']] = $key;
		}
		
		 
		
		foreach($accessRoutes as $key=>$route){
		
				if(isset($accessIndex[$route['id'] ])){
					// user have rule defined.
					$accessRoutes[$key]['hasAccess'] = $userAccessRoutes[ $accessIndex[$route['id'] ]  ]['hasAccess'];
					$accessRoutes[$key]['accessId'] =  $userAccessRoutes[ $accessIndex[$route['id'] ]  ]['id'];
				}else{
					$accessRoutes[$key]['hasAccess'] = '';
				}
			
		}
		
		
	  
		return view( "webManager::groupPermissions", ['accessRoutes'=>$accessRoutes, 'group'=>$group ] );
	 
	 
		
	}
	
	function updatePermissionsUser(Request $request){
	 
		if(!is_numeric($request['id'])){
			return json_encode(array('msg'=>'Invalid route [e:id]','errorCode'=>0),true); 
		}
		
		if(!is_numeric($request['access']) or $request['access']> 2){
			return json_encode(array('msg'=>'Invalid route [e:a]','errorCode'=>0),true); 
		}
		
		if(!is_numeric($request['u'])  ){
			return json_encode(array('msg'=>'Invalid route [e:u]','errorCode'=>0),true); 
		}
		
		
		if($request['access'] == 0 or $request['access'] == 1 ){
			
			$userAccessRoutes = accessUserPermissions::where('accessRoutesId','=',$request['id'])->
				where('userId','=', $request['u'])->
				first();
			
			  
			if($userAccessRoutes){
				$userAccessRoutes->hasAccess = $request['access'];
				$userAccessRoutes->save();
				return  json_encode(array('msg'=>'Permission updated','errorCode'=>1),true); 
			}else{
				// no record found let's insert it.
				$userAccessRoutes = new accessUserPermissions;
				$userAccessRoutes->userId = $request['u'];
				$userAccessRoutes->accessRoutesId=$request['id'];
				$userAccessRoutes->accessGrantedBy = Auth::getUser()->id;
				$userAccessRoutes->hasAccess = $request['access'];
				$userAccessRoutes->save();
				
				return  json_encode(array('msg'=>'Permission updated','errorCode'=>1),true); 
			}
			
			
			
		}elseif($request['access'] == 2){
			$userAccessRoutes = accessUserPermissions::where('accessRoutesId','=',$request['id'])->
				where('userId','=', $request['u'])->delete();
			return  json_encode(array('msg'=>'Permission updated','errorCode'=>1),true); 
		}
		
		
		return  json_encode(array('msg'=>'Invalid Request' . $request['access'],'errorCode'=>0),true); 
		// update the route
		
		
		
		
	}
	
	function editPermission($id){
		
		if(!is_numeric($id)){
			return redirect()->back()-with('alert-error','Invalid Identifier');
		}
		
		$route = accessRoutes::find($id);
		
		return view('webManager::editPermission', ['route'=>$route]);
		
	}
	
	
	function deletePermission($id){
		
		if(!is_numeric($id)){
			return redirect()->back()-with('alert-error','Invalid Identifier');
		}
		
		$route = accessRoutes::find($id)->delete();
		// now delete it from the other tables...
		$user = accessUserPermissions::where('accessRoutesId','=',$id)->delete();
		$group = accessGroupPermissions::where('accessRoutesId','=',$id)->delete();
		
		return redirect()->back()->with('alert-success','Route sucessfully removed');
		
	}
	
	function updateRoute(Request $request){
		 
		$validatedData = $request->validate([
        	'id'=>'required|numeric',
			'applicationName'=>'required',
			'url'=>'required'
       
    	] );
		
		$route = accessRoutes::find($request['id']);
		$route->applicationName = $request['applicationName'];
		$route->description = $request['description'];
		$route->url = $request['url'];
		$route->save();
		
		return redirect()->route('permissions')->with('alert-success', 'Route sucessfully updated');
		
	}
	
	
	function addRoute(){
		return view('webManager::addPermission');	
	}
	
	
	function saveRoute(Request $request){
		$validatedData = $request->validate([
			'applicationName'=>'required',
			'url'=>'required'
       
    	] );
		
		$route = new accessRoutes ;
		$route->applicationName = $request['applicationName'];
		$route->description = $request['description'];
		$route->url = $request['url'];
		$route->addedBy = Auth::getUser()->id;
		$route->save();
		
		return redirect()->route('permissions')->with('alert-success', 'Route sucessfully updated');
		
		
	}
	
	function updatePermissionsGroup(Request $request){
	 
		if(!is_numeric($request['id'])){
			return json_encode(array('msg'=>'Invalid route','errorCode'=>0),true); 
		}
		
		if(!is_numeric($request['access']) or $request['access']> 2){
			return json_encode(array('msg'=>'Invalid route','errorCode'=>0),true); 
		}
		if(!is_numeric($request['g'])  ){
			return json_encode(array('msg'=>'Invalid route','errorCode'=>0),true); 
		}
		
		
		if($request['access'] >= 0 and $request['access'] < 2 ){
			
			$userAccessRoutes = accessGroupPermissions::where('accessRoutesId','=',$request['id'])->
				where('groupId','=', $request['g'])->
				first();
			
			  
			if($userAccessRoutes){
				$userAccessRoutes->hasAccess = $request['access'];
				$userAccessRoutes->save();
				return  json_encode(array('msg'=>'Permission updated','errorCode'=>1),true); 
			}else{
				// no record found let's insert it.
				$userAccessRoutes = new accessGroupPermissions;
				$userAccessRoutes->groupId = $request['g'];
				$userAccessRoutes->accessRoutesId=$request['id'];
				$userAccessRoutes->accessGrantedBy = Auth::getUser()->id;
				$userAccessRoutes->hasAccess = $request['access'];
				$userAccessRoutes->save();
				
			}
			
			
			
		}elseif($request['access'] == 2){
			$userAccessRoutes = accessGroupPermissions::where('accessRoutesId','=',$request['id'])->
				where('groupId','=', $request['g'])->delete();
			return  json_encode(array('msg'=>'Permission updated','errorCode'=>1),true); 
		}
		
		
		return  json_encode(array('msg'=>'Invalid Request','errorCode'=>0),true); 
		// update the route
		
		
		
		
	}
	
	
	// Categories
	
	public function categories(){

		$sysApplicationCategories =  sysApplicationCategories::get();
		return view('webManager::category',['sysApplicationCategories'=>$sysApplicationCategories]);

	}

	public function addCategory(){

		return view('webManager::addCategory');

	}

	public function saveCategory(Request $post){


		$validator = Validator::make(
			$post->All()
		,[
			'name' => 'required',
			'description' => 'required',
			'created_at' => 'nullable',
			'updated_at' => 'nullable',
		])->validate();

		$sysApplicationCategories = new sysApplicationCategories;
		$sysApplicationCategories->name = $post['name'];
		$sysApplicationCategories->description = $post['description'];
		$sysApplicationCategories->save();

		return redirect()->route('categories')->with('alert-success','Category Sucessfully Added.');

	}

	public function editCategory($id){

		if(!is_numeric($id)){
			return redirect()->route('categories')->with('alert-error','Invalid identifier.');
		}

		$sysApplicationCategories =  sysApplicationCategories::where('id','=',$id)->first();

		return view('webManager::editCategory',['sysApplicationCategories'=>$sysApplicationCategories] );

	}

	public function updateCategory(Request $post){


		$validator = Validator::make(
			$post->All()
		,[
			'name' => 'required',
			'description' => 'nullable|string',
			'created_at' => 'nullable',
			'updated_at' => 'nullable',
		])->validate();

		$sysApplicationCategories =  sysApplicationCategories::find( $post['id'] );
		$sysApplicationCategories->name = $post['name'];
		$sysApplicationCategories->description = $post['description'];
		$sysApplicationCategories->save();

		return redirect()->route('categories')->with('alert-success','Category Sucessfully Updated.');

	}

	public function deleteCategory($id){

		if(!is_numeric($id)){
			return redirect()->route('categories')->with('alert-error','Invalid identifier.');
		}

		$sysApplicationCategories =  sysApplicationCategories::where('id','=',$id)->delete();

		return redirect()->route('categories')->with('alert-success','Category Sucessfully Deleted.');

	}

	
	
	
}