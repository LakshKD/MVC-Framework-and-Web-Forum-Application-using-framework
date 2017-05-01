<?php
  require_once("BaseModel.php");
  class ReadModel extends BaseModel {

    public function __construct() {
       parent::__construct();
     }

    public function getPost($postID) {
      $post = $this->queryBuilder->table("testdb.Posts")->where('PostId', $postID)->first();
      return $post;
    }

    public function getComments($postID) {
      $CommentIds = $this->queryBuilder->table('testdb.post_comment')->where('Post_Id', $postID)->pluck("Comment_Id");
      $res = array();
      foreach ($CommentIds as $key => $value) {
        $res[$key] = $this->queryBuilder->table('testdb.Comments')->where('CommentId', $value)->first();
      }
      return $res;
    }

    public function getInterests($userId) {
      return $this->queryBuilder->table("testdb.Users")->where("UserHandle", $userId)->value("Interests");
    }

    public function getFields($userId) {
        $interests = $this->getInterests($userId);
        $interests = explode(",", $interests);
        $res = array();
        foreach ($interests as $key => $value) {
          $res[$key] = $this->queryBuilder->table("testdb.Fields")->where("FieldName", $value)->first();
        }
        return $res;
    }

    public function verifyUser($userName, $password) {
      return $this->queryBuilder->table("testdb.Users")->where("UserHandle", $userName)->AND()->where("password", $password)->count();
    }

  }
?>
