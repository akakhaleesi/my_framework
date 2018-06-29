<?php

	namespace Core;

	class Core {

		public function run() {
			include 'Controller.php';
			include 'routes.php';

			$basepath = implode('/', array_slice(explode('/', $_SERVER['SCRIPT_NAME']), 0, -1)) . '/';
        	$url = '/'.substr($_SERVER['REQUEST_URI'], strlen($basepath));
			$go = new \Core\Router;
			
			if($go->get($url)) {
				$route = Router::get($url);

				$controller = '\src\Controller\\' . ucfirst($route['controller']) . 'Controller';
				$action = $route['action'] . 'Action';

				$do = new $controller;
				$do->$action();

			} else {
				echo "ERROR 404";
			}
		}
	}