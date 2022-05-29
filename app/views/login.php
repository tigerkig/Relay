<?php
session_start();
include('head.php');
include './classes/alerts.php';

if($_SESSION['loggedin'] == 1 && $_SESSION['first_login'] == 0) {
	header("Location: dashboard.php");
} elseif($_SESSION['loggedin'] == 1 && $_SESSION['first_login'] == 1 && $_SESSION['verify'] == 1) {
	header("Location: index.php?welcome");
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

            <?php 
            include('alerts.php');
            ?>

            <div class="login-form">
                <form action="./inc/login.inc.php" method="post">
                    <input type="text" class="input" name="uid" placeholder="Username or email" value="<?php echo $_SESSION['tempUid']; ?>">
                    <input type="password" class="input" name="pwd" placeholder="Password">
                    <input type="submit" class="submit" value="Login">
                    <input type="hidden" name="recaptcha_response" id="recaptchaResponse">
                    
                    <div class="rememberForgot">
                        <div>
                            <label class="main">
                                <input type="checkbox" name="remember">
                                Remember me
                            </label>
                        </div>
                        <div>
                            <a href="index.php?forgot">Forgot password?</a>
                        </div>
                    </div>

                </form>
            </div>

            <a href="index.php?register"><button class="submit grey">Sign up</button></a>


        </div>

    </div>

</body>
</html>