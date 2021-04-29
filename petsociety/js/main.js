/* DISABLED BUTTONS ON REGISTER & LOGIN PAGE */

if (document.querySelector(".register-page") || document.querySelector(".login-page") ) {
    var submit = document.querySelector("input[type=submit]");
    var usernameField = document.querySelector("input[name=username]");
    var passwordField = document.querySelector("input[name=password]");

    usernameField.addEventListener('input', checkForm);
    passwordField.addEventListener('input', checkForm);
    submit.disabled = true;
    
    function checkForm(e) {
        if (usernameField.value.length == 0 || passwordField.value.length == 0) {
            submit.disabled = true;
        } else {
            submit.disabled = false;
        }
    }
}

