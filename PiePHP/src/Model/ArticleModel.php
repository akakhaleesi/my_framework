<?php

	namespace src\Model;

	use Core\Model;

	class ArticleModel extends \Core\Entity {

		protected $table = 'articles';
		private static $relations;

		static function getRelations() {
			return self::$relations = ['has_many' => ['comments']];
		}
	}