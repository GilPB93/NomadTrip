import Route from "./Route.js";

//Définir ici les routes
export const allRoutes = [
    new Route("/", "Accueil", "/pages/home.html"),
    new Route("/contact", "Contact", "/pages/contact.html"),
    new Route("/signup", "Inscription", "/pages/signup.html"),
    new Route("/signin", "Connexion", "/pages/signin.html"),
    new Route("/account", "Mon Compte", "/pages/account.html"),
    new Route("/admin", "Mon Espace Admin", "/pages/admin.html"),
    new Route("/library", "Mes Carnets", "/pages/library.html"),

];

//Le titre s'affiche comme ceci : Route.titre - websitename
export const websiteName = "NomadTrip";
