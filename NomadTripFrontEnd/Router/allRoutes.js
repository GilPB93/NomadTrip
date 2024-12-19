import Route from "./Route.js";

//Définir ici les routes
export const allRoutes = [
    new Route("/", "Accueil", "/pages/home.html", []),
    new Route("/contact", "Contact", "/pages/contact.html", []),
    new Route("/signup", "Inscription", "/pages/signup.html", [], "/js/signup.js"),
    new Route("/signin", "Connexion", "/pages/signin.html", [], "/js/signin.js"),
    new Route("/account", "Mon Compte", "/pages/account.html", ["user"]),
    new Route("/admin", "Mon Espace Admin", "/pages/admin.html", ["admin"]),
    new Route("/library", "Mes Carnets", "/pages/library.html", ["user"], "/js/library.js"),

];

//Le titre s'affiche comme ceci : Route.titre - websitename
export const websiteName = "NomadTrip";
