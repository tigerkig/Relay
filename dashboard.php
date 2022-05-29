<?php
include('header.php');
session_start();

if($_SESSION['loggedin'] == 0 && $_SESSION['first_login'] == 0 || $_SESSION['loggedin'] == 2) {
	header("Location: index.php");
}
if($_SESSION['loggedin'] == 1 && $_SESSION['first_login'] == 1) {
  	header("Location: welcome.php");
}

// testing purposes
// echo 'PHP version: ' . phpversion();
// echo '<pre>'; print_r($_SESSION); echo '</pre>';
?>


<html>
    <body>
    	<div class="contentContainer">
			<?php
	    		include './classes/chat/scripts.php';
	    		include './alerts.php';
	    	?>
			<div class="navContainer padding5">

	    		<!-- Your profile status and name -->
	    		<div id="myProfile" class="padding5">
				
				<div class="myProfile">
                    <div class="friendInfo">

						<?php $_SESSION['account']['profilePic'] ? $profilePic = $_SESSION['account']['profilePic'] : $profilePic = 'no_pig.jpg'; ?>
                        <div style="background:green">
                            <img width="40" src="./uploads/<?php echo $profilePic; ?>">
                        </div>
                        <div><?=$_SESSION['USERNAME'];?></div>
                    </div>
                      
                    <div class="topNav">
                        <button id="navSettings"><i class="fa fa-cog" aria-hidden="true"></i>
                        </button>
                        <button id="navLogout"><i class="fa fa-sign-out" aria-hidden="true"></i>
                        </button>
                    </div>
                    
                </div>

				</div>

		        <!-- Add friend by username -->
	    		<div class="addFriendInput">
		            <form id="friendRequestForm" method="post">
		                <input type="text" class="search_friend"  name="friendRequest" id="friendRequest" placeholder="Add friend by username">
		            </form>
		            <button class="submit_friend" id="submitFriendRequest" name="submitFriendRequest"><i class="fa fa-search"></i></button>
		        </div>

	    		<!-- Incoming friend requests -->
	    		<div id="incomingRequests"></div>

				<!-- List of groupchats -->
				<p class="friendListSubcategory">
					Groupchats
					<span class="groupchatIcons">
						<button id="joinPublicGroupchatIcon" class="joinPublicGroupchatIcon"><i class="fas fa-user-friends" aria-hidden="true"></i></button>
						<button id="addGroupchatIcon" class="addGroupchatIcon"><i class="fas fa-plus" aria-hidden="true"></i></button>
					</span>
				</p>
	    		<div class="friendList" id="groupchatList">
				<div class="lds-ring"><div></div></div>
				</div>

	    		<!-- List of friends -->
				<p class="friendListSubcategory">Friends</p>
	    		<div class="friendList" id="friendList">
					<div class="lds-ring"><div></div></div>
				</div>

	    	</div>
	    	
	    		<div class="chatContainer" id="chatSessionContainer">
				</div>
	    	
	    		<div class="settingsContainer">
    				<?php include './settings.php'; ?>
    			</div>

				<div class="groupchatContainer">
    				<?php include './groupchat.php'; ?>
    			</div>

				<div class="groupchatPageContainer" id="groupchatSessionContainer">
				</div>

				<!-- admin page for group -->
				<div class="groupchatAdminContainer" id="groupchatAdminPage">
					
				</div>

	    	</div>
	   

			<!-- testing modal -->
			<div class="modalOverlay" id="modalOverlay"></div>

			<div class="modalContainer" id="addGroupchatModal">
				<div class="modal animate__animated animate__faster" id="modalAnimation">
					<h1 class="modalTitle">
						<!-- title will be added dynamically -->
						<span id="modalTitle"></span>
						<button class="modalClose" id="modalClose"><i class="fas fa-times"></i></button>
					</h1>
					<p class="modalSubtitle" id="modalSubtitle">
						<!-- subtitle will be added dynamically -->
					</p>

					<div id="modalForm">
						<!-- the form will be added dynamically using jquery -->
						
					</div>

				</div>
			</div>

			
	</body>
</html>