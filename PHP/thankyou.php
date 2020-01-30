<?php include('server.php')?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <style>
    .Thank{
      /* background-color: #011627; */
      /* color: #FDFFFC; */
      font-family: 'Gentium Basic', serif;
      text-align:center;
      padding-left: 600px;
      padding-right: 600px
    }
    </style>
    <meta charset="utf-8">
    <title>Thank you</title>
    <link href="https://fonts.googleapis.com/css?family=Fruktur&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
  </head>
  <body>
    <!-- <link rel="stylesheet" href="master.css"> -->
    <link href="https://fonts.googleapis.com/css?family=Gentium+Basic&display=swap" rel="stylesheet">
    <nav class="navbar navbar-expand-sm bg-light" id="navv">
      <div class="container">
      <div class="navbar-header">
        <a href="index.html" class='navbar-brand'>Home</a>
      </div>
      <ul class="navbar-nav">
    <li class="nav-item">
      <a class="nav-link" href="login2.html">Login</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="signup.html">Signup</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="contact.html">Contact Us</a>
    </li>
    <li>
      <!-- <a class="nav-item nav-link disabled" href="#" tabindex="-1" aria-disabled="true">Disabled</a> -->
    </li>
  </ul>
      </div>
    </nav>
    <form class="Thank" action="login.php" method="post">
      <h1>Signed up Successfully!</h1>
      <br><br>
      <h3>Sign in to continue</h3>
      <!-- <input type="submit" name="" value="Sign in"> -->
      <button type="submit" class="btn btn-primary btn-block"> Sign in  </button>
    </form>
  </body>
</html>
