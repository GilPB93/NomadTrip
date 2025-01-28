const inputName = document.getElementById('NameInput');
const inputFirstName = document.getElementById('FirstnameInput');
const inputPseudo = document.getElementById('PseudoInput');
const inputEmail = document.getElementById('EmailInput');
const inputPassword = document.getElementById('PasswordInput');
const inputPasswordValidate = document.getElementById('ValidatePasswordInput');
const btnValidate = document.getElementById('btnValidateSignup');

inputName.addEventListener("keyup", validateForm);
inputFirstName.addEventListener("keyup", validateForm);
inputPseudo.addEventListener("keyup", validateForm);
inputEmail.addEventListener("keyup", validateForm);
inputPassword.addEventListener("keyup", validateForm);
inputPasswordValidate.addEventListener("keyup", validateForm);
btnValidate.addEventListener("click", SignupUser);

function validateForm() {
    const nameOk = validateRequired(inputName);
    const firstNameOk = validateRequired(inputFirstName);
    const pseudoOk = validateRequired(inputPseudo);
    const emailOk = validateEmail(inputEmail);
    const passwordOk = validatePassword(inputPassword);
    const passwordValidateOk = validatePasswordValidate(inputPassword, inputPasswordValidate);

    if (nameOk && firstNameOk && pseudoOk && emailOk && passwordOk && passwordValidateOk) {
       btnValidate.disabled = false;
    }
    else {
        btnValidate.disabled = true;
    }
}

function validateRequired(input) {
    if (input.value != '') {
        input.classList.add('is-valid');
        input.classList.remove('is-invalid');
    } else {
        input.classList.remove('is-valid');
        input.classList.add('is-invalid');
    }
}

function validateEmail(input) {
    const emailRegex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
    const emailUser = input.value;
    if (emailUser.match(emailRegex)) {
        inputEmail.classList.add('is-valid');
        inputEmail.classList.remove('is-invalid');
        return true;
    }
    else {
        inputEmail.classList.remove('is-valid');
        inputEmail.classList.add('is-invalid');
        return false;
    }
}

function validatePassword(input) {
    const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d))(?=.*[\W_])[[a-zA-Z\d\W_]{8,}$/;
    const passwordUser = input.value;
    if (passwordUser.match(passwordRegex)) {
        inputPassword.classList.add('is-valid');
        inputPassword.classList.remove('is-invalid');
        return true;
    }
    else {
        inputPassword.classList.remove('is-valid');
        inputPassword.classList.add('is-invalid');
        return false;
    }
}

function validatePasswordValidate(inputPassword, inputValidatePassword) {
    if (inputPassword.value === inputValidatePassword.value) {
        inputPasswordValidate.classList.add('is-valid');
        inputPasswordValidate.classList.remove('is-invalid');
        return true;
    }
    else {
        inputPasswordValidate.classList.remove('is-valid');
        inputPasswordValidate.classList.add('is-invalid');
        return false;
    }
}