<?php
// Author: Sébastien Boisvert
// Client: Coop Roue-Libre de l'Université Laval
// License: GPLv3

class Model{
	protected $m_attributes;
	protected $m_core;
	protected $m_model;

	public function setCore($core){
		$this->m_core=$core;
	}

	public function setModel($model){
		$this->m_model=$model;
	}

	public function getId(){
		return $this->getAttribute("id");
	}

	public function getAttributes(){
		return $this->m_attributes;
	}

	public function getAttributeValue($field){
		return $this->getAttribute($field);
	}

	public function getAttribute($field){
		return $this->m_attributes[$field];
	}

	public static function makeObjectsFromRows($core,$list,$model){

		$objects=array();

		foreach($list as $i){
			$item=new $model();

			$item->setAttributes($i);
			array_push($objects,$item);

			$item->setCore($core);
			$item->setModel($model);
		}

		return $objects;

	}

	public static function findAll($core,$model){
		$table=$core->getTablePrefix().$model;

		$list=$core->getConnection()->query("select * from $table ;")->getRows();
		
		return Model::makeObjectsFromRows($core,$list,$model);
	}

	public static function findOne($core,$model,$identifier){
		$table=$core->getTablePrefix().$model;

		$list=$core->getConnection()->query("select * from $table where id = $identifier limit 1 ;")->getRows();
		
		$list=Model::makeObjectsFromRows($core,$list,$model);

		if(count($list)==1){
			return $list[0];
		}else{
			return NULL;
		}
	}


	public static function findWithIdentifier($core,$model,$identifier){
		return Model::findOne($core,$model,$identifier);

	}

	public function setAttributes(&$values){
		$this->m_attributes=$values;
	}

	public static function getPersistentAttributesForTable($core,$table){
		return $core->getConnection()->query("describe $table ;")->getRows();
	}

	public function getTableList($core){
		$list=$core->getConnection()->query("show tables ;")->getRows();

		$output= array();
		$databaseName=$core->getDatabaseName();
		$keyName="Tables_in_".$databaseName;

		foreach($list as $i){
			array_push($output,$i[$keyName]);
		}

		return $output;
	}

	public function getFieldNames(){
		return array();
	}

	public function isSelectField($core,$field){
		return false;
	}

	public function getSelectOptions($core,$field){

		echo "Hi!";
		return array();
	}

	public static function insertRow($core,$model,$attributeValues){
		$table=$core->getTablePrefix().$model;

		$attributes=Model::getPersistentAttributesForTable($core,$table);

		$attributeList="";
		$valuesList="";

		for($i=0;$i<count($attributes);$i++){
			$field=$attributes[$i]['Field'];

			if($i==0){
				$attributeList.=" ( ";
				$valuesList.=" ( ";
			}

			if($field=="id"){
				continue;
			}
	
			$type=$attributes[$i]['Type'];

			if(!array_key_exists($field,$attributeValues)){
				echo "<div class=\"error\">Field $field is not set</div>";
			}

			$value=$attributeValues[$field];

			$value=$core->getConnection()->escapeString($value);

			if($type=="varchar(255)" || $type=="date" || $type="char(1)"){				
				$value="'$value'";	
			}

			if($value == "''" || $value == ''){
				$value = 'NULL';
			}

			$attributeList.= $field;
			$valuesList.= $value;

			if($i==count($attributes)-1){
				$attributeList.=" ) ";
				$valuesList.=" ) ";
			}else{

				$attributeList.=" , ";
				$valuesList.=" , ";
			}
		}

		$query="insert into $table $attributeList values $valuesList ; ";

		$core->getConnection()->query($query);

		$id=$core->getConnection()->getInsertedIdentifier();

		return Model::findWithIdentifier($core,$model,$id);
	}

	public static function removeRow($core,$model,$identifier){
		$id=(int)$identifier;
		$table=$core->getTablePrefix().$model;

		$query="delete from $table where id = $identifier ; ";

		$core->getConnection()->query($query);
	}

	public function getName(){
		return $this->getAttributeValue("id");
	}

	public function isFilledField($core,$field){
		return false;
	}

	public function getFilledValue($core,$field){
		return "NULL";
	}

	public static function getObjectsInRelation($core,$model,$field,$identifier){

		$table=$core->getTablePrefix().$model;

		$list=$core->getConnection()->query("select * from $table where $field=$identifier ;")->getRows();
		
		$objects=array();

		foreach($list as $i){
			$item=new $model();

			$item->setAttributes($i);
			array_push($objects,$item);
		
			$item->setModel($model);
			$item->setCore($core);
		}

		return $objects;
	}

	public function getObjectInRelation($core,$model,$field){

		$identifier=$this->getId();

		$table=$core->getTablePrefix().$model;

		$query="select * from $table where $field=$identifier ;";

		return $this->findOneWithQuery($core,$query,$model);
	}


	public static function updateRow($core,$model,$attributeValues,$id){
		$table=$core->getTablePrefix().$model;

		$attributes=Model::getPersistentAttributesForTable($core,$table);

		$list="";

		//print_r($attributes);
		//print_r($attributeValues);

		for($i=0;$i<count($attributes);$i++){
			$field=$attributes[$i]['Field'];

			if($field=="id"){
				continue;
			}
	
			$type=$attributes[$i]['Type'];

			$value=$attributeValues[$field];

			$value=$core->getConnection()->escapeString($value);

			if($type=="varchar(255)" || $type=="date" || $type="char(1)"){
				
				$value="'$value'";
	
			}
		
			$list.= " $field = $value ";
		

			if($i==count($attributes)-1){

			}else{

				$list.=" , ";
			}
		}

		$query="update $table set $list where id=$id";

		$core->getConnection()->query($query);

		return Model::findWithIdentifier($core,$model,$id);
	}

	public static function findAllWithQuery($core,$query,$model){
		$list=$core->getConnection()->query($query)->getRows();

		$objects=Model::makeObjectsFromRows($core,$list,$model);

		return $objects;
	}

	public  static function findOneWithQuery($core,$query,$model){
		$list=$core->getConnection()->query($query)->getRows();

		if(count($list)==0){
			return NULL;
		}

		$objects=Model::makeObjectsFromRows($core,$list,$model);

		return $objects[0];
	}

	public function mustSkipAttribute($name){
		if($name=="id"){
			return true;
		}

		return false;
	}

	public function isLinkedAttribute($name){
		return false;
	}

	public function getAttributeLink($name){
		return getLink();
	}


	public function getLink(){
		return $this->getName();
		//return "<a href=\"?controller={$this->m_model}Management&action=view&id={$this->getId()}\">{$this->getName()}</a>";
	}

}

?>
