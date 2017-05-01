<?php
  require_once("Controller.php");
  require_once("Utility.php");
  require_once("./Models/ForumApp/PDO/Field.php");
  require_once("./Models/ForumApp/ReadModel.php");
  require_once("./Models/ForumApp/PDO/Comment.php");
  require_once("./Models/ForumApp/PDO/Topic.php");
  require_once("./Models/ForumApp/ReadModel.php");

  class ReadController extends Controller{

    public function viewFields($request) {
      $rm = new ReadModel();
      $res = $rm->getFields($request->getValue("UserHandle"));
      $this->view->loadView("ForumApp.showFields", "php", "fields", $res);

    }

    public function viewPost($request) {
      $rm  = new ReadModel();
      $post[0]["Post"] = $rm->getPost($request->getValue("PostId"));
      $post[0]["comments"] = $rm->getComments($request->getValue("PostId"));
      $post[0]["PostId"] = $request->getValue("PostId");
      $this->view->loadView("ForumApp.showPost", "php", "post", $post);
    }

    public function viewPosts($request) {
      $topic = new Topic();
      $posts = $topic->getPosts($request->getValue("TopicId"));
      session_start();
      $_SESSION["TopicId"] = $request->getValue("TopicId");
      $this->view->loadView('ForumApp.showPosts', "php", "posts", $posts);
    }

    public function viewTopics($request) {
      $field = new Field();
      $topics = $field->getTopics($request->getValue("FieldId"));
      session_start();
      $_SESSION['FieldId'] = $request->getValue("FieldId");
      $this->view->loadView('ForumApp.showTopics', "php", "topics", $topics);
    }

    public function viewTopic($topicId) {
        $qb = new QueryBuilder();
        $user = $qb->asObject($qb->table("testdb.Topics")->where('TopicId', 'Tamojit9')->first());
        $this->view->loadView('ForumApp.UserView', "php", $user);
    }

    public function viewUser($userId) {
      $qb = new QueryBuilder();
      $user = ($qb->table("testdb.Users")->where('UserHandle', 'Tamojit9')->chunk(1)[0]);
      $this->view->loadView('ForumApp.UserView', "php", "user", $user);
    }

    public function checkUserAnsShowField($request) {
      $rm = new ReadModel();
      #echo $rm->verifyUser($request->getValue("UserHandle"), $request->getValue("password"));
      if($rm->verifyUser($request->getValue("UserHandle"), $request->getValue("password")) === "1") {
        session_start();
        $_SESSION["UserId"] = $request->getValue("UserHandle");
        $this->viewFields($request);
      } else {
        session_start();
        $_SESSION["error"] = "UserName or Password is wrong";
        $this->view->loadView("ForumApp.login", "php", "fields", $res);
      }
    }
     public function reply($request) {
      $comment = new Comment();
      $replies = $comment->getReplies($request->getValue("CommentId"));
      session_start();
      $_SESSION['replies'][$request->getValue("CommentId")] = $replies;
      $this->viewPost($request);
    }
  }
?>
