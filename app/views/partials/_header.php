<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="description" content="Cruciweb: Une plateforme de mots croisés">
        <meta name="author" content="Umm-Habîbah Ouattara">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="assets/css/style.css">
        <title>
            <?= isset($title) ? $title . ' - Cruciweb' : 'Cruciweb' ?>
        </title>
    </head>
    <body>
    
        <header class="header" id="header">
            <nav class="nav">
                <div class="nav__menu">
                    <ul class="nav__list">
                        <li class="nav__item">
                            <a href="index.php" class="nav__link">Accueil</a>
                        </li>
                        <?php if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin'): ?>
                            <li class="nav__item">
                                <a href="index.php?q=users" class="nav__link">Gestion utilisateur</a>
                                <a href="index.php?q=grids" class="nav__link">Gestion mots croisés</a>
                                <a href="index.php?q=logout" class="nav__link">Déconnexion</a>
                            </li>
                        <?php elseif (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'registered'): ?>
                            <li class="nav__item">
                                <a href="index.php?q=grid-create" class="nav__link">Créer une grille</a>
                                <a href="index.php?q=grid-save-user" class="nav__link">Mes grilles sauvegardées</a>
                                <a href="index.php?q=logout" class="nav__link">Déconnexion</a>
                            </li>
                        <?php else: ?>
                            <li class="nav__item">
                                <a href="index.php?q=login" class="nav__link">Connexion</a>
                                <a href="index.php?q=register" class="nav__link">Inscription</a>
                            </li>
                        <?php endif ?>
                    </ul>
                </div>
            </nav>
        </header>