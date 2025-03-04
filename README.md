# NomadTrip App: Back-End Part

The Back-end (API) part of the NomadTrip project was developed using Visual Studio Code (Version: 1.95.3).  
This project is a backend API built with Symfony. It includes secure user authentication, database management via Doctrine, and endpoints to interact with user-related data and travel logs.

[![Author](https://img.shields.io/badge/author-gilpb.tech%40hotmail.com-green.svg)](https://github.com/GilPB93)

## 📋 Prerequisites

### Development Environment
- **IDE**: PhpStorm 2024.3.2
- **PHP**: Version 8.3.13 (CLI)

## 🛠️ Extensions and Tools

### Required PHP Extensions
Make sure you have installed and enabled the necessary extensions for Doctrine and Symfony, such as:
- PDO (with support for your database)
- Intl
- Mbstring
- XML

### Additional Tools
- **Composer**: for dependency management
- **Git**: for version control

---  

## ⚙️ Main Dependencies

Here are the main libraries and tools used in this project:

### **Doctrine**
- `doctrine/orm`: ORM for entity and relationship management
- `doctrine/migrations`: Migration management
- `doctrine/doctrine-bundle`: Integration with Symfony

### **Symfony**
- `symfony/framework-bundle`: Symfony component integration
- `symfony/security-bundle`: Application security
- `symfony/serializer`: Data serialization and deserialization
- `symfony/twig-bundle`: Twig templating engine

### **Others**
- `nelmio/api-doc-bundle`: Interactive API documentation
- `phpunit/phpunit`: Testing framework
- `symfony/maker-bundle`: Code generation
- `vich/uploader-bundle`: File upload management
- `nelmio/cors-bundle`: CORS configuration

For a complete list, check the [composer.json](composer.json) file.

---  

## 🚀 Installation and Configuration

### 1. Clone the Repository
```bash
git clone https://github.com/GilPB93/NomadTripBackEnd
cd NomadTripBackEnd
```

### 2. Install Dependencies
Ensure Composer is installed, then run:
```bash
composer install
```

### 3. Configure Environment Variables
Create a .env.local file at the root of the project and configure the necessary environment variables.
```bash
cp .env .env.local
```

### 4. Create the Database and Run Migrations to Update It
```bash
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
```

### 5. Start the Development Server
```bash
symfony server:start
```

---

## 🌱 Branches
This project uses the following branches:
- `main`: The main branch containing the stable, production-ready version.
- `dev`: The development and testing branch. Use this branch to test new features and make modifications.


---

## 🧪 Tests
Unit and functional tests can be run using PHPUnit:
```bash
php bin/phpunit
```

---

## 📝 Documentation
The API documentation is automatically generated using NelmioApiDocBundle.
To access the documentation, open your browser and navigate to the following URL:
```
http://127.0.0.1:8000/api/doc
```

--- 
