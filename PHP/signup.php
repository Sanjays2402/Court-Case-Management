<?php include('server.php') ?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Signup</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
  </head>
  <body>
      <nav class="navbar navbar-expand-sm bg-light" id="navv">
      <div class="container">
      <div class="navbar-header">
        <a href="index.php" class='navbar-brand'><img src="logo.png" style="width:38px;height:38px;">    Home</a>
      </div>
      <ul class="navbar-nav">
    <li class="nav-item">
      <a class="nav-link" href="login.php">Login</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="contact.php">Contact Us</a>
    </li>
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
    	<h4 class="card-title text-center mb-4 mt-1">Sign up</h4>
    	<hr>
    	<form action="signup.php" method="post">
        <?php include('errors.php'); ?><br>
      <div class="form-group">
        <!-- <p>Please wait for 2-4 working days to manually verify with your details with records</p> -->
    	<div class="input-group">
    		<div class="input-group-prepend">
    		    <span class="input-group-text"> <i class="fa fa-user">Name</i> </span>
    		 </div>
    		<input name="name" class="form-control" placeholder="Name" value="<?php echo $name; ?>"type="text" required>
    	</div>
    	</div>
      <div class="form-group">
      <div class="input-group">
        <div class="input-group-prepend">
            <span class="input-group-text"> <i class="fa fa-user">Email</i> </span>
         </div>
        <input name="email" class="form-control" placeholder="Email" value="<?php echo $email; ?>" type="email" required>
      </div>
      </div>
      <div class="form-group">
      <div class="input-group">
        <div class="input-group-prepend">
            <span class="input-group-text"> <i class="fa fa-user">DOB</i> </span>
         </div>
        <input name="dob" class="form-control" placeholder="Date of Birth" type="date" value="<?php echo $dob;?>" required>
      </div>
      </div>
      <div class="form-group">
    	<div class="input-group">
    		<div class="input-group-prepend">
    		    <span class="input-group-text"> <i class="fa fa-user">Designation</i> </span>
    		 </div>
    		<input name="desig" class="form-control" placeholder="Designation" type="text" value="<?php echo $desig;?>" required>
    	</div>
    	</div>
    	<div class="form-group">
    	<div class="input-group">
    		<div class="input-group-prepend">
    		    <span class="input-group-text"> <i class="fa fa-lock">Password</i> </span>
    		 </div>
    	    <input name="password" class="form-control" placeholder="password" type="password" required>
    	</div>
    	</div>
      <div class="form-group">
    	<div class="input-group">
    		<div class="input-group-prepend">
    		    <span class="input-group-text"> <i class="fa fa-user">Confirm-Password</i> </span>
    		 </div>
    		<input name="cpassword" class="form-control" placeholder="Confirm Password" type="password"required>
    	</div>
    	</div>
      <p>Please wait for 2-4 working days to manually verify your details with records</p>
      <p>Attach your ID proof and mail to "customersupport@supremecourt.gov.in" with your Email ID as Subject</p>
    	<div class="form-group">
    	<button type="submit" class="btn btn-primary btn-block" name="signup"> Sign up </button>
    	</div>
    	<!-- <p class="text-center"><a href="forgotpass.html" class="btn">Forgot password?</a></p> -->
    	</form>
    </article>
    </div>

    	<!-- </aside> <!-- col.// -->
    </div>

  </div>

    <br><br><br>


  </body>
</html>
