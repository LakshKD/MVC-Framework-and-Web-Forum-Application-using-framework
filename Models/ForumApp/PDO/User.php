<?php
   require_once 'BaseModel.php';
  class User extends BaseModel {
    private $UserHandle;
    private $Password, $Sex, $DOB, $Interests;

    public function setUserHandle($UserHandle) {
       $this->UserHandle = $UserHandle;
     }
     public function getUserHandle() {
        return $this->$UserHandle;
      }
     public function setPassword($Password) {
        $this->Password = $Password;
      }
      public function getPassword() {
         return $this->Password;
       }
    public function setSex($Sex) {
       $this->Sex = $Sex;
     }
    public function getSex() {
       return $this->Sex;
     }
    public function setDOB($DOB) {
       $this->DOB = $DOB;
     }
     public function getDOB() {
        return $this->DOB;
      }
    public function setInterests($Interests) {
       $this->Interests = $Interests;
     }
     public function getInterests() {
        return $this->Interests;
      }
  }

?>
