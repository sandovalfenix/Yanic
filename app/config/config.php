<?php 

namespace App\Config;

class Config{
	
	private $loader;
	private $twig;
	private $assets = array();
	private	$hash = 'Aitheria Solution Technology';
	private $hash2 = 'Colombia - Valle del Cauca';
	private $crypt_method = 'blowfish';

	function __construct(){
		$this->loader = new \Twig_Loader_Filesystem(
			array (
			   __ROOT__ . "web",
			   __ROOT__ . "app\config\Resources",
			   __ROOT__ . "app\config\Resources" . __DS__ . "layout",
			)
		);
		
		$this->twig = new \Twig_Environment($this->loader);
		$this->twig->addGlobal('Session', $_SESSION);
		$filter = new \Twig_SimpleFilter('encrypt', function ($string){
		    return $this->encrypt($string);
		});
		$this->twig->addFilter($filter);

		$filter = new \Twig\TwigFilter('assets', function ($string) {
			echo '/web/assets/'.$string;
		});
		$this->twig->addFilter($filter);
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
		    $mail->Username = '';                 // SMTP username
		    $mail->Password = '';                           // SMTP password
		    $mail->SMTPSecure = 'ssl';  
		    $mail->SMTPOptions = array(
			'ssl' => array(
			    'verify_peer' => false,
			    'verify_peer_name' => false,
			    'allow_self_signed' => true
			));                          // Enable TLS encryption, `ssl` also accepted
			$mail->Port = 465;                                    // TCP port to connect to

		    //Recipients
		    $mail->setFrom($from, '');
		    $mail->addAddress($address, '');          // Name is optional

		    //Content
		    $mail->isHTML(true);                                  // Set email format to HTML
		    $mail->Subject = $subject;
		    $mail->Body    = $body;

		    $send = ($mail->send()) ? true : false;
		    $msj = 'Mensaje registrado exitosamente';
		} catch (Exception $e) {
			$msj = 'Error al regstrarce. Mailer Error: ' . $mail->ErrorInfo;
		}

		return array('send' => $send, 'msj' => $msj);
	}

	public function encrypt($string){
	    $output = false;	    

	    $secret_key = hash('sha256',$this->hash);
	    $secret_iv = substr(hash('sha256',$this->hash2),0,8);
	    $string = trim(strval($string));
	    $output = base64_encode(openssl_encrypt($string, $this->crypt_method, $secret_key, 0, $secret_iv));

	    return $output;
	}

	public function decrypt($string){
	    $output = false;

	    $secret_key = hash('sha256',$this->hash);
	    $secret_iv = substr(hash('sha256',$this->hash2),0,8);
	    $string = trim(strval($string));
	    $output = openssl_decrypt(base64_decode($string), $this->crypt_method, $secret_key, 0, $secret_iv);

	    return $output;
	}

	public function setAlert($type, $msj){
		$_SESSION['alert'] = array(
			'type' => $type,
			'msj' => $msj
		);
	}

	public function getAlert(){
		$alert = (isset($_SESSION['alert'])) ? $_SESSION['alert'] : false ;
		$_SESSION['alert'] = false;
		
		return $alert;
	}

	public function redirect($url){
		header("location: ".$url); 
		exit();
	}
}