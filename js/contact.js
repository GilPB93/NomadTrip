document.addEventListener("DOMContentLoaded", function () {
    document.getElementById("sendandsavemessage").addEventListener("click", async function (event) {
        event.preventDefault(); // Empêche le rechargement de la page

        // Récupération des valeurs des champs
        const name = document.getElementById("contactName").value.trim();
        const email = document.getElementById("contactEmail").value.trim();
        const subject = document.getElementById("contactTitle").value.trim();
        const message = document.getElementById("contactMessage").value.trim();

        // Vérification de base
        if (!name || !email || !subject || !message) {
            alert("Veuillez remplir tous les champs.");
            return;
        }

        // Objet contenant les données
        const formData = { name, email, subject, message };

        try {
            // Envoi du message par email
            let emailResponse = await fetch("/api/contact/send", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify(formData)
            });

            let emailData = await emailResponse.json();
            if (emailResponse.ok) {
                console.log("Email envoyé:", emailData.message);
            } else {
                throw new Error(emailData.message || "Erreur lors de l'envoi de l'email");
            }

            // Sauvegarde du message dans la base de données
            let saveResponse = await fetch("/api/contact/save", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify(formData)
            });

            let saveData = await saveResponse.json();
            if (saveResponse.ok) {
                console.log("Message enregistré:", saveData.message);
                alert("Votre message a bien été envoyé et enregistré !");
                
                // Réinitialiser le formulaire après succès
                document.querySelector("form").reset();
            } else {
                throw new Error(saveData.message || "Erreur lors de l'enregistrement du message");
            }

        } catch (error) {
            console.error("Erreur:", error);
            alert("Une erreur est survenue. Veuillez réessayer.");
        }
    });
});
