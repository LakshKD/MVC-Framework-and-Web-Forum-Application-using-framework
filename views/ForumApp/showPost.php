<ul>
<?php
  session_start();
  $data = $_SESSION["post"];
  foreach ($data as $key => $value) {
    echo '<p>'.$value["Post"]["motherPost"].' </p></br> ';
    echo 'has '.$value["Post"]["Likes"].' likes';
    echo "<p><a href = \"../../Route.php?Controller=likePost&PostId=".$value["Post"]['PostId']."\">Like</a></p>";
    echo "<p> Comments </p>";
    $postId = $value["Post"]['PostId'];
    foreach ($value['comments'] as $k => $v) {
      echo "<p><a href = \"../../Route.php?Controller=user&UserId=".$v['UserId']."\">".$v['UserId']."</a></p>";
      echo "<p>".$v["Content"]."</p> <p> ".$v["Likes"]." likes ";
      echo "<a href = \"../../Route.php?Controller=like&CommentId=".$v["CommentId"]."&PostId=".$postId."\">Like</a> ";
      if(!isset($_SESSION['replies'][$v["CommentId"]]))
        echo "<a href = \"../../Route.php?Controller=reply&CommentId=".$v["CommentId"]."&PostId=".$postId."\">Reply</a>";
      if(isset($_SESSION['replies'][$v["CommentId"]])) {
        foreach ($_SESSION['replies'][$v["CommentId"]] as $key => $value) {
          echo "</br></br>&nbsp&nbsp&nbsp<a href = \"../../Route.php?Controller=user&UserId=".$value['UserId']."\">".$value['UserId']."</a></p>";
          echo "&nbsp&nbsp&nbsp".$value["Content"]."</br>";
          echo "&nbsp&nbsp&nbsp"."<p> ".$value["Likes"]." likes ";
          echo "<a href = \"../../Route.php?Controller=like&CommentId=".$value["CommentId"]."&PostId=".$postId."\">Like</a></p>";
        }
        ?>
      </br>
        <form action="../../Route.php" method="post">
          <input type="hidden" name="Controller" value="doReply"/>
          <input type="hidden" name="PostId" value="<?php echo $postId;?>"/>
          <input type="hidden" name="CommentId" value="<?php echo $v["CommentId"];?>"/>
          &nbsp&nbsp&nbsp<input type="text" name="Content" />
          <input type="submit" value="reply"/>
        </form>
        <?php
      }
    }
  }
  unset($_SESSION['replies']);
?>
  <form action="../../Route.php" method="post">
    <input type="hidden" name="Controller" value="writeComment"/>
    <input type="hidden" name="PostId" value="<?php echo $_SESSION["post"][0]["Post"]['PostId'];?>"/>
    <input type="text" name="Content" />
    <input type="submit" value="Add Comment"/>
  </form>
</ul>
