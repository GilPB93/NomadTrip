// GET INFORMATION FROM USERS
getTotalUsers();

document.addEventListener("DOMContentLoaded", getTotalUsers);
function getTotalUsers() {
    let myHeaders = new Headers();
    myHeaders.append("X-AUTH-TOKEN", getToken());

    let requestOptions = {
        method: 'GET',
        headers: myHeaders,
        redirect: 'follow',
        mode: 'cors',
        credentials: 'include',
    };

    fetch(apiURL + "user/total", requestOptions)
        .then(response => {
            if (response.ok) {
                return response.json();
            } else {
                throw new Error('Utilisateur invalide');
            }
        })
        .then(users => {
            console.log("✅ Données reçues:", users);
            document.getElementById("totalUsers").innerText = users.totalUsers || "N/A";
        })
        .catch(error => console.error("Erreur lors de la récupération des infos :", error));
}