import Route from "./Route.js";

//Définir ici les routes
export const allRoutes = [
    new Route("/", "Accueil", "./frontend/pages/home.html", []),
    new Route("/contact", "Contact", "./frontend/pages/contact.html", [], "./frontend/js/contact.js"),
    new Route("/signup", "Inscription", "./frontend/pages/signup.html", [], "./frontend/js/signup.js"),
    new Route("/signin", "Connexion", "./frontend/pages/signin.html", [], "./frontend/js/signin.js"),
    new Route("/account", "Mon Compte", "./frontend/pages/account.html", ["ROLE_USER"], "./frontend/js/account.js"),
    new Route("/admin", "Mon Espace Admin", "./frontend/pages/admin.html", ["ROLE_ADMIN,ROLE_USER"], "./frontend/js/admin.js"),
    new Route("/library", "Mes Carnets", "./frontend/pages/library.html", ["ROLE_USER"], "./frontend/js/library.js"),
    new Route("/about", "A Propos", "./frontend/pages/about.html", []),

];

//Le titre s'affiche comme ceci : Route.titre - websitename
export const websiteName = "NomadTrip";
