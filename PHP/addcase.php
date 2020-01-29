<?php include('server.php')?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>File new case</title>

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
  <div class="container">

  <div class="container">

  <div class="card">
  <article class="card-body">
    <h4 class="card-title text-center mb-4 mt-1">FILE NEW CASE</h4>
    <hr>
    <form action="addcase.php" method="post">
    <div class="form-group">
      <!-- <p>Please wait for 2-4 working days to manually verify with your details with records</p> -->
    <div class="input-group">
      <div class="input-group-prepend">
          <s0pan class="input-group-text"> <i class="fa fa-user" value="<?php echo $ctype;?>">Case Type</i> </span>
       </div>
      <input name="ctype" class="form-control" placeholder="Case Type" type="text" required>
    </div>
    </div>
    <div class="form-group">
    <div class="input-group">
      <div class="input-group-prepend">
          <span class="input-group-text"> <i class="fa fa-user">Case Number</i> </span>
       </div>
      <input name="cno" class="form-control" placeholder="Case Number" type="text" required>
    </div>
    </div>
    <div class="form-group">
    <div class="input-group">
      <div class="input-group-prepend">
          <span class="input-group-text"> <i class="fa fa-user" value="<?php echo $firno;?>">FIR Number</i> </span>
       </div>
      <input name="firno" class="form-control" placeholder="FIR number" type="text" required>
    </div>
    </div>
    <div class="form-group">
    <div class="input-group">
      <div class="input-group-prepend">
          <span class="input-group-text"> <i class="fa fa-user" value="<?php echo $advname;?>">Advocate Name</i> </span>
       </div>
      <input name="advname" class="form-control" placeholder="Advocate Name" type="text" required>
    </div>
    </div>
	<div class="form-group">
    <div class="input-group">
      <div class="input-group-prepend">
          <span class="input-group-text"> <i class="fa fa-user" value="<?php echo $advid;?>">Advocate id</i> </span>
       </div>
      <input name="advid" class="form-control" placeholder="Advocate id" type="text" required>
    </div>
    </div>
	<div class="form-group">
    <div class="input-group">
      <div class="input-group-prepend">
          <span class="input-group-text"> <i class="fa fa-user" value="<?php echo $dateon;?>">Dated on</i> </span>
       </div>
      <input name="dateon" class="form-control" placeholder="Dated on" type="date" required>
    </div>
    </div>
    <div class="form-group">
    <div class="input-group">
      <div class="input-group-prepend">
          <span class="input-group-text"> <i class="fa fa-lock" value="<?php echo $descri;?>">Description</i> </span>
       </div>
        <input name="descri" class="form-control" placeholder="Description" type="textarea" required>
    </div>
    </div>
    <div class="form-group">
    <div class="input-group">
      <div class="input-group-prepend">
          <span class="input-group-text"> <i class="fa fa-user" value="<?php echo $hear;?>">Next Hearing</i> </span>
       </div>
      <input name="hear" class="form-control" placeholder="Next hearing" type="date" required>
    </div>
    </div>
    <!-- <p>Please wait for 2-4 working days to manually verify your details with records</p>
    <p>Attach your ID proof and mail to "customersupport@supremecourt.gov.in" with your Email ID as Subject</p> -->
    <div class="form-group">
    <button type="submit" class="btn btn-primary btn-block" name="regcase"> Register Case </button>
    </div>
    <!-- <p class="text-center"><a href="forgotpass.html" class="btn">Forgot password?</a></p> -->
    </form>
  </article>
  </div>

    <!-- </aside> <!-- col.// -->
  </div>

</div>


  </body>
</html>
