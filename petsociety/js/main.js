/* DISABLED BUTTONS */

//The input fields take a few seconds to load, so the function needs to fire later
setTimeout(function(){

   //Checks which page we are on, and calls functions depending on that
   if (document.querySelector(".petprofiles-add-page") || document.querySelector(".animalcare-add-page")) {
        formValidation(true, true);
    }

    if (document.querySelector(".petprofiles-edit-page")  || document.querySelector(".animalcare-edit-page")) {
        formValidation(false, false);
    }

}, 4000);


//These pages don't need the setTimeout function
if (document.querySelector(".register-page")) {
    formValidation2(true);
}

if (document.querySelector(".account-page")) {
    formValidation2(false);
}



function formValidation(imageRequired, disable) {

    //Select all the required fields and add event listeners – some are only selected if they exist (otherwise error)
    var emojiFieldOne = document.querySelector(".required-emoji-1 > div");
    emojiFieldOne.addEventListener('input', checkForm);

    if (document.querySelector(".required-emoji-2 > div")) {
        var emojiFieldTwo = document.querySelector(".required-emoji-2 > div");
        emojiFieldTwo.addEventListener('input', checkForm);

    }
    if (document.querySelector(".required-other")) {
        var otherField = document.querySelector(".required-other");
        otherField.addEventListener('input', checkForm);
    }

    //Select submit button and set it to disable (depending on the argument passed to the function)
    var submit = document.querySelector("input[type=submit]");
    submit.disabled = disable;
 
    //If an image is required, add an event listener to the file input
    if (imageRequired) {
        imageInput = document.querySelector("input[type=file]");
        imageInput.addEventListener('input', checkForm); 
    }

    //Function triggered from the event listeners
    function checkForm(e) {

        //Check if the fields exist and have values/text. If not, disable buttons
        if (emojiFieldTwo && otherField) {
            if (emojiFieldOne.innerText == '' || emojiFieldTwo.innerText == '' || otherField.value == '') {
                submit.disabled = true;
            } else {
                submit.disabled = false;
            }
        } else if (otherField) {
            if (emojiFieldOne.innerText == '' || otherField.value == '') {
                submit.disabled = true;
            } else {
                submit.disabled = false;
            }
        } else if (emojiFieldTwo) {
            if (emojiFieldOne.innerText == '' || emojiFieldTwo.innerText == '') {
                submit.disabled = true;
            } else {
                submit.disabled = false;
            }
        }

        //If the image is required and the file input has no value, disable the submit button
        if (imageRequired && imageInput.value == '') {
            submit.disabled = true;
        }
    }
} 


function formValidation2(disabled) {

    //Select required fields
    var requiredFieldOne = document.querySelector(".required-1");
    var requiredFieldTwo = document.querySelector(".required-2");

    //Select submit button and disable it
    var submit = document.querySelector("input[type=submit]");
    submit.disabled = disabled;

    //Add event listeners
    requiredFieldOne.addEventListener('input', checkForm);
    requiredFieldTwo.addEventListener('input', checkForm);

    //If the required fields are empty, disable the button. Otherwise, enable it
    function checkForm(e) { 
        if (requiredFieldOne.value == '' || requiredFieldTwo.value == '') {
            submit.disabled = true;
        } else {
            submit.disabled = false;
        }
    }
}
    



/* DISABLED FIELDS ON ACCOUNT.PHP */


//Checks so that we are on the account page
if (document.querySelector(".account-page")) {

    var editButton = document.querySelector(".edit");

    //We get all the input fields and disable them
    var inputs = document.querySelectorAll("input");

    for (var i = 0; i < inputs.length; i++) { 
        inputs[i].disabled = true;
    } 

    //When the edit button is clicked, the input fields are enabled
    editButton.addEventListener('click', activateEditing);

    function activateEditing(e) {
        for (var i = 0; i < inputs.length; i++) { 
            inputs[i].disabled = false;
        }
    }
}


/* TOGGLE PASSWORD VISIBILITY */

function togglePassword() {

    //select input field and icon
    var x = document.getElementById("psw");
    var icon = document.getElementById("eye");

    //check current input type and set it and image source accordingly
    if (x.type === "password") {
        x.type = "text";
        icon.src = "img/icons/eye-closed.png";
    } else {
        x.type = "password";
        icon.src = "img/icons/eye-open.png";
    }
} 



/* CONFIRM POPUP FOR DELETE BUTTONS */

function deletePet() {
    if(confirm("Are you sure you want to delete this pet?") == false){
        return false;
    }
}

function deleteAnimal() {
    if(confirm("Are you sure you want to delete this animal?") == false){
        return false;
    }
}

function deleteAccount() {
    if(confirm("Are you sure you want to delete your account?") == false){
        return false;
    }
}