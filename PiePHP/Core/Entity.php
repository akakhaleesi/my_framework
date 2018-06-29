<?php

	namespace Core;

	class Entity extends \Core\ORM {

		public function __construct($attributes = null) {
			parent::__construct();
			if(is_array($attributes)) {
				foreach ($attributes as $key => $value) {
					$this->{$key} = $value;
				}
			}
			elseif(is_int($attributes)) {
				$read = $this->read($attributes);
				foreach ($read as $key => $value) {
					$this->{$key} = $value;
				}
				return $read;
			}
		}
		public function getPublicvars() {
        	function get($objet) {
            	return get_object_vars($objet);
       		}
        	return get($this);
        }
	}