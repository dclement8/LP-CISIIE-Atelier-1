<?php
namespace sportnet\utils;
class Router extends AbstractRouter
{
	public function __construct()
	{
		
	}
	
	public function addRoute($url, $ctrl, $mth, $level)
	{
		self::$routes[$url] = array($ctrl, $mth, $level);
	}
	
	public static function dispatch(HttpRequest $http_request)
	{
		if(array_key_exists($http_request->path_info, self::$routes) )
		{
			$method = self::$routes[$http_request->path_info][1];
			$ctrl = new self::$routes[$http_request->path_info][0]($http_request);
			$ctrl->$method();
		}
		else
		{
			$method = self::$routes['default'][1];
			$ctrl = new self::$routes['default'][0]($http_request);
			$ctrl->$method();
		}
	}
}