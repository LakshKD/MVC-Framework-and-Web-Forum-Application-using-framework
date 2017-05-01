<ul>
<?php
  session_start();
  $data = $_SESSION["topics"];
  foreach ($data as $key => $value) {
    echo '<a href = "../../Route.php?Controller=showPosts&TopicId='.$value["TopicId"].'">'.$value["TopicName"].' </a></br> ';
  }
?>
<form action="addTopic.php" method="post">
  <button type="submit">Add New Topics</button>
</form>
</ul>
