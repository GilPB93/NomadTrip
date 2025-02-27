//FUNCTION TO CLOSE DE COLLAPSE
const collapseCreateTravelbook = document.getElementById('collapseCreateTravelbook');
const newTravelbookForm = document.getElementById('newTravelbookForm');

collapseCreateTravelbook.addEventListener('hidden.bs.collapse', function () {
        newTravelbookForm.reset()
});

//FUNCTION TO CLOSE DE MODAL & ERASE THE FORM
const EditionTravelbookModal = document.getElementById('EditionTravelbookModal');
const editTravelbookForm = document.getElementById('editTravelbookForm');

EditionTravelbookModal.addEventListener('hidden.bs.modal', function () {
        editTravelbookForm.reset();
});




        //LIST OF F&B TO DO
const addButtonFB = document.getElementById('add-button-fb');
const popoverFB = document.getElementById('popover-fb');
const addFbButton = document.getElementById('add-fb');
const todoFb = document.getElementById('todo-fb');
const fbNameInput = document.getElementById('fb-name');
const fbDetailsInput = document.getElementById('fb-details');
const fbDateInput = document.getElementById('fb-date');

addButtonFB.addEventListener('click', () => {
    popoverFB.style.display = 'block';
});

addFbButton.addEventListener('click', async (event) => {
    event.preventDefault();

    const fbName = fbNameInput.value.trim();
    const fbDetails = fbDetailsInput.value.trim();
    const fbDate = fbDateInput.value.trim();

    if (!fbName || !fbDetails || !fbDate) {
        alert('Veuillez renseigner tous les champs');
        return;
    }

    const newFB = {
        name: fbName,
        address: fbDetails,
        visitAt: fbDate,
        travelbook: travelbookId
    };

    try {
        // 🔹 Enregistrement en BDD avec fetch
        const response = await fetch(apiURL + 'fb', {
            method: 'POST',
            mode: 'cors',
            credentials: 'include',
            headers: {
                "Content-Type": "application/json",
                "X-AUTH-TOKEN": getToken() // Fonction pour récupérer le token
            },
            body: JSON.stringify(newFB)
        });

        if (!response.ok) {
            const errorMsg = await response.text();
            throw new Error(`Erreur API: ${errorMsg}`);
        }

        const savedFB = await response.json();

        // 🔹 Ajout à la liste UI
        const listItem = document.createElement('li');

        const checkbox = document.createElement('input');
        checkbox.type = 'checkbox';
        checkbox.className = 'checkmark';

        const itemText = document.createElement('span');
        itemText.textContent = `${savedFB.name}, ${savedFB.address}, ${savedFB.visitAt}`;

        const deleteButton = document.createElement('button');
        deleteButton.textContent = 'X';
        deleteButton.classList.add('delete-button');

        // 🔹 Suppression de l'élément en UI + BDD
        deleteButton.addEventListener('click', async () => {
            try {
                const deleteResponse = await fetch(apiURL + `fb/${savedFB.id}`, {
                    method: 'DELETE',
                    headers: {
                        "X-AUTH-TOKEN": getToken()
                    }
                });

                if (!deleteResponse.ok) throw new Error('Erreur lors de la suppression');

                todoFb.removeChild(listItem);
            } catch (error) {
                console.error('Erreur lors de la suppression:', error);
            }
        });

        listItem.appendChild(checkbox);
        listItem.appendChild(itemText);
        listItem.appendChild(deleteButton);
        todoFb.appendChild(listItem);

        // 🔹 Réinitialisation des inputs
        fbNameInput.value = '';
        fbDetailsInput.value = '';
        fbDateInput.value = '';
        popoverFB.style.display = 'none';

    } catch (error) {
        console.error('❌ Erreur lors de l\'ajout:', error);
    }
});

// 🔹 Fermeture du popover si on clique en dehors
window.addEventListener('click', (event) => {
    if (!popoverFB.contains(event.target) && event.target !== addButtonFB) {
        popoverFB.style.display = 'none';
    }
});



        //LIST OF SOUVENIRS TO BUY
const addButtonSouvenirs = document.getElementById('add-button-souvenirs');
const popoverSouvenirs = document.getElementById('popover-souvenirs');
const addSouvenirsButton = document.getElementById('add-souvenirs');
const todoSouvenirs = document.getElementById('todo-souvenirs');
const souvenirsWhatInput = document.getElementById('souvenirs-what');
const souvenirsWhoInput = document.getElementById('souvenirs-who');

addButtonSouvenirs.addEventListener('click', () => {
        popoverSouvenirs.style.display = 'block';
});

addSouvenirsButton.addEventListener('click', (event) => {
        event.preventDefault();
        const souvenirsWhat = souvenirsWhatInput.value.trim();
        const souvenirsWho = souvenirsWhoInput.value.trim();
    
        if (souvenirsWhat) {
            const listItem = document.createElement('li');
    
            const checkbox = document.createElement('input');
            checkbox.type = 'checkbox';
            checkbox.className = 'checkmark';
    
            const itemText = document.createElement('span');
            itemText.textContent = `${souvenirsWhat}, ${souvenirsWho}`;
    
            const deleteButton = document.createElement('button');
            deleteButton.textContent = 'X';
            deleteButton.classList.add('delete-button');
    
            deleteButton.addEventListener('click', (deleteEvent) => {
                deleteEvent.preventDefault();
                todoSouvenirs.removeChild(listItem);
            });
    
            listItem.appendChild(checkbox);
            listItem.appendChild(itemText);
            listItem.appendChild(deleteButton);
            todoSouvenirs.appendChild(listItem);
    
            souvenirsWhatInput.value = '';
            souvenirsWhoInput.value = '';
            popover.style.display = 'none';
        } else {
            alert('Saisissez les informations du souvenir à acheter');
        }
});

window.addEventListener('click', (event) => {
        if (event.target !== popoverSouvenirs && !popoverSouvenirs.contains(event.target) && event.target !== addButtonSouvenirs) {
            popoverSouvenirs.style.display = 'none';
        }
});

        //LIST OF PHOTOS TO ADD
const addButtonPhotos = document.getElementById('add-button-photos');
const popoverPhotos = document.getElementById('popover-photos');
const addPhotosButton = document.getElementById('add-photos');
const todoPhotos = document.getElementById('todo-photos');
const photosInput = document.getElementById('photos');

addButtonPhotos.addEventListener('click', () => {
        popoverPhotos.style.display = 'block';
});

addPhotosButton.addEventListener('click', (event) => {
        event.preventDefault();
        const selectedPhoto = photosInput.files[0];
    
        if (selectedPhoto) {
            const listItem = document.createElement('li');
            listItem.classList.add('thumbnail-item'); 
    
        const img = document.createElement('img');
        img.src = URL.createObjectURL(selectedPhoto); 
        img.alt = 'Miniature';
        img.classList.add('thumbnail');

        const deleteButtonPhotos = document.createElement('button');
        deleteButtonPhotos.textContent = 'X';
        deleteButtonPhotos.classList.add('delete-button-photos', 'hidden');

        deleteButtonPhotos.addEventListener('click', (deleteEvent) => {
        deleteEvent.preventDefault();
        todoPhotos.removeChild(listItem);
        });
    
        listItem.addEventListener('mouseenter', () => {
        deleteButtonPhotos.classList.remove('hidden');
        });

        listItem.addEventListener('mouseleave', () => {
        deleteButtonPhotos.classList.add('hidden');
        });

        listItem.appendChild(img);
        listItem.appendChild(deleteButtonPhotos);
        todoPhotos.appendChild(listItem);

        photosInput.value = '';
        popoverPhotos.style.display = 'none';
        } else {
            alert('Veuillez sélectionner une image.');
        }
});
    

window.addEventListener('click', (event) => {
        if ( event.target !== popoverPhotos && !popoverPhotos.contains(event.target) && event.target !== addButtonPhotos) {
        popoverPhotos.style.display = 'none';
        }
});
     


// CREATE TRAVELBOOK
const inputTitle = document.getElementById('title');
const inputImgCover = document.getElementById('imgCouverture');
const inputDepartureAt = document.getElementById('departureAt');
const inputArrivalAt = document.getElementById('comebackAt');
const inputFlightNumber = document.getElementById('flightNumber');
const inputAccommodation = document.getElementById('accommodation');
const btnCreateTravelbook = document.getElementById('btnCreateTravelbook');
const formNewTravelbook = document.getElementById('newTravelbookForm');

const inputs = [inputTitle, inputImgCover, inputDepartureAt, inputArrivalAt];
inputs.forEach(input => {
        if (input.type === 'file' || input.type === 'datetime-local') {
            input.addEventListener("change", () => validateInput(input, true));
        } else {
            input.addEventListener("input", () => validateInput(input, true));
            input.addEventListener("blur", () => validateInput(input, false));
        }
});

function validateInput(input, isTyping) {
        if (validateRequired(input)) {
            input.classList.add('is-valid');
            input.classList.remove('is-invalid');
        } else {
            input.classList.remove('is-valid');
            if (isTyping) {
                input.classList.add('is-invalid');
            }
        }
    
        validateInputsNewTravelbook();
}
    

function validateInputsNewTravelbook() {
        const inputTitleOk = validateRequired(inputTitle);
        const inputImgCoverOk = validateRequired(inputImgCover);
        const inputDepartureAtOk = validateRequired(inputDepartureAt);
        const inputArrivalAtOk = validateRequired(inputArrivalAt);

        btnCreateTravelbook.disabled = !(inputTitleOk && inputImgCoverOk && inputDepartureAtOk && inputArrivalAtOk);
}

function validateRequired(input) {
        if (input.type === 'file') {
            return input.files.length > 0;
        }
        
        if (input.type === 'datetime-local') {
            return input.value !== '';
        }
    
        return input.value.trim() !== '';
}
            
btnCreateTravelbook.addEventListener('click', createTravelbookForm);

function createTravelbookForm(event) {
    event.preventDefault();

    let formData = new FormData();
    formData.append("title", inputTitle.value);
    formData.append("departureAt", inputDepartureAt.value);
    formData.append("comebackAt", inputArrivalAt.value);
    formData.append("flightNumber", inputFlightNumber.value);
    formData.append("accommodation", inputAccommodation.value);

    if (inputImgCover.files.length > 0) {
        formData.append("imgCouvertureFile", inputImgCover.files[0]);
    }

    fetch(apiURL + 'travelbook', {
        method: 'POST',
        mode: 'cors',
        credentials: 'include',
        headers: {
            "X-AUTH-TOKEN": getToken()
        },
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            return response.text().then(text => {
                throw new Error("❌ Erreur HTTP : " + response.status + " " + text);
            });
        }
        return response.json();
    })
    .then(result => {
        console.log("✅ Travelbook créé :", result);
        
        let collapseElement = document.getElementById("collapseCreateTravelbook");
        let collapse = new bootstrap.Collapse(collapseElement, { toggle: false });
        collapse.hide();

        formNewTravelbook.reset();
        
        setTimeout(() => {
            location.reload();
        }, 500);
    })
    .catch(error => {
        console.error("❌ Erreur lors de la création du travelbook :", error);
    });   
    
}


// DISPLAY TRAVELBOOKS ON LIBRARY BY USER
fetchUserTravelbooks();

document.addEventListener("DOMContentLoaded", () => {
    fetchUserTravelbooks();
});

function fetchUserTravelbooks() {
    fetch(apiURL + "travelbook/user", {
        method: "GET",
        headers: {
            "X-AUTH-TOKEN": getToken(), // Ajoute le token si ton API est protégée
            "Accept": "application/json"
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error("Erreur lors de la récupération des travelbooks");
        }
        return response.json();
    })
    .then(travelbooks => {
        console.log("📚 Travelbooks récupérés :", travelbooks);
        displayTravelbooks(travelbooks);
    })
    .catch(error => console.error("❌ Erreur :", error));
}

function displayTravelbooks(travelbooks) {
    const travelCardsContainer = document.getElementById("allTravelCards");
    travelCardsContainer.innerHTML = ""; // Nettoie les anciennes cartes

    travelbooks.forEach(travelbook => {
        const travelCard = document.createElement("div");
        travelCard.classList.add("travel-card", "col-12", "col-lg-4", "text-white");

        // Détermine l'image du travelbook
        const imageUrl = travelbook.imgCouverture
            ? `http://127.0.0.1:8000/uploads/images/travelbooks/${travelbook.imgCouverture}`
            : "/img/default.jpg";

        travelCard.innerHTML = `
            <img src="${imageUrl}" class="travel-img" alt="${travelbook.title}">
            <p class="title-image">${travelbook.title}</p> 
            <button class="btn btn-show" type="button" data-travelbook-id="${travelbook.id}" data-bs-toggle="modal" data-bs-target="#ShowTravelbookModal">Voir</button> 
            <button class="btn btn-modif" type="button" data-travelbook-id="${travelbook.id}" data-bs-toggle="modal" data-bs-target="#EditionTravelbookModal" id="btnModifTravelbook">Modifier</button>     
        `;

        travelCardsContainer.appendChild(travelCard);
    });

    // Ajoute un EventListener à tous les boutons "Voir"
    document.querySelectorAll(".btn-show").forEach(button => {
        button.addEventListener("click", (event) => {
            const travelbookId = event.target.getAttribute("data-travelbook-id");
            console.log("📌 ID du travelbook sélectionné :", travelbookId); // 🔴 Debugging
            if (travelbookId) {
                loadTravelbookDetails(travelbookId);
            } else {
                console.error("⚠️ Aucune ID trouvée sur le bouton !");
            }
        });
    });

    // Ajoute un EventListener à tous les boutons "Modifier"
    document.querySelectorAll(".btn-modif").forEach(button => {
        button.addEventListener("click", (event) => {
            const travelbookId = event.target.getAttribute("data-travelbook-id");
            editTravelbook(travelbookId);
        });
    });
}



// SHOW MODAL TRAVELBOOK
function loadTravelbookDetails(travelbookId) {
    fetch(apiURL + `travelbook/${travelbookId}`, {
        method: "GET",
        headers: {
            "X-AUTH-TOKEN": getToken(), 
            "Accept": "application/json"
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error("❌ Erreur lors du chargement du Travelbook");
        }
        return response.json();
    })
    .then(travelbook => {
        console.log("📖 Travelbook chargé :", travelbook);
        updateModalWithTravelbook(travelbook);
    })
    .catch(error => console.error("❌ Erreur :", error));
}

function updateModalWithTravelbook(travelbook) {
    document.getElementById("ShowTravelbookModalLabel").innerText = travelbook.title;

    // Récupération et mise à jour de l'image de couverture
    console.log("🔍 Vérification imgCouvertureUrl :", travelbook.imgCouvertureUrl);

    const imageUrl = travelbook.imgCouvertureUrl 
        ? travelbook.imgCouvertureUrl 
        : "/img/default.jpg"; // Image par défaut si aucune couverture

    const imgCoverElement = document.querySelector("#imgCouvertureModalShow img");
    if (imgCoverElement) {
        console.log("✅ Image mise à jour :", imageUrl);
        imgCoverElement.src = imageUrl;
        imgCoverElement.alt = travelbook.title;
    } else {
        console.error("⚠️ L'élément <img> pour la couverture est introuvable !");
    }

    // Mise à jour des détails du voyage
    function formatDateTime(datetimeString) {
        const options = { weekday: 'long', day: '2-digit', month: 'long', year: 'numeric', hour: '2-digit', minute: '2-digit' };
        return new Date(datetimeString).toLocaleDateString('fr-FR', options);
    }
    document.getElementById("departureAtModalShow").innerHTML = `
        <h3>📅 Date et heure de départ :</h3> 
        <p><strong>${formatDateTime(travelbook.departureAt)}</strong></p>
    `;

    document.getElementById("comebackAtModalShow").innerHTML = `
        <h3>📅 Date et heure de retour :</h3> 
        <p><strong>${formatDateTime(travelbook.comebackAt)}</strong></p>
    `;

    // Calcul du nombre de jours
    const departure = new Date(travelbook.departureAt);
    const comeback = new Date(travelbook.comebackAt);
    const nbDays = Math.ceil((comeback - departure) / (1000 * 60 * 60 * 24));

    document.getElementById("nbDaysModalShow").innerHTML = `<h3>📅 Nombre de jours :</h3> ${nbDays} jours`;
    document.getElementById("flightNumberModalShow").innerHTML = `<h3>✈️ Numéro de vol :</h3> ${travelbook.flightNumber || "Non renseigné"}`;
    document.getElementById("accommodationModalShow").innerHTML = `<h3> 🏨 Logement :</h3> ${travelbook.accommodation || "Non renseigné"}`;

    // Mise à jour des activités et des restaurants/bars
    document.getElementById("allPlacesToVisitModalShow").innerHTML = travelbook.places.length > 0 
        ? travelbook.places.map(place => `<h3> 🎟️ ${place.name}</h3>`).join("") 
        : "<h3>🎟️ Aucune activité enregistrée</h3>";

    document.getElementById("allFBToDoModalShow").innerHTML = travelbook.fBs.length > 0
        ? travelbook.fBs.map(fb => `<h3>🍽️ ${fb.name}</h3>`).join("")
        : "<h3>🍽️ Aucun restaurant ou bar enregistré</h3>";

    // Mise à jour des photos
    const photoContainer = document.getElementById("allphotosTravelbookModalShow");
    photoContainer.innerHTML = "";
    if (travelbook.photos.length > 0) {
        travelbook.photos.forEach(photo => {
            const img = document.createElement("img");
            img.src = `http://127.0.0.1:8000/uploads/images/travelbooks/${photo.filename}`;
            img.classList.add("travel-photo");
            photoContainer.appendChild(img);
        });
    } else {
        photoContainer.innerHTML = "<h3>📸 Aucune photo enregistrée</h3>";
    }
}



// EDIT TRAVELBOOK BY USER


// DELETE TRAVELBOOK BY USER


