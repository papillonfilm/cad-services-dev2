<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

//use App\sysMailLog;

 
class EmailBasic extends Mailable{
	
    use Queueable, SerializesModels;

    // Variables
	public $msg;
	public $subject;
	public $signature;
	public $siteName ; 
	public $emailLogo = '';
	public $emailLogoUrl = '';
	public $viewOnly = false;
	public $trackEmail = true;
	public $trackingImage = '';
	public $returnHTML = false;
	
    public function __construct($array){
        
		 
		// Variables
		$this->msg = (isset($array['msg'])?$array['msg']:'' );
		$this->subject = (isset($array['subject'])?$array['subject']:'' );
		$this->signature =  (isset($array['signature'])?$array['signature']:'' );
		$this->trackingImage =  (isset($array['trackingImage'])?$array['trackingImage']:'' );
		$this->returnHTML =  (isset($array['returnHTML'])?$array['returnHTML']:false );
		
		// Application variables or global stuff.
		$this->siteName = config('app.name'); 
		
		// File attachment.
		$this->emailLogo = public_path('/images/logoEmail.gif');
		$this->emailLogoUrl = url('/images/logoEmail.gif');
		 
		 
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(){
		
		// Do some validation
		if($this->signature == ''){
			$this->signature = config('vidalFramework.emailSignature'); //'Support Team'; 
		}
		
		// parse the email template using blade and return it to be sended.
		// located @ resources/email/template.blade.php
        return $this->view('email.template'); 
		
    }
	
 
	
	
}
