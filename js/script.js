//SET COOKIE MANAGEMENT
const tokenCookieName = "accesstoken"; 
function setToken(token){ 
    setCookie(tokenCookieName, token, 7); 
}

function getToken(){
    return getCookie(tokenCookieName); 
}

function setCookie(name,value,days) { 
    let expires = "";
    if (days) {
        let date = new Date();
        date.setTime(date.getTime() + (days*24*60*60*1000));
        expires = "; expires=" + date.toUTCString();
    }
    document.cookie = name + "=" + (value || "")  + expires + "; path=/";
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
    document.cookie = name +'=; Path=/; Expires=Thu, 01 Jan 1970 00:00:01 GMT;';
}

//SIGNIN MANAGEMENT
function userConnected(){
    return !(getToken() == null || getToken == undefined);
}

//SIGNOUT MANAGEMENT
const btnSignout = document.getElementById('btnSignout');

btnSignout.addEventListener("click", signOut);

function signOut() {
    eraseCookie(tokenCookieName);
    eraseCookie(RoleCookieName);
    window.location.replace("/");
}

//ROLE MANAGEMENT
const RoleCookieName = "role";
function getRole(){
    return getCookie(RoleCookieName); 
}


//HIDE AND SHOW ELEMENTS BASED ON ROLE
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
                if (!isUserConnected || role !== "ROLE_ADMIN") {
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
