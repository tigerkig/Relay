<!-- NOTE TO SELF NOV 26 2021... REMOVE ONLINE STATUS COMPLETELY, REMOVE FROM DATABSE AND ANY SCRIPT FILE -->

<script type="text/javascript">
$(document).ready(function(){

    var groupchatPageInterval;
    var updateMessagesInterval;
    var chatSession;
    
    // set global username and uid
    let usersUid;
    let usersUsername;

    setInterval(function(){

        // NAVIGATION/FRIENDS LIST RELATED        
        fetchData();

        // CHAT RELATED
        
        updateChatProfileStatus();
        initChangeReadStatus();
        //updateLastActivity();
     }, 1000);

    // function to make first character uppercase
    const capitalize = (s) => {
    if (typeof s !== 'string') return ''
    return s.charAt(0).toUpperCase() + s.slice(1)
    }

    $('#alerts').click(function() {

        // update user's last activity timestamp in database
        updateLastActivity();

        $('.alert').css('animation', 'slideToTop .5s linear 0s 1 normal forwards');
        $(this).children().fadeOut(1000);
    });

    // Update last activity timestamp in database whenever the user interacts with the website
    updateLastActivity = function () {

        $.post("./inc/updateLastActivity.inc.php", // url of the page on server
        { // Data Sending With Request To Server
            // friendRequest:vfriendRequest //msg being sent
        },
        function(response,status){
            // var response = JSON.parse(response);
        });
        
    }

    // Submit a friend request via post function, returns data 
    addFriend = function (username = null) {

        // update user's last activity timestamp in database
        updateLastActivity();
        let friendRequest;
        if(!username) {
            friendRequest = $("#friendRequest").val();
        } else {
            friendRequest = username;
        }
        
        var alert = '';

        $.post("./inc/addFriend.inc.php", // url of the page on server
        { // Data Sending With Request To Server
            friendRequest:friendRequest //msg being sent
        },
        function(response,status){
            var response = JSON.parse(response);
            alert += `
            <div class="alert ${response['type']}">
                <span class="closeAlert">&times;</span>
                ${response['message']}
            </div>`
            $('#alerts').html(alert);
            $("#friendRequestForm")[0].reset(); 
        });
        
    }

    // if "enter key" is press while typing in message input
    $('#friendRequest').on('keypress', function (e) {
         if(e.which === 13){
            e.preventDefault(); // needed to prevent page refresh
            //Disable textbox to prevent multiple submit
            $(this).attr("disabled", "disabled");
            addFriend(); // forward msg to submitMsg function   
            //Enable the textbox again if needed
            $(this).removeAttr("disabled");
         }
    });

    // Send message through post call via click
    $("#submitFriendRequest").click(function(){
        addFriend();
    });

    //////////////////////////////////////

    // UPDATE PROFILE STATUS
    fetchData = function () {
        $.ajax({
            type:'POST',
            url:'./inc/fetchData.inc.php',
            success:function(data){
                
                var myProfile = '';
                var incomingRequests = '';
                var friendsList = '';
                var groupchatsList = '';
                var friends = JSON.parse(data);
                // console.log(friends)

                // set username and uid to global vars
                usersUid = friends['account']['id'];
                usersUsername = friends['account']['username'];

                // Refresh my profile stats
                myProfile += `
                <div class="myProfile">
                    <div class="friendInfo">

                        <div style="background:${friends['account']['status_color']}">
                            <img width="40" src="./uploads/${friends['account']['profilePic']}">
                        </div>
                        <div>${friends['account']['username']}</div>
                    </div>
                      
                    <div class="topNav">
                        <button id="navSettings"><i class="fa fa-cog" aria-hidden="true"></i>
                        </button>
                        <button id="navLogout"><i class="fa fa-sign-out" aria-hidden="true"></i>
                        </button>
                    </div>
                    
                </div>`;
                $('#myProfile').html(myProfile);
                
                // Navigation links
                $("#navSettings").click(function(){

                    // update user's last activity timestamp in database
                    updateLastActivity();

                    $('.settingsContainer').css('display','flex');
                    $(".groupchatPageContainer").css('display','none');
                    $('.groupchatAdminContainer').css('display','none');
                    $('.chatContainer').css('display','none');

                    // end any intervals we arent using currently
                    clearInterval(groupchatPageInterval);
                    clearInterval(updateMessagesInterval);
                });
                $("#navLogout").click(function(){
                    window.location = "./inc/logout.inc.php";
                });

                // Display incoming friend requests
                $.each(friends['requests'], function(k, v) {
                    incomingRequests += `
                    <div class="friendRequest">
                        <p>${v.username} sent a friend request</p>
                        <div>
                            <form method="post">
                                <input type="hidden" id="sender_id" name="sender_id" value="${v.sender_id}">
                                
                            </form>
                            <button id="acceptRequest" name="accept" class="requestButton green">Accept</button>
                            <button id="declineRequest" name="decline" class="requestButton error">Decline</button>
                        </div>
                    </div>`;
                });
                $('#incomingRequests').html(incomingRequests);
                // Respond to a friend request via post function, returns data 
                requestResponse = function (response) {

                    // update user's last activity timestamp in database
                    updateLastActivity();

                    var vsender_id = $("#sender_id").val();
                    var vresponse = response;
                    //var vdecline = response;
                    var alert = '';

                    $.post("./inc/friendRequest.inc.php", // url of the page on server
                    { // Data Sending With Request To Server
                        sender_id:vsender_id, //msg being sent
                        response:vresponse
                    },
                    function(data,status){
                        var data = JSON.parse(data);
                        alert += `
                        <div class="alert ${data['type']}">
                            <span class="closeAlert">&times;</span>
                            ${data['message']}
                        </div>`
                        $('#alerts').html(alert);
                    });    
                }

                // Send message through post call via click
                $("#acceptRequest, #acceptRequestMembersList").click(function(){
                    requestResponse('accept');
                });
                $("#declineRequest, #declineRequestMembersList").click(function(){
                    requestResponse('decline');
                });

                if(friends['groupchats']) {
                    // insert a list of groupchats
                    $.each(friends['groupchats'], function(k, v) {
                        var chatSession = [friends['account']];
                        
                        chatSession[1] = friends['groupchats'][k];
                        
                        var object = encodeURIComponent(JSON.stringify(chatSession));
                            
                            groupchatsList += `
                            <div>
                                <button class="startGroupChat" data-object="${object}">
                                    <div class="friendInfo">
                                        
                                    <div class="friendListUsername">
                                        ${v.name}
                                    </div>
                                </button>
                            </div>`;
                        
                    });         
                    $('#groupchatPageContainer').html(groupchatsList);

                } else if(friends['groupchats'] === null) {
                    $('#groupchatPageContainer').html('poo');
                
                    
                }
                $('#groupchatList').html(groupchatsList);

                // find public group chats
                $(".joinPublicGroupchatIcon").click(function(){
                
                    // update user's last activity timestamp in database
                    updateLastActivity();
                    $('#modalOverlay').css('display','block');

                    // add animation
                    $("#modalAnimation").addClass("animate__zoomIn");
                    $("#modalAnimation").removeClass("animate__zoomOut");

                    // modal title
                    $("#modalTitle").html('Public group chats');

                    // modal subtitle
                    $("#modalSubtitle").html('Below is a list popular public group chats that are free to join');

                    // modal form
                    if(friends['publicGroupchats']) {
                        // insert a list of groupchats
                        $('#modalForm').html('<div clas="publicGroupchats" id="publicGroupchatsList"></div>');
                        $.each(friends['publicGroupchats'], function(k, v) {

                            var joinGroupchat = [friends['account']];
                        
                            joinGroupchat[1] = friends['publicGroupchats'][k];
                            var object = encodeURIComponent(JSON.stringify(joinGroupchat));

                            $('#publicGroupchatsList').append(`
                                <div class="publicGroupchatContainer">
                                    <p class="publicGroupchatTitle">${v.name}</p>
                                    <p class="publicGroupchatDesc">${v.desc}</p>
                                    <p class="publicGroupchatDetails">(${v.memberCount} members)</p>
                                    <button data-object="${object}" class="publicGroupchatJoin" id="publicGroupchatJoin">Join</button>
                                </div>
                            `)
                        });
                        // $('#groupchatSessionContainer').html('groupchatPageContainer');
                    } else {
                        $('#groupchatSessionContainer').html('');
                    }
                    $('#friendList').html(friendsList);
                    $('#addGroupchatModal').css('display','block');
                });

                // join group chat button
                $(".publicGroupchatJoin").off().click(function(){
                    // update user's last activity timestamp in database
                    updateLastActivity();

                    var object = $(this).data('object');
                    object = JSON.parse(decodeURIComponent(object));
                    joinPublicGroupchat(object[1]['id']);
                });

                // Join public group chat
                function joinPublicGroupchat(groupid) {
                    $.ajax({
                        // url:"./inc/getChatMessages.inc.php",
                        url:"./inc/joinPublicGroupchat.inc.php",
                        method:"POST",
                        data:{groupid:groupid},
                        success:function(data){
                            var data = JSON.parse(data);
                            var messages = '';
                            var alert = '';
                            if(data != null) {
                                alert += `
                                <div class="alert ${data['type']}">
                                    <span class="closeAlert">&times;</span>
                                    ${data['message']}
                                </div>`
                                $('#alerts').html(alert)
                            }
                            if(data['type'] === 'success') {
                                closeModal();
                            }
                        }
                    })
                    
                };
                //////////////////////


                // groupchat modal
                $("#addGroupchatIcon").click(function(){

                    // update user's last activity timestamp in database
                    updateLastActivity();

                    // add animation
                    $("#modalAnimation").addClass("animate__zoomIn");
                    $("#modalAnimation").removeClass("animate__zoomOut");

                    // modal title
                    $("#modalTitle").html('Create a group chat');

                    // modal subtitle
                    $("#modalSubtitle").html('A group chat is where you can communicate securely with friends or colleagues');

                    // modal form
                    $('#modalForm').html(`
                        <label class="modalFormLabel" for="name">Groupchat name</label>
						<input id="modalGroupchatName" class="modalFormInput" type="text" name="name"/>

                        <label class="modalFormLabel" for="desc">Groupchat description</label>
						<input id="modalGroupchatDesc" class="modalFormInput" type="text" name="desc"/>
						
						<label class="modalFormLabel" for="type">Groupchat privacy</label>
						<select id="modalGroupchatType" class="modalFormInput" name="type">
							<option value="0">Private</option>
							<option value="1">Public</option>
						</select>

						<button class="modalSubmit" id="modalGroupchatSubmit" type="submit">Create</button>
                    `)

                    $('#modalOverlay').fadeIn();
                    $('#addGroupchatModal').css('display','block');
                });

                $("#modalGroupchatSubmit").click(function() {
                    createGroupchat();
                });

                closeModal = function () {
                    // update user's last activity timestamp in database
                    updateLastActivity();

                    // remove animation
                    $("#modalAnimation").removeClass("animate__zoomIn");
                    $("#modalAnimation").addClass("animate__zoomOut");

                    // fade out modal overlay
                    $('#modalOverlay').fadeOut();

                    setTimeout(function() { 
                        $('#addGroupchatModal').css('display','none');
                    }, 250);
                }

                // Submit a chat message
                createGroupchat = function () {
                    
                    // update user's last activity timestamp in database
                    updateLastActivity();

                    var alert = '';
                    var name = $("#modalGroupchatName").val();
                    var desc = $("#modalGroupchatDesc").val();
                    var type = $("#modalGroupchatType").val();

                    $.post("./inc/startGroupchat.inc.php", // url of the page on server
                    { // Data Sending With Request To Server
                        name:name,
                        desc:desc,
                        type:type
                    },
                    function(response,status){     
                        var response = JSON.parse(response);
                        if(response != null) {
                            alert += `
                            <div class="alert ${response['type']}">
                                <span class="closeAlert">&times;</span>
                                ${response['message']}
                            </div>`
                            $('#alerts').html(alert)
                        }
                        if(response['type'] === 'success') {
                            closeModal();
                        }
                    });
                    $("#modalGroupchatName")[0].reset();
                    
                }


                $("#modalOverlay").click(function() {
                    closeModal();
                })
                $("#modalClose").click(function() {
                    closeModal();  
                })
                
                // end of groupchat modal

                if(friends['friends'] != null) {
                    // Insert a list of friends
                    $.each(friends['friends'], function(k, v) {
                        chatSession = [friends['account']];
                        
                        chatSession[1] = friends['friends'][k];
                        var object = encodeURIComponent(JSON.stringify(chatSession));
                        
                        friendsList += `
                        <div>
                            <button class="start_chat" data-object="${object}">
                                <div class="friendInfo">
                                    <div style="background:${v.status_color}">
                                        <img width="40" src="./uploads/${v.profilePic}">
                                    </div>
                                <div class="friendListUsername">
                                    ${v.username}
                                </div>
                            </button>
                        </div>`;

                    });         
                    $('#friendList').html(friendsList);
                } else {
                    $('#friendList').html('');
                }
                // INITIATE CHAT SESSION
                $(".start_chat, .MemberListChatFriend").click(function(){

                    // update user's last activity timestamp in database
                    updateLastActivity();

                    // end any intervals we arent using currently
                    clearInterval(groupchatPageInterval);
                    clearInterval(updateMessagesInterval);

                    // set new interval
                    updateMessagesInterval = setInterval(() => {
                        updateMessages();
                    }, 1000);

                    var object = $(this).data('object');
                    
                    object = JSON.parse(decodeURIComponent(object));
                    // console.log('startchatobj ', object)
                    $(".chatContainer").css('display','flex');
                    $(".settingsContainer").css('display','none');
                    $(".groupchatPageContainer").css('display','none');
                    $('.groupchatAdminContainer').css('display','none');
                    initChat(object);
                    changeReadStatus(object[1]['id']);
                });
                // INITIATE CHAT SESSION
                $(".startGroupChat").click(function(){

                    // update user's last activity timestamp in database
                    updateLastActivity();

                    // end any intervals we arent using currently
                    clearInterval(groupchatPageInterval);
                    clearInterval(updateMessagesInterval);

                    // set new interval
                    updateMessagesInterval = setInterval(() => {
                        updateMessages();
                    }, 1000);

                    var object = $(this).data('object');
                    
                    object = JSON.parse(decodeURIComponent(object));
                    $(".groupchatPageContainer").css('display','none');
                    $(".chatContainer").css('display','flex');
                    $(".settingsContainer").css('display','none');
                    $('.groupchatAdminContainer').css('display','none');
                    initGroupChat(object);
                    changeReadStatus(object[1]['id']);
                });
            }
        });
    }
    
// CHAT RELATED
    // INITIALIZE GROUPCHAT BOX
    function initGroupChat(objects) {
        var updateObjects = encodeURIComponent(JSON.stringify(objects));
        var chatSession = `
        <div class="top_of_chat padding">
            <div id="profileStatus" data-objects="${updateObjects}">          
                     
            </div>
            
        </div>

        <div class="chat" id="chat">
            <div class="chat_history padding" id="chatContainer" data-objects="${updateObjects}">
                <span>&nbsp</span>
            </div>      
        </div>

        <div class="msg_container">
            <form id="form" method="post">
                <input hidden type="text" name="receiver_id" id="receiver_id" value="${objects[1]['members']}">
                <input hidden type="text" name="groupid" id="groupid" value="${objects[1]['id']}">
                <input hidden type="text" name="type" id="type" value="${objects[1]['type']}">

                <input type="text" class="msg_input" name="msg" id="msg" placeholder="Message the chat">
                
            </form>
            <button id="submit" class="msg_submit"><i class="fa fa-paper-plane"></i></button>
        </div>
        `;
        $('.chatContainer').html(`${chatSession}`);
        scrollToBottom('chatContainer');
        fetchChatMessages(objects[1]['id'],objects[1]['type']);
        fetchChatProfileStatus(objects[1]['id'],objects[1]['type']);
    }
    // INITIALIZE CHAT BOX
    function initChat(objects) {
        // console.log('initchat ' ,objects)
        var updateObjects = encodeURIComponent(JSON.stringify(objects));

        var chatSession = `
        <div class="top_of_chat padding">
            <div id="profileStatus" data-objects="${updateObjects}">          
                     
            </div>
            
        </div>

        <div class="chat" id="chat">
            <div class="chat_history padding" id="chatContainer" data-objects="${updateObjects}">
                <span>&nbsp</span>
            </div>      
        </div>

        <div class="msg_container">
            <form id="form" method="post">
                
                <input hidden type="text" name="receiver_id" id="receiver_id" value="${objects[1]['id']}">
                <input hidden type="text" name="type" id="type" value="${objects[1]['type']}">

                <input type="text" class="msg_input" name="msg" id="msg" placeholder="Message the chat">
                
            </form>
            <button id="submit" class="msg_submit"><i class="fa fa-paper-plane"></i></button>
        </div>
        `;
        $('.chatContainer').html(`${chatSession}`);
        scrollToBottom('chatContainer');
        fetchChatMessages(objects[1]['id'],objects[1]['type']);
        fetchChatProfileStatus(objects[1]['id'],objects[1]['type']);
    }

    // FETCH CHAT PROFILE STATUS
    function fetchChatProfileStatus(chat_userid,type) {
        $.ajax({
            url:"./inc/getChatProfileStatus.inc.php",
            method:"POST",
            data:{chat_userid:chat_userid,type:type},
            success:function(data){
                var data = JSON.parse(data);
                var profile = '';
                var alert = '';
                if(data == null) {
                    alert += `
                    <div class="alert error">
                        <span class="closeAlert">&times;</span>
                        You do not have access to chat with this user
                    </div>`
                    $('#alerts').html(alert);
                    $('.chatContainer').empty();
                    $('.settingsContainer').css('display','flex');
                    $('.chatContainer').css('display','none');
                    $(".groupchatPageContainer").css('display','none');
                    $('.groupchatAdminContainer').css('display','none');
                } else {

                    // if chat session is peer to peer
                    if(data['profile']['type'] === '0') {
                        profile += `
                        <div class="friendInfo" id="friendInfo">
                            <div style="background:${data['profile']['status_color']}">
                                <img width="40" src="./uploads/${data['profile']['profilePic']}">
                            </div>
                            <div>
                                ${data['profile']['username']}${data['profile']['active_now']}
                            </div>
                        </div>
                        <div class="topNav">
                            <form method="post">
                                <input hidden type="text" name="uid" id="uidToRemove" value="${data['profile']['id']}">
                            </form>
                            <button id="removeFriend" class="removeFriend error">Remove friend</button>
                        </div>
                        `;
                        
                    } else {
                        var groupchatObject = encodeURIComponent(JSON.stringify(data));
                        profile += `
                        <div class="friendInfo" id="friendInfo">
                            <div class="groupchatPage" data-objects="${groupchatObject}"> 
                                ${data['profile']['name']} (${data['profile']['memberCount']} members)
                            </div>
                        </div>
                        <div class="topNav">
                            <form method="post">
                                <input hidden type="text" name="uid" id="groupidToRemove" value="${data['profile']['id']}">
                            </form>
                            <button id="leaveGroupchat" class="removeFriend error">Leave group</button>
                        </div>
                        `;
                    }

                    // add profile status to page
                    $('#profileStatus').html(profile);

                    // groupchat page link
                    $('.groupchatPage').click(function() {

                        // update user's last activity timestamp in database
                        updateLastActivity();

                        // end any intervals we arent using currently
                        clearInterval(groupchatPageInterval);
                        clearInterval(updateMessagesInterval);

                        var object = $(this).data('objects');
                        object = JSON.parse(decodeURIComponent(object));
                        // console.log(object)
                        $('.groupchatPageContainer').html('');
                        $(".groupchatPageContainer").css('display','flex');
                        $(".chatContainer").css('display','none');
                        $(".settingsContainer").css('display','none');
                        $('.groupchatAdminContainer').css('display','none');
                        initGroupchatPage(object);

                        // changeReadStatus(object[0]['id'],object[1]['id']);

                    })
                    
                    // Remove friend
                    $("#removeFriend").click(function(){
                        
                        // update user's last activity timestamp in database
                        updateLastActivity();
                        
                        var uid = $("#uidToRemove").val();
                        
                        if(!uid==''){
                            $.post("./inc/removeFriend.inc.php", // url of the page on server
                            { // Data Sending With Request To Server
                                uid:uid //msg being sent
                            },
                            function(response,status){
                                var response = JSON.parse(response);
                                alert += `
                                <div class="alert ${response['type']}">
                                    <span class="closeAlert">&times;</span>
                                    ${response['message']}
                                </div>`
                                $('#alerts').html(alert);
                                fetchData();
                                $('.chatContainer').empty();
                                $(".groupchatPageContainer").css('display','none');
                                $('.settingsContainer').css('display','flex');
                                $('.chatContainer').css('display','none');
                                $('.groupchatAdminContainer').css('display','none');

                                // end any intervals we arent using currently
                                clearInterval(groupchatPageInterval);
                                clearInterval(updateMessagesInterval);
                            })
                        }
                    })

                    // leave group
                    $("#leaveGroupchat").click(function(){
                        
                        // update user's last activity timestamp in database
                        updateLastActivity();
                        
                        var groupid = $("#groupidToRemove").val();
                        
                        if(!groupid==''){
                            $.post("./inc/leaveGroupchat.inc.php", // url of the page on server
                            { // Data Sending With Request To Server
                                groupid:groupid //msg being sent
                            },
                            function(response,status){
                                var response = JSON.parse(response);
                                alert += `
                                <div class="alert ${response['type']}">
                                    <span class="closeAlert">&times;</span>
                                    ${response['message']}
                                </div>`
                                $('#alerts').html(alert);
                                fetchData();
                                if(response['type'] === 'success') {
                                    $('.chatContainer').empty();
                                    $(".groupchatPageContainer").css('display','none');
                                    $('.settingsContainer').css('display','flex');
                                    $('.chatContainer').css('display','none');
                                    $('.groupchatAdminContainer').css('display','none');

                                    // end any intervals we arent using currently
                                    clearInterval(groupchatPageInterval);
                                    clearInterval(updateMessagesInterval);
                                }
                                
                            })
                        }
                    })
                }     
            }
        })
    }
    //////////////////////

    

// group chat page
function initGroupchatPage(objects) {

    var groupid = objects['profile'].id;

    var groupchatPageData = `
    <div class="top_of_chat padding">
        <div class="friendInfo" id="friendInfo">

            <div class="startGroupchat" id="groupchatPage" data-objects="${objects}"> 
                Back to <span id="groupchatPageName">${objects['profile'].name}</span> chat
            </div>
        </div>
        <div class="topNav">
            <form method="post">
                <input hidden type="text" name="uid" id="groupidToRemove" value="${objects['profile'].id}">
            </form>
            <button id="leaveGroupchat" class="removeFriend error">Leave group</button>
        </div>
    </div>

    <div class="accountContainer">

        <div>
        
            <p class="formTitle" id="groupchatInformationTitle">Group Chat Information</p>

            <p class="formSubtitle">Group Chat Name</p>
            <input disabled type="text" class="input" id="groupchatPageNameInfo" value="${objects['profile'].name}">
            <p class="formSubtitle">Description</p>
            <input disabled type="text" class="input" id="groupchatPageDescInfo" value="${objects['profile'].desc}">
            
            <p class="formSubtitle">Group Chat Privacy</p>
            <input disabled type="text" class="input" id="groupchatPagePrivacyInfo" value="${objects['profile'].typeOf}">
                
        </div>

    </div>
    <div class="accountContainer">

        <div>
            
            <p class="formTitle">Members List (<span id="groupchatPageMemberCount">${objects['profile'].memberCount}</span> members)</p>

            <div id="groupchatPageMemberList" class="memberListContainer">
                <div class="lds-ring"><div></div></div>
            </div>
            
        </div>

    </div>
    `;
    $('.groupchatPageContainer').html(`${groupchatPageData}`);

    fetchGroupchatInfo(objects['profile'].id);

}

// fetches groupchat data and changes data real time
function fetchGroupchatInfo(groupid) {
    
    // fetch group info every 5000ms
    groupchatPageInterval = setInterval(() => {

        $.post("./inc/fetchGroupInfo.inc.php", // url of the page on server
        { // Data Sending With Request To Server
            groupid:groupid
        },
        function(response,status){
            var response = JSON.parse(response);
            // console.log(response)
            let membersList = '';
            let memberPic;
            let memberListButton;
            let memberUsername;

            $('#groupchatPageMemberCount').html(response['currentGroupchat'].memberCount);
            $('#groupchatPageName').html(response['currentGroupchat'].name);
            $('#groupidToRemove').val(response['currentGroupchat'].id);
            $('#groupchatPageNameInfo').val(response['currentGroupchat'].name);
            $('#groupchatPagePrivacyInfo').val(capitalize(response['currentGroupchat'].typeOf));
            
            // group chat page member list
            $.each(response['currentGroupchat'].members, function(k, v) {
                v.profilePic === '1' ? memberPic = `${v.id}_${v.username}.jpg` : memberPic = `no_pic.jpg`;
    
                // data-object to be sent
                var object = {
                    1: {
                        id: v.id,
                        type: 0
                    }
                }
                
                object = encodeURIComponent(JSON.stringify(object));
                let username = v.username;
                // username = encodeURIComponent(JSON.stringify(v.username));

                // check if you are admin and create admin settings button
                if(v.isAdmin === 1 && usersUid === v.id) {
                    $('#groupchatInformationTitle').html(`
                    Group Chat Information
                    <button class="groupAdminPageButton" id="groupAdminPageButton"><i class="fas fa-users-cog"></i></button>
                    `)
                    // console.log('admin: ',response['currentGroupchat'])
                }

                

                // check if user is a friend or themself
                if(v.isFriend === 0) {
                    memberListButton = `<button class="memberListItemButton greenButton MemberListChatFriend" data-object="${object}">Chat</button>`;
                } else if(v.isFriend === 1) {
                    memberListButton = `<button class="memberListItemButton blueButton MemberListAddFriend" data-object="${username}">Add Friend</button>`;
                } else {
                    memberListButton = '';
                }

                //cancelFriendRequest
                let cancelFriendRequest = v.id;

                // if user sent a member a friend request change button to cancel button
                if(v.sentFriendRequest) {
                    memberListButton = `<button class="memberListItemButton cancelButton MemberListCancel" data-object="${v.id}">Cancel</button>`;
                } else if (v.receivedFriendRequest) {
                    memberListButton = `
                    <div class="memberListButtonContainer">
                        <button id="acceptRequestMembersList" class="memberListItemAction MemberListAccept greenButton" data-object="${username}"><i class="fas fa-check" aria-hidden="true"></i></button>
                        <button id="declineRequestMembersList" class="memberListItemAction MemberListDecline redbutton" data-object="${username}"><i class="fas fa-times" aria-hidden="true"></i></button>
                    </div>
                    `;
                }
                
                // check if user is admin
                v.isAdmin === 1 ? memberUsername = `<p class="isAdmin">${v.username}</p>` : memberUsername = `<p>${v.username}</p>`;

                // v.isAdmin === 1 && friends['account'].username === 'dallan' ? $('#imadmin').val('hi') : null
                membersList += `
                    <div class="memberListItem">
                        <img src="./uploads/${memberPic}"/>
                        ${memberUsername}
                        ${memberListButton}
                    </div>
                `;
                $('#groupchatPageMemberList').html(`${membersList}`);

                
                // send a member a friend request
                $(".MemberListAddFriend").click(function(){
                    // update user's last activity timestamp in database
                    updateLastActivity()

                    let uid = $(this).data('object');
                    addFriend(uid)
                    
                });
                // cancel outgoing friend request
                $(".MemberListCancel").click(function(){

                    // update user's last activity timestamp in database
                    updateLastActivity()
                    // consoleolog('a+d/pppptember to cancel outougoingrequest')

                    // cancel friend request that the user sent 
                    let uid = $(this).data('object');
                    cancelFriendRequest(uid);
                    // console.log(uid)
                });
                // cancel outgoing friend request
                $(".MemberListAccept").click(function(){

                // update user's last activity timestamp in database
                updateLastActivity()
                // consoleolog('a+d/pppptember to cancel outougoingrequest')

                // cancel friend request that the user sent 
                let uid = $(this).data('object');

                // console.log(uid)
                // cancelFriendRequest(uid);
                });

                // Submit a friend request via post function, returns data 
                cancelFriendRequest = function (uid) {
                    let alert = '';
                    $.post("./inc/cancelFriendRequest.inc.php", // url of the page on server
                    { // Data Sending With Request To Server
                        uid:uid
                    },
                    function(response,status){     
                        var response = JSON.parse(response);
                        if(response != null) {
                            alert += `
                            <div class="alert ${response['type']}">
                                <span class="closeAlert">&times;</span>
                                ${response['message']}
                            </div>`
                            $('#alerts').html(alert)
                        } else {
                            alert += `
                            <div class="alert ${response['type']}">
                                <span class="closeAlert">&times;</span>
                                ${response['message']}
                            </div>`
                            $('#alerts').html(alert)
                        }
                    });
                }
                  
            });

            // if the admin settings button is clicked open up new page
            $('#groupAdminPageButton').click(function(){
                console.log('hiiiiii admin')
                console.log(usersUsername)

                // update user's last activity timestamp in database
                updateLastActivity();

                $('.chatContainer').empty();
                $(".groupchatPageContainer").css('display','none');
                $('.settingsContainer').css('display','none');
                $('.groupchatAdminContainer').css('display','flex');
                $('.chatContainer').css('display','none');

                // var object = $(this).data('objects');
                // object = JSON.parse(decodeURIComponent(object));
                // console.log('admin ', object)

                // var groupid = objects['profile'].id;

                var groupchatAdminPage = `
                <div class="top_of_chat padding">
                    <div class="friendInfo" id="friendInfo">

                        <div id="groupchatPage" data-objects="id"> 
                            Back to <span id="groupchatPageName">groupchat name</span> chat
                        </div>
                    </div>
                    <div class="topNav">
                        <form method="post">
                            <input hidden type="text" name="uid" id="groupidToRemove" value="id">
                        </form>
                        <button id="leaveGroupchat" class="removeFriend error">Leave group</button>
                    </div>
                </div>

                <div class="accountContainer">

                    <div>
                        <form id="updateGroupchatSettings" method="post">
                            <p class="formTitle" id="groupchatInformationTitle">Group Chat Settings</p>

                            <label for="groupchatName" class="formSubtitle">Group Chat Name</label>
                            <input type="text" name="name" class="input" id="groupchatPageNameInfo" value="${response['currentGroupchat'].name}">
                            
                            <label for="groupchatDesc" class="formSubtitle">Description</label>
                            <input type="text" name="desc" class="input" id="groupchatPageDescInfo" value="${response['currentGroupchat'].desc}">
                            
                            <label for"groupchatPrivacy" class="formSubtitle">Group Chat Privacy</label>
                            <select name="privacy" class="input" id="groupchatPagePrivacyInfo">
                                <option value="0">Private</option>
                                <option value="1">Public</option>
                            </select>
                        </form>
                        <button id="saveGroupchatSettings" class="saveButton">Update</button>
                            
                    </div>

                </div>
                <div class="accountContainer">

                    <div>
                        
                        <p class="formTitle">Members List (<span id="groupchatPageMemberCount">${response['currentGroupchat'].memberCount}</span> members)</p>

                        <div class="adminMemberListContainer">
                            <div class="tr th">
                                <div class="td"></div>
                                <div class="td"
                                style="flex-grow: 2;">
                                Username
                                </div>
                                <div class="td">
                                Promote
                                </div>
                                <div class="td">
                                Ban
                                </div>
                                <div class="td">
                                Kick
                                </div>
                            </div>
                            <span id="groupchatAdminMembers">
                        

                            </span>
                            
                        </div>

                    </div>

                </div>
                `;
                $('.groupchatAdminContainer').html(groupchatAdminPage);

                // change privacy selected option based on current typeOf
                if(response['currentGroupchat'].typeOf == 'public') {
                    $('#groupchatPagePrivacyInfo option[value="1"]').attr('selected', true);
                } else {
                    $('#groupchatPagePrivacyInfo option[value="0"]').attr('selected', true);
                }

                let adminMembersList = '';

                // create members list for admin page
                $.each(response['currentGroupchat'].members, function(k, v) {
                    v.profilePic === '1' ? memberPic = `${v.id}_${v.username}.jpg` : memberPic = `no_pic.jpg`;
    
                    // data-object to be sent
                    let object = {
                        uid: v.id,
                        groupid: response['currentGroupchat'].id
                    }
                
                    let username = v.username;
                    let memberListButtonRank;
                    let memberListButtonKick;
                    let memberListButtonBan;
                    let objRank;
                    let objBan;
                    let objKick;
                    // username = encodeURIComponent(JSON.stringify(v.username));
                    
                    // check if user is admin
                    v.isAdmin === 1 ? memberUsername = `<p class="isAdmin">${v.username}</p>` : memberUsername = `<p>${v.username}</p>`;

                    // rank button
                    if(v.isAdmin === 0) {
                        objRank = {
                            ...object,
                            action:'promote'
                        }
                        objRank = encodeURIComponent(JSON.stringify(objRank));
                        memberListButtonRank = `<button class="adminMemberListItemButton greenButton AdminButtonAction" data-object="${objRank}">Promote</button>`;
                    } else if(v.isAdmin === 1 && v.username !== usersUsername) {
                        objRank = {
                            ...object,
                            action:'demote'
                        }
                        objRank = encodeURIComponent(JSON.stringify(objRank));
                        memberListButtonRank = `<button class="adminMemberListItemButton redButton AdminButtonAction" data-object="${objRank}">Demote</button>`;
                    } else {
                        memberListButtonRank = ``;
                    }

                    // ban button
                    if(v.isBanned === 0 && v.username !== usersUsername) {
                        objBan = {
                            ...object,
                            action:'ban'
                        }
                        objBan = encodeURIComponent(JSON.stringify(objBan));
                        memberListButtonBan = `<button class="adminMemberListItemButton greenButton AdminButtonAction" data-object="${objBan}">Ban</button>`;
                    } else if(v.isBanned === 1 && v.username !== usersUsername) {
                        objBan = {
                            ...object,
                            action:'unban'
                        }
                        objBan = encodeURIComponent(JSON.stringify(objBan));
                        memberListButtonBan = `<button class="adminMemberListItemButton redButton AdminButtonAction" data-object="${objBan}">Unban</button>`;
                    } else {
                        memberListButtonBan = ``;
                    }


                    // kick button
                    if(v.username !== usersUsername) {
                        objKick = {
                        ...object,
                        action:'kick'
                        }
                        objKick = encodeURIComponent(JSON.stringify(objKick));
                        memberListButtonKick = `<button class="adminMemberListItemButton redButton AdminButtonAction" data-object="${objKick}">Kick</button>`;
                    } else {
                        memberListButtonKick = '';   
                    }
                    
                    adminMembersList += `
                        <div class="tr">
                            <div class="td">
                            <img src="./uploads/${memberPic}"/>
                            </div>
                            <div class="td"
                            style="flex-grow: 2;">
                            <p>${memberUsername}</p>
                            </div>
                            <div class="td"
                            style="justify-content: center;">
                            <span>${memberListButtonRank}</span>
                            </div>
                            <div class="td"
                            style="justify-content: center;">
                            <span>${memberListButtonBan}</span>
                            </div>
                            <div class="td"
                            style="justify-content: center;">
                            <span>${memberListButtonKick}</span>
                            </div>
                        </div>
                    `;
                    $('#groupchatAdminMembers').html(`${adminMembersList}`);

                    // cancel outgoing friend request
                    $(".AdminButtonAction").click(function(){

                        // update user's last activity timestamp in database
                        updateLastActivity()
                        let alert = '';

                        let obj = $(this).data('object');
                        obj = JSON.parse(decodeURIComponent(obj));

                        // call php script to update groupchat settings
                        $.post("./inc/groupchatMembersAdmin.inc.php", // url of the page on server
                        { // Data Sending With Request To Server
                            obj:obj
                        },
                        function(response,status){    
                            
                            var response = JSON.parse(response);

                            if(response != null) {
                                alert += `
                                <div class="alert ${response['type']}">
                                    <span class="closeAlert">&times;</span>
                                    ${response['message']}
                                </div>`
                                $('#alerts').html(alert)
                            } else {
                                alert += `
                                <div class="alert ${response['type']}">
                                    <span class="closeAlert">&times;</span>
                                    ${response['message']}
                                </div>`
                                $('#alerts').html(alert)
                            }
                        });
                    });
   
            });

                // serialize the update group chat form to check if its been updated
                $('#updateGroupchatSettings').data('serialize',$('#updateGroupchatSettings').serialize());

                // if admin clicks update settings button
                $('#saveGroupchatSettings').click(function() {
                    let updateGroupchatObj;
                    let alert = '';
                    
                    if($('#updateGroupchatSettings').serialize()!=$('#updateGroupchatSettings').data('serialize')){
                        // make an object containing the form input names and values
                        $('#updateGroupchatSettings input, #updateGroupchatSettings select').each(
                            
                            function(index){  
                                let input = $(this);

                                // using the spread operator to append data to object
                                updateGroupchatObj = {
                                    ...updateGroupchatObj,
                                    [input.attr('name')]: input.val(),
                                }
                                
                            }
                            
                        )

                        // add the group id to obj
                        updateGroupchatObj = {
                            ...updateGroupchatObj,
                            'id':response['currentGroupchat'].id
                        }

                        // log obj test
                        console.log(updateGroupchatObj)

                        // call php script to update groupchat settings
                        $.post("./inc/updateGroupchatSettings.inc.php", // url of the page on server
                        { // Data Sending With Request To Server
                            updateGroupchatObj:updateGroupchatObj
                        },
                        function(response,status){    
                            
                            var response = JSON.parse(response);

                            if(response != null) {
                                alert += `
                                <div class="alert ${response['type']}">
                                    <span class="closeAlert">&times;</span>
                                    ${response['message']}
                                </div>`
                                $('#alerts').html(alert)
                            } else {
                                alert += `
                                <div class="alert ${response['type']}">
                                    <span class="closeAlert">&times;</span>
                                    ${response['message']}
                                </div>`
                                $('#alerts').html(alert)
                            }
                        });
                    } else {

                        // display message that no changes have been made
                        alert += `
                        <div class="alert warning">
                            <span class="closeAlert">&times;</span>
                            No changes have been made
                        </div>`
                        $('#alerts').html(alert)


                    }
                })

            })

        });
    }, 1000);
    
}



    // UPDATE CHAT PROFILE STATUS EVERY SECOND 
    function updateChatProfileStatus() {
        $('#profileStatus').each(function(){
            var objects = $(this).attr("data-objects");
            object = JSON.parse(decodeURIComponent(objects));
            fetchChatProfileStatus(object[1]['id'],object[1]['type']);

        })
    };
    ////////////////////////

    // SCROLL TO BOTTOM TO LATEST MESSAGES
    function scrollToBottom (id) {
        var div = document.getElementById(id);
        div.scrollTop = div.scrollHeight - div.clientHeight;
    };
    /////////////////////

    // FETCH CHAT HISTORY
    function fetchChatMessages(receiver_id, type) {
        $.ajax({
            // url:"./inc/getChatMessages.inc.php",
            url:"./inc/getChatMessages.inc.php",
            method:"POST",
            data:{receiver_id:receiver_id,type:type},
            success:function(data){
                var data = JSON.parse(data);
                var messages = '';
                if(data != null) {
                    $.each(data['messages'], function(k, v) {
                        // if msg isn't null
                        if(v['message']) {
                            // for the user
                            if(v['sessionUid'] == v['messageUid']) {
                                messages += `
                                <div class="bubbleContainer">
                                    <div class="bubble right">
                                        <div class="bubbleInfoRight"><small>${v['date']}</small></div>
                                        <p>${v['message']}</p>
                                    </div>
                                </div>
                                `;
                            } else {
                            // for user's friends
                                messages += `
                                <div class="bubbleContainer">
                                    <div>
                                        <img src="./uploads/${v['messagePic']}">
                                    </div>
                                    <div class="bubble left">
                                        <div class="bubbleInfoLeft">
                                        ${v['messageUser']} <small>${v['date']}</small>
                                        </div>
                                        <p>${v['message']}</p>
                                    </div>
                                </div>
                                `;
                            };
                        }
                        $('.chat_history').html(messages);
                    })
                }
            }
        })
        
    };
    //////////////////////

    // UPDATE CHAT EVERY SECOND 
    function updateMessages() {
        $('.chat_history').each(function(){
            var objects = $(this).attr("data-objects");
            object = JSON.parse(decodeURIComponent(objects));
            fetchChatMessages(object[1]['id'],object[1]['type']);

        });
    };
    ////////////////////////

    // UPDATE UNREAD MESSAGE TO READ
    function initChangeReadStatus() {
        if($('.chatContainer').css('display') == 'flex') {
            $('.chat_history').each(function(){
                var objects = $(this).attr("data-objects");
                object = JSON.parse(decodeURIComponent(objects));
                changeReadStatus(object[1]['id']);

            });
        };
    };
    changeReadStatus = function (chat_uid) {

        $.ajax({    // make an ajax call to getChatMessages.php
            type:'POST', // needed even though we are using sessions in the php script
            url:'./inc/changeReadStatus.inc.php',
            data:{chat_uid:chat_uid},
        });
    };
    //////////////////////////

    // Submit a chat message
    submitMsg = function () {
        
        // update user's last activity timestamp in database
        updateLastActivity();

        var alert = '';
        var msg = $("#msg").val();
        var receiver_id = $("#receiver_id").val();
        if($("#groupid").val()) {
            var groupid = $("#groupid").val();
        } else {
            var groupid = null;
        }
        var type = $("#type").val();

        $.post("./inc/sendMessage.inc.php", // url of the page on server
        { // Data Sending With Request To Server
            msg:msg, //msg being sent
            receiver_id:receiver_id,
            type:type,
            groupid:groupid
        },
        function(response,status){     
            var response = JSON.parse(response);
            if(response != null) {
                alert += `
                <div class="alert ${response['type']}">
                    <span class="closeAlert">&times;</span>
                    ${response['message']}
                </div>`
                $('#alerts').html(alert)
            }
        });
        $("#form")[0].reset();
        scrollToBottom('chatContainer');
    }

    // if "enter key" is press while typing in message input
    $(document).on('keypress', '.msg_input', function (e) {
         if(e.which === 13){
            //Disable textbox to prevent multiple submit
            $('#msg').attr("disabled", "disabled");
            submitMsg(); // forward msg to submitMsg function   
            //Enable the textbox again if needed
            $('#msg').removeAttr("disabled");
         }
    });

    // Send message through post call via click
    $(document).on('click', '.msg_submit', function(){
        submitMsg();
    }); 

    // whenever key is pressed/released -> update user's last activity timestamp in database
    $("input").keyup(function(){

        // update user's last activity timestamp in database
        updateLastActivity();
        
    });
    ///////////////////

});
</script>