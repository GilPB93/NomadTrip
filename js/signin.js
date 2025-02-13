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

    fetch(apiURL + "login", requestOptions)
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
            console.log("Login response:", result); // Vérifier ce que renvoie l'API
            if (!result.id) {
                throw new Error("User ID not found in API response");
            }

            const token = result.apiToken;
            const role = result.roles;
            const userId = result.id;

            setToken(token);
            setCookie(RoleCookieName, role, 7);
            setCookie(UserIdCookieName, userId, 7);

            window.location.replace("/library");
        })
        .catch(error => console.log('error', error));
}

function getUserRole(){
    return getCookie(RoleCookieName);
}

function getUserId(){
    return getCookie(UserIdCookieName);
}

function setToken(token){
    setCookie(tokenCookieName, token, 7);
}

function isUserConnected(){
    return getToken() != null && getToken != undefined;
}