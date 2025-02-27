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
            let travelbookId = event.target.getAttribute("data-travelbook-id");
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
            let travelbookId = event.target.getAttribute("data-travelbook-id");
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
            img.src = `http://127.0.0.1:8000/uploads/photos/${photo.filename}`;
            img.classList.add("travel-photo");
            photoContainer.appendChild(img);
        });
    } else {
        photoContainer.innerHTML = "<h3>📸 Aucune photo enregistrée</h3>";
    }
}


// EDIT TRAVELBOOK BY USER
const travelbookId = document.getElementById("EditionTravelbookModal").getAttribute("data-travelbook-id");

function editTravelbook(travelbookId) {
    document.getElementById("EditionTravelbookModal").setAttribute("data-travelbook-id", travelbookId);

    fetch(apiURL + `travelbook/${travelbookId}`, {
        method: "GET",
        headers: {
            "X-AUTH-TOKEN": getToken(),
            "Accept": "application/json"
        }
    })
    .then(response => {
        if (!response.ok) throw new Error("Erreur lors du chargement du carnet de voyage");
        return response.json();
    })
    .then(travelbook => {
        console.log("📝 Carnet de voyage en édition :", travelbook);
        
        // Remplir les champs principaux
        document.getElementById("edit-title").value = travelbook.title;
        document.getElementById("edit-departureAt").value = travelbook.departureAt.substring(0, 16);
        document.getElementById("edit-comebackAt").value = travelbook.comebackAt.substring(0, 16);
        document.getElementById("edit-flightNumber").value = travelbook.flightNumber || "";
        document.getElementById("edit-accommodation").value = travelbook.accommodation || "";

        // Charger chaque liste individuellement
        loadPlaces(travelbook.places || []);
        loadFB(travelbook.fBs || []);
        loadSouvenirs(travelbook.souvenirs || []);
        loadPhotos(travelbook.photos || []);
    })
    .catch(error => console.error("❌ Erreur :", error));
}

document.getElementById("btnValidateEditTravelbook").addEventListener("click", async (event) => {
    event.preventDefault();
    updateTravelbook();
});


async function updateTravelbook() {
    const updatedTravelbook = {
        title: document.getElementById("edit-title").value,
        departureAt: document.getElementById("edit-departureAt").value,
        comebackAt: document.getElementById("edit-comebackAt").value,
        flightNumber: document.getElementById("edit-flightNumber").value || null,
        accommodation: document.getElementById("edit-accommodation").value || null,
    };

    try {
        // Envoyer les données mises à jour à l'API
        const response = await fetch(apiURL + `travelbook/${travelbookId}`, {
            method: "PUT",
            headers: {
                "Content-Type": "application/json",
                "X-AUTH-TOKEN": getToken(),
            },
            body: JSON.stringify(updatedTravelbook),
        });

        if (!response.ok) {
            throw new Error("Erreur lors de la mise à jour du carnet de voyage.");
        }

        console.log("✅ Carnet de voyage mis à jour avec succès !");
        alert("Modifications enregistrées !");

        const modal = bootstrap.Modal.getInstance(document.getElementById("EditionTravelbookModal"));
        modal.hide();

        fetchUserTravelbooks();
    } catch (error) {
        console.error("❌ Erreur :", error);
        alert("Une erreur s'est produite lors de la mise à jour.");
    }
}

    // MANAGE PLACES LIST
    function loadPlaces(places) {
        console.log("🔍 Structure complète des places :", JSON.stringify(places, null, 2));
    
        const todoPlaces = document.getElementById("todo-places");
        todoPlaces.innerHTML = ""; // Nettoie la liste
    
        if (!places || places.length === 0) {
            todoPlaces.innerHTML = "<p>Aucun lieu enregistré</p>";
            return;
        }
    
        places.forEach(place => {
            console.log("✅ Ajout du lieu :", place);
            const listItem = createPlaceItem(place);
            todoPlaces.appendChild(listItem);
        });
    }
    
    const addButtonPlaces = document.getElementById("add-button-places");
    const popoverPlaces = document.getElementById("popover-places");
    const addPlacesButton = document.getElementById("add-place");
    const todoPlaces = document.getElementById("todo-places");
    const placeNameInput = document.getElementById("place-name");
    const placeDetailsInput = document.getElementById("place-details");
    const placeDateInput = document.getElementById("place-date");
    
    addButtonPlaces.addEventListener("click", () => {
        popoverPlaces.style.display = "block";
    });

    addPlacesButton.addEventListener("click", async (event) => {
        event.preventDefault();
    
        const name = placeDetailsInput.value.trim();
        const address = placeDetailsInput.value.trim();
        const visitAt = placeDateInput.value.trim();
        let travelbookId = document.getElementById("EditionTravelbookModal").getAttribute("data-travelbook-id");

    
        if (!name || !address || !visitAt) {
            alert("Veuillez remplir tous les champs.");
            return;
        }
    
        const newPlace = { 
            name, 
            address, 
            visitAt, 
            travelbook: travelbookId 
        };
    
        try {
            const response = await fetch(apiURL + `places`, {
                method: "POST",
                headers: { 
                    "Content-Type": "application/json", 
                    "X-AUTH-TOKEN": getToken() 
                },
                body: JSON.stringify(newPlace)
            });
    
            if (!response.ok) {
                throw new Error("Erreur API lors de l'ajout du lieu.");
            }
    
            const savedPlace = await response.json();
    
            const listItem = createPlaceItem(savedPlace);
            todoPlaces.appendChild(listItem);
    
            placeNameInput.value = "";
            placeDetailsInput.value = "";
            placeDateInput.value = "";
            popoverPlaces.style.display = "none";
    
            console.log("✅ Lieu ajouté :", savedPlace);
        } catch (error) {
            console.error("❌ Erreur lors de l'ajout du lieu :", error);
        }
    });

    
    function createPlaceItem(place) { 
        const listItem = document.createElement("li");
        listItem.dataset.id = place.id;

        const checkbox = document.createElement("input");
        checkbox.type = "checkbox";
        checkbox.className = "checkmark";

        const placeNameText = place.name ? place.name : "❌ Aucun lieu";
        const placeDetailsText = place.address ? place.address : "❌ Non spécifié";
        const placeDateText = place.visitAt ? place.visitAt : "❌ Non spécifié";

    
        const itemText = document.createElement("span");
        itemText.textContent = `${placeNameText}, ${placeDetailsText}, ${placeDateText}`;
    
        const deleteButton = document.createElement("button");
        deleteButton.textContent = "X";
        deleteButton.classList.add("delete-button");
    
        deleteButton.addEventListener("click", async (event) => {
            if (confirm("Voulez-vous supprimer ce lieu ?")) {
                try {
                    const response = await fetch(apiURL + `places/${place.id}`, {
                        method: "DELETE",
                        headers: { 
                            "X-AUTH-TOKEN": getToken() 
                        }
                    });
    
                    if (!response.ok) {
                        throw new Error("Erreur lors de la suppression.");
                    }
    
                    listItem.remove();
                    console.log("✅ Lieu supprimé !");
                } catch (error) {
                    console.error("❌ Erreur lors de la suppression :", error);
                }
            }
        });
    
        listItem.appendChild(checkbox);
        listItem.appendChild(itemText);
        listItem.appendChild(deleteButton);
    
        return listItem;
    };
    
    window.addEventListener("click", (event) => {
        if (!popoverPlaces.contains(event.target) && event.target !== addButtonPlaces) {
            popoverPlaces.style.display = "none";
        }
    });
    
    
    
    // MANAGE FB LIST
    function loadFB(restaurants) { 
        const todoFB = document.getElementById("todo-fb");
        todoFB.innerHTML = ""; // Nettoie la liste
    
        if (!restaurants || restaurants.length === 0) {
            todoFB.innerHTML = "<p>Aucun restaurant/bar enregistré</p>";
            return;
        }
    
        restaurants.forEach(fb => {
            console.log("✅ Ajout du restaurant/bar :", fb);
            const listItem = createFBItem(fb);
            todoFB.appendChild(listItem);
        });
    }
    
    const addButtonFB = document.getElementById("add-button-fb");
    const popoverFB = document.getElementById("popover-fb");
    const addFBButton = document.getElementById("add-fb");
    const todoFB = document.getElementById("todo-fb");
    const fbNameInput = document.getElementById("fb-name");
    const fbDetailsInput = document.getElementById("fb-details");
    const fbDateInput = document.getElementById("fb-date");
    
    addButtonFB.addEventListener("click", () => {
        popoverFB.style.display = "block";
    });
    
    addFBButton.addEventListener("click", async (event) => {
        event.preventDefault();
    
        const name = fbNameInput.value.trim();
        const address = fbDetailsInput.value.trim();
        const visitAt = fbDateInput.value.trim();
        let travelbookId = document.getElementById("EditionTravelbookModal").getAttribute("data-travelbook-id");
    
        if (!name || !address || !visitAt) {
            alert("Veuillez remplir tous les champs.");
            return;
        }
    
        const newFB = { 
            name, 
            address, 
            visitAt, 
            travelbook: travelbookId 
        };
    
        try {
            const response = await fetch(apiURL + `fb`, {
                method: "POST",
                headers: { 
                    "Content-Type": "application/json", 
                    "X-AUTH-TOKEN": getToken() 
                },
                body: JSON.stringify(newFB)
            });
    
            if (!response.ok) {
                throw new Error("Erreur API lors de l'ajout du restaurant/bar.");
            }
    
            const savedFB = await response.json();
    
            const listItem = createFBItem(savedFB);
            todoFB.appendChild(listItem);
    
            fbNameInput.value = "";
            fbDetailsInput.value = "";
            fbDateInput.value = "";
            popoverFB.style.display = "none";
    
            console.log("✅ Restaurant/Bar ajouté :", savedFB);
        } catch (error) {
            console.error("❌ Erreur lors de l'ajout du restaurant/bar :", error);
        }
    });
    
    function createFBItem(fb) {
        console.log("📌 Création d'un élément Restaurant/Bar :", fb);
    
        const listItem = document.createElement("li");
        listItem.dataset.id = fb.id;

        const checkbox = document.createElement("input");
        checkbox.type = "checkbox";
        checkbox.className = "checkmark";

        const fbNameText = fb.name ? fb.name : "❌ Aucun restaurant/bar";
        const fbDetailsText = fb.address ? fb.address : "❌ Non spécifié";
        const fbDateText = fb.visitAt ? fb.visitAt : "❌ Non spécifié";
    
        const itemText = document.createElement("span");
        itemText.textContent = `${fbNameText}, ${fbDetailsText}, ${fbDateText}`;
    
        const deleteButton = document.createElement("button");
        deleteButton.textContent = "X";
        deleteButton.classList.add("delete-button");
    
        deleteButton.addEventListener("click", async () => {
            if (confirm("Voulez-vous supprimer ce restaurant/bar ?")) {
                try {
                    const response = await fetch(apiURL + `fb/${fb.id}`, {
                        method: "DELETE",
                        headers: { 
                            "X-AUTH-TOKEN": getToken() 
                        }
                    });
    
                    if (!response.ok) {
                        throw new Error("Erreur lors de la suppression.");
                    }
    
                    listItem.remove();
                    console.log("✅ Restaurant/Bar supprimé !");
                } catch (error) {
                    console.error("❌ Erreur lors de la suppression :", error);
                }
            }
        });
    
        listItem.appendChild(checkbox);
        listItem.appendChild(itemText);
        listItem.appendChild(deleteButton);
    
        return listItem;
    }
    
    window.addEventListener("click", (event) => {
        if (!popoverFB.contains(event.target) && event.target !== addButtonFB) {
            popoverFB.style.display = "none";
        }
    });
    
    
    // MANAGE SOUVENIRS LIST
    function loadSouvenirs(souvenirs) {
        const todoSouvenirs = document.getElementById("todo-souvenirs");
        todoSouvenirs.innerHTML = "";
    
        if (!souvenirs || souvenirs.length === 0) {
            todoSouvenirs.innerHTML = "<p>Aucun souvenir enregistré</p>";
            return;
        }
    
        souvenirs.forEach(souvenir => {
            console.log("✅ Ajout de souvenir :", souvenir);
            const listItem = createSouvenirItem(souvenir);
            todoSouvenirs.appendChild(listItem);
        });
    }
    
    const addButtonSouvenirs = document.getElementById("add-button-souvenirs");
    const popoverSouvenirs = document.getElementById("popover-souvenirs");
    const addSouvenirsButton = document.getElementById("add-souvenirs");
    const todoSouvenirs = document.getElementById("todo-souvenirs");
    const souvenirsWhatInput = document.getElementById("souvenirs-what");
    const souvenirsWhoInput = document.getElementById("souvenirs-who");

    addButtonSouvenirs.addEventListener("click", () => {
        popoverSouvenirs.style.display = "block";
    });

    addSouvenirsButton.addEventListener("click", async (event) => {
    event.preventDefault();

    const souvenirsWhat = souvenirsWhatInput.value.trim();
    const souvenirsWho = souvenirsWhoInput.value.trim();
    let travelbookId = document.getElementById("EditionTravelbookModal").getAttribute("data-travelbook-id");

    if (!souvenirsWhat) {
        alert("Saisissez le souvenir à acheter.");
        return;
    }

    const newSouvenir = {
        what: souvenirsWhat,
        forWho: souvenirsWho,
        travelbook: travelbookId
    };

    try {
        const response = await fetch(apiURL + "souvenirs", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-AUTH-TOKEN": getToken(),
            },
            body: JSON.stringify(newSouvenir),
        });

        if (!response.ok) {
            throw new Error("Erreur lors de l'ajout du souvenir.");
        }

        const savedSouvenir = await response.json();

        const listItem = createSouvenirItem(savedSouvenir);
        todoSouvenirs.appendChild(listItem);

        souvenirsWhatInput.value = "";
        souvenirsWhoInput.value = "";
        popoverSouvenirs.style.display = "none";

        console.log("✅ Souvenir ajouté :", savedSouvenir);
    } catch (error) {
        console.error("❌ Erreur lors de l'ajout du souvenir :", error);
    }
    });

    function createSouvenirItem(souvenir) {    
        const listItem = document.createElement("li");
        listItem.dataset.id = souvenir.id;
    
        const checkbox = document.createElement("input");
        checkbox.type = "checkbox";
        checkbox.className = "checkmark";
    
        const whatText = souvenir.what ? souvenir.what : "❌ Aucun élément";
        const forWhoText = souvenir.forWho ? souvenir.forWho : "❌ Non spécifié";
    
        const itemText = document.createElement("span");
        itemText.textContent = `${whatText}, ${forWhoText}`;
    
        const deleteButton = document.createElement("button");
        deleteButton.textContent = "X";
        deleteButton.classList.add("delete-button");
    
        deleteButton.addEventListener("click", async () => {
            if (confirm("Voulez-vous supprimer ce souvenir ?")) {
                try {
                    const response = await fetch(apiURL + `souvenirs/${souvenir.id}`, {
                        method: "DELETE",
                        headers: { "X-AUTH-TOKEN": getToken() },
                    });
    
                    if (!response.ok) {
                        throw new Error("Erreur lors de la suppression.");
                    }
    
                    listItem.remove();
                    console.log("✅ Souvenir supprimé !");
                } catch (error) {
                    console.error("❌ Erreur lors de la suppression :", error);
                }
            }
        });
    
        listItem.appendChild(checkbox);
        listItem.appendChild(itemText);
        listItem.appendChild(deleteButton);
    
        return listItem;
    }
    
    window.addEventListener("click", (event) => {
        if (!popoverSouvenirs.contains(event.target) && event.target !== addButtonSouvenirs) {
            popoverSouvenirs.style.display = "none";
        }
    });
    

    // MANAGE PHOTOS LIST
    function loadPhotos(photos) {   
        const todoPhotos = document.getElementById("todo-photos");
        todoPhotos.innerHTML = ""; // Nettoie la liste
    
        if (!photos || photos.length === 0) {
            todoPhotos.innerHTML = "<p>Aucune photo ajoutée</p>";
            return;
        }
    
        photos.forEach(photo => {
            console.log("✅ Ajout de la photo :", photo);
            const listItem = createPhotoItem(photo);
            todoPhotos.appendChild(listItem);
        });
    }
    
    const addButtonPhotos = document.getElementById("add-button-photos");
    const popoverPhotos = document.getElementById("popover-photos");
    const addPhotosButton = document.getElementById("add-photos");
    const todoPhotos = document.getElementById("todo-photos");
    const photosInput = document.getElementById("photos");

    
    addButtonPhotos.addEventListener("click", () => {
        popoverPhotos.style.display = "block";
    });
    
    addPhotosButton.addEventListener("click", async (event) => {
        event.preventDefault();
    
        const file = photosInput.files[0];
        let travelbookId = document.getElementById("EditionTravelbookModal").getAttribute("data-travelbook-id");
        if (!file) {
            alert("Veuillez sélectionner une image.");
            return;
        }
    
        const formData = new FormData();
        formData.append("imgUrl", file);
        formData.append("travelbook", travelbookId);
    
        try {
            const response = await fetch(apiURL + `photos`, {
                method: "POST",
                headers: { "X-AUTH-TOKEN": getToken() },
                body: formData
            });
    
            if (!response.ok) {
                throw new Error("Erreur API lors de l'ajout de la photo.");
            }
    
            const savedPhoto = await response.json();
    
            const listItem = createPhotoItem(savedPhoto);
            todoPhotos.appendChild(listItem);
    
            photosInput.value = "";
            popoverPhotos.style.display = "none";
    
            console.log("✅ Photo ajoutée :", savedPhoto);
        } catch (error) {
            console.error("❌ Erreur lors de l'ajout de la photo :", error);
        }
    });
    
    function createPhotoItem(photo) {
        console.log("📌 Création d'un élément Photo :", photo);
    
        const listItem = document.createElement("li");
        listItem.dataset.id = photo.id;
        listItem.classList.add("thumbnail-item");
    
        const img = document.createElement("img");
        img.src = `${photo.imgUrl}`;
        img.alt = "Photo souvenir";
        img.classList.add("thumbnail");
    
        const deleteButton = document.createElement("button");
        deleteButton.textContent = "X";
        deleteButton.classList.add("delete-button-photos");
    
        deleteButton.addEventListener("click", async () => {
            if (confirm("Voulez-vous supprimer cette photo ?")) {
                try {
                    const response = await fetch(apiURL + `photos/${photo.id}`, {
                        method: "DELETE",
                        headers: { "X-AUTH-TOKEN": getToken() }
                    });
    
                    if (!response.ok) {
                        throw new Error("Erreur lors de la suppression.");
                    }
    
                    listItem.remove();
                    console.log("✅ Photo supprimée !");
                } catch (error) {
                    console.error("❌ Erreur lors de la suppression :", error);
                }
            }
        });
    
        listItem.appendChild(img);
        listItem.appendChild(deleteButton);
        
        return listItem;
    }
    
    window.addEventListener("click", (event) => {
        if (!popoverPhotos.contains(event.target) && event.target !== addButtonPhotos) {
            popoverPhotos.style.display = "none";
        }
    });
    



    // CREATE LIST ITEM
    function createListItem(id, text, deleteFunction) {
        const listItem = document.createElement("li");
        listItem.dataset.id = id;
    
        const checkbox = document.createElement("input");
        checkbox.type = "checkbox";
        checkbox.className = "checkmark";
    
        const itemText = document.createElement("span");
        itemText.textContent = text;
    
        const deleteButton = document.createElement("button");
        deleteButton.textContent = "X";
        deleteButton.classList.add("delete-button");
        deleteButton.addEventListener("click", () => deleteFunction(id));
    
        listItem.appendChild(checkbox);
        listItem.appendChild(itemText);
        listItem.appendChild(deleteButton);
    
        return listItem;
    }
    




// DELETE TRAVELBOOK BY USER


