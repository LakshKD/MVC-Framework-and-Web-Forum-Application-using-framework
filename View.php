<?php
  require_once('Utility.php');

  class View {
    private $viewsLocation = "./views";

    public function __construct() {
    }

    function redirect($url) {
      ob_start();
      header('Location: '.$url);
      ob_end_flush();
      die();
    }

    public function loadView($location, $extension, $key, $data) {
      $completePath = constructPath($this->viewsLocation, $location, $extension);
      session_start();
      $_SESSION[$key] = $data;
      try {
        $this->redirect($completePath);
      } catch(Exception $e) {
        jTraceEx($e);
      }
    }
  }
  function testView() {
    (new View())->loadView('oneMore', "php", array(1 => 1));
  }
  #testView();
?>
