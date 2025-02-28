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


// GET LIST OF USERS
fetchListUsers();

function fetchListUsers() {
    fetch(apiURL + 'admin/stats/list-users', {
        method: 'GET',
        headers: {
            'X-AUTH-TOKEN': getToken()
        },
        credentials: 'include'
    })
    .then(response => response.json())
    .then(data => {
        let userSelect = document.getElementById('userSelect');
        userSelect.innerHTML = '<option selected>Sélectionnez l\'utilisateur souhaité</option>';

        data.forEach(user => {
            let option = document.createElement('option');
            option.value = user.id;
            option.textContent = `${user.name} (${user.email})`;
            userSelect.appendChild(option);
        });

        // Gestion de l'affichage des informations
        userSelect.addEventListener('change', function () {
            let selectedUserId = this.value;
            if (selectedUserId) {
                let selectedUser = data.find(user => user.id == selectedUserId);
                showUserDetails(selectedUser);
            } else {
                hideUserDetails();
            }
        });
    })
    .catch(error => console.error('Erreur lors de la récupération des utilisateurs:', error));
}

function showUserDetails(user) {
    document.getElementById('userName').textContent = `${user.name}`;
    document.getElementById('userEmail').textContent = `${user.email}`;
    document.getElementById('userCreatedAt').textContent = `${user.createdAt}`;
}

function hideUserDetails() {
    document.getElementById('userName').textContent = "";
    document.getElementById('userEmail').textContent = "";
    document.getElementById('userCreatedAt').textContent = "";
}


// GET LIST OF TRAVELBOOKS
fetchListTravelbooks();
const userID = getUserId();

function fetchListTravelbooks() {
    fetch(apiURL + 'admin/stats/list-travelbooks', {
        method: 'GET',
        headers: {
            'X-AUTH-TOKEN': getToken()
        },
        credentials: 'include'
    })
    .then(response => response.json())
    .then(data => {
        let travelbookSelect = document.getElementById('travelbookSelect');
        travelbookSelect.innerHTML = '<option selected>Sélectionnez un carnet de voyage</option>';

        data.forEach(userGroup => {
            // Création d'un optgroup pour chaque utilisateur
            let optgroup = document.createElement('optgroup');
            optgroup.label = userGroup.createdBy;

            userGroup.travelbooks.forEach(travelbook => {
                let option = document.createElement('option');
                option.value = travelbook.id;
                option.textContent = travelbook.title;
                optgroup.appendChild(option);
            });

            travelbookSelect.appendChild(optgroup);
        });

        // Gestion de l'affichage des informations
        travelbookSelect.addEventListener('change', function () {
            let selectedTravelbookId = this.value;
            let selectedTravelbook = null;

            // Recherche du carnet sélectionné dans les données groupées
            data.forEach(userGroup => {
                userGroup.travelbooks.forEach(travelbook => {
                    if (travelbook.id == selectedTravelbookId) {
                        selectedTravelbook = travelbook;
                    }
                });
            });

            if (selectedTravelbook) {
                showTravelbookDetails(selectedTravelbook, this.options[this.selectedIndex].parentNode.label);
            } else {
                hideTravelbookDetails();
            }
        });
    })
    .catch(error => console.error('Erreur lors de la récupération des carnets de voyage:', error));
}

function showTravelbookDetails(travelbook, createdBy) {
    document.getElementById('travelbookTitle').textContent = ` ${travelbook.title}`;
    document.getElementById('travelbookCreatedBy').textContent = ` ${createdBy}`;
    document.getElementById('travelbookCreatedAt').textContent = ` ${travelbook.createdAt}`;
}

function hideTravelbookDetails() {
    document.getElementById('travelbookTitle').textContent = "";
    document.getElementById('travelbookCreatedBy').textContent = "";
    document.getElementById('travelbookCreatedAt').textContent = "";
}

function getUserId(){
    return getCookie(UserIdCookieName);
}
