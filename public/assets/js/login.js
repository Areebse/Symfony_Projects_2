document.getElementById("login-form").onsubmit = function (event) {
    event.preventDefault();

    let email = document.getElementById("email").value;
    let password = document.getElementById("password").value;
    let emailError = document.getElementById("email-error");
    let passwordError = document.getElementById("password-error");
    let valid = true;
    let regex = /^\w+@\w+\.\w+$/;

    emailError.innerHTML = "";
    passwordError.innerHTML = "";

    if (email === "") {

        emailError.innerHTML = "Please enter an email address.";
        valid = false;
        event.preventDefault();
    }

    if (password === "") {
        passwordError.innerHTML = "Please enter a password.";
        valid = false;
        event.preventDefault();
    }
    if (password.length < 6 || password.length > 18) {
        passwordError.innerHTML =
            " Password should be minimum 6 and maximum 18 characters long.";
        valid = false;
        event.preventDefault();
    }

    if (!regex.test(email)) {
        emailError.innerHTML = "Please enter a valid email address.";
        valid = false;
        event.preventDefault();
    }

    if (valid) {
        this.submit();
    }


}