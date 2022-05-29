<?php
include('head.php');

if($_SESSION['loggedin'] == 1 && $_SESSION['first_login'] == 0) {
	header("Location: dashboard.php");
} elseif($_SESSION['loggedin'] == 1 && $_SESSION['first_login'] == 1 && $_SESSION['verify'] == 1) {
	header("Location: index.php?welcome");
}

$email = $_SESSION['EMAIL'];
$code = $_SESSION['code'];
?>

	<body>

		<div class="container">
		    
		    <!-- relay logo -->
			<div class="logo">
				<img class="relayLogo" src="./uploads/relay.png">
				<h2>A secure way to communicate</h2>
			</div>

			<div class="panel">

				<?php include('alerts.php'); ?>

				<?php
				if(empty($email) || empty($code)) {
		            $_SESSION['error'] = "Could not validate your request";
		            echo '<div class="alert error">'; 
				    	echo '<span class="closeAlert" onclick="this.parentElement.style=`display:none`">&times;</span>';
				        echo $_SESSION['error'];
				    echo '</div>';       
				    unset($_SESSION['error']);                 
		        } else {
		            
		        ?>
					<div class="login-form">
						<form action="./inc/changePassword.inc.php" method="post">

							<input type="password" class="input" id="psw" name="pwd" placeholder="New password">

							<div id="conditions" class="alert info">Make sure your password is atleast <b>8 characters</b> long, contains a <b>lowercase</b> and an <b>uppercase</b> letter, and a <b>number</b></div>

							<input type="password" class="input" id="confirm" name="pwdRepeat" placeholder="Confirm password">

							<div id="match" class="alert info">
		                	Both passwords must <b>match</b>
		            		</div>

							<input type="submit" name="submit" class="submit" value="Change password">

							<input type="hidden" name="recaptcha_response" id="recaptchaResponse">

						</form>
					</div>
				<?php
					
				}
				?>

				<a href="index.php"><button class="submit grey">Back to login</button></a>

			</div>

		</div>

	</body>
</html>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="inc/scripts.js"></script>