<?php
include('head.php');

if($_SESSION['loggedin'] == 1 && $_SESSION['first_login'] == 0) {
	header("Location: dashboard.php");
} elseif($_SESSION['loggedin'] == 1 && $_SESSION['first_login'] == 1 && $_SESSION['verify'] == 1) {
	header("Location: welcome.php");
}
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
				if(empty($_SESSION['token'])) {
				    $_SESSION['error'] = "Could not validate your request";
				    echo '<div class="alert error">'; 
				    	echo '<span class="closeAlert" onclick="this.parentElement.style=`display:none`">&times;</span>';
				        echo $_SESSION['error'];
				    echo '</div>';       
				    unset($_SESSION['error']);                 
				} elseif($_SESSION['verifyType'] == 'login') {
				?>

					<div class="login-form">
						<form action="./inc/verifyLogin.inc.php" method="post">
							<input type="text" class="input" name="code" placeholder="Verification code">

							<input type="submit" name="submit" class="submit" value="Verify email">

							<input type="hidden" name="recaptcha_response" id="recaptchaResponse">
							

						</form>
					</div>
				<?php
				}
				elseif($_SESSION['verifyType'] == 'forgot')
				{
				?>
					<div class="login-form">
						<form action="./inc/verifyForgot.inc.php" method="post">
							<input type="text" class="input" name="code" placeholder="Verification code">

							<input type="submit" name="submit" class="submit" value="Verify email">

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