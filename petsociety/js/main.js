/* DISABLED BUTTONS */

//Checks which page we are on (these classes are set to <main>)
if (document.querySelector(".register-page")) {
    formValidation('input', 'input', 'username', 'password', false, true); 
} else if (document.querySelector(".newpet-page")) {
    formValidation('input', 'input', 'name', 'species', true, true);
} else if (document.querySelector(".petprofiles-edit-page")) {
    formValidation('input', 'input', 'name', 'species', false, false);
} else if (document.querySelector(".animalcare-add-page")) {
    formValidation('textarea', 'textarea', 'species', 'facts', true, true);
} else if (document.querySelector(".animalcare-edit-page")) {
    formValidation('textarea', 'textarea', 'species', 'facts', false, false);
} else if (document.querySelector(".account-page")) {
    formValidation('input', 'input', 'username', 'password', false, false);
}



function formValidation (fieldTypeOne, fieldTypeTwo, fieldOne, fieldTwo, imageRequired, disable) {

    //Checks which input fields we are listening to
    var submit = document.querySelector("input[type=submit]");
    var inputValueOne = document.querySelector("" + fieldTypeOne + "[name=" + fieldOne + "]");
    var inputValueTwo = document.querySelector("" + fieldTypeTwo + "[name=" + fieldTwo + "]");
    var inputValueThree;

    //This is to see if we want to require an image or not (if so, we add an event listener)
    if (imageRequired == true) {
        inputValueThree = document.querySelector("input[type=file]");
        inputValueThree.addEventListener('input', checkForm);
    } else {
        //Even the image is not required, we still need a value for the checkForm function
        inputValueThree = 'noimg';
    }
    
    //Adds event listeners and calls checkForm
    inputValueOne.addEventListener('input', checkForm);
    inputValueTwo.addEventListener('input', checkForm);

    //Sets the initial value of the submit button to false so that it is disabled at first
    submit.disabled = disable;

    //If the fields are empty, we disable the submit button, otherwise it's not disabled
    function checkForm(e) {
        if (inputValueOne.value == '' || inputValueTwo.value == '' || inputValueThree.value == '') {
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