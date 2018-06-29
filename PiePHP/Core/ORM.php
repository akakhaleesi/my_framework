<?php

namespace Core;

class ORM {

	private $bdd;
	private $id;

	public function __construct() {
		try {
			$this->bdd = new \PDO('mysql:host=localhost;dbname=pie_cinema', 'root', '');
		}
		catch (Exception $e) {
			die('Erreur : ' . $e->getMessage());
		}
	}
	public function create () {
		$fields = entity::getPublicVars();
		if(is_array($fields)) {
			$i=0;
			$col_names = null;
			$col_vals = null;
			foreach($fields as $col=>$val) {
				$i++;
				if($i == count($fields)) {
					$col_names .= $col;
					$col_vals .= ':' . $col;
				}else{
					$col_names .= $col . ',';
					$col_vals .= ':' . $col . ',';
				}
			}
			$create = $this->bdd->prepare('INSERT INTO ' . $this->table . '(' . $col_names . ') VALUES (' . $col_vals . ')');
			foreach($fields as $col=>$val) {
				$create->bindValue($col, $val);
			}
			$create->execute();

			$id = $this->bdd->lastInsertId();

			return $id;
		}	
	}
	public function read($id = null) {

		$relations = get_class($this)::getRelations();
		$values = [];

		$table = $this->table;
		$sql = 'SELECT * FROM ' . $table;
		
		if($id === null) {
			$id = $this->id;
		}

		if(array_key_exists('has_many', $relations)) {
			$has_many = $relations['has_many'];

			foreach ($has_many as $value) {
				$table_id = substr($table, 0, -1) . '_id';
				$sql .= ' INNER JOIN ' . $value . ' ON ' . $table . '.' . $table_id . '=' . $value . '.' . $table_id;
			}
		}
		if(array_key_exists('has_one', $relations)) {
			$has_one = $relations['has_one'];

			foreach ($has_one as $value) {
				$relation_id = substr($value, 0, -1) . '_id';
				$sql .= ' INNER JOIN ' . $value . ' ON ' . $table . '.' . $relation_id . '=' . $value . '.' . $relation_id;
			}
		}

		$sql .= ' WHERE ' . $table . '.' . substr($table, 0, -1) . '_id' . ' = :id';

		$read = $this->bdd->prepare($sql);
		$read->bindParam(':id', $id);
		$read->execute();
		while($donnees = $read->fetch()) {
			array_push($values, $donnees);
		}
		//var_dump($sql);

		return $values;
	}
	public function read_all($table = null) {

		$values = [];

		if($table === null) {
			$table = $this->table;
		}
		$sql = 'SELECT * FROM ' . $table;
		
		$read_all = $this->bdd->prepare($sql);
		$read_all->execute();
		while($donnees = $read_all->fetch()) {
			array_push($values, $donnees);
		}
		return $values;
	}
	public function update($fields, $id = null, $table = null) {

		if($table === null) {
			$table = $this->table;
		}
		if($id === null) {
			$id = $this->id;
		}
		$table_id = substr($table, 0, -1) . '_id';

		if(is_array($fields)) {
			$i=0;
			$col_val = null;
			foreach($fields as $col=>$val) {
				$i++;
				if($i == count($fields)) {
					$col_val .= $col . '="' . $val . '"';
				}else{
					$col_val .= $col . '="' . $val . '",';
				}
			}
			$update = $this->bdd->prepare('UPDATE ' . $table . ' SET ' . $col_val . ' WHERE '. $table_id .'=' . $id);
			return $update->execute();
		}
	}
	public function delete($id = null, $table = null) {

		if($table === null) {
			$table = $this->table;
		}
		if($id === null) {
			$id = $this->id;
		}
		$table_id = substr($table, 0, -1) . '_id';

		$delete = $this->bdd->prepare('DELETE FROM ' . $table . ' WHERE '. $table_id .'=' . $id);

		return $delete->execute();

	}
	public function find($params, $table = null) {
		$relations = get_class($this)::getRelations();

		if($table === null) {
			$table = $this->table;
		}

		if(is_array($params)) {

			$i=0;
			$param = null;
			$values = [];
			$sql = 'SELECT * FROM ' . $table;

			if(array_key_exists('has_many', $relations)) {
			$has_many = $relations['has_many'];

			foreach ($has_many as $value) {
				$table_id = substr($table, 0, -1) . '_id';
				$sql .= ' INNER JOIN ' . $value . ' ON ' . $table . '.' . $table_id . '=' . $value . '.' . $table_id;
				}
			}
			if(array_key_exists('has_one', $relations)) {
				$has_one = $relations['has_one'];

				foreach ($has_one as $value) {
					$relation_id = substr($value, 0, -1) . '_id';
					$sql .= ' INNER JOIN ' . $value . ' ON ' . $table . '.' . $relation_id . '=' . $value . '.' . $relation_id;
				}
			}

			foreach($params as $req=>$val) {
				$i++;
				if($i == count($params)) {
					$param .= $req . ' ' . $val;
				}
				else {
					$param .= $req . ' ' . $val . ' ';
				}
			}
			$sql .= ' ' . $param;

			$find = $this->bdd->prepare($sql);
			$find->execute();

			while($donnees = $find->fetch()) {
				array_push($values, $donnees);
			}
			return $values;
		}
	}
}