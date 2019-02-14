<?php

namespace Config;

class Logger {

	public function addLogFile($file){
		$json = file_get_contents($file);	
		$Log = json_decode($json);
		$i = ($Log) ? count($Log) : 1;

		$content = str_replace("]", ","."\n", file_get_contents($file)); 
		file_put_contents($file, $content);

		return $i++;
	}

	public function url(){
		return (!isset($_SERVER["SCRIPT_URL"])) ? "/home" : $_SERVER["SCRIPT_URL"];
	} 
	
	public function setLogViews() {
		$file = "var/log/log_views.json";
		
		$id = $this->addLogFile($file);	
		
		$log = json_encode(array(
			'idLog' => $id,
			'dateTime' => date("Y-m-d\TH:i:sO"),
			'ip' => $_SERVER['REMOTE_ADDR'],
			'device' => $_SERVER["HTTP_USER_AGENT"],
			'URL' => $this->url(),
		));

		$this->putLogFile($file, $log);
	}

	public function setLogSession($status) {
		$file = "var/log/log_session.json";
		$id = $this->addLogFile($file);		
		
		$log = json_encode(array(
			'idLog' => $id,
			'ID' => $_SESSION['ID'],
			'username' => $_SESSION['username'],
			'status' => $status,
			'dateTime' => date("Y-m-d\TH:i:sO"),
			'ip' => $_SERVER['REMOTE_ADDR'],
			'device' => $_SERVER["HTTP_USER_AGENT"],
			'URL' => $this->url()
		));

		$this->putLogFile($file, $log);
	}

	public function putLogFile($file, $log){
		file_put_contents($file, $log, FILE_APPEND);
		file_put_contents($file, "]", FILE_APPEND);
	}	
}
