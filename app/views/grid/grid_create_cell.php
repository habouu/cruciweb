<?php
$title = "Cases noires";
include 'views/partials/_header.php';
?>

<main class="main">
    <section class="grid-create-cell section">
        <h2>Coordonnées des cases noires</h2>

        <?php include 'views/partials/_message.php'; ?>

        <?php if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'registered'): ?>
            <form action="index.php?q=grid-create-cell" method="POST" autocomplete="off" class="grid-create-cell-form">
                <label for="blackcells">Renseigner les coordonnées des cases noires</label>
                <textarea name="blackcells" id="blackcells" cols="100" rows="1" placeholder="Exemple: 1E;3F;2A" required></textarea><br>

                <input type="hidden" name="grid_id" value="<?= isset($_GET['grid_id']) ? $_GET['grid_id'] : '' ?>">
                <button type="submit" class="grid-create-cell-btn">Définir les définitions</button>
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