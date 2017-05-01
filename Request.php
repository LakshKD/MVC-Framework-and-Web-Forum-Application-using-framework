<?php
  class Request {
    private $__GET, $__POST, $__SERVER, $__FILES;

    public function __construct(&$__GET, &$__POST, &$__SERVER, &$__FILES) {
      $this->__GET = $__GET;
      $this->__POST = $__POST;
      $this->__SERVER = $__SERVER;
      $this->__FILES = $__FILES;
    }

    public function getPOST() {
       return $this->__POST;
    }

    public function getGET() {
       return $this->__GET;
    }

    public function getSERVER() {
       return $this->__SERVER;
    }

    public function getFILE() {
       return $this->__FILES;
    }

    public function isGetRequest() {
      return !empty($this->__GET);
    }

    public function isPostRequest() {
      return !empty($this->_POST);
    }

    public function isAJaxRequest() {
      return isset($this->_SERVER['HTTP_X_REQUESTED_WITH']) && !empty($this->_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($this->_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
    }

    public function getIPAddress() {
      return $this->_SERVER["SERVER_ADDR"];
    }

    public function getServerName() {
      return $this->__SERVER["SERVER_NAME"];
    }

    public function getReuestMethod() {
      return $this->__SERVER["REQUEST_METHOD"];
    }

    public function getQueryString() {
      return $this->__SERVER["QUERY_STRING"];
    }

    public function getUserAgent() {
      return $this->__SERVER["HTTP_USER_AGENT"];
    }

    public function getHostHeader() {
      return $this->__SERVER["HTTP_HOST"];
    }

    public function getConnectionHeader() {
      return $this->__SERVER["HTTP_CONNECTION"];
    }

    public function isHTTPS() {
      return isset($this->__SERVER["HTTPS"]);
    }

    public function getValueOrDefault($key, $fallback) {
      if(isset($this->__GET[$key])) {
        return $this->__GET[$key];
      }
      if(isset($this->__POST[$key])) {
        return $this->__POST[$key];
      }
      return $fallback;
    }

    public function isControllerSet() {
      if(array_key_exists("Controller", $thi->__GET)) {
        return true;
      } else if(array_key_exists("Controller", $this->__POST)) {
        return true;
      }
      return false;
    }

    public function getValue($key) {
      if(isset($this->__GET[$key])) {
        return $this->__GET[$key];
      }
      return $this->__POST[$key];
    }
  }
  #$r = new Request($_POST, $_GET, $_SERVER, $_FILES);
  #$r->getValueOrDefault("wjat", "s");
?>
