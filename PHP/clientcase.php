<?php include('server.php') ?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <style>
    /* body
    {
      background-color: aquamarine;
    } */
      #heading
      {
        text-align: center;
      }
      #status
      {
        color: red;
        text-align: center;
      }

    </style>
    <meta charset="utf-8">
    <title>Case details</title>
    <link href="https://fonts.googleapis.com/css?family=Lato&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
  </head>
  <body>
    <nav class="navbar navbar-expand-sm bg-light" id="navv">
      <div class="container">
      <div class="navbar-header">
        <a href="clientlogin.php" class='navbar-brand'><img src="logo.png" style="width:38px;height:38px;">    Home</a>
      </div>
      <ul class="navbar-nav">
    <!-- <li class="nav-item">
      <a class="nav-link" href="login2.html">Login</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="signup.html">Signup</a>
    </li> -->

    <li>
      <!-- <a class="nav-item nav-link disabled" href="#" tabindex="-1" aria-disabled="true">Disabled</a> -->
    </li>
  </ul>
      </div>
    </nav>


    <div id="heading">
          <h1>Case Detail</h1>
        </div>
    <div id="status">
      <h3>Current Status : Unsolved </h3>
    </div>
    <div class="container">
      <div class="container">
    <br><br><br>
    <h4>Case Type:          Civil Case</h4>
    <p></p>
    <h4>Case Number:        AC235417</h4>
    <p></p>
    <h4>FIR Number:         TN234ER</h4>
    <h4></h4>
    <h4>Advocate Name:       Sathish</h4>
    <p></p>
    <h4>Advocate Id:         5478</h4>
    <p></p>
    <h4>Dated on:            10/09/2019</h4>
    <p></p>
    <h4>Case Description:    Land Sharing issue</h4>
    <p><p></p></p>
    <h4>Next Hearing:        26/01/2020</h4>
    <p></p>
    <h4></h4>
        </div>
          </div>
      </div>
  </body>
</html>
