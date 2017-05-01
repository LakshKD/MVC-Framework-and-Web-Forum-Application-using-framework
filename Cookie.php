<?php
  class Cookie {
    public function set($key, $value, $timeInSecs = 86400, $directory = "/") {
      setcookie($cookie_name, $cookie_value, time() + $timeInSecs, $directorys);
    }

    public function getOrDefault($key, $fallBack) {
      if(!isset($_COOKIE[$key])) {
        return $fallBack;
      } else {
        return $_COOKIE[$key];
      }
    }

    public function get($key, $fallBack) {
      if(!isset($_COOKIE[$key])) {
        return null;
      } else {
        return $_COOKIE[$key];
      }
    }

    public function delete($key) {
      setcookie($key, "", time() - 10);
    }
  }
?>
