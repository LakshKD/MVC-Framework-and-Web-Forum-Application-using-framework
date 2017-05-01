<?php
  require_once('ConfigReader.php');
  class DatabaseAccessor {
      private $configReader;
      private $dbDetails;

      function __construct() {
        $this->configReader = new ConfigReader();
        $this->configReader->setConfigFileLocation('./config/database.config');
        $this->dbDetails = $this->configReader->loadConfigFile();
      }

      private function getConnection($details) {
        // Create connection
        try {
          $servername = $details["servername"];
          $database = $details["database"];
          $conn = new PDO("$database:host=$servername", $details["username"], $details["password"]);
          $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(Exception $e) {
          echo $e->getMessage();
        }
        return $conn;
      }

      public function getDatabaseConnection() {

        $exists = 0;

        if (array_key_exists('mutipleDatabase', $this->dbDetails) && $this->dbDetails['mutipleDatabase'] == 1) {
          $exists = 1;
        }

        if($exists == 0) {

          if (!array_key_exists('servername', $this->dbDetails) ||
              !array_key_exists('username', $this->dbDetails) ||
              !array_key_exists('password', $this->dbDetails) ||
              !array_key_exists('database', $this->dbDetails)) {
            throw new Exception("one or more entries like username, password not found in database.config file", 1);
          }

          $servername = $this->dbDetails["servername"];
          $username = $this->dbDetails["username"];
          $password = $this->dbDetails["password"];
          $database = $this->dbDetails["database"];


          return array("read" => $this->getConnection(array("servername" => $servername, "username" => $username,
                                                            "password" => $password, "database" => $database)));
        } else {

          if (!array_key_exists('read_servername', $this->dbDetails) ||
              !array_key_exists('read_username', $this->dbDetails) ||
              !array_key_exists('read_password', $this->dbDetails) ||
              !array_key_exists('read_database', $this->dbDetails)) {
            throw new Exception("one or more entries like read_username, read_password not found in database.config file", 1);
          }

          if (!array_key_exists('write_servername', $this->dbDetails) ||
              !array_key_exists('write_username', $this->dbDetails) ||
              !array_key_exists('write_password', $this->dbDetails) ||
              !array_key_exists('write_database', $this->dbDetails)) {
            throw new Exception("one or more entries like write_username, write_password not found in database.config file", 1);
          }

          $readServername = $this->dbDetails["read_servername"];
          $readUsername = $this->dbDetails["read_username"];
          $readPassword = $this->dbDetails["read_password"];
          $readDatabase = $this->dbDetails["read_database"];

          $writeServername = $this->dbDetails["write_servername"];
          $writeUsername = $this->dbDetails["write_username"];
          $writePassword = $this->dbDetails["write_password"];
          $writeDatabase = $this->dbDetails["write_database"];

          $read =   $this->getConnection(array("servername" => $readServername, "username" => $readUsername,
                                              "password" => $readPassword, "database" => $readDatabase));
          $write =  $this->getConnection(array("servername" => $writeServername, "username" => $writeUsername,
                                               "password" => $writePassword, "database" => $writeDatabase));

          return array("read" => $read, "write" => $write);
        }
      }
    }

    function testDatabaseAccessor() {
      $da = new DatabaseAccessor();
      var_dump($da->getDatabaseConnection());
    }
    #testDatabaseAccessor();
?>
