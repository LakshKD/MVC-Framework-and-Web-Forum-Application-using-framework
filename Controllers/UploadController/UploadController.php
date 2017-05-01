<?php
  require_once('Controller.php');
  require_once('FileUploader.php');
  class UploadController extends Controller {
      public function uploadFile(&$data) {
      $fph = new FileUploadHandler('./uploads');
      $FILE = $data->getFILE();
      $POST = $data->getPOST();
      if($fph->upload($FILE, $POST, "fileToUpload") === True) {
        $this->view->loadView('showUpload', 'php', array("location" => './uploads/'.$FILE["fileToUpload"]["name"]));
      } else {
        echo "what the fuck happened";
      }
    }
  }
?>
