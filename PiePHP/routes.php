<?php
	
namespace Core;

$basepath = implode('/', array_slice(explode('/', $_SERVER['SCRIPT_NAME']), 0, -1)) . '/';
$url = '/'.substr($_SERVER['REQUEST_URI'], strlen($basepath));
$args = explode('/', $url);
$routes = [
	'/app/index/'
	];
if(count($args) > 3) {
	$base_url = '/'.implode('/', [$args[1], $args[2]]).'/';
	foreach ($routes as $route) {
		if($route === $base_url) {
			$final_route = implode('/', $args);
			$field = explode('/', substr($final_route, strlen($base_url)));
			Router::connect($final_route, ['controller' => $args[1], 'action' => $args[2], 'args' => $field]);
		}
	}
}
elseif(count($args) == 3) {
	$base_url = '/'.implode('/', [$args[1], $args[2]]).'/';
	foreach ($routes as $route) {
		if($route === $base_url) {
			$final_route = implode('/', $args);
			Router::connect($final_route, ['controller' => $args[1], 'action' => $args[2]]);
		}
	}
}

Router::connect('/', ['controller' => 'app', 'action' => 'index']);