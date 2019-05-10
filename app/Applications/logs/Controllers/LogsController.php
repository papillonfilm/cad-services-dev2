<?php


namespace App\Applications\logs\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

// Models
use App\Applications\logs\Models\sysLog;
use App\User;
use DB;
use Validator;

class LogsController extends Controller{
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
        return view('logs::viewLogs');
    }
	
	public function viewLog($id){
		
		$validator = Validator::make(
		[
			'id'=>$id
		],[
			'id' => 'required|numeric|max:999999999' 
		] )->validate();
		
	 
		$log = sysLog::find($id);
		if(!$log){
			return redirect()->route('logs')->with('alert-error','Log not found.');
		}
		
		$user = User::find($log->userId);
		
        return view('logs::viewLog',['log'=>$log,'user'=>$user]);
    }
	
	public function showLogs(Request $post){
		
		
		$validator = Validator::make(
		$post->all(),[
			'start' => 'nullable|numeric|max:999999',
			'length'=>'nullable|numeric|max:999999'	
		] )->validate();
		
		// Get post values
		$start = $post['start'];
		$lenght = $post["length"];
		$order = $post["order"];
		$draw = $post["draw"];
		$orderBy = $order[0]["column"];
		$orderType = $order[0]["dir"];
		$column = 'id'; // default column
		$direction = 'asc'; // default order
		$search = addslashes($post['search']['value']) ;
		
		if($start == 0 and !is_numeric($lenght)){
			$lenght = 50; // list default
		}

		// Get the order by information.
		if( $order[0]['dir'] == "desc"){
			if (is_numeric($order[0]['column'])){
				$column  = $order[0]['column'] ;
				$direction = 'desc';
			}
		}else{
			if (is_numeric($order[0]['column'])){
				$column  = $order[0]['column'] ;
				$direction = 'asc';
			}
		}
		
		// Lets construct the queries
		$totalResults = DB::table('sysLog')->count();
		
		// Define column array to also be use in order by, becasue laravel does not support
		// order by a column number ex. order by 2 asc
		// we have to use COALESCE becasue we may have null values on some fields aka initial or lastname2
		$columnArray = array(
			'sysLog.created_at',
			'type',
			'category',
			   DB::raw("CONCAT(
			   		COALESCE(sysUsers.name,''),  ' ',
					COALESCE(sysUsers.lastname,' ') , ' ',
					COALESCE(sysUsers.lastname2, ' ')
					
					)AS fullname
					"),
			'description',
			'sysLog.id');
		
		// for order purposes only
		$columnOrder = array(
			'sysLog.created_at',
			'type',
			'category',
			'fullname', 
			'description',
			'sysLog.id');
		
		$records = DB::table('sysLog')->
			select($columnArray)->
		
			orWhere($columnArray[0],'like','%'.$search.'%')->
			orWhere($columnArray[1],'like','%'.$search.'%')->
			orWhere($columnArray[2],'like','%'.$search.'%')->
			orWhere( DB::raw("CONCAT(
			   		COALESCE(sysUsers.name,''),' ',
					COALESCE(sysUsers.lastname,' '), ' ',
					COALESCE(sysUsers.lastname2,' ')
					
					)")
			
			,'like','%'.$search.'%')->
			orWhere($columnArray[4],'like','%'.$search.'%')->
			leftJoin('sysUsers','sysUsers.id','=','sysLog.userId')->
			skip($start)->
			take($lenght)->
			orderBy($columnOrder[$column] ,$direction)->
			get();
		
		$recordsNotFiltered = DB::table('sysLog')->
			select($columnArray)->
		
			orWhere($columnArray[0],'like','%'.$search.'%')->
			orWhere($columnArray[1],'like','%'.$search.'%')->
			orWhere($columnArray[2],'like','%'.$search.'%')->
			orWhere( DB::raw("CONCAT(
			   		COALESCE(sysUsers.name,''),' ',
					COALESCE(sysUsers.lastname,' '), ' ',
					COALESCE(sysUsers.lastname2,' ')
					
					)")
			
			,'like','%'.$search.'%')->
			orWhere($columnArray[4],'like','%'.$search.'%')->
			join('sysUsers','sysUsers.id','=','sysLog.userId')->
 			count();
			 
	 
		$data = $records->toArray();
		$totalFiltered = $recordsNotFiltered; // $records->count();
		// we have to run the json array to be able to later create a json array and not a json object.
		$dataJson = array();
		foreach($data as $key=>$value){
			$row = array();
			
			foreach($value as $k=>$val){
				 
				if($k =='created_at'){
					$val = "<a href='".route('viewLog',[ $value->id ]) ."' > $val </a>";
					$row[] =  $val  ;
				}else{
					$row[] = htmlentities($val) ;
				}
				
				
			}
			$dataJson[] = $row; 
		}
		
		
		 
		// create array
		$json = array();
		$json['draw'] = $draw;
		$json['recordsTotal'] = $totalResults;
		$json['recordsFiltered'] = $totalFiltered;
		$json['data']= $dataJson;
		
		return json_encode($json);
		
		
	}
	
	
	
}

?>