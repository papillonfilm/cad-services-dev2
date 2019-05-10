<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;

class createApplicationCrud extends Command{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vidal:crud {appName} {--table=}';
	
	public $applicationFolder = 'Applications';
	public $applicationLocation = '';
	private $table = '';
	private $appName = '';
	private $fields = array();
	private $showFields = array();
	private $rules = array();
	
	// Fields that we do not want to show to the users. (Views)
	private $skipFields = array('created_at','updated_at','userId');
	
	// Template to Extend (Views)
	private $templateToExtend = "layouts.template";
	
	 

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create crud for table and place it in under Application';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(){
        parent::__construct();
		$this->applicationLocation = base_path( 'app'. DIRECTORY_SEPARATOR. $this->applicationFolder.DIRECTORY_SEPARATOR ); 
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(){
        $message = '';
		$appName = $this->argument('appName');
		$this->appName = $this->argument('appName');
		$this->table = $this->option('table');
		$this->applicationLocation .= $this->argument('appName');
		
		if(!$this->table){
			$this->error("Missing table, please specify a table. --table=TABLE");
			return true;
		}
	 
		// Verify if application exists
		if(!file_exists($this->applicationLocation )){
			$this->error("Target application does not exists");
			return true;
		}
		
		// Verify if table exists
		if(!$this->getTableInfo()){
			$this->error("Table [ ". $this->table ." ] does not exits." );
			return true;
		} 
		 
		
		
		
		// Create the model
		$message .= $this->createModel($appName, $this->table);
		// Create the controller
		$message .= $this->createController(); 
		// Add the routes
		$message .= $this->addRoutes();
		/**/
		
		$this->createViews();
	 
	 	
	 
	 
		 
		$this->info($message);
		
    }
	
	public function createController(){
	
		// create the controller.
		
		if(file_exists($this->applicationLocation . DIRECTORY_SEPARATOR.'Controllers'.DIRECTORY_SEPARATOR .$this->option('table') .'Controller.php' )){
			// File Already Exists.
			return false;
		}
	 
		$controller = "<?php\n\n";
		$controller .= "namespace App\\Applications\\".$this->appName."\\Controllers;\n";
		$controller .= "use Illuminate\Http\Request;\n";
		$controller .= "use App\Http\Controllers\Controller;\n";
		$controller .= "use App\\" . $this->applicationFolder ."\\". $this->appName ."\\Models\\". $this->table . ";\n";
		$controller .= "use Auth;\n\n";
		$controller .= "use Validator;\n\n";
		$controller .= "class " .$this->table ."Controller extends Controller{\n\n";
		$controller .= "\tpublic function __construct(){\n\n";
		$controller .= "\t}\n\n";
		
		// add Methods...
		$controller .= $this->indexMethod();
		$controller .= $this->addRecord();
		$controller .= $this->saveRecord();
		$controller .= $this->editRecord();
		$controller .= $this->updateRecord();
		$controller .= $this->deleteRecord();
		

		$controller .="}\n";
		
		$file = fopen($this->applicationLocation . DIRECTORY_SEPARATOR .'Controllers'.DIRECTORY_SEPARATOR  .$this->table. "Controller.php",'w');
		fwrite($file,$controller);
		fclose($file);
		
		return "Created Controller: " . $this->table . "Controller.php\n"; 
		
	}
	
	
	public function addRoutes(){
		
		
		$newRoutes = array();
		$newRoutes[] = "/tRoute:get('/viewRecord',  )->name()";
		
		$routeLocation = $this->applicationLocation .DIRECTORY_SEPARATOR. 'routes.php' ;
		
		if(!file_exists($routeLocation)){
			return false;
		}
		
		// Route exists let's load it to be able to add more routes..
		
		$routes = file_get_contents($routeLocation);
		
		$patern = "/Route::(get|post)\('.*'\);\s*\n/";
		preg_match_all($patern,$routes,$match);
		$routeArray = $match[0];
		
		if(count($routeArray) > 0){
			$routes = str_replace($routeArray[0] , '{routes}' , $routes);
		}
		
		foreach($routeArray as $value){
			$routes = str_replace($value,'',$routes);
		}
		
		// Add new routes.
		$routeArray[] = "Route::get('/viewAll','".$this->table."Controller@index')->name('".$this->table.".viewAll');\n";
		$routeArray[] = "Route::get('/editRecord/{id}','".$this->table."Controller@editRecord')->name('".$this->table.".editRecord');\n";
		$routeArray[] = "Route::get('/deleteRecord/{id}','".$this->table."Controller@deleteRecord')->name('".$this->table.".deleteRecord');\n";
		
		$routeArray[] = "Route::get('/addRecord','".$this->table."Controller@addRecord')->name('".$this->table.".addRecord');\n";
		$routeArray[] = "Route::post('/saveRecord','".$this->table."Controller@saveRecord')->name('".$this->table.".saveRecord');\n";
		
		 
		$routeArray[] = "Route::post('/updateRecord','".$this->table."Controller@updateRecord')->name('".$this->table.".updateRecord');\n\n";
		 
	 	$routesToPrint = implode("\t\t",$routeArray);
		
		$routes = str_replace('{routes}',$routesToPrint,$routes) ;
	 	
		$file = fopen($this->applicationLocation . DIRECTORY_SEPARATOR . "routes.php",'w');
		fwrite($file,$routes);
		fclose($file);
		
		return "Updated routes \n";
		
	}
	
	public function getTableInfo(){
		
		//$tableInfo = DB::getSchemaBuilder()->getColumnListing($this->table);
		
		  if(!DB::getSchemaBuilder()->hasTable($this->table)){
			  return false;
		  }
		 
		$shema = DB::select(DB::raw('SHOW FIELDS FROM  ' . $this->table ));
		
		foreach($shema as $col){
			
			// Add field to array is not autoIncrement.
			if(!$col->Extra =='auto_increment'){
				$this->fields[] = $col->Field;
				
				if(!in_array($col->Field,$this->skipFields )){
					// create array of fields to display.
					$this->showFields[] = $col->Field;
				}
				
				if($col->Null =='YES'){
					$this->rules[$col->Field][] = 'nullable';
				}else{

					$this->rules[$col->Field][] ='required';
				}
				
				
				if($this->isNumeric($col->Type)){
					$this->rules[$col->Field][] = 'numeric';
				}
				
				
			}
			
	 
			
		}
		
 
		return true;
		
	}
	
 
	
	public function getFieldLength($type){
		
		// -- Characters --
		if(preg_match('/^varchar\(([0-9]{1,3})\)/' ,$type,$match)){
			return $match[1];
		}
		
		if(preg_match('/^char\(([0-9]{1,3})\)/' ,$type,$match)){
			return $match[1];
		}
		
		// -- Binary
		if(preg_match('/^binary\(([0-9]{1,3})\)/' ,$type,$match)){
			return $match[1];
		}
		if(preg_match('/^varbinary\(([0-9]{1,3})\)/' ,$type,$match)){
			return $match[1];
		}
		
		// -- Numbers --
		if(preg_match('/^tinyint\(([0-9]{1,3})\)/' ,$type,$match)){
			return $match[1];
		}
		
		if(preg_match('/^smallint\(([0-9]{1,3})\)/' ,$type,$match)){
			return $match[1];
		}
		
		if(preg_match('/^mediumint\(([0-9]{1,3})\)/' ,$type,$match)){
			return $match[1];
		}
		
		if(preg_match('/^int\(([0-9]{1,3})\)/' ,$type,$match)){
			return $match[1];
		}
		
		if(preg_match('/^bigint\(([0-9]{1,3})\)/' ,$type,$match)){
			return $match[1];
		}
		
		if(preg_match('/^double\(([0-9]{1,3}),([0-9{1,3}])\)/' ,$type,$match)){
			return $match[1] + $match[2];
		}
		
		if(preg_match('/^float\(([0-9]{1,3}),([0-9{1,3}])\)/' ,$type,$match)){
			return $match[1] + $match[2];
		}
		
		return false;
		
		
	}
	
	public function isNumeric($value){
		
		if(preg_match('/^(tinyint|smallint|mediumint|int|bigint|double|float)\(([0-9,]{1,})\)/' ,$value,$match)){
			return true;
		}else{
			 
			return false;
		}
		
	}
	
	public function editRecord(){
		
		$header = "\tpublic function editRecord(\$id){\n\n";
		$header .= "\t\tif(!is_numeric(\$id)){\n";
		$header .="\t\t\treturn redirect()->route('".$this->table.".viewAll')->with('alert-error','Invalid identifier.');\n";
		$header .="\t\t}\n\n";
		
		$out = '';
		
		if(in_array('userId',$this->fields)){
			$out .= "\t\t\$".$this->table . " = ". $this->table ."::where('id','=',\$id)->\n";
			$out .="\t\t\twhere('userId','=',\$userId)->\n";
			$out .="\t\t\tfirst();\n\n";
			$addUserId = "\t\t\$userId = Auth::getUser()->id; \n\n";
		}else{
			$out  = "\t\t\$".$this->table . " =  ". $this->table ."::where('id','=',\$id)->first();\n\n";
			$addUserId = '';
		}
		
		// View information
		$out .="\t\treturn view('".$this->appName."::editRecord',['".$this->table."'=>\$".$this->table."] );\n\n"; 
		
		$out .="\t}\n\n"; 
		
		return $header . $addUserId . $out;
	}
	
	public function saveRecord(){
		$addUserId = '';
		
		$header = "\tpublic function saveRecord(Request \$post){\n\n";
		$header .= $this->generateValidation();
		
		$out  = "\t\t\$".$this->table . " = new ". $this->table .";\n";
		foreach($this->fields as $field){
			if($field != 'created_at' and $field != 'updated_at'){
				if($field == 'userId'){
					$addUserId = "\t\t\$userId = Auth::getUser()->id; \n\n";
					$out .= "\t\t\$".$this->table.'->'.$field . " = \$userId;\n";
				}else{
					$out .= "\t\t\$".$this->table.'->'.$field . " = \$post['".$field."'];\n";
				}
			}
		}
		
		$out .= "\t\t\$".$this->table."->save();\n\n";
		
		$out .="\t\treturn redirect()->route('".$this->table.".viewAll')->with('alert-success','Record Sucessfully Added.');\n\n";
		
		$out .="\t}\n\n";
		
		return $header . $addUserId . $out;
		
	}
	
	public function addRecord(){
		
		$out = "\tpublic function addRecord(){\n\n";
		$out .="\t\treturn view('".$this->appName."::addRecord');\n\n";
		$out .="\t}\n\n";
		
		return $out;
	}
	
	public function indexMethod(){
		
		$header = "\tpublic function index(){\n\n";
		$addUserId = '';
		if(in_array('userId',$this->fields)){
			$addUserId = "\t\t\$userId = Auth::getUser()->id; \n\n";
			$out  = "\t\t\$".$this->table . " =  ". $this->table ."::where('userId','=',\$userId)->get();\n";
		}else{
			$out  = "\t\t\$".$this->table . " =  ". $this->table ."::get();\n";
		}
				
		$out .="\t\treturn view('".$this->appName."::viewAll',['".$this->table."'=>\$".$this->table."]);\n\n";
		$out .="\t}\n\n";
		
		return $header. $addUserId . $out;
		
	}
	
	public function updateRecord(){
		
		$header = "\tpublic function updateRecord(Request \$post){\n\n";
		$header .= $this->generateValidation();
		$addUserId = '';
		if(in_array('userId',$this->fields)){
			$out  = "\t\t\$".$this->table . " =  ". $this->table ."::where('id','=', \$post['id'] )->\n";
			$out .= "\t\t\twhere('userId','=',\$userId)->first();\n";
		}else{
			$out  = "\t\t\$".$this->table . " =  ". $this->table ."::find( \$post['id'] );\n";
		}
		
		foreach($this->fields as $field){
			if($field != 'created_at' and $field != 'updated_at'){
				
				if($field == 'userId'){
					// $out .= "\t\t\$".$this->table.'->'.$field . " = \$userId;\n";
					$addUserId = "\t\t\$userId = Auth::getUser()->id; \n\n";
				}else{
					$out .= "\t\t\$".$this->table.'->'.$field . " = \$post['".$field."'];\n";
					
				}
				
			}
		}
		
		$out .= "\t\t\$".$this->table."->save();\n\n";
		
		$out .="\t\treturn redirect()->route('".$this->table.".viewAll')->with('alert-success','Record Sucessfully Updated.');\n\n";
		
		$out .="\t}\n\n";
		
		return $header . $addUserId . $out;
	}
	
	
	public function deleteRecord(){
		
		$header = "\tpublic function deleteRecord(\$id){\n\n";
		$header .= "\t\tif(!is_numeric(\$id)){\n";
		$header .="\t\t\treturn redirect()->route('".$this->table.".viewAll')->with('alert-error','Invalid identifier.');\n";
		$header .="\t\t}\n\n";
		
		$out = '';
		if(in_array('userId',$this->fields)){
			$out .= "\t\t\$".$this->table . " = ". $this->table ."::where('id','=',\$id)->\n";
			$out .="\t\t\twhere('userId','=',\$userId)->\n";
			$out .="\t\t\tdelete();\n\n";
			$addUserId = "\t\t\$userId = Auth::getUser()->id; \n\n";
		}else{
			$out .= "\t\t\$".$this->table . " =  ". $this->table ."::where('id','=',\$id)->delete();\n\n";
			$addUserId = '';
		}
		
		$out .="\t\treturn redirect()->route('".$this->table.".viewAll')->with('alert-success','Record Sucessfully Deleted.');\n\n";
		
		$out .="\t}\n\n";
		
		
		return $header . $addUserId . $out;
	}
 
	public function generateValidation(){
				
		$validation  = "\n\t\t".'$validator = Validator::make(';
		$validation .= "\n\t\t\t" . '$post->All()';
		$validation .= "\n\t\t,[";
		
		foreach($this->rules as $key=>$rule){
			$validation .= "\n\t\t\t'" . $key . "' => '";
			$validation .= implode('|',$rule);
			$validation .= "',";
		}
		 
		$validation .= "\n\t\t])->validate();\n\n";
		
		return $validation ;
		
	}
	
	
	public function createModel($appName, $table){
				
		$out  = '<?php' ."\n\n";
		$out .="namespace App\\". $this->applicationFolder .'\\'. $appName . "\\Models;\n" ;
		$out .="use Illuminate\\Database\\Eloquent\\Model;\n\n";
		$out .="class " . $table . " extends Model{\n\n";
		$out .="\t".'protected $table=\''.$table . "';\n";
		$out .="}";
		
		// let's verify if the folder exists
		if(!file_exists($this->applicationLocation  . DIRECTORY_SEPARATOR. 'Models' )){
			// Folder does not exist, lets create it
			mkdir($this->applicationLocation . DIRECTORY_SEPARATOR. 'Models' );
			
		}
		
		if(file_exists($this->applicationLocation . DIRECTORY_SEPARATOR. 'Models' )){
			
			$file = fopen($this->applicationLocation  . DIRECTORY_SEPARATOR. 'Models'.DIRECTORY_SEPARATOR."$table.php",'w');
			fwrite($file,$out);
			fclose($file);
			return "Created Model: " . $table.".php\n";
		}else{
			 
			return false;	
		}
		
		
		
	}
	
	public function createViews(){
		
		// View - All Records
		$allRecords = $this->createViewMain();
		$file = fopen($this->applicationLocation  . DIRECTORY_SEPARATOR. 'Views'.DIRECTORY_SEPARATOR."viewAll.blade.php",'w');
		fwrite($file,$allRecords);
		fclose($file);
		
		// View - Create Record
		$createView = $this->createViewAddRecord();
		$file = fopen($this->applicationLocation  . DIRECTORY_SEPARATOR. 'Views'.DIRECTORY_SEPARATOR."addRecord.blade.php",'w');
		fwrite($file,$createView);
		fclose($file);
		
		// View - Update Record
		$updateRecord = $this->createViewUpdateRecord();
		$file = fopen($this->applicationLocation  . DIRECTORY_SEPARATOR. 'Views'.DIRECTORY_SEPARATOR."editRecord.blade.php",'w');
		fwrite($file,$updateRecord);
		fclose($file);

		
	}
	
	public function createViewAddRecord(){
		
		$viewTitle = 'Add Record';
		
		$html =  "@extends('".$this->templateToExtend."')\n";
		$html .= "@section('pageTitle', '$viewTitle')\n";
		$html .= "@section('content')\n\n";
	 
		$html .="<form role='form' name='form1' method='post' action='{{route('".$this->table.".saveRecord')}}' > \n";
		$html .="\t<div class='row'>\n";
		$html .="\t\t<div class='col-md-12'> \n";
		$html .="\t\t\t<div class='box box-solid'>\n";
		$html .="\t\t\t\t<div class='box-header'>\n";
		$html .="\t\t\t\t\t<h3>$viewTitle</h3>\n";
		$html .="\t\t\t\t</div>\n";
		$html .="\t\t\t\t<div class='box-body'>\n";
 
		
		foreach($this->fields as $field){
			if(!in_array($field, $this->skipFields)){
				$html .= $this->getFieldHtml($field);
			}
			
		}
		
		// Add button.
		$html .= "\t\t\t\t\t<div class='form-group'>\n";
		$html .= "\t\t\t\t\t\t{!! csrf_field() !!}\n";
		$html .= "\t\t\t\t\t\t<input type='submit' value='$viewTitle' class='btn btn-primary'>\n";
		$html .= "\t\t\t\t\t</div>\n";
		// Close the html
		$html .="\t\t\t\t</div>\n";
		$html .="\t\t\t</div>\n";
		$html .="\t\t</div>\n";
		$html .="\t</div>\n";
		$html .="</form>\n";
		$html .= "@endsection\n";
		
		return $html;
		
	}
	
	public function createViewUpdateRecord(){
		
		$viewTitle = 'Edit Record';
		
		$html =  "@extends('".$this->templateToExtend."')\n";
		$html .= "@section('pageTitle', '$viewTitle')\n";
		$html .= "@section('content')\n\n";
	 
		$html .="<form role='form' name='form1' method='post' action='{{route('".$this->table.".updateRecord')}}' > \n";
		$html .="\t<div class='row'>\n";
		$html .="\t\t<div class='col-md-12'> \n";
		$html .="\t\t\t<div class='box box-solid'>\n";
		$html .="\t\t\t\t<div class='box-header'>\n";
		$html .="\t\t\t\t\t<h3>$viewTitle</h3>\n";
		$html .="\t\t\t\t</div>\n";
		$html .="\t\t\t\t<div class='box-body'>\n";
		
		foreach($this->fields as $field){
			if(!in_array($field, $this->skipFields)){
				$html .= $this->getFieldHtml($field,true);
			}	
		}
		
		// Add button.
		$html .= "\t\t\t\t\t<div class='form-group'>\n";
		$html .= "\t\t\t\t\t\t{!! csrf_field() !!}\n";
	 	$html .= "\t\t\t\t\t\t <input type='hidden' name='id' value='{{\$".$this->table."->id }}'> \n";
		
		$html .= "\t\t\t\t\t\t<input type='submit' value='Save Changes' class='btn btn-primary'>\n";
		$html .= "\t\t\t\t\t</div>\n";
		// Close the html
		$html .="\t\t\t\t</div>\n";
		$html .="\t\t\t</div>\n";
		$html .="\t\t</div>\n";
		$html .="\t</div>\n";
		$html .="</form>\n";
		$html .= "@endsection\n";
		
		return $html;
		
	}
	
	
	public function createViewMain(){
		
		$viewTitle = 'View All Records';
	
		$actionButton = "<div class='btn-group' style='min-width: 69px;'>\n ";
		$actionButton .="\t\t\t\t\t\t\t\t\t\t<button type='button' class='btn btn-default btn-xs'>Action</button>\n";
		$actionButton .="\t\t\t\t\t\t\t\t\t\t<button aria-expanded='false' type='button' class='btn btn-default dropdown-toggle btn-xs' data-toggle='dropdown' >\n";
		$actionButton .="\t\t\t\t\t\t\t\t\t\t\t <span class='caret'></span>\n";
		$actionButton .="\t\t\t\t\t\t\t\t\t\t\t <span class='sr-only'>Toggle Dropdown</span>\n";
		$actionButton .="\t\t\t\t\t\t\t\t\t\t</button>\n";
		$actionButton .="\t\t\t\t\t\t\t\t\t\t<ul class='dropdown-menu' role='menu'>\n";
		$actionButton .="\t\t\t\t\t\t\t\t\t\t\t  <li><a href='{{route('".$this->table.".editRecord',['id'=> \$row->id])}}'><i class='fa fa-pencil'></i> Edit</a></li> \n";
		$actionButton .="\t\t\t\t\t\t\t\t\t\t\t  <li><a href='{{route('".$this->table.".deleteRecord',['id'=> \$row->id])}}'><i class='fa fa-trash'></i> Delete</a></li> \n";
		$actionButton .="\t\t\t\t\t\t\t\t\t\t</ul>\n";
		$actionButton .="\t\t\t\t\t\t\t\t\t</div>";
		
		$html =  "@extends('".$this->templateToExtend."')\n";
		$html .= "@section('pageTitle', '$viewTitle')\n";
		$html .= "@section('content')\n\n";
	 
	 
		$html .="<div class='row'>\n";
		$html .="\t<div class='col-md-12'> \n";
		$html .="\t\t<div class='box box-solid'>\n";
		$html .="\t\t\t<div class='box-header'>\n";
		$html .="\t\t\t\t<h3 style='display:inline-block'>$viewTitle</h3>\n";
		
		//Add Button
		$html .="\t\t\t\t<a href='{{route('".$this->table.".addRecord')}}' class='btn btn-success' style='float:right;'><i class='fa fa-plus'></i> Add Record</a>\n";
		
		$html .="\t\t\t</div>\n";
		$html .="\t\t\t<div class='box-body'>\n";
		
		// Body  
		$html .="\t\t\t\t<table class='table table-bordered table-hover responsive nowrap' width='100%' id='table' >\n";
		$html .="\t\t\t\t\t<thead>\n";
		$html .="\t\t\t\t\t\t<tr>\n";
		
		// Table titles
		foreach($this->showFields as $field){
			$html .="\t\t\t\t\t\t\t<th> ".$this->splitCamelCase($field) ." </th>\n";
		}
		$html .="\t\t\t\t\t\t\t<th> Action </th>\n";
		$html .="\t\t\t\t\t\t</tr>\n";
		$html .="\t\t\t\t\t</thead>\n";
		$html .="\t\t\t\t\t<tbody>\n";
		$html .="\t\t\t\t\t\t@foreach (\$".$this->table." as \$row)\n";
		$html .="\t\t\t\t\t\t\t<tr>\n";
		
		// Table data
	 
		foreach($this->showFields as $field){
				 $html .="\t\t\t\t\t\t\t\t<td> {{\$row" .'->'.$field."}} </td>\n";	 
		}
			
		$html .="\t\t\t\t\t\t\t\t<td> $actionButton </td>\n";
		
		$html .="\t\t\t\t\t\t\t</tr>\n";
		$html .="\t\t\t\t\t\t@endforeach \n";
		$html .="\t\t\t\t\t</tbody>\n";
		$html .="\t\t\t\t</table>\n";
		
		// Close the html
		$html .="\t\t\t</div>\n";
		$html .="\t\t</div>\n";
		$html .="\t</div>\n";
		$html .="</div>\n";
		$html .= "@endsection\n";
		
		// Javascript
		$html .= "@section('javascript')\n\n";
		$html .= "<script type=\"text/javascript\">\n";
		$html .= "\t$(document).ready(function(){\n";
		$html .= "\t\t\$('#table').DataTable({ \n";
		$html .= "\t\t\t'pageLength':50,\n";
		$html .= "\t\t\t''responsive':true\n";
		$html .="\t\t});\n";
		$html .= "\t});\n";
		$html .= "</script>\n";
		$html .= "@endsection\n";

		return $html;
	}
	
	
	public function getFieldHtml($field,$edit=false){
	 	
		$capsField = $this->splitCamelCase($field);		
		$html = "\t\t\t\t\t<div class='form-group'>\n";
		$html .= "\t\t\t\t\t\t<label for='$field' > $capsField </label>\n ";
		if($edit == true){
			$html .= "\t\t\t\t\t\t<input type='text' class='form-control' name='$field' id='$field' placeholder='$capsField' value='{{\$".$this->table."->".$field."}}' >\n";
		}else{
			$html .= "\t\t\t\t\t\t<input type='text' class='form-control' name='$field' id='$field' placeholder='$capsField' value='{{old('$field')}}' >\n";
		}
		$html .="\t\t\t\t\t</div>\n";
		
		return $html;
		
	}
	
	
	public function splitCamelCase($input){
		$pattern ='/(?<=[a-z])(?=[A-Z])/x';
		$matches = preg_split($pattern, $input );
		
		$camelCaseSpaces = implode(" ", $matches);
		
		// for Snake Case
		$camelCaseSpaces = str_replace('_', ' ', $camelCaseSpaces );
		
		// All words Uppercase
		$camelCaseSpaces = ucwords($camelCaseSpaces);
		
		return $camelCaseSpaces; 
		
	}
	
	
}
