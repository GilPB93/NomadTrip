import Route from "./Route.js";

//Définir ici les routes
export const allRoutes = [
    new Route("/", "Accueil", "/pages/home.html", []),
    new Route("/contact", "Contact", "/pages/contact.html", [], "/js/contact.js"),
    new Route("/signup", "Inscription", "/pages/signup.html", [], "/js/signup.js"),
    new Route("/signin", "Connexion", "/pages/signin.html", [], "/js/signin.js"),
    new Route("/account", "Mon Compte", "/pages/account.html", ["ROLE_USER"], "/js/account.js"),
    new Route("/admin", "Mon Espace Admin", "/pages/admin.html", ["ROLE_ADMIN,ROLE_USER"], "/js/admin.js"),
    new Route("/library", "Mes Carnets", "/pages/library.html", ["ROLE_USER"], "/js/library.js"),
    new Route("/about", "A Propos", "/pages/about.html", []),

];

//Le titre s'affiche comme ceci : Route.titre - websitename
export const websiteName = "NomadTrip";
