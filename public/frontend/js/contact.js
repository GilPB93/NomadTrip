const contactForm = document.getElementById('contactForm');
const btnContact = document.getElementById('sendandsavemessage');

btnContact.addEventListener('click', saveContactMessage);

function saveContactMessage (event){
    event.preventDefault();

    let dataForm = new FormData(contactForm);

    let myHeaders = new Headers();
    myHeaders.append("Content-Type", "application/json");

    let raw = JSON.stringify({
        "name": dataForm.get('name'),
        "email": dataForm.get('email'),
        "subject": dataForm.get('subject'),
        "message": dataForm.get('message')
    });

    let requestOptions = {
        method: 'POST',
        headers: myHeaders,
        body: raw,
        redirect: 'follow'
    };

    fetch(apiURL + "contact/save", requestOptions)
    .then(response => {
        if(response.ok){
            alert("Message envoyé avec succès.");
            contactForm.reset();
        } else {
            alert("Une erreur est survenue lors de l'envoi du message.");
        }
    })
    .catch(error => console.error('Erreur:', error));
}