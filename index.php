<?php
// Inclure les fichiers de configuration et les en-têtes globaux
// require_once 'config.php';
require_once 'includes/header.php';

// Gestion des routes
$page = isset($_GET['page']) ? $_GET['page'] : 'home';

switch ($page) {
    case 'categories':
        require 'pages/categories/index.php';
        break;
    case 'add-category':
        require 'pages/categories/add.php';
        break;
    case 'edit-category':
        require 'pages/categories/edit.php';
        break;
    case 'delete-category':
        require 'pages/categories/delete.php';
        break;
    case 'events':
        require 'pages/events/index.php';
        break;
    case 'showtimes':
        require 'pages/showtimes/index.php';
        break;
    default:
        echo "<h1>Page d'accueil</h1>";
        break;
}

require_once 'includes/footer.php';
