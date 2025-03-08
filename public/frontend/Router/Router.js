import Route from "./Route.js";
import { allRoutes, websiteName } from "./allRoutes.js";

// Route pour la page 404 (page introuvable)
const route404 = new Route("404", "Page introuvable", "./frontend/pages/404.html", []);

// Fonction pour récupérer la route correspondant à une URL donnée
const getRouteByUrl = () => {
    // Récupère le hash (ex: `#/contact` devient `/contact`)
    let path = window.location.hash.substring(1) || "/";
    let currentRoute = allRoutes.find(route => route.url === path) || route404;
    return currentRoute;
};

// Fonction pour charger le contenu de la page
const LoadContentPage = async () => {
    const actualRoute = getRouteByUrl();

    // Vérifier les droits d'accès à la page
    const allRolesArray = actualRoute.authorize;
    if (allRolesArray.length > 0) {
        if (allRolesArray.includes("disconnected")) { 
            if (isConnected()) {
                window.location.hash = "/"; // Rediriger vers l'accueil si connecté
                return;
            }
        } else {
            const roleUser = getRole();
            if (!allRolesArray.includes(roleUser)) {
                window.location.hash = "/"; // Rediriger vers l'accueil si accès refusé
                return;
            }
        }
    }

    // Récupération du contenu HTML de la route
    const html = await fetch(actualRoute.pathHtml).then(data => data.text());
    document.getElementById("main-page").innerHTML = html;

    // Charger le fichier JavaScript associé (si défini)
    if (actualRoute.pathJS) {
        let scriptTag = document.createElement("script");
        scriptTag.setAttribute("type", "text/javascript");
        scriptTag.setAttribute("src", actualRoute.pathJS);
        document.body.appendChild(scriptTag);
    }

    // Modifier le titre de la page
    document.title = actualRoute.title + " - " + websiteName;

    // Afficher ou masquer les éléments en fonction du rôle utilisateur
    hideAndShowElementsByRoles();
};

// Fonction pour gérer la navigation sur les liens internes
const routeEvent = (event) => {
    event.preventDefault();
    const targetHref = event.target.getAttribute("href");

    // Met à jour l'URL en utilisant le hash (ex: `#/contact`)
    window.location.hash = targetHref;
};

// Écouter les changements de hash et charger la bonne page
window.addEventListener("hashchange", LoadContentPage);
window.route = routeEvent;

// Charger la page au démarrage
LoadContentPage();