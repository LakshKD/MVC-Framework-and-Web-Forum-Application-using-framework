<?php

  require_once("Validator.php");
  class FileUploadHandler {
    private $targetUploadDirectory = "./uploads";
    private $fileType = "image";

    public function __construct($directory) {
      $this->targetUploadDirectory = $directory;
    }

    public function setFileType($fileType) {
       $this->fileType = $fileType;
     }

     public function checkType($fileName) {
       return Validator::startsWith(mime_content_type($fileName), $this->fileType) === true;
     }

     public function isFileThere($fileName) {
       if (file_exists($target_file)) {
         return true;
       }
       return false;
     }

     public function isSizeLimitedTo($fileName, $size) {
       if ($_FILES[$fileName]["size"] > $size) {
         return false;
       }
       return true;
     }

     public function upload(&$__FILES, &$__POST, $fieldName) {
       error_reporting(-1);
       $target_file = $this->targetUploadDirectory."/".basename($__FILES[$fieldName]["name"]);
       $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
       // Check if image file is a actual image or fake image
       if(isset($__POST["submit"])) {
         $check = $this->checkType((string)$__FILES[$fieldName]["tmp_name"]);
         if($check !== false) {
           if( !is_writeable(realpath($this->targetUploadDirectory)) ||!is_dir($this->targetUploadDirectory)) {
              echo "error in dir";
           }
           if (move_uploaded_file($_FILES[$fieldName]["tmp_name"], $target_file)) {
             return True;
           } else {
             return False;
           }
          } else {
            return False;
          }
       }
       return false;
     }
  }
?>
