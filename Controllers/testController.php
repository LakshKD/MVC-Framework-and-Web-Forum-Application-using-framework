<?php
  require_once("Controller.php");

  class testController extends Controller {

    public function __construct() {
      parent::__construct();
    }

    public function testing($data) {
      $this->view->loadView('myView', "html", $data);
    }
  }
?>
