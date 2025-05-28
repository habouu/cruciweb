<?php
$title = "Liste des grilles";
include 'views/partials/_header.php';
?>

<main class="main container">
    <section class="grids-list section">
        <h2>Liste des grilles</h2>

        <?php include 'views/partials/_message.php'; ?>

        <div class="sort-options">
            <a href="index.php?q=grid-sort-level" class="sort-link">Trier par niveau</a>
            <a href="index.php?q=grid-sort-date" class="sort-link">Trier par date</a>
        </div>

        <?php if (!empty($grids)): ?>
            <table class="grids-list-table">
                <thead>
                    <tr>
                        <th>N° identifiant</th>
                        <th>Nom</th>
                        <th>Difficulté</th>
                        <th>Posté le</th>
                        <th>Proposé par</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($grids as $grid): ?>
                        <tr>
                            <td><?= $grid['id'] ?></td>
                            <td><?= $grid['name'] ?></td>
                            <td>
                            <?php if ($grid['level'] === 'easy'): ?>
                                Débutant
                            <?php elseif ($grid['level'] === 'medium'): ?>
                                Intermédiaire
                            <?php else: ?>
                                Expert
                            <?php endif ?>
                            </td>
                            <td><?= date('d-m-Y', strtotime($grid['created_at'])) ?></td>
                            <td><?= $grid['username'] ?></td>
                            <?php if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin'): ?>
                                <th>
                                    <a href="index.php?q=grid-delete&grid_id=<?= $grid['id'] ?>" class="grids-list-del">Supprimer</a>
                                </th>
                            <?php else: ?>
                                <th>
                                    <a href="index.php?q=grid-show&grid_id=<?= $grid['id'] ?>" class="grids-list-show">Voir</a>
                                </th>
                            <?php endif ?>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>
                Il n'y a pas de grille pour le moment.
            </p>
            <?php if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'registered'): ?>
                <a href="index.php?q=grid-create" class="grids-list-link">Créer une grille</a>
            <?php endif ?>
        <?php endif ?>
    </section>
</main>


<?php include 'views/partials/_footer.php'; ?> 