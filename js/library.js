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


//LISTS ON EDIT TRAVELBOOK
        //LIST OF PLACES TO VISIT
const addButtonPlaces = document.getElementById('add-button-places');
const popoverPlaces = document.getElementById('popover-places');
const addPlaceButton = document.getElementById('add-place');
const todoPlaces = document.getElementById('todo-places');
const placeNameInput = document.getElementById('place-name');
const placeDetailsInput = document.getElementById('place-details');
const placeDateInput = document.getElementById('place-date');

addButtonPlaces.addEventListener('click', () => {
        popoverPlaces.style.display = 'block';
    });
    
    addPlaceButton.addEventListener('click', (event) => {
        event.preventDefault();
        const placeName = placeNameInput.value.trim();
        const placeDetails = placeDetailsInput.value.trim();
        const placeDate = placeDateInput.value.trim();
    
        if (placeName) {
            const listItem = document.createElement('li');
    
            const checkbox = document.createElement('input');
            checkbox.type = 'checkbox';
            checkbox.className = 'checkmark';
    
            const placeText = document.createElement('span');
            placeText.textContent = `${placeName}, ${placeDetails}, ${placeDate}`;
    
            const deleteButton = document.createElement('button');
            deleteButton.textContent = 'X';
            deleteButton.classList.add('delete-button');
    
            deleteButton.addEventListener('click', (deleteEvent) => {
                deleteEvent.preventDefault();
                todoPlaces.removeChild(listItem);
            });
    
            listItem.appendChild(checkbox);
            listItem.appendChild(placeText);
            listItem.appendChild(deleteButton);
            todoPlaces.appendChild(listItem);
    
            placeNameInput.value = '';
            placeDetailsInput.value = '';
            placeDateInput.value = '';
            popoverPlaces.style.display = 'none';
        } else {
            alert('Saisissez les informations du lieu à visiter');
        }
});
    
window.addEventListener('click', (event) => {
        if (event.target !== popoverPlaces && !popoverPlaces.contains(event.target) && event.target !== addButtonPlaces) {
                popoverPlaces.style.display = 'none';
        }
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
     

// SHOW TRAVELCARDS


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

btnCreateTravelbook.addEventListener('click', createTravelbookForm);

function createTravelbookForm(event) {
    event.preventDefault();

    let formData = new FormData();

    formData.append("title", inputTitle.value);
    formData.append("departureAt", inputDepartureAt.value);
    formData.append("comebackAt", inputArrivalAt.value);
    formData.append("flightNumber", inputFlightNumber.value);
    formData.append("accommodation", inputAccommodation.value);

    if (inputImgCover.files[0]) {
        formData.append("imgCouverture", inputImgCover.files[0]);
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
        return response.text().then(text => {
            try {
                return JSON.parse(text); // ✅ Si c'est JSON, on le parse normalement
            } catch {
                throw new Error("❌ Erreur non JSON : " + text); // ❌ Affiche l'erreur HTML complète
            }
        });
    })
    .then(result => {
        console.log("✅ Travelbook créé :", result);
        formNewTravelbook.reset();
    })
    .catch(error => {
        console.error("❌ Erreur lors de la création du travelbook :", error);
    });
    
    
}

