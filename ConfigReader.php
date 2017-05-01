<?php
class ConfigReader
{
    // config fileLocation
    private $configFileLocation = './config/database.config';

    // method declaration
    public function getConfigFileLocation() {
        return $this->configFileLocation;
    }

    public function setConfigFileLocation($configFileLocation) {
      $this->configFileLocation = $_SERVER['DOCUMENT_ROOT'].$configFileLocation;
    }

    public function loadConfigFile($seprator = "=", $start = -1, $end = -1) {
      if($start > $end) {
        throw new Exception('incorrect line numbers start > end');
      }
      $fieldsTOValues = array();
      $configFile = fopen($this->configFileLocation, "r") or die("Unable to open config file please mention correct location of config file!");
      $lineNumber = 0;
      while(!feof($configFile)) {
          $lineNumber += 1;
          $line = fgets($configFile);
          if($start != -1 && $lineNumber < $start) continue;
          if($end != -1 && $lineNumber > $end) break;
          $pieces = explode($seprator, $line);
          if (count($pieces) < 2) {
              break;
          }
          $fieldsTOValues[trim($pieces[0])] = trim($pieces[1]);
      }
      fclose($configFile);
      return $fieldsTOValues;
    }
}
function test() {
  $readFile = new ConfigReader();
  var_dump($readFile->loadConfigFile());
  var_dump($readFile->loadConfigFile('=', 2, 4));
  echo $readFile->getConfigFileLocation()."<br>";
  $readFile->setConfigFileLocation('./fuckoff');
  echo $readFile->getConfigFileLocation()."<br>";
}
?>
