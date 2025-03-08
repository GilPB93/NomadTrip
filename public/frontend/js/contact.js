const contactForm = document.getElementById('contactForm');
const btnContact = document.getElementById('sendandsavemessage');

btnContact.addEventListener('click', saveContactMessage);

async function saveContactMessage(event) {
    event.preventDefault();

    // Désactiver le bouton pour éviter un double envoi
    btnContact.disabled = true;
    btnContact.textContent = "Envoi...";

    try {
        let dataForm = new FormData(contactForm);

        let raw = JSON.stringify({
            name: dataForm.get('name'),
            email: dataForm.get('email'),
            subject: dataForm.get('subject'),
            message: dataForm.get('message')
        });

        let response = await fetch("/api/contact/save", {
            method: 'POST',
            headers: {
                "Content-Type": "application/json"
            },
            body: raw
        });

        if (!response.ok) {
            // Lecture du message d'erreur depuis le backend
            let errorData = await response.json();
            throw new Error(errorData.error || "Une erreur est survenue lors de l'envoi.");
        }

        // Succès : Message bien envoyé
        alert("Message envoyé avec succès.");
        contactForm.reset();

    } catch (error) {
        console.error('Erreur:', error);
        alert(error.message);
    } finally {
        // Réactiver le bouton après la requête
        btnContact.disabled = false;
        btnContact.textContent = "Envoyer";
    }
}
