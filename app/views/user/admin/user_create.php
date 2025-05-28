<?php
$title = "Créer un compte";
include 'views/partials/_header.php';
?>

<main class="main container">
    <section class="register section">

        <?php include 'views/partials/_message.php'; ?>

        <?php if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin'): ?>
            <form action="index.php?q=user-create" method="POST" class="register-form" autocomplete="off">
                <h2>Créer un compte</h2>
                <label for="username">Identifiant</label>
                <input type="text" name="username" id="username" required><br>

                <label for="email">Adresse e-mail</label>
                <input type="email" name="email" id="email" placeholder="xyz@exemple.com" required><br>

                <label for="password">Mot de passe</label>
                <input type="password" name="password" id="password" placeholder="Au moins 6 caractères" required><br>

                <button type="submit" class="btn-register">Créer un compte</button>
            </form>
        <?php elseif (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'registered'):
            header('Location: index.php?q=grids');
            exit; ?>
        <?php else:
            header('Location: index.php?q=grids');
            exit;
        endif ?>
    </section>
</main>


<?php include 'views/partials/_footer.php'; ?> 