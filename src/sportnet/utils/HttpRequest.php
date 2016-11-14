<?php
namespace sportnet\utils;
class HttpRequest extends AbstractHttpRequest
{
	public function __construct()
	{
		//parent::__construct();
		$this->script_name = $_SERVER["SCRIPT_NAME"];
		if(isset($_SERVER["PATH_INFO"]))
		{
			$this->path_info = $_SERVER["PATH_INFO"];
		}
		$this->query = $_SERVER["QUERY_STRING"];
		$this->method = $_SERVER["REQUEST_METHOD"];
		$this->get = $_GET;
		$this->post = $_POST;
    }
	
	public function getRoot()
	{
		$position = strrpos($this->script_name,"/");
		return substr($this->script_name, 0, $position)."/";
	}
	
	public function getController()
	{
		$position = strpos($this->path_info,"/");
		$chaine = substr($this->path_info, $position + 1);
		$position = strpos($chaine,"/");
		return substr($chaine, 0, $position);
	}
	
	public function getAction()
	{
		$chaine = str_replace("/".$this->getController()."/","",$this->path_info);
		$chaine = str_replace("/","",$chaine);
		return $chaine;
	}
}