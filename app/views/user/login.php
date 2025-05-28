<?php
$title = "Se connecter";
include 'views/partials/_header.php';
?>

<?php if (isset($_SESSION['user'])):
    header('Location: index.php?q=grids');
    exit;
else: ?>
    <main class="main container">
        <?php include 'views/partials/_message.php'; ?>
        <section class="login section">

            <form action="index.php?q=login" method="POST" class="login-form" autocomplete="off">

                <h2>Se connecter</h2>
                <label for="identifiant">Identifiant ou Adresse e-mail</label>
                <input type="text" name="identifiant" id="identifiant" required><br>

                <label for="password">Mot de passe</label>
                <input type="password" name="password" id="password" placeholder="******" required><br>

                <button type="submit" class="btn-login">Se connecter</button>
            </form>
        </section>
    </main>
<?php endif ?>


<?php include 'views/partials/_footer.php'; ?> 