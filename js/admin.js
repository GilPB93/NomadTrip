document.addEventListener('DOMContentLoaded', () => {
    fetchStats();
});

fetchStats();

function fetchStats() {
    fetch(apiURL + 'admin/stats/totals', {
        method: 'GET',
        headers: {
            'X-AUTH-TOKEN' : getToken()
        },
        credentials: 'include'
    })
        .then(response => response.json())
        .then(data => {
            document.getElementById('totalUsers').textContent = data.totalUsers;

            document.getElementById('totalTravelbooks').textContent = data.totalTravelbooks;

            let lastSignupElement = document.getElementById('lastSignup');
            lastSignupElement.innerHTML = '';
            data.lastSignups.forEach(user => {
                let userItem = document.createElement('p');
                userItem.innerHTML = `<strong>${user.name}</strong> - <em>Inscrit le ${user.createdAt}</em>`;
                lastSignupElement.appendChild(userItem);
            });
        })
        .catch(error => console.error('Erreur lors de la récupération des statistiques:', error));
}

document.addEventListener("DOMContentLoaded", function () {
    loadUsers();
    loadTravelbooks();
});


// GET LIST OF USERS
function loadUsers() {
    fetch(apiURL + "user/all", {
        method: "GET",
        headers: {
            "X-AUTH-TOKEN": getToken()
        },
        credentials: "include"
    })
        .then(response => response.json())
        .then(users => {
            const selectUser = document.querySelector(".form-select-user");
            selectUser.innerHTML = '<option selected>Selectionnez l\'utilisateur souhaité</option>';

            users.forEach(user => {
                selectUser.innerHTML += `
                    <option value="${user.id}">
                        ${user.firstName} ${user.lastName} (${user.pseudo})
                    </option>`;
            });

            selectUser.addEventListener("change", function () {
                const userId = this.value;
                if (userId !== "Selectionnez l'utilisateur souhaité") {
                    showUserDetails(userId);
                }
            });
        });
}

    
// GET DETAILS OF USER
function showUserDetails(userId) {
    fetch(apiURL + `user/details/${userId}`, {
        method: "GET",
        headers: {
            "X-AUTH-TOKEN": getToken()
        },
        credentials: "include"
    })
        .then(response => response.json())
        .then(user => {
            alert(`Nom: ${user.firstName} ${user.lastName}\nEmail: ${user.email}\nPseudo: ${user.pseudo}\nCompte créé le: ${user.createdAt}`);
        });
}


// ACTIVATE/DEACTIVATE USER
function toggleUserStatus(userId) {
    fetch(apiURL + `user/toggle-status/${userId}`, { 
        method: "PATCH",
        headers: {
            "Content-Type": "application/json"
        },
        credentials: "include",
        body: JSON.stringify({ userId: userId })
    })
        .then(response => response.json())
        .then(data => alert(data.message));
}

// GET LIST OF TRAVELBOOKS
function loadTravelbooks() {
    fetch(apiURL + "travelbook/all", {
        method: "GET",
        headers: {
            "X-AUTH-TOKEN": getToken()
        },
        credentials: "include"
    })
        .then(response => response.json())
        .then(travelbooks => {
            const selectTravelbook = document.querySelector(".form-select-travelbook");
            selectTravelbook.innerHTML = '<option selected>Selectionnez le carnet souhaité</option>';

            travelbooks.forEach(travelbook => {
                selectTravelbook.innerHTML += `<option value="${travelbook.id}">${travelbook.title}</option>`;
            });

            selectTravelbook.addEventListener("change", function () {
                const travelbookId = this.value;
                if (travelbookId !== "Selectionnez le carnet souhaité") {
                    showTravelbookDetails(travelbookId);
                }
            });
        });
}

// GET DETAILS OF TRAVELBOOK
function showTravelbookDetails(travelbookId) {
    fetch(apiURL + `travelbook/details/${travelbookId}`, {
        method: "GET",
        headers: {
            "X-AUTH-TOKEN": getToken()
        },
        credentials: "include"
    })
        .then(response => response.json())
        .then(travelbook => {
            alert(`Titre: ${travelbook.title}\nDépart: ${travelbook.departureAt}\nRetour: ${travelbook.comebackAt}\nCarnet créé le: ${travelbook.createdAt}`);
        });
}

// PURGE LOGOUTS NULL
document.getElementById("btnPurgeLogs").addEventListener("click", purgeActivityLogs);

function purgeActivityLogs() {
    fetch(apiURL + "activity-log/purge-logout-null", {
        method: "DELETE",
        headers: {
            "X-AUTH-TOKEN": getToken(),
            "Content-Type": "application/json"
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error("Échec de la purge des logs");
        }
        return response.json();
    })
    .then(data => {
        console.log("✅ Logs purgés :", data);
        alert("Purge effectuée avec succès !");
    })
    .catch(error => {
        console.error("❌ Erreur lors de la purge :", error);
        alert("Erreur lors de la purge !");
    });
}
