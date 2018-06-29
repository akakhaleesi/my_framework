<?php

	namespace Core;

	class Controller {

		private $_render = null;

		public function __construct() {
			$secure = new \Core\Request;
			$secure->cleanAll();
		}

		protected function render($view, $scope = []) { var_dump('ooooooooooo');

			extract($scope);
			$f = implode(DIRECTORY_SEPARATOR, [dirname(__DIR__), 'src', 'View', str_replace('Controller', '',basename(str_replace('\\', '/', get_class($this)))), $view]) . '.php';

			if (file_exists($f)) {
				ob_start();
				include($f);
				$view = ob_get_clean();
				ob_start();
				include(implode(DIRECTORY_SEPARATOR, [dirname(__DIR__), 'src', 'View', 'index']) . '.php');
				$this->_render = ob_get_clean();
				return $this->_render;
			}
		}
	}
	