<?php
  require_once($_SERVER['DOCUMENT_ROOT']."Controller.php");
  require_once($_SERVER['DOCUMENT_ROOT']."QueryBuilder.php");

  class ajaxcontroller extends Controller {

    public function __construct() {
      parent::__construct();
    }

    public function display_Interest($data) {

       $obj = new QueryBuilder();
      $res = ($obj->table("testdb.Fields")->pluck("FieldName"));
      return $res;
    }
  }

?>
