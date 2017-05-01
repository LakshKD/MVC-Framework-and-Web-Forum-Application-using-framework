<ul>
<?php
  session_start();
  $data = $_SESSION["posts"];
  foreach ($data as $key => $value) {
    echo "<p><a href = \"../../Route.php?Controller=user&UserId".$value['UserId']."\">".$value['UserId']."</a></p>";
    echo '<p><a href="../../Route.php?Controller=viewPost&PostId='.$value["PostId"].'\">'.$value["motherPost"].' </a></p></br> ';
    echo 'has '.$value["Likes"].' likes';
  }

?>
  <form action="addpost.php" method="post">
    <button type="submit">Add New Post</button>
  </form>
</ul>
