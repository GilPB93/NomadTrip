
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

                const deleteButton = document.createElement('button');
                deleteButton.textContent = 'X';
                deleteButton.classList.add('delete-button');

                deleteButton.addEventListener('click', (deleteEvent) => {
                        deleteEvent.preventDefault();
                        todoPlaces.removeChild(listItem);
                });

                listItem.textContent = `${placeName}, ${placeDetails}, ${placeDate}`;
                listItem.appendChild(deleteButton);
                todoPlaces.appendChild(listItem);

                placeNameInput.value = '';
                placeDetailsInput.value = '';
                placeDateInput.value = '';
                popover.style.display = 'none';
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

addFbButton.addEventListener('click', (event) => {
        event.preventDefault();
        const fbName = fbNameInput.value.trim();
        const fbDetails = fbDetailsInput.value.trim();
        const fbDate = fbDateInput.value.trim();

        if (fbName) {
                const listItem = document.createElement('li');

                const deleteButton = document.createElement('button');
                deleteButton.textContent = 'X';
                deleteButton.classList.add('delete-button');

                deleteButton.addEventListener('click', (deleteEvent) => {
                        deleteEvent.preventDefault();
                        todoFb.removeChild(listItem);
                });

                listItem.textContent = `${fbName}, ${fbDetails}, ${fbDate}`;
                listItem.appendChild(deleteButton);
                todoFb.appendChild(listItem);

                fbNameInput.value = '';
                fbDetailsInput.value = '';
                fbDateInput.value = '';
                popover.style.display = 'none';
        } else {
                alert('Saisissez les informations du restaurant/bar à tester');
        }
});

window.addEventListener('click', (event) => {
        if (event.target !== popoverFB && !popoverFB.contains(event.target) && event.target !== addButtonFB) {
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

                const deleteButton = document.createElement('button');
                deleteButton.textContent = 'X';
                deleteButton.classList.add('delete-button');

                deleteButton.addEventListener('click', (deleteEvent) => {
                        deleteEvent.preventDefault();
                        todoSouvenirs.removeChild(listItem);
                });

                listItem.textContent = `${souvenirsWhat}, ${souvenirsWho}`;
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