<?php 

namespace Config;

class Config{
	
	private $loader;
	private $twig;
	private $assets = array();

	function __construct(){
		$this->loader = new \Twig_Loader_Filesystem(
			array (
			   __ROOT__ . "Templates",
			   __ROOT__ . "Templates" . __DS__ . "views",
			)
		);
		
		$this->twig = new \Twig_Environment($this->loader);
		$this->assets = array('assets' => '/Templates/assets');
	}

	public function addGlobal($name, $vars = array()){	
		$this->twig->addGlobal($name, $vars);
	}

	public function render($template, $vars = array()){	
		$vars = array_merge($vars, $this->assets);
        echo $this->twig->render($template, $vars);
	}

	public function phpMailer($from, $address, $body, $subject){
		$mail = new \Vendors\PHPMailer\PHPMailer(true);
		try {
		    //Server settings                               // Enable verbose debug output
		    $mail->isSMTP();                               // Set mailer to use SMTP
		    $mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
		    $mail->SMTPAuth = true;                               // Enable SMTP authentication
		    $mail->Username = 'mailoceanycode@gmail.com';                 // SMTP username
		    $mail->Password = 'Andres1234.';                           // SMTP password
		    $mail->SMTPSecure = 'ssl';  
		    $mail->SMTPOptions = array(
			'ssl' => array(
			    'verify_peer' => false,
			    'verify_peer_name' => false,
			    'allow_self_signed' => true
			));                          // Enable TLS encryption, `ssl` also accepted
			$mail->Port = 465;                                    // TCP port to connect to

		    //Recipients
		    $mail->setFrom($from, 'Seguridad Social Integral');
		    $mail->addAddress($address, 'Cliente');          // Name is optional

		    //Content
		    $mail->isHTML(true);                                  // Set email format to HTML
		    $mail->Subject = $subject;
		    $mail->Body    = $body;

		    //$send = ($mail->send()) ? true : false;
		    $msj = null;
		} catch (Exception $e) {
			$msj = 'Error al regstrarce. Mailer Error: ' . $mail->ErrorInfo;
		}

		return array('send' => true, 'msj' => $msj);
	}

	function openCypher ($action='encrypt',$string=false){
	    $action = trim($action);
	    $output = false;

	    $myKey = 'oW%c76+jb2';
	    $myIV = 'A)2!u467a^';
	    $encrypt_method = 'AES-256-CBC';

	    $secret_key = hash('sha256',$myKey);
	    $secret_iv = substr(hash('sha256',$myIV),0,16);

	    if ( $action && ($action == 'encrypt' || $action == 'decrypt') && $string )
	    {
	        $string = trim(strval($string));

	        if ( $action == 'encrypt' ){
	            $output = base64_encode(openssl_encrypt($string, $encrypt_method, $secret_key, 0, $secret_iv));
	        };

	        if ( $action == 'decrypt' ){
	            $output = openssl_decrypt(base64_decode($string), $encrypt_method, $secret_key, 0, $secret_iv);
	        };
	    };

	    return $output;
	}
}