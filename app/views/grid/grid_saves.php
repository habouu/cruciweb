<?php
$title = "Mes sauvegardes";
include 'views/partials/_header.php';
?>

<main class="main">
    <section class="grid-save-user section">
        <h2>Mes sauvegardes</h2>

        <?php include 'views/partials/_message.php'; ?>

        <?php if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'registered'): ?>
            <?php if (!empty($saves)): ?>
                <table class="saves-list-table">
                    <thead>
                        <tr>
                            <th>N° identifiant</th>
                            <th>Nom</th>
                            <th>Difficulté</th>
                            <th>Sauvegardé le</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($saves as $save): ?>
                            <tr>
                                <td><?= $save['id'] ?></td>
                                <td><?= $save['name'] ?></td>
                                <td>
                                <?php if ($save['level'] === 'easy'): ?>
                                    Débutant
                                <?php elseif ($save['level'] === 'medium'): ?>
                                    Intermédiaire
                                <?php else: ?>
                                    Expert
                                <?php endif ?>
                                </td>
                                <td><?= date('d-m-Y', strtotime($save['created_at'])) ?></td>
                                <th>
                                    <a href="index.php?q=grid-show&grid_id=<?= $save['grid_id'] ?>" class="save-list-show">Voir</a>
                                    <a href="index.php?q=grid-save-del&grid_id=<?= $save['grid_id'] ?>" class="save-list-del">Supprimer</a>
                                </th>
                            </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>Aucune sauvegarde n'a été effectuée. <a href="index.php">Retournez à la liste des grilles</a></p>
            <?php endif ?>
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