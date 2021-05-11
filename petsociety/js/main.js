/* if (document.querySelector(".petprofiles-edit-page")) {
    formValidation(false, false);
} */

setTimeout(function(){
   if (document.querySelector(".newpet-page")) {
        formValidation(true, true);
    }
}, 3000);
 

function formValidation(imageRequired, disable) {

    var fields = document.querySelectorAll(".required > .emojionearea-editor");
    var submit = document.querySelector("input[type=submit]");

    console.log(fields);

    submit.disabled = true;

 
    for (var i = 0; i < fields.length; i++) {
        fields[i].addEventListener('input', checkForm);
    } 

    function checkForm(e) {
        console.log('hello');
    }
    
    /*

    if (imageRequired == true) {
        imageInput = document.querySelector("input[type=file]");
        imageInput.addEventListener('input', checkForm); 
    } */
/* 
    function checkForm(e) {
        for (var i = 0; i < fields.length; i++) {
            if (fields[i].value == '') {
                submit.disabled = true;
            } else {
                submit.disabled = false;
            }
        }
    } */
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