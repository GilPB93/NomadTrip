//CONSTANTS
const apiURL = "https://nomadtripbackendapi-9416dc608e6c.herokuapp.com/api/";
const tokenCookieName = "accesstoken"; 
const btnSignout = document.getElementById('btnSignout');
const UserIdCookieName = 'user.Id';
const RoleCookieName = "role";
const loginTimeCookieName = "login_time";

// FETCH API BACK-END



// TOKEN MANAGEMENT
function setToken(token){ 
    setCookie(tokenCookieName, token, 7); 
}

function getToken(){
    return getCookie(tokenCookieName); 
}

// COOKIE MANAGEMENT
function setCookie(name,value,days) { 
    let expires = "";
    if (days) {
        let date = new Date();
        date.setTime(date.getTime() + (days*24*60*60*1000));
        expires = "; expires=" + date.toUTCString();
    }
    document.cookie = name + "=" + (value || "")  + expires + "; path=/; Secure; SameSite=Strict";
}

function getCookie(name) {
    let nameEQ = name + "=";
    let ca = document.cookie.split(';');
    for(const element of ca) {
        let c = element;
        while (c.startsWith(' ')) c = c.substring(1,c.length);
        if (c.startsWith(nameEQ)) return c.substring(nameEQ.length,c.length);
    }
    return null;
}

function eraseCookie(name) { 
    document.cookie = name + '=; Path=/; Expires=Thu, 01 Jan 1970 00:00:01 GMT; Secure; SameSite=Strict';
}

//SIGNIN MANAGEMENT
function userConnected(){
    return !(getToken() == null || getToken == undefined);
}

//SIGNOUT MANAGEMENT
const timeToLogout = 5 * 60 * 1000; 
let logoutTimer;

function resetLogoutTimer() {
    clearTimeout(logoutTimer);
    logoutTimer = setTimeout(signOut, timeToLogout);
}
window.addEventListener("mousemove", resetLogoutTimer);
window.addEventListener("keydown", resetLogoutTimer);
window.addEventListener("click", resetLogoutTimer);

resetLogoutTimer();

btnSignout.addEventListener('click', signOut);
function signOut() {
    setLogoutTime();
    setTimeout(() => {
        eraseCookie(tokenCookieName);
        eraseCookie(RoleCookieName);
        eraseCookie(UserIdCookieName);
        eraseCookie(loginTimeCookieName);
        window.location.replace("/");
    }, 500); 
}

window.addEventListener("beforeunload", setLogoutTime);



//ROLE MANAGEMENT
function getRole(){
    return getCookie(RoleCookieName); 
}

// HIDE AND SHOW ELEMENTS BASED ON ROLE
function hideAndShowElementsByRoles() {
    const isUserConnected = userConnected();
    const role = getRole();

    const allElementsToEdit = document.querySelectorAll('[data-show]');

    allElementsToEdit.forEach(element => {
        element.classList.remove("d-none");

        switch (element.dataset.show) {
            case "disconnected":
                if (isUserConnected) {
                    element.classList.add("d-none");
                }
                break;
            case "connected":
                if (!isUserConnected) {
                    element.classList.add("d-none");
                }
                break;
            case "admin":
                if (!isUserConnected || !role.includes("ROLE_ADMIN")) {
                    element.classList.add("d-none");
                }
                break;
            case "user":
                if (!isUserConnected || role !== "ROLE_USER") {
                    element.classList.add("d-none");
                }
                break;
        }
    });
}

document.addEventListener('DOMContentLoaded', () => {
        hideAndShowElementsByRoles();
     
        if (userConnected()) {
            setLoginTime(); 
        }

        //SET TIMEOUT TO LOGOUT
        const timeToLogout = 1000 * 60 * 5; // 5 minutes
        let time = 0;
        let timer = setInterval(() => {
            time += 1000;
            if (time >= timeToLogout) {
                clearInterval(timer);
                signOut();
            }
        }, 1000);    

});


// UPDATE TOTAL TIME OF CONNECTION
let loginTime = null;
function setLoginTime() { 
    if (!userConnected()) return;

    fetch(apiURL + 'activity-log/set-login-time', {
        method: 'POST',
        headers: {
            'X-AUTH-TOKEN': getToken(),
            'Content-Type': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.login_time) {
            console.log("✅ Heure de connexion enregistrée :", data.login_time);
            loginTime = new Date(data.login_time);
        } else {
            console.warn("⚠️ Problème lors de l'enregistrement de l'heure de connexion.");
        }
    })
    .catch(error => console.error('⚠️ Erreur lors de l’enregistrement du login time:', error));
}

async function setLogoutTime() {
    let loginTime = getCookie(loginTimeCookieName);

    if (!loginTime) {
        return;
    }

    try {
        const response = await fetch(apiURL + 'activity-log/set-logout-time', {
            method: "POST",
            headers: {
                'X-AUTH-TOKEN': getToken(),
                "Content-Type": "application/json"
            },
            credentials: 'include',
            body: JSON.stringify({ login_time: loginTime })
        });

        console.log("📥 Réponse reçue:", response);

        if (!response.ok) {
            throw new Error(`❌ Erreur HTTP ${response.status}`);
        }

        const data = await response.json();
        console.log("✅ Déconnexion enregistrée :", data);

        console.log("🗑️ Purge des logs en cours...");
        await purgeLogoutNull();


        setTimeout(() => {
            eraseCookie(loginTimeCookieName);
            eraseCookie(tokenCookieName);
            eraseCookie(RoleCookieName);
            eraseCookie(UserIdCookieName);
            window.location.replace("/");
        }, 1000);


    } catch (error) {
        console.error("🚨 Erreur lors de l’enregistrement du logout:", error);
    }
}

// PURGE LOGOUT NULL AT LOGOUT
async function purgeLogoutNull() {
    try {
        console.log("🗑️ Purge des logs en cours...");

        const response = await fetch(apiURL + 'activity-log/purge-logout-null', {
            method: "DELETE",
            headers: {
                'X-AUTH-TOKEN': getToken(),
                "Content-Type": "application/json"
            },
            credentials: 'include',
        });

        console.log("📥 Réponse reçue:", response);

        if (!response.ok) {
            throw new Error(`❌ Erreur HTTP ${response.status}`);
        }

        const data = await response.json();
        console.log("🗑️ Purge des logs terminée :", data);

        if (data.deleted_rows === 0) {
            console.warn("⚠️ Aucun log supprimé. Vérifie s'il y a bien des entrées avec logout NULL.");
        }

    } catch (error) {
        console.error("🚨 Erreur lors de la purge des logs :", error);
    }
}