<?php
// Démarre une session
session_start();
// Point d'entrée de l'application
// Le fichier .htaccess va permettre de dire à Apache pour chaque requête
// "Si le fichier existe -> renvoie le"
// "Sinon, laisse index.php traiter la requête"
// Pour que cela fonctionne, il faut que le serveur pointe vers le dossier public
require_once __DIR__ . '/../router.php';
