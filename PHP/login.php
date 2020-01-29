<?php include('server.php') ?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Login</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>  </head>
  </head>
  <body>
      <nav class="navbar navbar-expand-sm bg-light" id="navv">
      <div class="container">
      <div class="navbar-header">
        <a href="index.php" class='navbar-brand'><img src="logo.png" style="width:38px;height:38px;">    Home</a>
      </div>
      <ul class="navbar-nav">

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

	<!-- <aside class="col-sm-4"> -->
<div class="card">
<article class="card-body">
	<h4 class="card-title text-center mb-4 mt-1">Sign in</h4>
	<hr>
	<form method="post" action="login.php">
	<?php include('errors.php'); ?><br>
	<div class="form-group">
	<div class="input-group">
		<div class="input-group-prepend">
		    <span class="input-group-text"> <i class="fa fa-user">Email</i> </span>
		 </div>
		<input name="email" class="form-control" placeholder="Email or login" type="email" required>
	</div>
	</div>
	<div class="form-group">
	<div class="input-group">
		<div class="input-group-prepend">
		    <span class="input-group-text"> <i class="fa fa-lock">Password</i> </span>
		 </div>
	    <input name="password" class="form-control" placeholder="Password" type="password" required>
	</div>
	</div>
    <!--<p>Try after some time</p>-->
	<div class="form-group">
	<button type="submit" class="btn btn-primary btn-block" name="login"> Login  </button>
	</div>
	<p class="text-center"><a href="forgotpass.php" class="btn">Forgot password?</a></p>
	<p class="text-center"><a href="signup.php" class="btn">New User? Register here</a></p>
	</form>

</article>
</div>


</div>


<br><br><br>

  </body>
</html>
