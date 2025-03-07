# NomadTrip App: Front End

## Description  
NomadTrip is a travel planning application designed for digital nomads and travelers. It provides users with an intuitive interface to organize their trips, manage travel logs, and access useful travel-related resources. The front-end is built using modern web technologies, ensuring a responsive and interactive user experience.

[![Author](https://img.shields.io/badge/author-gilpb.tech%40hotmail.com-green.svg)](https://github.com/GilPB93)

---

## 📋 Prerequisites  
Here are the recommended versions of the required tools:

- **VS Code**: `version 1.96.4`  
- **Node.js**: `version 20.18.1`  

---

## 🛠️ Extensions and Dependencies  

### Required Extensions (VS Code):  
- **Live Sass Compiler** by Glenn Marks  
  [Extension Marketplace](https://marketplace.visualstudio.com/items?itemName=glenn2223.live-sass)

### Dependencies Used:  
- **Bootstrap**: `version 5.3.3`  
- **Bootstrap Icons**: `version 1.11.3`  

These dependencies are installed via `npm`. See the "Installation" section for more details.

---

## 🚀 Installation  

### 1. **Clone the Repository**  
```bash
git clone https://github.com/GilPB93/ZooNomadTripFrontEnd.git
cd NomadTripFrontEnd
```


### 2. Install Dependencies
Ensure that Node.js is installed, then run:
```bash
npm install
```

### 3. Configure the Sass Compiler
Install the Live Sass Compiler extension in VS Code.
Once installed, open your project in VS Code and activate the compiler for your Sass files.

### 4. Run the Project
You can start a local server with your preferred tool or use the command:
```bash
npm start
```

### 5. Access the Application
```bash
http://localhost:3000
```

---

## 🌱 Branches
This project uses the following branches:
- `main`: The main branch containing the stable, production-ready version.
- `dev`: The development and testing branch. Use this branch to test new features and make modifications.

---

## 🎨 Styling with Sass and Bootstrap
The styles are written in Sass and compiled using the Live Sass Compiler extension. 
We also use Bootstrap for a responsive design and ready-to-use components.

### Customizing Bootstrap
The Sass files allow overriding Bootstrap variables to customize the theme.

---

## 📝 Useful Links
- [GitHub Repository](https://github.com/GilPB93/ZooArcadiaFrontEnd)
- [Bootstrap Documentation](https://getbootstrap.com/docs/5.3/)


--- 



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
