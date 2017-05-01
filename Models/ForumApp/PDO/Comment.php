<?php
    require_once 'BaseModel.php';
  class Comment extends BaseModel{
    private $UserId;
    private $CommentId, $Likes, $Content;

    public function setUserId($UserId) {
       $this->UserId = $UserId;
     }
     public function getUserId() {
        return $this->UserId;
      }
      public function setContent($Content) {
         $this->Content = $Content;
       }
       public function getContent() {
          return $this->Content;
        }
      public function setCommentId($CommentId) {
         $this->CommentId = $CommentId;
       }
       public function getCommentId() {
          return $this->CommentId;
        }
        public function setLikes($Likes) {
           $this->Likes = $Likes;
         }
         public function getLikes() {
            return $this->Likes;
          }
    public function getReplies($CommentId) {
       //Function to get the replies on a particular Comment
        $replyInfo = array();
        $res = ($this->queryBuilder->table("testdb.comment_rply")->where('CommentId',$CommentId)->chunk(-1));
        #var_dump($res);
        foreach ($res as $key => $value) {
          $replyInfo[$key] = $this->queryBuilder->table("testdb.Comments")->where('CommentId',$value["replyId"])->first();
        }
        #return $this->queryBuilder->asObjects($TopicInfo);
       var_dump($replyInfo);


     }
  }
  #(new Comment())->getReplies(1);
?>
