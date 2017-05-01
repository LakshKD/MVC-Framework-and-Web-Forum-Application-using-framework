<?php
  require_once('Utility.php');
  require_once('ConfigReader.php');
  require_once('Request.php');

  function exception_handler($e) {
    echo "Uncaught exception: " , jTraceEx($e), "\n";
  }

  set_exception_handler('exception_handler');

  class Route {

    private static $controllersLocation = "./Controllers";
    private static $securityMapping = "./config/securityMapping.config";
    private static $securityMap, $loaded = false;

    public function __construct() {
    }

    public function initialize() {
      if(self::$loaded == false) {
        $cr = new ConfigReader();
        $cr->setConfigFileLocation(self::$securityMapping);
        self::$securityMap = $cr->loadConfigFile();
      }
    }

    public static function route($controllerName, $functionName, $data) {
      $completePath = constructPath(self::$controllersLocation, $controllerName, "php");
      $hasDot = strpos($completePath, ".") !== false;
      try {
        require_once($completePath);
      } catch(Exception $e) {
        echo "unable to find the php file ".$completePath;
      }
      if($hasDot) {
        $locationalParts = explode(".", $controllerName);
        $className = $locationalParts[count($locationalParts)-1];
      }
      else {
        $className = $controllerName;
      }
      try {
        $myclass = new $className;
      } catch(Exception $e) {
        echo "not able to create object of class ".$className;
        return false;
      }
      try {
        $myclass->{ $functionName }($data);
      } catch(Exception $e) {
        echo "not able to call function ".$functionName." in class ".$className;
        return false;
      }
    }

    public static function decodeAndRoute($parameterParts, $request) {
      if(!isset(self::$securityMap[$parameterParts])) {
        throw new Exception("incorrectly mentioned encoded Controller name");
      } else {
        $parameterParts = self::$securityMap[$parameterParts];
        $parameterParts = explode("->", $parameterParts);
        $hasDot = strpos($parameterParts, "->") !== false;
        if(!$hasDot) {
          throw new Exception("function name is not mentioned correctly in the securityMapping File");
        }
        $controllerName = $parameterParts[0];
        $functionName = $parameterParts[1];
        self::route($controllerName, $functionName, $request);
      }
    }
  }
  Route::initialize();
  #$_GET["Controller"] = "uploadFile";
  $request = new Request($_GET, $_POST, $_SERVER, $_FILES);

  if(!$request->getValueOrDefault("Controller",false)) {
    throw new Exception("no Controller argument mentioned. Cannnot route to destination");
  } else {
    $parameterParts = $request->getValue("Controller");
    #var_dump($parameterParts);
    Route::decodeAndRoute($parameterParts, $request);
  }

?>
