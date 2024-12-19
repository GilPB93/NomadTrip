const inputEmail = document.getElementById('EmailInput');
const inputPassword = document.getElementById('PasswordInput');
const btnSignIn = document.getElementById('btnSignin');

btnSignIn.addEventListener("click", checkCredentials);

function checkCredentials() {
    
    if (inputEmail.value == "test@email.com" && inputPassword.value == "123") {
        const token = "1234567890";
        setToken(token);
        window.location.replace("/"); 
        alert("Bienvenue sur votre espace personnel");
    }
    else {
        inputEmail.classList.add("is-invalid");
        inputPassword.classList.add("is-invalid");
        alert("Invalid credentials");
    }
}