<?php
$title = "Infos générales d'une grille";
include 'views/partials/_header.php';
?>

<main class="main">
    <section class="grid-create section">
        <?php include 'views/partials/_message.php'; ?>
        <h2>Infos générales de la grille</h2>

        <?php if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'registered'): ?>
            <form action="index.php?q=grid-create" method="POST" autocomplete="off" class="grid-create-form">
                <label for="name">Nom de la grille</label>
                <input type="text" name="name" id="name" required><br>

                <label for="nb_row">Nombre de ligne</label>
                <input type="number" name="nb_row" id="nb_row" min="1" required><br>

                <label for="nb_col">Nombre de colonne</label>
                <input type="number" name="nb_col" id="nb_col" min="1" required><br>

                <label for="level">Niveau de difficulté</label>
                <select name="level" id="level" required>
                    <option value="">-- Choisir un niveau de difficulté --</option>
                    <option value="easy">Débutant</option>
                    <option value="medium">Intermédiaire</option>
                    <option value="hard">Expert</option>
                </select><br>

                <button type="submit" class="grid-create-btn">Définir les cases noires</button>
            </form>
        <?php elseif (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin'):
            header('Location: index.php?q=grids');
            exit;
        else: ?>
            <p>
            Vous devez être <a href="index.php?q=login">connecté</a> pour accéder à cette page.
            </p>
        <?php endif ?>
    </section>
</main>

<?php include 'views/partials/_footer.php'; ?> 