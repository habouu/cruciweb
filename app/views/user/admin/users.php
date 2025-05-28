<?php
$title = "Liste des utilisateurs";
include 'views/partials/_header.php';
?>

<main class="main">
    <section class="users-list section">
        <h2>Liste des utilisateurs</h2>

        <?php include 'views/partials/_message.php'; ?>

        - <a href="index.php?q=user-create" class="users-list-link">Créer un utilisateur</a><br><br>

        <?php if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin'): ?>
            <?php if (!empty($users)): ?>
                <table class="users-list-table">
                    <thead>
                        <tr>
                            <th>N° identifiant</th>
                            <th>Username</th>
                            <th>Adresse e-mail</th>
                            <th>Inscription fait le</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td><?= $user['id'] ?></td>
                                <td><?= $user['username'] ?></td>
                                <td><?= $user['email'] ?></td>
                                <td><?= date('d/m/Y', strtotime($user['created_at'])) ?></td>
                                <th>
                                    <a href="index.php?q=user-delete&user_id=<?= $user['id'] ?>" class="users-list-del">Supprimer</a>
                                </th>
                            </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>
                    Il n'y a pas eu d'inscriptions de faites pour le moment.
                    <a href="index.php?q=user-create" class="users-list-link">Créer un utilisateur</a>
                </p>
            <?php endif ?>
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