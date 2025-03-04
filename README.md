# App NomadTrip : Partie Back-End

La partie Back-end (API) du projet NomadTrip a été réalisé avec Visual Studio Code (Version: 1.95.3).
Ce projet est une API backend construite avec Symfony. Il comprend une authentification utilisateur sécurisée, 
une gestion de base de données via Doctrine, et des endpoints pour interagir avec les données liées aux utilisateurs et aux carnets de voyage.

[![Author](https://img.shields.io/badge/author-gilpb.tech%40hotmail.com-green.svg)](https://github.com/GilPB93)

## 📋 Pré-requis

### Environnement de développement
- **IDE** : PhpStorm 2024.3.2
- **PHP** : Version 8.3.13 (CLI)

## 🛠️ Extensions et outils

### Extensions PHP nécessaires
Assurez-vous d'avoir installé et activé les extensions nécessaires à Doctrine et Symfony, telles que :
- PDO (avec le support pour votre base de données)
- Intl
- Mbstring
- XML

### Outils supplémentaires
- **Composer** : pour la gestion des dépendances
- **Git** : pour le contrôle de version

---

## ⚙️ Dépendances principales

Voici les principales bibliothèques et outils utilisés dans ce projet :

### **Doctrine**
- `doctrine/orm` : ORM pour la gestion des entités et des relations
- `doctrine/migrations` : Gestion des migrations
- `doctrine/doctrine-bundle` : Intégration avec Symfony

### **Symfony**
- `symfony/framework-bundle` : Intégration des composants Symfony
- `symfony/security-bundle` : Sécurisation de l'application
- `symfony/serializer` : Sérialisation et désérialisation des données
- `symfony/twig-bundle` : Moteur de templates Twig

### **Autres**
- `nelmio/api-doc-bundle` : Documentation interactive de l'API
- `phpunit/phpunit` : Framework pour les tests unitaires
- `symfony/maker-bundle` : Génération de code
- `vich/uploader-bundle` : Gestion des fichiers uploadés
- `nelmio/cors-bundle` : Configuration des CORS

Pour une liste complète, consultez le fichier [composer.json](composer.json).

---

## 🚀 Installation et configuration

### 1. Cloner le dépôt
```bash
git clone https://github.com/GilPB93/NomadTripBackEnd
cd NomadTripBackEnd
```

### 2. Installer les dépendances
Assurez-vous que Composer est installé, puis exécutez :
```bash
composer install
```

### 3. Configurer les variables d'environnement
Créez un fichier `.env.local` à la racine du projet et configurez les variables d'environnement nécessaires.
```bash
cp .env .env.local
```

### 4. Créer la base de données et exécuter les migrations pour mettre à jour la base de données :
```bash
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
```

### 5. Démarrer le serveur de développement
```bash
symfony server:start
```

---

## 🌱 Branches
Ce projet utilise les branches suivantes :

- `main` : Branche principale contenant la version stable et prête pour la production.
- `dev` : Branche dédiée au développement et aux tests. Utilisez cette branche pour tester de nouvelles fonctionnalités et apporter des modifications.


---

## 🧪 Tests
Les tests unitaires et fonctionnels peuvent être exécutés avec PHPUnit :
```bash
php bin/phpunit
```

---

## 📝 Documentation
La documentation de l'API est générée automatiquement à l'aide de NelmioApiDocBundle.
Pour accéder à la documentation, ouvrez votre navigateur et accédez à l'URL suivante :
```
http://127.0.0.1:8000/api/doc
```

--- 


[⬆️ Vers le haut](#NomadTripBackEnd)
```
