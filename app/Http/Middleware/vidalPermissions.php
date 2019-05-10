<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use Request;
use DB;

/**
 * Authenticate access to the routes of the system
 *
 * Middleware to verify all routes for access or deny.
 * 
 * @author Ramiro Vidal
 *
 */
class vidalPermissions{
    
	/**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
	
    public function handle($request, Closure $next){
		
		// Get the user id
		$userId = Auth::getUser()->id;
		
		$routeName = Request::route()->getName();
		$routeUrl = Request::route()->uri();
		$method = Request::route()->getActionMethod();
		
		// have access direct to the user.	
		$haveAccess = DB::table('accessRoutes')->
			join('accessUserPermissions','accessRoutes.id','=','accessUserPermissions.accessRoutesId')->
			select('accessUserPermissions.hasAccess')->
			where('accessRoutes.url', '=', $routeUrl)->
			where('accessUserPermissions.userId','=',$userId)->
			where('accessUserPermissions.hasAccess','=', 1)->
			first() ;
		// dd($haveAccess);
		
		if($haveAccess){
			if($haveAccess->hasAccess === 1){
				// user have access !.
				return $next($request);
			}elseif($haveAccess->hasAccess === 0){
				// User does not have access/ implicit denied
				return redirect()->back()->with('alert-error','Access Denied.');
			}
		}
		
		// if we get here, there is not set permision direct to the user, we can see if the user
		// have permisions via a group.
		//dd('asd');
		/*
		// have access via a group permission.
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
			select('accessGroupPermissions.hasAccess')->
			where('accessRoutes.url', '=', $routeUrl)->
			whereIn('accessGroupPermissions.groupId',$groupId)->
			where('accessGroupPermissions.hasAccess','=', 1)->
			first() ;
		
		 
		if($groupHaveAccess){
			if($groupHaveAccess->hasAccess == 1){
				// our friend have access
				return $next($request);
			}
		}
		*/
		 
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
			where('accessRoutes.url', '=', $routeUrl)->
			whereIn('accessGroupPermissions.groupId',$groupId)->
			//where('accessGroupPermissions.hasAccess','=', 1)->
			orderBy('permissionPriority')->
			 //toSql();
			get() ;
		
		
		
		 if($groupHaveAccess){
			 
			 foreach($groupHaveAccess as $groupAccess){
				 if($groupAccess->hasAccess ===1){
					 return $next($request);
				 }elseif($groupAccess->hasAccess ===0){
					 return redirect()->back()->with('alert-error','Access Denied.');
				 } 
			 }

		 }
		
		
		
		
 		// no access...  
	 
		return redirect()->back()->with('alert-error','Access Denied.');
		 

		
    }
}
