<?php
  class Validator {

    public function __construct() {
    }

    public static function isSet($toCheck) {
      return isSet($toCheck);
    }

    public static function checkLength($toCheck, $low, $high) {
      if(gettype($toCheck) === "double" || gettype($toCheck) == "integer") {
        $toCheck = (string)$toCheck;
      }
      return strlen($toCheck) >= $low && strlen($toCheck) <= $high;
    }

    public static function startsWith($haystack, $needle) {
      $length = strlen($needle);
      return (substr($haystack, 0, $length) === $needle);
    }

    public static function endsWith($haystack, $needle) {
      $length = strlen($needle);
      if ($length == 0) {
          return true;
      }
      return (substr($haystack, -$length) === $needle);
    }

    public static function contains($toCheck) {
      return strpos($completePath, ".") !== false;
    }

    public static function isValueWithin($toCheck, $low, $high) {
      return $toCheck >= $low && $toCheck <= $high;
    }

    public static function isNumber($toCheck) {
      return is_numeric($toCheck);
    }
  }
  #var_dump(Validator::endsWith('a', 'a'));
?>
