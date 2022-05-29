<?php
include('./inc/db.php');
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
?>

<div class="top_of_chat padding">
	<div id="accountSettings">
		<div class="title padding5">Account settings for <?php echo $_SESSION['USERNAME']; ?>
			
		</div>
	</div>
</div>


<div class="accountContainer">

	<div>
		<form action="./inc/updateEmail.inc.php" method="POST">
			<p class="formTitle">Update email</p>

			<p class="formSubtitle">Email</p>
			<input type="text" class="input" name="email" placeholder="Email address" value="<?php echo $_SESSION['EMAIL']; ?>">

			<p class="formSubtitle">Password</p>
			<input type="password" class="input" name="password" placeholder="Password">
			
			<input type="submit" name="submit" class="saveButton" value="Update email">

		</form>
	</div>

	<div>
		<form action="./inc/uploadPic.inc.php" enctype="multipart/form-data" method="POST">
			<p class="formTitle">Profile photo</p>
			
			<?php
			// I need to update this whole page
			
                $sql = "SELECT * FROM users WHERE id=?";
                $stmt = mysqli_stmt_init($conn);
                
                if(!mysqli_stmt_prepare($stmt, $sql)) {
                    $_SESSION['error'] = 'There was an error, try again later';
                } else {
                    mysqli_stmt_bind_param($stmt, "s", $_SESSION['UID']);
                    mysqli_stmt_execute($stmt);
                
                    $result = mysqli_stmt_get_result($stmt);
                    $row_count = mysqli_num_rows($result);
                    while($row = mysqli_fetch_assoc($result)) {
                        if($row['profile_pic'] == 1) {
                            echo '<img width="150" id="previewpic" src="./uploads/'.$_SESSION['UID'].'_'.$_SESSION['USERNAME'].'.jpg">';
                        } else {
                            echo '<img width="150" id="previewpic" src="./uploads/no_pic.jpg">';
                        }
                      
                    }
                }
            
			?>
		
		    <input name="file" type="file" id="file">
		    
			<input type="submit" class="changePicButton" name="profilepic" value="Upload an Image">

			
		</form>
	</div>

</div>

<div class="accountContainer">

	<div>
		<form action="./inc/updatePassword.inc.php" method="POST">
			<p class="formTitle">Update password</p>

			<p class="formSubtitle">Old password</p>
			<input type="password" class="input" name="oldPwd" placeholder="Current password">

			<p class="formSubtitle">New password</p>
			<input type="password" class="input" name="pwd" placeholder="New password">

			<p class="formSubtitle">Confirm password</p>
			<input type="password" class="input" name="repeatPwd" placeholder="Confirm password">
			
			<input type="submit" name="submit" class="saveButton" value="Update password">

		</form>
	</div>

</div>

<div class="accountContainer">

	<div>
		<form action="./inc/updatePreferences.inc.php" method="post">
			<p class="formTitle">Update preferences</p>

					<p class="formSubtitle">
						
				      	<input <?php if($_SESSION['notify'] == 1) echo 'checked'; ?> type="checkbox" name="notify">
				      	Email about news and updates
				    </p>
			
					<p class="formSubtitle">
						<input <?php if($_SESSION['security'] == 1) echo 'checked'; ?> type="checkbox" name="security">
						Turn on two factor authorization by email
					</p>

			<p>
			<input type="password" class="input" name="pwd" placeholder="Password">
			<input type="submit" name="submit" class="saveButton" value="Update preferences">
			
		</form>
	</div>

</div>

<div class="accountContainer">

	<div>
		<form action="./inc/deleteAccount.inc.php" method="post">
			<p class="formTitle">Delete account</p>

				<p>Warning! You cannot retrieve your account once it's been deleted!</p>

			<p>
			<input type="password" class="input" name="pwd" placeholder="Password">
			<input type="submit" name="submit" class="saveButton" value="Delete account">
			
		</form>
	</div>

</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

<script type="text/javascript">

  function readURL(input) {
        if (input.files && input.files[0]) {
            let reader = new FileReader();
            
            reader.onload = function (e) {
                $('#previewpic').attr('src', e.target.result);
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }
    
    $("#file").change(function(){
        readURL(this);
    });
    
</script>
		