const inputLastName = document.getElementById('LastnameInput');
const inputFirstName = document.getElementById('FirstnameInput');
const inputPseudo = document.getElementById('PseudoInput');
const inputEmail = document.getElementById('EmailInput');
const inputPassword = document.getElementById('PasswordInput');
const inputPasswordValidate = document.getElementById('ValidatePasswordInput');
const btnValidate = document.getElementById('btnValidateSignup');
const formSignup = document.getElementById('signupForm');

const inputs = [inputLastName, inputFirstName, inputPseudo, inputEmail, inputPassword, inputPasswordValidate];
inputs.forEach(input => {
    input.addEventListener("input", () => validateInput(input, true));
    input.addEventListener("blur", () => validateInput(input, false));
});

function validateInput(input, isTyping) {
    let isValid = false;

    if (input === inputEmail) {
        isValid = validateEmail(input);
    } else if (input === inputPassword) {
        isValid = validatePassword(input);
    } else if (input === inputPasswordValidate) {
        isValid = validatePasswordValidate(inputPassword, inputPasswordValidate);
    } else {
        isValid = validateRequired(input);
    }

    if (isValid) {
        input.classList.add('is-valid');
        input.classList.remove('is-invalid');
    } else {
        input.classList.remove('is-valid');
        if (isTyping) {
            input.classList.add('is-invalid');
        }
    }

    validateForm();
}

function validateForm() {
    const lastnameOk = validateRequired(inputLastName);
    const firstNameOk = validateRequired(inputFirstName);
    const pseudoOk = validateRequired(inputPseudo);
    const emailOk = validateEmail(inputEmail);
    const passwordOk = validatePassword(inputPassword);
    const passwordValidateOk = validatePasswordValidate(inputPassword, inputPasswordValidate);

    btnValidate.disabled = !(lastnameOk && firstNameOk && pseudoOk && emailOk && passwordOk && passwordValidateOk);
}

function validateRequired(input) {
    return input.value.trim() !== '';
}

function validateEmail(input) {
    const emailRegex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
    return emailRegex.test(input.value.trim());
}

function validatePassword(input) {
    const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_])[a-zA-Z\d\W_]{8,}$/;
    return passwordRegex.test(input.value.trim());
}

function validatePasswordValidate(inputPassword, inputValidatePassword) {
    return inputPassword.value === inputValidatePassword.value && inputPassword.value !== '';
}

btnValidate.addEventListener("click", signupForm);

function signupForm(event) {
    event.preventDefault();

    let raw = JSON.stringify({
        "lastname": inputLastName.value,
        "firstname": inputFirstName.value,
        "pseudo": inputPseudo.value,
        "email": inputEmail.value,
        "password": inputPassword.value,
        "password_confirmation": inputPasswordValidate.value
    });

    let myHeaders = new Headers();
    myHeaders.append("Content-Type", "application/json");

    let requestOptions = {
        method: "POST",
        headers: myHeaders,
        body: raw
    };

    fetch(apiURL + "register", requestOptions)
        .then(response => response.json().then(data => ({ status: response.status, body: data })))
        .then(result => {
            if (result.status === 201) {
                alert("Bravo " + inputFirstName.value + ", vous êtes maintenant inscrit, vous pouvez vous connecter.");
                window.location.href = "/signin";
            } else {
                let errorMessage = "Erreur lors de l'inscription : ";
                if (result.body.errors) {
                    errorMessage += Object.values(result.body.errors).join(", ");
                } else {
                    errorMessage += result.body.message || "Veuillez vérifier vos informations.";
                }
                alert(errorMessage);
            }
        })
        .catch(error => console.error('Erreur:', error));
}
