<?php
    require_once 'BaseModel.php';

  class Topic extends BaseModel  {
    private $TopicName;
    private $TopicId, $UserId;

      public function __construct(){
        parent::__construct();
      }

      public function setTopicName($TopicName) {
       $this->TopicName = $TopicName;
      }
      public function getTopicName() {
        return $this->TopicName;
      }
      public function setTopicId($TopicId) {
        $this->TopicId = $TopicId;
      }
      public function getTopicId() {
         return $this->TopicId;
       }
      public function setUserId($UserId) {
        $this->UserId = $UserId;
      }
      public function getUserId() {
         return $this->UserId;
       }
    public function getPosts($TopicId) //Function to get the Posts in the Topic
    {

        $PostInfo = array();
        $res = ($this->queryBuilder->table("testdb.topic_post")->where('Topic_Id',$TopicId)->chunk(-1));

        foreach ($res as $key => $value) {
          $PostInfo[$key] = $this->queryBuilder->table("testdb.Posts")->where('PostId',$value["Post_Id"])->first();
        }
        return $PostInfo;
    }
  }

#var_dump((new Topic())->getPosts(1));


?>
