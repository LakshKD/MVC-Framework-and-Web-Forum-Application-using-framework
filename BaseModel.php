<?php

  require_once('DatabaseAccessor.php');
  require_once('ConfigReader.php');
  require_once('QueryBuilder.php');

  class BaseModel {
      protected $conn, $queryTemplates, $queryBuilder, $tableName, $primaryKey;

     public function __construct() {
       $db = new DatabaseAccessor();
       $conn = $db->getDatabaseConnection();
       $configReader = new ConfigReader();
       $configReader->setConfigFileLocation('./config/queryMapping.config');
       $this->queryTemplates = $configReader->loadConfigFile();
       $this->queryBuilder = new QueryBuilder();
     }

     public function setTableName($tableName) {
        $this->tableName = $tableName;
     }

     public function setPrimaryKey($primaryKey) {
       $this->primaryKey = $primaryKey;
     }
     private function constructQuery($tableName, $attributes, $relations, $logical) {
       $query = $this->queryTemplates["read"];
       $selectPortion = "";
       $wherePortion = "";
       foreach($attributes as $key => $value) {
         if(strlen($selectPortion) == 0 ) {
           $selectPortion = $key;
         } else {
           $selectPortion = $selectPortion." , ".$key;
         }
         if(strlen($wherePortion) == 0 ) {
           $wherePortion = $wherePortion." ".$key." ".$relations[$key]." ".$value;
         } else{
           $wherePortion = $wherePortion." ".$logical[$key]." ".$key." ".$relations[$key]." ".$value;
         }
       }
       $toReplace = "SELECT_CLAUSE";
       $query = str_replace($toReplace, $selectPortion, $query);
       $toReplace = "TABLE_NAME";
       $query = str_replace($toReplace, $tableName, $query);
       $toReplace = "SELECTION_CRITEIRA";
       $query = str_replace($toReplace, $wherePortion, $query);
       return $query;
     }

     public function read($tableName, $attributes, $relations, $logical) {
       $this->constructQuery($tableName, $attributes, $relations, $logical);
     }

     public function save() {
       if($this->tableName === null ||  $this->primaryKey === null) {
         throw new Exception("tableName is not set for this object");
       }
       $res = $this->queryBuilder->save($this->tableName, $this, array($this->primaryKey => 1));
       return (int)$this->queryBuilder->table($this->tableName)->max($this->primaryKey);
     }
  }
  function testBaseModel() {
    $baseModel = new BaseModel();
    $tableName = "testDB.users";
    $attributes = array("username" => "\"tamojit9\"", "password" => "\"abc\"");
    $relations = array("username" => "=", "password" => "=");
    $logical = array("username" => "and", "password" => "and");
    $baseModel->read($tableName, $attributes, $relations, $logical);
  }
  #testBaseModel();
?>
