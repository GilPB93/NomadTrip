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


// UPDATE PASSWORD


// DELETE ACCOUNT


// GET INFORMATION FROM USER

