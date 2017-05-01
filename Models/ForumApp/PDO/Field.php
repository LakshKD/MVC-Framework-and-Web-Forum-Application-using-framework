<?php
   require_once 'BaseModel.php';
  class Field extends BaseModel {
    private $FieldName;
    private $FieldId ;

    public function setFieldName($FieldName) {
       $this->FieldName = $FieldName;
     }
     public function getFieldName() {
        return $this->FieldName;
      }
    public function setFieldId($FieldId) {
       $this->FieldId = $FieldId;
     }
     public function getFieldId() {
        return $this->FieldId;
      }


    public function setTopics() {
        // Function to set topics for the given Field
    }

    public function getTopics($FieldId) {    //Function to get the Topics in a particular Field

      $TopicInfo = array();
      $res = ($this->queryBuilder->table("testdb.field_topic")->where('Field_Id',$FieldId)->chunk(-1));
      foreach ($res as $key => $value) {

        $TopicInfo[$key] = $this->queryBuilder->table("testdb.Topics")->where('TopicId',$value["Topic_Id"])->first();
      }
      return ($TopicInfo);

     }
  }
  #var_dump((new Field())->getTopics(1));
?>
