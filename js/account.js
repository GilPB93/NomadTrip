document.addEventListener("DOMContentLoaded", getInfosUser);

getInfosUser();

// FETCH INFORMATION ACCOUNT
function getInfosUser() {
    let myHeaders = new Headers();
    myHeaders.append("X-AUTH-TOKEN", getToken());

    let requestOptions = {
        method: 'GET',
        headers: myHeaders,
        redirect: 'follow',
        mode: 'cors',
        credentials: 'include',
    };

    fetch(apiURL + "accountInfo", requestOptions)
        .then(response => {
            if (response.ok) {
                return response.json();
            } else {
                throw new Error('Utilisateur invalide');
            }
        })
        .then(user => {
            console.log("✅ Données reçues:", user);
            document.getElementById("lastNameInfo").innerText = user.lastName || "N/A";
            document.getElementById("firstNameInfo").innerText = user.firstName || "N/A";
            document.getElementById("pseudoInfo").innerText = user.pseudo || "N/A";
            document.getElementById("emailInfo").innerText = user.email || "N/A";
        })
        .catch(error => console.error("Erreur lors de la récupération des infos :", error));
}


// UPDATE PSEUDO
document.getElementById('btnModifyPseudo').addEventListener('click', function () {
    let newPseudo = document.getElementById('NewPseudoInput').value;
    document.getElementById('newPseudoText').textContent = newPseudo || 'votre nouveau pseudo';
});

document.getElementById('confirmPseudoChange').addEventListener('click', function () {
    alert('Pseudo modifié avec succès !'); 
    document.getElementById('confirmPseudoModal').querySelector('.btn-close').click();
});

function updatePseudo() {
    let newPseudo = document.getElementById("NewPseudoInput").value;
    let userId = getUserId();

    if (!newPseudo.trim()) {
        alert("Veuillez entrer un pseudo valide.");
        return;
    }

    let myHeaders = new Headers();
    myHeaders.append("Content-Type", "application/json");
    myHeaders.append("X-AUTH-TOKEN", getToken());

    let raw = JSON.stringify({
        "pseudo": newPseudo
    });

    let requestOptions = {
        method: 'PUT',
        headers: myHeaders,
        body: raw,
        redirect: 'follow'
    };

    fetch(apiURL + `user/${userId}`, requestOptions)
        .then(response => {
            if (response.ok) {
                return response.json();
            } else {
                throw new Error('Pseudo invalide');
            }
        })
        .then(result => {
            console.log("✅ Pseudo mis à jour:", result);
            getInfosUser();

            let modal = bootstrap.Modal.getInstance(document.getElementById('confirmPseudoModal'));
            modal.hide();

            alert("Pseudo mis à jour avec succès !");
        })
        .catch(error => console.error("❌ Erreur lors de la mise à jour du pseudo :", error));
}

document.getElementById('confirmPseudoChange').addEventListener('click', updatePseudo);

function getUserId() {
    return getCookie(UserIdCookieName);
}


// UPDATE EMAIL
const newPasswordInput = document.getElementById('NewPasswordInput');
const validatePasswordInput = document.getElementById('ValidateNewPasswordInput');
const btnModifyPassword = document.getElementById('btnModifyPassword');
const confirmModifyPassword = document.getElementById('btnConfirmPasswordChange');

btnModifyPassword.addEventListener('click', function () {
    const newPassword = newPasswordInput.value.trim();
    const confirmPassword = validatePasswordInput.value.trim();

    if (!validatePassword(newPasswordInput)) {
        alert("Le mot de passe ne respecte pas les critères.");
        return;
    }

    if (newPassword !== confirmPassword) {
        alert("Les mots de passe ne correspondent pas.");
        validatePasswordInput.classList.add('is-invalid');
        return;
    }

    const modal = new bootstrap.Modal(document.getElementById('confirmPasswordModal'));
    modal.show();
});

confirmModifyPassword.addEventListener('click', function () {
    updatePassword(); 
});

async function updatePassword() {
    let newPassword = newPasswordInput.value;
    let userId = getUserId();

    if (!newPassword.trim()) {
        alert("Veuillez entrer un mot de passe valide.");
        return;
    }

    try {
        showLoadingState(true);

        const hashedPassword = await hashPassword(newPassword);

        let myHeaders = new Headers();
        myHeaders.append("Content-Type", "application/json");
        myHeaders.append("X-AUTH-TOKEN", getToken());

        let raw = JSON.stringify({
            "password": hashedPassword
        });

        let requestOptions = {
            method: 'PUT',
            headers: myHeaders,
            body: raw,
            redirect: 'follow'
        };

        const response = await fetch(apiURL + `user/${userId}/password`, requestOptions);
        
        if (!response.ok) {
            throw new Error('Erreur lors de la mise à jour du mot de passe');
        }

        const result = await response.json();
        console.log("✅ Mot de passe mis à jour:", result);
        getInfosUser();

        let modal = bootstrap.Modal.getInstance(document.getElementById('confirmPasswordModal'));
        modal.hide();

        alert("Mot de passe mis à jour avec succès !");
    } catch (error) {
        console.error("❌ Erreur lors de la mise à jour du mot de passe :", error);
        alert("Erreur lors de la mise à jour du mot de passe.");
    } finally {
        showLoadingState(false);
    }
}

function showLoadingState(isLoading) {
    const loader = document.getElementById('loader');
    if (isLoading) {
        loader.style.display = 'block';
    } else {
        loader.style.display = 'none';
    }
}

function validatePassword(input) {
    const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_])[a-zA-Z\d\W_]{8,}$/;
    if (passwordRegex.test(input.value.trim())) {
        input.classList.add('is-valid');
        input.classList.remove('is-invalid');
        return true;
    } else {
        input.classList.remove('is-valid');
        input.classList.add('is-invalid');
        return false;
    }
}

async function hashPassword(password) {
    const encoder = new TextEncoder();
    const data = encoder.encode(password);
    const hashBuffer = await crypto.subtle.digest('SHA-256', data);
    const hashArray = Array.from(new Uint8Array(hashBuffer));
    const hashedPassword = hashArray.map(byte => byte.toString(16).padStart(2, '0')).join('');

    return hashedPassword;
}


// DELETE ACCOUNT
const btnDeleteAccount = document.getElementById('confirmDeleteButton');
const userId = getUserId();

btnDeleteAccount.addEventListener('click', function () {
    deleteAccount(); 
});

function deleteAccount() {
    let myHeaders = new Headers();
    myHeaders.append("Content-Type", "application/json");
    myHeaders.append("X-AUTH-TOKEN", getToken());

    let requestOptions = {
        method: 'DELETE',
        headers: myHeaders,
        redirect: 'follow'
    };

    fetch(apiURL + `user/${userId}`, requestOptions)
        .then(response => {
            if (response.ok) {
                return response.json();
            } else {
                throw new Error('Erreur lors de la suppression du compte');
            }
        })
        .then(result => {
            console.log("✅ Compte supprimé avec succès:", result);
            alert("Votre compte a été supprimé avec succès.");

            eraseCookie(tokenCookieName);
            eraseCookie(RoleCookieName);
            eraseCookie(UserIdCookieName);

            setTimeout(() => {
                window.location.href = "/";
            }, 500);
        })
        .catch(error => {
            console.error("❌ Erreur lors de la suppression du compte :", error);
            alert("Erreur lors de la suppression du compte.");
        });
}
