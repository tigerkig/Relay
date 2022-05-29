	let userInput = document.getElementById("username");
	let pswInput = document.getElementById("psw");
    let confirmInput = document.getElementById("confirm");
    let userConditions = document.getElementById("userConditions");
    let userConditionsColor = document.getElementById("userConditionsColor");
    let conditions = document.getElementById("conditions");
    let conditionsColor = document.getElementById("conditionsColor");
    let match = document.getElementById("match");
    let matchColor = document.getElementById("matchColor");

    // When the user clicks on the password field, show the message box
    $("#username").focus(function(){
		$('#userConditions').fadeIn(1000);
    })
    $('#username').blur(function() {
		$('#userConditions').fadeOut(1000);
	});

	$("#psw").focus(function(){
		$('#conditions').fadeIn(1000);
    })
    $('#psw').blur(function() {
		$('#conditions').fadeOut(1000);
	});

	$("#confirm").focus(function(){
		$('#match').fadeIn(1000);
    })
    $('#confirm').blur(function() {
		$('#match').fadeOut(1000);
	});

	let lowerCase = /[a-z]/g; // Validate lowercase letters
	let upperCase = /[A-Z]/g; // Validate capital letters  
	let numbers = /[0-9]/g; // Validate numbers
	let letterNumber = /^[0-9a-zA-Z]+$/; // Validate only containers letters and numbers

	// When the user starts to type something inside the password field
    userInput.onkeyup = function() {

	// Validate passwords contain correct characters and length
		if((userInput.value.match(letterNumber)) && userInput.value.length >= 3) {  
			userConditionsColor.classList.remove("info");
			userConditionsColor.classList.add("success");
		} else {
			userConditionsColor.classList.remove("success");
			userConditionsColor.classList.add("info");
		}
    }

    // When the user starts to type something inside the password field
    pswInput.onkeyup = function() {

		

		// Validate passwords contain correct characters and length
		if(pswInput.value.match(letterNumber) && pswInput.value.match(upperCase) && pswInput.value.match(lowerCase) && pswInput.value.match(numbers) && pswInput.value.length >= 8) {  
			conditionsColor.classList.remove("info");
			conditionsColor.classList.add("success");
		} else {
			conditionsColor.classList.remove("success");
			conditionsColor.classList.add("info");
		}

		// Validate passwords matching
		if(confirmInput.value == pswInput.value || pswInput.value == confirmInput.value) {
			matchColor.classList.remove("info");
			matchColor.classList.add("success");
		} else {
			matchColor.classList.remove("success");
			matchColor.classList.add("info");
		}


		}
		confirmInput.onkeyup = function() {
		// Validate length
		if(confirmInput.value == pswInput.value || pswInput.value == confirmInput.value) {
			matchColor.classList.remove("info");
			matchColor.classList.add("success");
		} else {
			matchColor.classList.remove("success");
			matchColor.classList.add("info");
		}
	}