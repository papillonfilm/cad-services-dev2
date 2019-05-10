<?php


namespace App\Applications\mailLog\Controllers;
 

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Applications\mailLog\Models\sysMailLog;
 
use App\Mail\EmailBasic;
use Session;
use DB;
use Validator;

class mailLogController extends Controller{
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
			
		

		$mailLogs = sysMailLog::get();
        return view('mailLog::mailLog',['mailLogs'=>$mailLogs]);
		
    }
	
	public function ajaxMailLogs(Request $post){
		// Function to get all mail logs and send it via json for data tables
		// Get post values
		
		$validator = Validator::make(
		$post->all(),[
			'start' => 'nullable|numeric|max:999999',
			'length'=>'nullable|numeric|max:999999'	
		] )->validate();
		
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
		$totalResults = DB::table('sysMailLog')->count();
		
		// Column array to use in query
		$columnArray = array(
			'created_at',
			'email',
			'subject',
			'lastOpenedDate',
			'id'
			 );
		
		$records = DB::table('sysMailLog')->
			select($columnArray)->
		
			orWhere($columnArray[0],'like','%'.$search.'%')->
			orWhere($columnArray[1],'like','%'.$search.'%')->
			orWhere($columnArray[2],'like','%'.$search.'%')->
			orWhere($columnArray[3],'like','%'.$search.'%')->
			skip($start)->
			take($lenght)->
			orderBy($columnArray[$column] ,$direction)->
			get();
		
		
		$recordsFiltered = DB::table('sysMailLog')->
			select($columnArray)->
			orWhere($columnArray[0],'like','%'.$search.'%')->
			orWhere($columnArray[1],'like','%'.$search.'%')->
			orWhere($columnArray[2],'like','%'.$search.'%')->
			orWhere($columnArray[3],'like','%'.$search.'%')->
			count();
		 
		
		$data = $records->toArray();
		$totalFiltered = $recordsFiltered ; 
		// we have to run the json array to be able to later create a json array and not a json object.
		$dataJson = array();
		foreach($data as $key=>$value){
			$row = array();
			
			foreach($value as $k=>$val){
				 // Column modifiers in case we need to add links for example..
			 
				if($k =='created_at'){
					$val = "<a href='".route('viewEmailLog',[ $value->id ]) ."' > $val </a>";
					$row[] = $val ;
				}else{
					$row[] = htmlentities($val);
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
	
	public function mailDetail($id){
		
		$validator = Validator::make(
		[
			'id'=>$id
		],[
			'id' => 'required|numeric|max:999999999' 
		] )->validate();
		
		
		$log = sysMailLog::find($id);
		 
		// Create array to recreate the sent email.
		$email = array();
		$email['to'] = $log->email;
		$email['name'] = $log->email;
		$email['fromName'] = $log->fromName;
		$email['fromEmail'] = $log->fromEmail;
		$email['subject'] = $log->subject;
		$email['trackEmail'] = false;  // do not track email
		$email['msg'] = $log->message;
		 
		 
		
		$emailHtml = sendEmail($email, true); // execute sendEmail helper function. and return html, not send
		
		return view('mailLog::emailLog',['log'=>$log,'email'=>$emailHtml] );
		
	}
 
	
	public function resendEmail($id){
		
		$validator = Validator::make(
		[
			'id'=>$id
		],[
			'id' => 'required|numeric|max:999999999' 
		] )->validate();
		
		
		$log = sysMailLog::find($id);
		
		// Let's get all the values to resend the email like it was send the first time
		$email = array();
		$email['to'] = $log->email;
		$email['name'] = $log->name;
		$email['fromName'] = $log->fromName;
		$email['fromEmail'] = $log->fromEmail;
		$email['subject'] = $log->subject;
		$email['trackEmail'] = true;   
		$email['msg'] = $log->message;
		 
		
		$emailHtml = sendEmail($email); // execute sendEmail helper function.
		
		logAction('Email re-sended to: [ ' .$log->email . ' ]',8 );
		
		Session::flash('alert-success', 'Email sucessfully sent'); 
		return redirect()->route('viewEmailLog',['id'=>$id]);
		
	}
 
	
	
}

?>