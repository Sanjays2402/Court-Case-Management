
<?php
	session_start();

	if (!isset($_SESSION['email'])) {
		$_SESSION['msg'] = "You must log in first";
		header('location: login.php');
	}

	if (isset($_GET['logout'])) {
		session_destroy();
		unset($_SESSION['email']);
		header("location: index.php");
	}

?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
		<style>
		body{
			margin:0;padding:0;
background:url(loo.jpg) no-repeat center center fixed;
/* -webkit-background-size:cover;
-moz-background-size:cover;
-o-background-size:cover; */
		}
		</style>
    <meta charset="utf-8">
    <title>Client Login</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>  </head>
  </head>
  <body>
    <nav class="navbar navbar-expand-sm bg-light" id="navv">
      <div class="container">
      <div class="navbar-header">
        <a href="#" class='navbar-brand'><img src="logo.png" style="width:38px;height:38px;">    Home</a>
      </div>
      <ul class="navbar-nav">

				<li>
					<a class="nav-link" href="addcase.php">File new case</a>
				</li>
				<li class="nav-item">
		      <a class="nav-link" href="clientcase.php">Case Details</a>
		    </li>
    <li class="nav-item">
      <a class="nav-link" href="contact.php">Contact Us</a>
    </li>

    <li>
			<a class="nav-link" href="index.php?logout='1'">Logout</a>

    </li>
  </ul>
      </div>
    </nav>



		<?php  if (isset($_SESSION['email'])) : ?>
<h1 style="color:purple;text-align:center;">Welcome <strong><?php echo $_SESSION['email']; ?></strong> !</h1>
<?php endif ?>
<br><br><br><br>
<center><h3>A court is any person or institution, often as a government institution, with the authority to adjudicate legal disputes between parties and carry out the administration of justice in civil, criminal, and administrative matters in accordance with the rule of law. In both common law and civil law legal systems, courts are the central means for dispute resolution, and it is generally understood that all people have an ability to bring their claims before a court. Similarly, the rights of those accused of a crime include the right to present a defense before a court.</h3></center>
				  <!-- <a class="nav-link" href="addcase.php">File new case</a> -->
<!--
					<h3>The most important part was Ius Civile (latin for "civil law"). This consisted of Mos Maiorum (latin for "way of the ancestors") and Leges (latin for "laws"). Mos Maiorum was the rules of conduct based on social norms created over the years by predecessors. In 451-449 BC, the Mos Maiorum was written down in the Twelve Tables. Leges were rules set by the leaders, first the kings, later the popular assembly during the Republic. In these early years, the legal process consisted of two phases. The first phase, In Iure, was the judicial process. One would go to the head of the judicial system (at first the priests as law was part of religion) who would look at the applicable rules to the case. Parties in the case could be assisted by jurists. Then the second phase would start, the Apud Iudicem. The case would be put before the judges, which were normal Roman citizens in an uneven number. No experience was required as the applicable rules were already selected. They would merely have to judge the case.</h3> -->

  </body>
</html>
