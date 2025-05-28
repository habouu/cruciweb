<?php
$title = "Détail d'une grille";
include 'views/partials/_header.php';
?>

<main class="main">
    <section class="grid-show section">

        - <a href="index.php?q=grids">Retour à la liste des grilles</a><br><br>


        <?php include 'views/partials/_message.php'; ?>

        <h2><?= $grid['name'] ?></h2>

        <p>
            <strong>Dimension:</strong> <?= $grid['nb_row'] ?>x<?= $grid['nb_col'] ?><br>
            <strong>Difficulté:</strong>
                <?php if ($grid['level'] === 'easy'): ?>
                    Débutant
                <?php elseif ($grid['level'] === 'medium'): ?>
                    Intermédiaire
                <?php else: ?>
                    Expert
                <?php endif ?>
        </p>

        <div class="grid-show-container">
            <form action="index.php?q=grid-save" method="POST">
                <table class="grid-show-container-table">
                    <thead>
                        <tr>
                            <!-- le coin sup droit -->
                            <th></th>
                            <?php for ($col = 1; $col <= $grid['nb_col']; $col++): ?>
                                <th>
                                    <?= chr($col + 64) ?>
                                </th>
                            <?php endfor ?>
                        </tr>
                    </thead>
                    <tbody>
                    <?php for ($row = 1; $row <= $grid['nb_row']; $row++): ?>
                        <tr>
                            <th>
                                <?= $row ?>
                            </th>
                            <?php for ($col = 1; $col <= $grid['nb_col']; $col++):
                                $head = chr($col + 64);
                                // cherche dans le tableau des cases noires des cellules dont les coordonnées rowhead y sont
                                $isBlack = in_array("$row$head", array_column($grid['blackcells'], 'cells'));
                            ?>
                            <td style="background-color: <?= $isBlack ? 'black' : 'white' ?>;">
                                <?php if (!$isBlack): ?>
                                    <input type="text" name="cells[<?= $row ?>][<?= $head ?>]" maxlength="1">
                                <?php endif ?>
                            </td>
                            <?php endfor ?>
                        </tr>
                    <?php endfor ?>
                    </tbody>
                </table>
                <input type="hidden" name="grid_id" value="<?= isset($_GET['grid_id']) ? $_GET['grid_id'] : '' ?>">
                <?php if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'registered'): ?>
                    <button type="submit" class="grid-show-save">Sauvegarder</button>
                <?php endif ?>
            </form>
        </div>

        <div class="grid-show-definitions">
            <h4>HORIZONTAL</h4>
            <ul>
                <?php
                // affiche à chaque fois deux fois les définitions /!\ à revoir
                $groupByHorizontal = [];
                foreach ($grid['definitions'] as $defHorizontal) {
                    if ($defHorizontal['direction'] === 'horizontal') {
                        $groupByHorizontal[$defHorizontal['start_num_row']][] = $defHorizontal['content'];
                        
                    }
                }
                foreach ($groupByHorizontal as $row => $content) {
                    echo '<li>' . $row . ': ' . implode(' - ', $content);
                }
                ?>
            </ul>

            <h4>VERTICAL</h4>
            <ul>
                <?php
                // affiche à chaque fois deux fois les définitions /!\ à revoir
                $groupByVertical = [];
                foreach ($grid['definitions'] as $defVertical) {
                    if ($defVertical['direction'] === 'vertical') {
                        $groupByVertical[$defVertical['start_num_col']][] = $defVertical['content'];
                        
                    }
                }
                foreach ($groupByVertical as $col => $content) {
                    echo '<li>' . $col . ': ' . implode(' - ', $content);
                }
                ?>
            </ul>
        </div>

    </section>
</main>

<?php include 'views/partials/_footer.php'; ?>