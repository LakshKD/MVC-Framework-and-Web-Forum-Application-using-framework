<?php
  class Field {
    private $fieldID, $fieldName, $fieldDescription;

    public function getFieldID() {
      return $this->fieldID;
    }

    public function getFieldName() {
       return $this->fieldName;
    }

    public function getFieldDescription() {
       return $this->fieldDescription;
     }

    public function setFieldName($fieldName) {
       $this->fieldName = $fieldName;
    }

    public function setFieldID($fieldID) {
       $this->fieldID = $fieldID;
     }

    public function setFieldDescription($fieldDescription) {
       $this->fieldDescription = $fieldDescription;
    }
  }
?>
