<?php
namespace App\Http\Middleware;

use Closure;
use DB;
use Auth;




/**
 * Authenticate access to applications
 *
 * Middleware to verify if the user hace access to the applications.
 * 
 * @author Ramiro Vidal
 *
 */


class vidalFramework{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $appKey=''){
		
		$userId = Auth::getUser()->id;
		 
		if($appKey == ''){
			// no app key value pass?
			// let's add a string so the query does not explode
			// this string can not match any appKey on the applications table on the db
			$appKey ='$nada$nada$nada$nada$nada$nada$nada$nada$nada$nada$';
		}
		
		//Query to get all the applications
		$apps = DB::table('sysApplications')->
			join('sysGroupApplication','sysApplications.id','=','sysGroupApplication.applicationId')->
			join('sysGroups','sysGroupApplication.groupId','=','sysGroups.id')->
			join('sysUserGroup','sysGroups.id','=','sysUserGroup.groupId')->
			select('sysApplications.*')->
			where('sysApplications.appKey', '=', $appKey)->
			where('sysUserGroup.userId','=',$userId)->count() ;
		
		//$totalApps = $apps->count();
		 
		if($apps == 0){
			// no access to app lets the the app information to make the log.
			
			logAction('Deny Access to Application',9,4);
			 
			return redirect('dashboard')->with('alert-error','Access Denied.');
		}
		
		 
		
		
        return $next($request);
    }
}
