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
        let infoMessage = document.querySelector('.user-details h4');

        userSelect.innerHTML = '<option selected>Sélectionnez l\'utilisateur souhaité</option>';

        data.forEach(user => {
            let option = document.createElement('option');
            option.value = user.id;
            option.textContent = `${user.name} (${user.email})`;
            userSelect.appendChild(option);
        });

        userSelect.addEventListener('change', function () {
            let selectedUserId = this.value;

            if (selectedUserId && selectedUserId !== "Sélectionnez l'utilisateur souhaité") {
                let selectedUser = data.find(user => user.id == selectedUserId);
                showUserDetails(selectedUser);

                if (infoMessage) {
                    infoMessage.style.display = "none";
                }
            } else {
                hideUserDetails();

                if (infoMessage) {
                    infoMessage.style.display = "block";
                }
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
        let infoMessage = document.querySelector('.travelbook-details h4');

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

                if (infoMessage) {
                    infoMessage.style.display = "none";
                }
            } else {
                hideTravelbookDetails();

                if (infoMessage) {
                    infoMessage.style.display = "block";
                }
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


// FETCH CONTACT MESSAGES
let messages = [];
let currentIndex = 0;
console.log("Script JS chargé et prêt.");

function fetchMessages() {
    console.log("Envoi de la requête pour récupérer les messages...");

    fetch(apiURL + 'contact/all', {
        method: 'GET',
        headers: {
            'X-AUTH-TOKEN': getToken()
        },
        credentials: 'include'
    })
        .then(response => {
            console.log("Réponse reçue de l'API", response);
            return response.json()
        })
        .then(data => {
            console.log("Données reçues:", data);

            if (!Array.isArray(data)) {
                console.error("Données invalides reçues, attendu un tableau:", data);
                return;
            }

            messages = data;
            console.log("Messages stockés:", messages);

            if (messages.length > 0) {
                currentIndex = 0;
                displayMessage(currentIndex);
            } else {
                document.getElementById("message-container").innerHTML = "<p>Aucun message disponible.</p>";
            }
        })
        .catch(error => console.error("Erreur lors de la récupération des messages:", error));
}

function displayMessage(index) {
    if (messages.length === 0) {
        console.warn("Aucun message à afficher.");
        return;
    }

    console.log("Affichage du message à l'index:", index);
    const message = messages[index];

    if (!message) {
        console.error("Message introuvable à l'index:", index);
        return;
    }

    document.getElementById("message-subject").innerText = message.subject;
    document.getElementById("message-name").innerText = message.name;
    document.getElementById("message-email").innerText = message.email;
    document.getElementById("message-content").value = message.message;

    console.log("Message affiché:", message);

}

document.getElementById("prev-message").addEventListener("click", function () {
    if (currentIndex > 0) {
        currentIndex--;
        displayMessage(currentIndex);
    }
});

document.getElementById("next-message").addEventListener("click", function () {
    if (currentIndex < messages.length - 1) {
        currentIndex++;
        displayMessage(currentIndex);
    }
});

document.getElementById("reply-message").addEventListener("click", function (event) {
    event.preventDefault();
    console.log("Bouton 'Répondre' cliqué");
    
    const email = document.getElementById("message-email").innerText;
    const messageId = messages[currentIndex]?.id;
    
    if (email) {
        console.log("Ouverture du client mail pour:", email);
        window.open("mailto:" + email + "?subject=Re:%20" + document.getElementById("message-subject").innerText);
            
            fetch(apiURL + `contact/reply/${messageId}`, { 
                method: "PATCH",
                headers: {
                    'X-AUTH-TOKEN': getToken()
                },
                credentials: 'include'
            })
                .then(response => response.json())
                .then(data => {
                    console.log("Statut mis à jour:", data);

                    messages.splice(currentIndex, 1);

                    if (messages.length > 0) {
                        if (currentIndex >= messages.length) {
                            currentIndex = messages.length - 1;
                        }
                        displayMessage(currentIndex);
                    } else {
                        document.getElementById("message-container").innerHTML = "<p>Aucun message non lu.</p>";
                    }
                })
            .catch(error => console.error("Erreur lors de la mise à jour du statut:", error));
        } else {
        console.error("Aucun email disponible pour répondre ou ID de message manquant.");
        }
});

fetchMessages();


// FETCH CONTACT MESSAGES REPLIED
let repliedMessages = [];
console.log("Chargement des messages répondus...");

function fetchRepliedMessages() {
    fetch(apiURL + 'contact/replied', {
        method: 'GET',
        headers: {
            'X-AUTH-TOKEN': getToken()
        },
        credentials: 'include'
    })
        .then(response => response.json())
        .then(data => {
            let messageSelect = document.getElementById('messageSelect');
            let infoMessage = document.querySelector('.message-details h4');

            messageSelect.innerHTML = '<option selected>Sélectionnez un message</option>';

            data.forEach(message => {
                let option = document.createElement('option');
                option.value = message.id;
                option.textContent = `${message.subject} - ${message.name}`;
                messageSelect.appendChild(option);
            });

            messageSelect.addEventListener('change', function () {
                let selectedMessageId = this.value;
                if (selectedMessageId) {
                    let selectedMessage = data.find(message => message.id == selectedMessageId);
                    showMessageDetails(selectedMessage);
                    if (infoMessage) infoMessage.style.display = "none"; 
                } else {
                    hideMessageDetails();
                    if (infoMessage) infoMessage.style.display = "block";
                }
            });
        })
        .catch(error => console.error('Erreur lors de la récupération des messages traités:', error));
    }

function showMessageDetails(message) {
    document.getElementById('messageSubject').textContent = message.subject;
    document.getElementById('messageName').textContent = message.name;
    document.getElementById('messageEmail').textContent = message.email;
    document.getElementById('messageSentAt').textContent = message.sentAt;
    document.getElementById('messageContent').value = message.message;
}

function hideMessageDetails() {
    document.getElementById('messageSubject').textContent = "-";
    document.getElementById('messageName').textContent = "-";
    document.getElementById('messageEmail').textContent = "-";
    document.getElementById('messageSentAt').textContent = "-";
    document.getElementById('messageContent').value = "";
}

fetchRepliedMessages();

