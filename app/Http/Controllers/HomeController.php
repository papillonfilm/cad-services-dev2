<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\User;
use groups;
use DB;
use Redirect;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(){
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
		return Redirect::to('dashboard');
    }
	
		
	public function dashboard(){
		$userId = Auth::getUser()->id;
		
		// Get all the categories for the applications that the user have access.
		$categories = DB::table('sysApplications')->
			join('sysGroupApplication','sysApplications.id','=','sysGroupApplication.applicationId')->
			join('sysGroups','sysGroupApplication.groupId','=','sysGroups.id')->
			join('sysUserGroup','sysGroups.id','=','sysUserGroup.groupId')->
			join('sysApplicationCategories','sysApplications.categoryId','=','sysApplicationCategories.id')->
			select('sysApplicationCategories.id', 'sysApplicationCategories.name')->
			groupBy('sysApplicationCategories.id')->
			groupBy('sysApplicationCategories.name')-> 
			orderBy('sysApplicationCategories.name')->
			where('sysUserGroup.userId','=',$userId)->get();
		
		// Query to get all the applications
		$apps = DB::table('sysApplications')->
			join('sysGroupApplication','sysApplications.id','=','sysGroupApplication.applicationId')->
			join('sysGroups','sysGroupApplication.groupId','=','sysGroups.id')->
			join('sysUserGroup','sysGroups.id','=','sysUserGroup.groupId')->
			select('sysApplications.name', 'sysApplications.location', 'sysApplications.iconPath','sysApplications.categoryId')->
			groupBy('sysApplications.id')->
			groupBy('sysApplications.categoryId')->
			groupBy('sysApplications.name')->
			groupBy('sysApplications.location')->
			groupBy('sysApplications.iconPath')->
			orderBy('sysApplications.name')->
			where('sysUserGroup.userId','=',$userId)->get();
		
		return view('home',['applications'=>$apps, 'categories'=>$categories]);
		
	}
	
	public function dashboard4(){
		$userId = Auth::getUser()->id;
		
		// Get all the categories for the applications that the user have access.
		$categories = DB::table('sysApplications')->
			join('sysGroupApplication','sysApplications.id','=','sysGroupApplication.applicationId')->
			join('sysGroups','sysGroupApplication.groupId','=','sysGroups.id')->
			join('sysUserGroup','sysGroups.id','=','sysUserGroup.groupId')->
			join('sysApplicationCategories','sysApplications.categoryId','=','sysApplicationCategories.id')->
			select('sysApplicationCategories.id', 'sysApplicationCategories.name')->
			groupBy('sysApplicationCategories.id')->
			groupBy('sysApplicationCategories.name')-> 
			orderBy('sysApplicationCategories.name')->
			where('sysUserGroup.userId','=',$userId)->get();
		
		// Query to get all the applications
		$apps = DB::table('sysApplications')->
			join('sysGroupApplication','sysApplications.id','=','sysGroupApplication.applicationId')->
			join('sysGroups','sysGroupApplication.groupId','=','sysGroups.id')->
			join('sysUserGroup','sysGroups.id','=','sysUserGroup.groupId')->
			select('sysApplications.name', 'sysApplications.location', 'sysApplications.iconPath','sysApplications.categoryId')->
			groupBy('sysApplications.id')->
			groupBy('sysApplications.categoryId')->
			groupBy('sysApplications.name')->
			groupBy('sysApplications.location')->
			groupBy('sysApplications.iconPath')->
			orderBy('sysApplications.name')->
			where('sysUserGroup.userId','=',$userId)->get();
		
		return view('homebootstrap4',['applications'=>$apps, 'categories'=>$categories]);
		
	}
	
	
	
	
	
	
}
