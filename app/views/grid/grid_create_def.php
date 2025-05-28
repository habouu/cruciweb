<?php
$title = "Les définitions";
include 'views/partials/_header.php';
?>

<main class="main">
    <section class="grid-create-def section">
        <h2>Les définifions</h2>

        <?php include 'views/partials/_message.php'; ?>

        <?php if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'registered'): ?>
            <form action="index.php?q=grid-create-def" method="POST" autocomplete="off" class="grid-create-def-form">
                <div class="def-group">
                    <label for="start_num_row">Commence à la ligne</label>
                    <input type="number" name="definitions[0][start_num_row]" id="start_num_row" min="1" required><br>

                    <label for="start_num_col">Commence à la colonne</label>
                    <input type="number" name="definitions[0][start_num_col]" id="start_num_col" min="1" required><br>

                    <label for="end_num_row">Se termine à la ligne</label>
                    <input type="number" name="definitions[0][end_num_row]" id="end_num_row" min="1" required><br>

                    <label for="end_num_col">Se termine à la colonne</label>
                    <input type="number" name="definitions[0][end_num_col]" id="end_num_col" min="1" required><br>

                    <div class="def-group-radio">
                        <input type="radio" id="horizontal-0" name="definitions[0][direction]" value="horizontal" required>
                        <label for="horizontal-0">Horizontal</label>
                        <input type="radio" id="vertical-0" name="definitions[0][direction]" value="vertical" required>
                        <label for="vertical-0">Vertical</label>
                    </div>

                    <label for="content">Définition</label>
                    <input type="text" name="definitions[0][content]" id="content" maxlength="255" required><br>
                </div>
                <button type="button" id="add-new-def-btn">Ajouter une nouvelle définition</button>
                <button type="submit" class="grid-create-def-btn">Soumettre toutes les définitions</button>
                <input type="hidden" name="grid_id" value="<?= isset($_GET['grid_id']) ? $_GET['grid_id'] : '' ?>">
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

<script>
    // on prend le bouton d'ajout grace à son ID pour lui mettre un événement de click
    document.getElementById('add-new-def-btn').addEventListener('click', function() {
        // prendre le formulaire entier grace à sa classe
        const formDef = document.querySelector('.grid-create-def-form');

        // prendre le dernier élement qui a la classe def-group avec last-of-type
        const lastDefGrp = formDef.querySelector('.def-group:last-of-type');

        // si y pas le dernier groupe on quitte
        if (!lastDefGrp) return;

        // le dernier groupe est cloner en gardant tout sur le formulaire
        const newGrp = lastDefGrp.cloneNode(true);

        // compte le nb de groupe qui exise déjà
        const index = formDef.querySelectorAll('.def-group').length;
        
        // avec la boucle on parcourt tous les champs du clone pour mettre le bon indice
        newGrp.querySelectorAll('input').forEach(input => {
            if (input.name) {
                input.name = input.name.replace(/\[\d+\]/, `[${index}]`);
            }
            if (input.id) {
                input.id = input.id.replace(/\d+/, index);
            }

            // mettre à vide les champs (/!\ ne fonctionne pas garde les inputs d'avant)
            if (input.type === 'input' || input.type === 'number') {
                input.value = '';
            } else if (input.type === 'radio') {
                input.checked = false;
            }
        });
        // pour éviter qu'on est deux btn radio en plus
        const btnRadioGrp = newGrp.querySelector('.def-group-radio');
        if (btnRadioGrp) {
            btnRadioGrp.querySelectorAll('label[for]').forEach(label => {
                const newIndexBtn = label.getAttribute('for').replace(/\d+/, index);
                label.setAttribute('for', newIndexBtn);
            });
        }
        

        // les btn radios sont ajoutés au nouveau groupe
        //newGrp.appendChild(radioBtn);
        
        // on fait un esapce de 10px entre l'ancien et le nouveau formulaire
        const spacer = document.createElement('div');
        spacer.style.marginBottom = '10px';

        // le nouveau formulaire sera placé avant les boutons d'ajout et de soumission
        const addButton = document.getElementById('add-new-def-btn');
        formDef.insertBefore(spacer, addButton);
        formDef.insertBefore(newGrp, addButton);
    });
</script>

<?php include 'views/partials/_footer.php'; ?> 