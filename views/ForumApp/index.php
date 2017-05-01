<html>
  <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Sign Up </title>
         <link rel="stylesheet" href="css/normalize.css">
       <link href='http://fonts.googleapis.com/css?family=Nunito:400,300' rel='stylesheet' type='text/css'>
    </head>
    <body>

      <?php
         session_start();
       ?>

      <form action="../../Route.php" method="post">

        <h1>Sign Up for Discussion Forum</h1>

        <fieldset>
          <legend><span class="number">1</span>Fill Your basic details</legend>
          <label for="name">UserHandle:</label>
          <input type="text" id="handle" name="UserHandle">

          <label for="password">Password:</label>
          <input type="password" id="password" name="Password">

          <label for="bday">Enter your birthday:</label>
          <input type="date" id="bday" name="bday">

          <label>Sex:</label>
          <input type="radio" id="male" value="male" name="Sex"><label for="Sex" class="light">Male</label><br>
          <input type="radio" id="female" value="female" name="Sex"><label for="Sex" class="light">Female</label>
        </fieldset>

        <fieldset>
          <legend><span class="number">2</span>Choose Your Interests</legend>
        </fieldset>
        <fieldset>
          <label>List of Interests:</label>
             <?php
                  require_once $_SERVER['DOCUMENT_ROOT'].'/Controllers/ForumApp/ajaxcontroller.php';
                  $ac  = new ajaxcontroller();
                  $arr = array();
                  $res = $ac->display_Interest($arr);
                   foreach ($res as $key => $value) {
                     # code...
                     echo  '<input type="checkbox" name="'.$key.'" value="'.$value.'">'.$value.'<br/>';
                   }

              ?>
        </fieldset>
        <input type="hidden" name="Controller" value="signup"/>
        <button type="submit">Sign Up</button>
      </form>
      <form action="../login.php" method="post">
        <fieldset>
        <button type="submit">Login</button>
      </fieldset>
      </form>

    </body>
</html>
