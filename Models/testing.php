<?php
  require_once("BaseModel.php");

  class testing extends BaseModel {
    private $name;
    private $password, $age;

    public function setAge($age) {
       $this->age = $age;
    }

    public function getAge() {
       return $this->age;
     }

    public function getName() {
       return $this->name;
    }

    public function setName($name) {
       $this->name = $name;
    }

    public function getPassword() {
       return $this->password;
    }

    public function setPassword($password) {
       $this->password = $password;
     }
  }
  $t = (new testing('testdb.testing'));
  $t->setAge(10);
  $t->setName('Tamojit');
  $t->save();
?>
