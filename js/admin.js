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