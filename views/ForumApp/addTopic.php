<html>
  <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>AddTopic </title>
         <link rel="stylesheet" href="css/normalize.css">
       <link href='http://fonts.googleapis.com/css?family=Nunito:400,300' rel='stylesheet' type='text/css'>
    </head>
    <body>
      <?php
        session_start();
      ?>

      <form action="../../Route.php" method="post">

        <h1>Add Topic</h1>

        <fieldset>
          <legend><span class="number">1</span>Enter Topic Details</legend>
          <label for="name">Topicname:</label>
          <input type="text" id="tname" name="TopicName">
          <input type="hidden" name="FieldId" value="<?php echo $_SESSION['FieldId']; ?>"/>
        </fieldset>
        <input type="hidden" name="Controller" value="addtopic"/>
        <button type="submit">Add Topic</button>
      </form>

    </body>
</html>
