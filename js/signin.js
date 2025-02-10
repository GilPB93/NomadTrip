const inputEmail = document.getElementById('EmailInput');
const inputPassword = document.getElementById('PasswordInput');
const btnSignIn = document.getElementById('btnSignin');
const formSignin = document.getElementById('signinForm');

btnSignIn.addEventListener("click", checkCredentials);


function checkCredentials(event) {
    event.preventDefault();

    let myHeaders = new Headers();
    myHeaders.append("Content-Type", "application/json");

    let raw = JSON.stringify({
        "email": inputEmail.value,
        "password": inputPassword.value
    });

    let requestOptions = {
        method: 'POST',
        headers: myHeaders,
        body: raw,
        redirect: 'follow'
    };

    fetch("http://127.0.0.1:8000/api/login", requestOptions)
        .then(response => {
            if (response.ok) {
                return response.json();
            } else {
                inputEmail.classList.add('is-invalid');
                inputPassword.classList.add('is-invalid');
                throw new Error('Invalid credentials');
            }
        })
        .then(result => {
            const token = result.token;
            const role = result.roles;
            setToken(token);
            setCookie(RoleCookieName, role, 7);
            window.location.replace("/library");
        })
        .catch(error => console.log('error', error));
}

function getUserRole(){
    return getCookie(RoleCookieName);
}

function isUserConnected(){
    return getToken() != null && getToken != undefined;
}
