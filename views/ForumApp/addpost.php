<html>
  <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>AddPost </title>
         <link rel="stylesheet" href="css/normalize.css">
       <link href='http://fonts.googleapis.com/css?family=Nunito:400,300' rel='stylesheet' type='text/css'>
    </head>
    <body>
      <?php
        session_start();
      ?>

      <form action="../../Route.php" method="post">

        <h1>Add Post</h1>

        <fieldset>
          <legend><span class="number">1</span>Enter Post Details</legend>
          <label for="name">motherPost:</label>
          <input type="text" id="postname" name="motherPost">
          <input type="hidden" name="TopicId" value=<?php echo $_SESSION['TopicId']; ?>/>
        </fieldset>
        <input type="hidden" name="Controller" value="addpost"/>
        <button type="submit">Add Post</button>
      </form>

    </body>
</html>
