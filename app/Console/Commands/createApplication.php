<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class createApplication extends Command{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vidal:create {appName}';
	
	public $applicationFolder = 'Applications';
	public $applicationLocation = '';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create application structure vidalFramework style.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(){
        parent::__construct();
		$this->applicationLocation = base_path( 'app/'. $this->applicationFolder ); 
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(){
        
		$appName = $this->argument('appName');
	
		$controllerName = $appName."Controller";
		$createFolders = ['Controllers','Models','Views','Migrations'];
		$randomString = $appName . $this->generateRandomString(5);
		
		if(!file_exists($this->applicationLocation)){
			// Application directory is not created, create it !
			 mkdir($this->applicationLocation);
		}
		
		if(file_exists($this->applicationLocation. "/$appName")){
			$this->info("$appName Already exists, unable to create.");
			return true;
		}else{
			// Create application folder for first time.
			mkdir($this->applicationLocation. "/$appName");
		}
		
		
		// Create the Application Folder
		foreach($createFolders as $folder){
			// Create Folder Structure on Application Directory
			mkdir($this->applicationLocation ."/$appName/".$folder);	
		}
		
		// Create route.php
		$routeSkeleton = "<?php \n\n". '$arguments=';
		$routeSkeleton .="array(\n";
		$routeSkeleton .="\n\t'applications'=>'$appName', ";
		$routeSkeleton .="\n\t// applicationKey:secret this secret is defined on the applications table";
		$routeSkeleton .="\n\t// this key must be unique per application.";
		$routeSkeleton .="\n\t'middleware'=>['web','auth','applicationKey:$randomString'],";
		$routeSkeleton .="\n\t'namespace' => 'App\Applications\\".$appName."\Controllers',";
		$routeSkeleton .="\n\t'prefix'=>'/dashboard/$appName'";
		$routeSkeleton .="\n\t);\n\n";

		$routeSkeleton .="\n\tRoute::group(".'$arguments'.", function() { \n";
		$routeSkeleton .= "\n\t\tRoute::get('/','$controllerName@index')->name('$appName');";
		$routeSkeleton .= "\n\t});\n\n";

		$routefile = fopen($this->applicationLocation. "/$appName/routes.php",'w');
	 	fwrite($routefile,$routeSkeleton);
		fclose($routefile);
		
		$this->createController();
		$this->info("$appName successfully created.");
		
    }
	
	
	public function generateRandomString($length = 10) {
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!@#%^%^&*()_+=-/.,<>:\\';
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		return $randomString;
	}
	
	
	public function createController(){
		$appName = $this->argument('appName');
	 
		
		// create baseController 
		$controllerSkeleton = "<?php\n\n";
		$controllerSkeleton .= "namespace App\Applications\\".$appName."\Controllers;\n";
		$controllerSkeleton .= "use Illuminate\Http\Request;\n";
		$controllerSkeleton .= "use App\Http\Controllers\Controller;\n\n";
		$controllerSkeleton .= "class ".$appName."Controller extends Controller{ \n\n";
		$controllerSkeleton .= "\tpublic function __construct(){\n\n";
		$controllerSkeleton .="\t}\n\n";
		$controllerSkeleton .="}\n";
		
		if(file_exists($this->applicationLocation. "/$appName/Controllers/")){
			
			$controllerfile = fopen($this->applicationLocation. "/$appName/Controllers/".$appName."Controller.php",'w');
			fwrite($controllerfile,$controllerSkeleton);
			fclose($controllerfile);
		}
		
	}
	
	
}
