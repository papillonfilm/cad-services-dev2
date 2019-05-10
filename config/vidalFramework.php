<?php

return [

    /*
    |--------------------------------------------------------------------------
    | VidalFramework Encryption Hash for urls
    |--------------------------------------------------------------------------
    |
    |  preSalt 100 character string for md5 encription for urls.
	|  postSalt 50 character string
    |
    */

    'preSalt' => '1passwordp@$$w0rd123456sup3rchallenge78secr3t143r5value=apple,mac$4567R0meuser/pass093l^4sadm1n1223',
	'postSalt'=> '671r@nd0mst31n6##urluser=1938st@ng3#sup3rh@$h66f^',
	
	/*
	|--------------------------------------------------------------------------
    | Default email signature if not defined in the parameters array
    |--------------------------------------------------------------------------
	*/
	'emailSignature'=>'Support Team',
	
	/*
	|--------------------------------------------------------------------------
    | Default profile picture for users.
    |--------------------------------------------------------------------------
	*/
	'userDefaultProfilePicture'=>'images/avatar/avatar0.png',
	/*
	|--------------------------------------------------------------------------
    | rememberUser, if this value is set to true the user will be
	| remember even if he/she close the browser, user have to logout
	| to remove the remember_token. If value set to false the session will expire
	| after X amount configure in config/sessions.php lifetime that is also configured
	| in the .env file as SESSION_LIFETIME .(if exists on .env file that value will be used)
    |--------------------------------------------------------------------------
	*/
	 
	
   

];



?>