<html>
  <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>AddField </title>
         <link rel="stylesheet" href="css/normalize.css">
       <link href='http://fonts.googleapis.com/css?family=Nunito:400,300' rel='stylesheet' type='text/css'>
    </head>
    <body>
      <?php
        session_start();
      ?>

      <form action="../../Route.php" method="post">

        <h1>Add Field of Your Interest</h1>

        <fieldset>
          <legend><span class="number">1</span>Enter Field Details</legend>
          <label for="name">FieldName:</label>
          <input type="text" id="fname" name="FieldName">
        </fieldset>
        <input type="hidden" name="Controller" value="addfield"/>
        <input type="hidden" name="UserHandle" value="<?php echo $_SESSION['UserId'];?>"/>
        <button type="submit">Add Field</button>
      </form>

    </body>
</html>
