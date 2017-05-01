<?php

  require_once("DatabaseAccessor.php");
  require_once('Validator.php');
  require_once('Utility.php');

  class QueryBuilder {
    private $query, $database = "mysql", $tableName;
    private $whereValues, $selected = false;
    private static $loaded = false, $dbModels;

    public function setTableName($tableName) {
       $this->tableName = $tableName;
     }

     public function getTableName() {
       return $this->tableName;
     }

     public function setDatabase($database) {
        $this->database = $database;
     }

     public function getDatabase() {
        return $this->database;
      }

    public function __construct() {
      $this->db = new DatabaseAccessor();
      $this->whereValues = array();
      if(!self::$loaded) {
        $this->configReader = new ConfigReader();
        $this->configReader->setConfigFileLocation('./config/models.config');
        self::$dbModels = $this->configReader->loadConfigFile();
        self::$loaded = true;
      }
    }

    public function table($tableName) {
      if($this->database === 'mysql') {
        $this->query = $this->query.$tableName;
        $this->tableName = $tableName;
      }
      return $this;
    }
    public function where($field, $value) {
      if($this->database === 'mysql') {
        if(strpos($this->query, "where") === false) {
            $this->query = $this->query." where";
        } else if(!Validator::endsWith($this->query, "and")
                  && !Validator::endsWith($this->query, "or")) {
          throw new Exception("syntax error in query must have logical connectors and,or");
        }
      }
      $this->whereValues[$field] = $value;
      $this->query = $this->query." ".$field." = :".$field;
      return $this;
    }

    public function AND() {
      $this->query = $this->query." and";
      return $this;
    }

    public function OR() {
      $this->query = $this->query." or";
      return $this;
    }

    private function selectStar() {
      if($this->selected) return;
      if($this->database === 'mysql')
        $this->query = "select * from ".$this->query;
    }

    public function count() {
      if($this->database === 'mysql' && !$this->selected)
        $this->query = "select count(*) from ".$this->query;
      return $this->fetchRow(-1, "assoc")[0]["count(*)"];
    }

    public function avg($columnName) {
      if($this->database === 'mysql' && !$this->selected)
        $this->query = "select avg(".$columnName.") from ".$this->query;
      return $this->fetchRow(-1, "assoc")[0]["avg(".$columnName.")"];
    }

    public function select(...$columnNames) {
      $this->selected = true;
      $makeQuery = "select";
      foreach ($columnNames as $key => $value) {
        if(Validator::endsWith($makeQuery, "select")) {
          $makeQuery = $makeQuery." \"".$value."\"";
        } else {
          $makeQuery = $makeQuery.", \"".$value."\"";
        }
      }
      $this->query = $makeQuery." from ".$this->query;
      return $this;
    }

    public function max($columnName) {
      if($this->database === 'mysql' && !$this->selected)
        $this->query = "select max(".$columnName.") from ".$this->query;
      return $this->fetchRow(-1, "assoc")[0]["max(".$columnName.")"];
    }

    public function first() {
      $this->selectStar();
      return $this->fetchRow(1, "assoc")[0];
    }

    public function value($columnName) {
      $this->selectStar();
      return $this->fetchRow(1, "assoc")[0][$columnName];
    }

    public function chunk($number, $filterFunction = null) {
      $this->selectStar();
      $res = $this->fetchRow($number, "assoc");
      $columnValues = array();
      $length = count($res);
      if($filterFunction != null) {
        for($i = 0; $i < $length; $i++) {
          if($filterFunction($res[$i]) === True)
            $subset[$i] = $res[$i];
        }
        return $subset;
      }
      return $res;
    }

    public function pluck($columnName) {
      $this->selectStar();
      $res = $this->fetchRow(-1, "assoc");
      $columnValues = array();
      $length = count($res);
      for($i = 0; $i < $length; $i++) {
        $columnValues[$i] = $res[$i][$columnName];
      }
      return $columnValues;
    }

    private function resetState() {
      $this->query = "";
      $this->selected = false;
      $this->whereValues = array();
    }

    private function fetchRow($howMany, $type) {
      if($howMany > 0) {
        if($this->database == 'mysql')
          $this->query = $this->query." LIMIT 0, ".$howMany;
      }
      $conn = $this->db->getDatabaseConnection()["read"];
      $result = $conn->prepare($this->query);
      foreach ($this->whereValues as $key => $value) {
        $result->bindValue(":".$key, $value);
      }
      $this->resetState();
      $result->execute();
      if($result === false) {
        throw new Exception("query failed!!");
      }
      if($type == "assoc") {
        $result->setFetchMode(PDO::FETCH_ASSOC);
      } else if($type == "object") {

      } else if($type == "class") {

      }
      $res = array();
      $numRows = 0;
      while(($row = $result->fetch()) && ($howMany <= -1 || $howMany > 0)) {
        $res[$numRows] = $row;
        $numRows += 1;
        $howMany -= 1;
      }
      return $res;
    }

    public function rawQuery($query, $howMany) {
      $this->query = $query;
      return $this->fetchRow($howMany, "assoc");
    }

    public function asObject($data) {
      return getObjectFromArray(self::$dbModels[$this->tableName], $data);
    }

    public function asObjects($dataArray) {
      $res = array();
      foreach ($dataArray as $key => $value) {
        $res[$key] = $this->asObject($value);
      }
      return $res;
    }

    public function delete($table, $data) {
      if($this->database == "mysql") {
        $deleteStatement = "delete from ".$table." WHERE";
      }
      foreach ($data as $key => $value) {
        $deleteStatement = $deleteStatement." ".$key." = :".$key." and";
      }
      $deleteStatement = substr($deleteStatement, 0, -3);
      $conn = $this->db->getDatabaseConnection()["read"];
      $res = $conn->prepare($deleteStatement);
      foreach ($data as $key => $value) {
        $res->bindValue(":".$key, $value);
      }
      return $res->execute();
    }

    public function update($table, $data, $primaryKey) {
      if($this->database == "mysql") {
        $updateStatement = "update ".$table." SET";
      }
      foreach ($data as $key => $value) {
        $updateStatement = $updateStatement." ".$key." = :".$key.",";
      }
      $updateStatement = substr($updateStatement, 0, -1);
      $updateStatement = $updateStatement." where";
      foreach ($primaryKey as $key => $value) {
        $updateStatement = $updateStatement." ".$key." = :__pk__".$key." and";
      }
      $updateStatement = substr($updateStatement, 0, -3);
      $conn = $this->db->getDatabaseConnection()["read"];
      $res = $conn->prepare($updateStatement);
      foreach ($data as $key => $value) {
        $res->bindValue(":".$key, $value);
      }
      foreach ($primaryKey as $key => $value) {
        $res->bindValue(":__pk__".$key, $value);
      }
      return $res->execute();
    }



    public function insert($table, $data) {
      $insertStatement = "insert into ".$table." (";
      foreach ($data as $key => $value) {
        $insertStatement = $insertStatement." ".$key.",";
      }
      $insertStatement = substr($insertStatement, 0, -1);
      $insertStatement = $insertStatement." ) values (";
      foreach ($data as $key => $value) {
        $insertStatement = $insertStatement." :".$key.",";
      }
      $insertStatement = substr($insertStatement, 0, -1);
      $insertStatement = $insertStatement." )";
      $conn = $this->db->getDatabaseConnection()["read"];
      $result = $conn->prepare($insertStatement);
      foreach ($data as $key => $value) {
        $result->bindValue(":".$key, $value);
      }
      return $result->execute();
    }

    public function save($tableName, $object, $leave) {
      $res = toArray($object, $leave);
      return $this->insert($tableName, $res);
    }

    static $typeMapper;
    private function getTypes(){
      if(self::$typeMapper == null) {
        $c = new ConfigReader();
        $c->setConfigFileLocation("./config/".$this->database."TypeMapping.config");
        self::$typeMapper = $c->loadConfigFile();
      }
    }

    public function generateCreateQuery($dbName,$completePath, $type, $constraints){
      $classObject = asObject($completePath);
      $reflect = new ReflectionClass($classObject);
      $props   = $reflect->getProperties();
      $query = "CREATE TABLE ".$dbName.".".getClassName($completePath)."s (";
      $this->getTypes();
      $k = 0;
      foreach ($props as $prop) {
        $fieldName = $prop->getName();
        $query = $query." ".$fieldName." ".self::$typeMapper[$type[$k]]." ".$constraints[$k].",";
        $k += 1;
      }
      $query = substr($query, 0, -1);
      $query = $query." )";
      return $query;
    }

    public function createTable($dbName, $completePath, $type, $constraints) {
      $query = $this->generateCreateQuery($dbName, $completePath, $type, $constraints);
      $conn = $this->db->getDatabaseConnection()["read"];
      $res = $conn->prepare($query);
      return $res->execute();
    }
  }

  function testQuryBuilder() {

    var_dump($qb->table("testdb.testing")->where('name', 'tamojit')->chunk(3, function ($val) {
      if($val["password"] == "defe") return true;
      return false;
    }));
    #var_dump(getObjectFromArray(, $qb->table("testdb.testing")->where('name', 'tamojit')->chunck(1)));
    var_dump($qb->asObjects($qb->table("testdb.testing")->where('name', 'tamojit')->chunk(2)));
    #var_dump($qb->table("testdb.testing")->where('name', 'tamojit')->AND()->where('password', 'defe')->max('age'));
    var_dump($qb->delete('testdb.testing', array('name' => 'tamojit', 'password' => 'tamojit')));\
    #$qb->createTable("testdb", "Models.Fields.Field", array("int", "string", "string"), array("PRIMARY KEY", "NOT NULL", ""));
    #$qb->update("testdb.Fields", array("fieldID" => 1, "fieldName" => "CSEss1212", "fieldDescription" => "An awesome Engineerinf field"), array("fieldID" => 1));
    #$qb->save("testdb.Fields", getObjectFromArray("Models.Fields.Field", array("fieldID" => 2, "fieldName" => "ME", "fieldDescription" => "A not so awesome Engineering field")));
    var_dump($qb->delete('testdb.testing', array('name' => 'tamojit', 'password' => 'tamojit')));
  }
  $qb = new QueryBuilder();
  #var_dump($qb->table("testdb.testing")->where('name', 'tamojit')->AND()->where('password', 'defe')->max('age'));
?>
