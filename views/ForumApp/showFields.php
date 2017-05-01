<ul>

<?php
  session_start();
  #var_dump($_SESSION);
  $data = $_SESSION["fields"];
  #var_dump($data);
  foreach ($data as $key => $value) {
    echo '<a href = "../../Route.php?Controller=showTopics&FieldId='.$value["FieldId"].'">'.$value["FieldName"].' </a></br>';
  }
?>

<form action="addField.php" method="post">
  <button type="submit">Add Fields</button>
</form>
</ul>
