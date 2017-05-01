<?php
  require_once('Utility.php');
  require_once('ReadController.php');
  require_once('Controller.php');
  require_once($_SERVER['DOCUMENT_ROOT'].'/Models/ForumApp/WriteModel.php');
  require_once($_SERVER['DOCUMENT_ROOT'].'/Models/ForumApp/WriteModel.php');

  class WriteController extends Controller{

public function writeComment($commentInfo) {
      $Content = $commentInfo->getValue("Content");
      $wm = new WriteModel();
      session_start();
      $res = $wm->putComments($commentInfo->getValue("PostId"), array("CommentId" => 1, "Content" => $Content, "Likes" => 0, "UserId" => $_SESSION["UserId"]));
      $rc = new ReadController();
      $rc->viewPost($commentInfo);
    }

    public function writeField($fieldInfo) {
      $obj = $fieldInfo->getPOST();
      session_start();
      $obj['UserId'] = $_SESSION['UserId'];
      $writeModelObj = new WriteModel();
      $writeModelObj->putField($obj);
      $readcontrolobj = new ReadController();
      $readcontrolobj->viewFields($fieldInfo);
    }

    public function writePost($postInfo) {
      $obj = $postInfo->getPOST();
      session_start();
      $obj['UserId'] = $_SESSION['UserId'];
      $obj['TopicId'] = $_SESSION['TopicId'];
      $obj['PostId'] = 1;
      $obj['Likes'] = 0;
      $writeModelObj = new WriteModel();
      $writeModelObj->putPost($obj);
      $readcontrolobj = new ReadController();
      $readcontrolobj->viewPosts($postInfo);
    }

    public function writeTopic($topicInfo) {
      $obj = $topicInfo->getPOST();
      var_dump($topicInfo);
      session_start();
      $obj['UserId'] = $_SESSION['UserId'];
      $obj['FieldId'] = $_SESSION['FieldId'];
      $writeModelObj = new WriteModel();
      $writeModelObj->putTopic($obj);
      $readcontrolobj = new ReadController();
      $readcontrolobj->viewTopics($topicInfo);
    }

    public function like($request) {
      $CommentId = $request->getValue("CommentId");
      $wm = new WriteModel();
      $wm->like($CommentId);
      (new ReadController())->viewPost($request);
    }

    public function likePost($request) {
      $PostId = $request->getValue("PostId");
      $wm = new WriteModel();
      $wm->likePost($PostId);
      (new ReadController())->viewPost($request);
    }

    public function writeUser($userInfo) {
      #var_dump($userInfo);
      $userhandle = $userInfo->getValue('UserHandle');
      $password = $userInfo->getValue('Password');
      $dob = $userInfo->getValue('bday');
      $sex  = $userInfo->getValue('Sex');
      for ($i=0; $i <100 ; $i++) {
        # code...
         if(isset($_POST[$i])){
             $interest = $interest." ,".$userInfo->getValue((string)$i);
         }

      }
      $writeModelObj = new WriteModel();
      $writeModelObj->putUser($userhandle,$password,$dob,$sex,$interest);

    }
  }


?>
