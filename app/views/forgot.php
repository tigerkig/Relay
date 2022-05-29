<?php
include('head.php');

if($_SESSION['loggedin'] == 1 && $_SESSION['first_login'] == 0) {
	header("Location: dashboard.php");
} elseif($_SESSION['loggedin'] == 1 && $_SESSION['first_login'] == 1 && $_SESSION['verify'] == 1) {
	header("Location: index.php?welcome");
}
echo $_SESSION['loggedin'];
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

				<div class="login-form">
					<form action="./inc/forgotRequest.inc.php" method="post">
						<input type="text" class="input" name="uid" placeholder="Username or email">

						<input type="submit" name="submit" class="submit" value="Request new password">

						<input type="hidden" name="recaptcha_response" id="recaptchaResponse">

					</form>
				</div>

				<a href="index.php"><button class="submit grey">Back to login</button></a>

			</div>

		</div>

	</body>
</html>