<?php
  require_once("Utility.php");
  require_once('BaseModel.php');
  class WriteModel extends BaseModel {

    public function putPost($postInfo) {
        $userObj = getObjectFromArray("Models.ForumApp.PDO.Post", $postInfo);
        $userObj->setTableName('testdb.Posts');
        $userObj->setPrimaryKey('PostId');
        $postid = $userObj->save();
        #var_dump($userObj->save());
        $this->queryBuilder->insert("testdb.topic_post", array("Post_Id" => (int)$postid, "Topic_Id" => (int)$postInfo["TopicId"]));
    }

   public function deletePost($postID) {
      $this->queryBuilder->delete('testdb.topic_post', array('Post_Id' => $postID));
      $this->queryBuilder->delete('testdb.Posts', array("PostdId" => $postID));
      $commentIds = $this->queryBuilder->table('testdb.post_comment')->where('Post_Id', $postID)->pluck("Comment_Id");
      foreach ($CommentIds as $key => $value) {
        $this->queryBuilder->delete('testdb.Comments', array("CommentId" => $value));
        $this->deleteComment($value);
      }
      $this->queryBuilder->delete('testdb.post_comment', array("Post_Id" => $postID));
    }

    public function deletePosts($topicID) {
      $postIds = $this->queryBuilder->table('testdb.topic_post')->where('Topic_Id', $topicID)->pluck("Post_Id");
      foreach ($postIds as $key => $value) {
        $this->queryBuilder->delete('testdb.Posts', array("PostdId" => $value));
        $this->deletePost($value);
      }
      $this->queryBuilder->delete('testdb.topic_post', array('Topic_Id' => $topicID));
    }

    public function putComments($postID, $comments) {
      $userObj = getObjectFromArray("Models.ForumApp.PDO.Comment", $comments);
      $userObj->setTableName('testdb.Comments');
      $userObj->setPrimaryKey('CommentId');
      $commentId = $userObj->save();
      #var_dump(array("User_Id" => $_SESSION["UserId"], "Comment_Id" => $commentId));
      return $this->queryBuilder->insert("testdb.post_comment", array("Post_Id" => (int)$postID, "Comment_Id" => $commentId));
    }

     public function deleteComment($commentID) {
      $this->queryBuilder->delete('testdb.post_comment', array('Comment_Id', $commentID));
      $this->queryBuilder->delete('testdb.Comments', array('CommentId' => $commentID));
      $replyIds = $this->queryBuilder->table('testdb.comment_rply')->where('Comment_Id', $CommentId)->pluck("Reply_Id");
      foreach ($replyIds as $key => $value) {
        $this->queryBuilder->delete('testdb.Comments', array("CommentId" => $value));
      }
      $this->queryBuilder->delete('testdb.comment_rply', array("Comment_Id" => $commentID));
    }

    public function putTopic($topicInfo) {
        $userObj = getObjectFromArray("Models.ForumApp.PDO.Topic", $topicInfo);
        $userObj->setTableName('testdb.Topics');
        $userObj->setPrimaryKey('TopicId');
        $new_topicId = $userObj->save();
        $this->queryBuilder->insert("testdb.field_topic", array("Field_Id" => $topicInfo["FieldId"], "Topic_Id" => $new_topicId));

    }

    public function deleteTopics($topicID) {
      $this->queryBuilder->delete('testdb.field_topic', array('Topic_Id', $topicID));
      $this->queryBuilder->delete('testdb.Topics', array('TopicId' => $topicID));
      $this->queryBuilder->delete('testdb.topic_posts', array('TopicId' => $topicID));
      $this->deletePosts($topicId);
    }

    public function putField($fields) {
        $userObj = getObjectFromArray("Models.ForumApp.PDO.Field", $fields);
        $userObj->setTableName('testdb.Fields');
        $userObj->setPrimaryKey('FieldId');
        $userObj->save();

    }

    public function like($commentId) {
      $comment = $this->queryBuilder->table("testdb.Comments")->where("CommentId", $commentId)->first();
      $comment["Likes"] = (string)(1 + (int)$comment["Likes"]);
      return $this->queryBuilder->update("testdb.Comments", $comment, array("CommentId" => $commentId));
    }

    public function likePost($postId) {
      $comment = $this->queryBuilder->table("testdb.Posts")->where("PostId", $postId)->first();
      $comment["Likes"] = (string)(1 + (int)$comment["Likes"]);
      return $this->queryBuilder->update("testdb.Posts", $comment, array("PostId" => $postId));
    }

    public function putUser($userhandle,$password,$dob,$sex,$Interest) {
      $this->queryBuilder->insert("testdb.Users", array("UserHandle" => $userhandle,"Password" => $password,"Sex" => $sex,"DOB"=>$dob,"Interests"=>$Interest));
    }
  }
?>
