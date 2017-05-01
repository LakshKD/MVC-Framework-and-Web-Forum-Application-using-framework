<?php
   require_once 'BaseModel.php';
  class Post  extends BaseModel{
    private $motherPost;
    private $UserId, $PostId, $Likes;


    public function setmotherPost($motherPost) {
       $this->motherPost = $motherPost;
     }
     public function getmotherPost() {
        return $this->motherPost;
      }
    public function setUserId($UserId) {
       $this->UserId = $UserId;
     }
     public function getUserId() {
        return $this->UserId;
      }
    public function setPostId($PostId) {
       $this->PostId = $PostId;
     }
     public function getPostId() {
        return $this->PostId;
      }
     public function setLikes($Likes) {
        $this->Likes = $Likes;
      }
      public function getLikes() {
         return $this->Likes;
       }
    public function getComments($PostId)
    {
       //Returns the comments on that Posts
       $CommentInfo = array();
       $res = ($this->queryBuilder->table("testdb.post_comment")->where('Post_Id',$PostId)->chunk(-1));
       foreach ($res as $key => $value) {

         $CommentInfo[$key] = $this->queryBuilder->table("testdb.Comments")->where('CommentId',$value["Comment_Id"])->first();
       }

    }
  }
  #(new Post())->getComments(1);
?>
