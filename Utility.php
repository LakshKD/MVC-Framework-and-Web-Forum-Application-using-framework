
<?php
  function jTraceEx($e, $seen=null) {
      $starter = $seen ? 'Caused by: ' : '';
      $result = array();
      if (!$seen) $seen = array();
      $trace  = $e->getTrace();
      $prev   = $e->getPrevious();
      $result[] = sprintf('%s%s: %s', $starter, get_class($e), $e->getMessage());
      $file = $e->getFile();
      $line = $e->getLine();
      while (true) {
          $current = "$file:$line";
          if (is_array($seen) && in_array($current, $seen)) {
              $result[] = sprintf(' ... %d more', count($trace)+1);
              break;
          }
          $result[] = sprintf(' at %s%s%s(%s%s%s)',
                                      count($trace) && array_key_exists('class', $trace[0]) ? str_replace('\\', '.', $trace[0]['class']) : '',
                                      count($trace) && array_key_exists('class', $trace[0]) && array_key_exists('function', $trace[0]) ? '.' : '',
                                      count($trace) && array_key_exists('function', $trace[0]) ? str_replace('\\', '.', $trace[0]['function']) : '(main)',
                                      $line === null ? $file : basename($file),
                                      $line === null ? '' : ':',
                                      $line === null ? '' : $line);
          if (is_array($seen))
              $seen[] = "$file:$line";
          if (!count($trace))
              break;
          $file = array_key_exists('file', $trace[0]) ? $trace[0]['file'] : 'Unknown Source';
          $line = array_key_exists('file', $trace[0]) && array_key_exists('line', $trace[0]) && $trace[0]['line'] ? $trace[0]['line'] : null;
          array_shift($trace);
      }
      $result = join("\n", $result);
      if ($prev)
          $result  .= "\n" . jTraceEx($prev, $seen);

      return $result;
  }
  function constructPath($basePath, $rawPath, $extension) {
    $locationalParts = explode(".", $rawPath);
    $completePath = $basePath;
    $hasDot = false;
    foreach ($locationalParts as $key => $value) {
      $hasDot = true;
      $completePath = $completePath."/".$value;
    }
    if($hasDot == false) {
      $completePath += $rawPath;
    }
    return $completePath.".".$extension;
  }

  function getClassName($completePath) {
    $locationalParts = explode(".", $completePath);
    return $locationalParts[count($locationalParts)-1];
  }

  function getObjectFromArray($completeClassName, $data) {
    $classObject = asObject($completeClassName);
    $reflect = new ReflectionClass($classObject);
    $props   = $reflect->getProperties();
    try {
      foreach ($props as $prop) {
        if($prop->isProtected()) continue;
        $setterMethod = "set".ucfirst($prop->getName());
        $classObject->{$setterMethod}($data[$prop->getName()]);
      }
    } catch(Exception $e) {
      echo $e->getTrace();
    }
    return $classObject;
  }

  function asObject($completeClassName) {
    try {
      require_once(constructPath(".", $completeClassName, "php"));
      $className = explode(".", $completeClassName);
      $classObject = new $className[count($className)-1];
    } catch(Exception $e) {
      print $e->getMessage();
    }
    return $classObject;
  }

  function toArray($object, $leave) {
    $reflect = new ReflectionClass($object);
    $props   = $reflect->getProperties();
    $res = array();
    foreach ($props as $prop) {
      if($prop->isProtected() || isset($leave[$prop->getName()])) continue;
      $getterMethod = "get".ucfirst($prop->getName());
      $res[$prop->getName()] = $object->{$getterMethod}();
    }
    return $res;
  }

  function testUtility() {
    var_dump(toArray(getObjectFromArray("Models.Fields.Field",
    array("fieldID" => "fuck this works", "fieldDescription" => "this is sparta",
    "fieldName" => "Sparta"))));
  }
  #testUtility();
?>
