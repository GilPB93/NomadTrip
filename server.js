const express = require('express');
const app = express();
const path = require('path');

// Servir les fichiers statiques du dossier public
app.use(express.static(path.join(__dirname)));

// Si aucune route ne correspond, renvoyer index.html
app.get('*', (req, res) => {
  res.sendFile(path.join(__dirname, 'index.html'));
});

// Définir le port Heroku
const PORT = process.env.PORT || 5000;
app.listen(PORT, () => console.log(`Serveur en ligne sur le port ${PORT}`));
