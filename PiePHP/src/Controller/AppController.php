<?php

	namespace src\Controller;

	class AppController extends \Core\Controller {

		public function __construct() {
			parent::__construct();
		}
		public function indexAction() {
			var_dump('DEFAULT INDEX');
		}
	}