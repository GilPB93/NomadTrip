// FETCH INFORMATION ACCOUNT
document.addEventListener("DOMContentLoaded", getInfoUser);

const apiURL = "http://127.0.0.1:8000/api/";
const userId = getUserId();


function getUserId(){
    return getCookie(UserIdCookieName);
}

function getInfoUser() {
    if (!userId) {
        console.error("User ID not found in cookies");
        return;
    }   

    fetch(apiURL + `user/${userId}`, {
        method: "GET",
        headers: {
            "Content-Type": "application/json"
        }
    })
        .then(response => {
            if (!response.ok) {
                throw new Error("Error when fetching user information");
            }
            return response.json();
        })
        .then(user => {
            document.getElementById("lastNameInfo").textContent = user.lastName || "N/A";
            document.getElementById("firstNameInfo").textContent = user.firstName || "N/A";
            document.getElementById("pseudoInfo").textContent = user.pseudo || "N/A";
            document.getElementById("emailInfo").textContent = user.email || "N/A";
        })
        .catch(error => {
            console.error("Erreur :", error);
        });
}


// UPDATE PSEUDO
document.getElementById('btnModifyPseudo').addEventListener('click', function(e) {
    e.preventDefault();

    const newPseudo = document.getElementById('NewPseudoInput').value.trim();
    
    if (!newPseudo) {
        alert('Le pseudo ne peut pas être vide.');
        return;
    }

    fetch(`/api/user/${userId}`, {
        method: 'PUT', 
        headers: {
            'Content-Type': 'application/json', 
        },
        body: JSON.stringify({
            pseudo: newPseudo,
        }),
    })
    .then(response => {
        if (response.ok) {
            return response.json();
        } else {
            throw new Error('Erreur lors de la modification du pseudo');
        }
    })
    .then(data => {
        alert('Pseudo modifié avec succès !');
    })
    .catch(error => {
        console.error('Erreur:', error);
        alert('Une erreur est survenue. Veuillez réessayer plus tard.');
    });
});




// UPDATE EMAIL


// UPDATE PASSWORD


// DELETE ACCOUNT
const btnDeleteAccount = document.getElementById("btnDeleteAccount");
btnDeleteAccount.addEventListener("click", function() {
    console.log("Button clicked");
    deleteAccount();
});
function deleteAccount() {
    if (!userId) {
        console.error("User ID not found in cookies");
        return;
    }

    fetch(apiURL + `user/${userId}`, {
        method: "DELETE",
        headers: {
            "Content-Type": "application/json"
        }
    })
        .then(response => {
            if (!response.ok) {
                throw new Error("Error when deleting user account");
            }
            eraseCookie(tokenCookieName);
            eraseCookie(RoleCookieName);
            eraseCookie(UserIdCookieName);
            window.location.replace("/");
        })
        .catch(error => {
            console.error("Erreur :", error);
        });
}


