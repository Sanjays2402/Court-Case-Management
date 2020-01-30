<?php
	session_start();

	// variable declaration
	$name = "";
	$email    = "";
	$errors = array();
	$dob = 0;
	$desig ="";
	$_SESSION['success'] = "";

	// connect to database
	$db = mysqli_connect('localhost', 'root', '', 'project');

	// REGISTER USER
	if (isset($_POST['signup'])) {
		// receive all input values from the form
		$name = mysqli_real_escape_string($db, $_POST['name']);
		$email = mysqli_real_escape_string($db, $_POST['email']);
		$dob = mysqli_real_escape_string($db, $_POST['dob']);
		$desig = mysqli_real_escape_string($db, $_POST['desig']);
		$password = mysqli_real_escape_string($db, $_POST['password']);
		$cpassword = mysqli_real_escape_string($db, $_POST['cpassword']);


	 if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
	array_push($errors, "Invalid email address ....");
	}
	if(!preg_match("/^[a-zA-Z]*$/",$name)){
		array_push($errors, "Name is invalid...");
	}
		if ($password != $cpassword) {
			array_push($errors, "The two passwords do not match");
		}

		// register user if there are no errors in the form
		if (count($errors) == 0) {
			$query="SELECT name FROM userdb WHERE email='$email'";
			mysqli_query($db, $query);
			if(mysqli_num_rows(mysqli_query($db, $query))>0){
			array_push($errors,"Already Registered...");
			}
			else{
			$password = md5($password);//encrypt the password before saving in the database
			$query = "INSERT INTO userdb (name, email, dob, desig, password)
					  VALUES('$name', '$email','$dob','$desig', '$password')";
			mysqli_query($db, $query);

			$_SESSION['email'] = $email;
			//$_SESSION['success'] = "You are now logged in";
			header('location: thankyou.php');}
		}

	}

	// ...

	// LOGIN USER
	if (isset($_POST['login'])) {
		$email = mysqli_real_escape_string($db, $_POST['email']);
		$password = mysqli_real_escape_string($db, $_POST['password']);

		if (empty($email)) {
			array_push($errors, "Username is required");
		}
		if (empty($password)) {
			array_push($errors, "Password is required");
		}

		if (count($errors) == 0) {
			$password = md5($password);
			$query = "SELECT * FROM userdb WHERE email='$email' AND password='$password'";
			$results = mysqli_query($db, $query);

			if (mysqli_num_rows($results) == 1) {
				//$_SESSION['email'] = $email;
				$query=mysqli_query($db, "SELECT name FROM userdb WHERE email='$email'");
				$row=mysqli_fetch_assoc($query);
				$_SESSION['email']=$row['name'];
				$_SESSION['success'] = "You are now logged in";
				header('location: clientlogin.php');
			}
			else {
				array_push($errors, "Wrong username/password combination");
			}
		}
	} 
	
	// register case
	if(isset($_POST['regcase'])){
		$ctype= mysqli_real_escape_string($db,$_POST['ctype']);
		$cno= mysqli_real_escape_string($db,$_POST['cno']);
		$firno= mysqli_real_escape_string($db,$_POST['firno']);
		$advname= mysqli_real_escape_string($db,$_POST['advname']);
		$advid= mysqli_real_escape_string($db,$_POST['advid']);
		$dateon= mysqli_real_escape_string($db,$_POST['dateon']);
		$descri= mysqli_real_escape_string($db,$_POST['descri']);
		$hear= mysqli_real_escape_string($db,$_POST['hear']);
		
		$query="SELECT * FROM casedb WHERE cno='$cno'";
		 mysqli_query($db,$query);
		if(mysqli_num_rows( mysqli_query($db,$query))==1){
			array_push($errors,"Case already registered..  ");
		}
		else{
			$query= "INSERT INTO casedb(ctype,cno,firno,advname,advid,dateon,descri,hear) VALUES('$ctype','$cno','$firno','$advname','$advid','$dateon','$descri','$hear')";
			$results=mysqli_query($db,$query);
			array_push($errors,"Registration completed");
			header("Location: clientlogin.php");
		}
	}

?>
<?php
	session_start();

	// variable declaration
	$name = "";
	$email    = "";
	$errors = array();
	$dob = 0;
	$desig ="";
	$_SESSION['success'] = "";

	// connect to database
	$db = mysqli_connect('localhost', 'root', '', 'project');

	// REGISTER USER
	if (isset($_POST['signup'])) {
		// receive all input values from the form
		$name = mysqli_real_escape_string($db, $_POST['name']);
		$email = mysqli_real_escape_string($db, $_POST['email']);
		$dob = mysqli_real_escape_string($db, $_POST['dob']);
		$desig = mysqli_real_escape_string($db, $_POST['desig']);
		$password = mysqli_real_escape_string($db, $_POST['password']);
		$cpassword = mysqli_real_escape_string($db, $_POST['cpassword']);


	 if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
	array_push($errors, "Invalid email address ....");
	}
	if(!preg_match("/^[a-zA-Z]*$/",$name)){
		array_push($errors, "Name is invalid...");
	}
		if ($password != $cpassword) {
			array_push($errors, "The two passwords do not match");
		}

		// register user if there are no errors in the form
		if (count($errors) == 0) {
			$query="SELECT name FROM userdb WHERE email='$email'";
			mysqli_query($db, $query);
			if(mysqli_num_rows(mysqli_query($db, $query))>0){
			array_push($errors,"Already Registered...");
			}
			else{
			$password = md5($password);//encrypt the password before saving in the database
			$query = "INSERT INTO userdb (name, email, dob, desig, password)
					  VALUES('$name', '$email','$dob','$desig', '$password')";
			mysqli_query($db, $query);

			$_SESSION['email'] = $email;
			//$_SESSION['success'] = "You are now logged in";
			header('location: thankyou.php');}
		}

	}

	// ...

	// LOGIN USER
	if (isset($_POST['login'])) {
		$email = mysqli_real_escape_string($db, $_POST['email']);
		$password = mysqli_real_escape_string($db, $_POST['password']);

		if (empty($email)) {
			array_push($errors, "Username is required");
		}
		if (empty($password)) {
			array_push($errors, "Password is required");
		}

		if (count($errors) == 0) {
			$password = md5($password);
			$query = "SELECT * FROM userdb WHERE email='$email' AND password='$password'";
			$results = mysqli_query($db, $query);

			if (mysqli_num_rows($results) == 1) {
				//$_SESSION['email'] = $email;
				$query=mysqli_query($db, "SELECT name FROM userdb WHERE email='$email'");
				$row=mysqli_fetch_assoc($query);
				$_SESSION['email']=$row['name'];
				$_SESSION['success'] = "You are now logged in";
				header('location: clientlogin.php');
			}
			else {
				array_push($errors, "Wrong username/password combination");
			}
		}
	} 
	
	// register case
	if(isset($_POST['regcase'])){
		$ctype= mysqli_real_escape_string($db,$_POST['ctype']);
		$cno= mysqli_real_escape_string($db,$_POST['cno']);
		$firno= mysqli_real_escape_string($db,$_POST['firno']);
		$advname= mysqli_real_escape_string($db,$_POST['advname']);
		$advid= mysqli_real_escape_string($db,$_POST['advid']);
		$dateon= mysqli_real_escape_string($db,$_POST['dateon']);
		$descri= mysqli_real_escape_string($db,$_POST['descri']);
		$hear= mysqli_real_escape_string($db,$_POST['hear']);
		
		$query="SELECT * FROM casedb WHERE cno='$cno'";
		 mysqli_query($db,$query);
		if(mysqli_num_rows( mysqli_query($db,$query))==1){
			array_push($errors,"Case already registered..  ");
		}
		else{
			$query= "INSERT INTO casedb(ctype,cno,firno,advname,advid,dateon,descri,hear) VALUES('$ctype','$cno','$firno','$advname','$advid','$dateon','$descri','$hear')";
			$results=mysqli_query($db,$query);
			array_push($errors,"Registration completed");
			header("Location: clientlogin.php");
		}
	}

?>
