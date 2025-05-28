<?php if (!empty($_SESSION['errors'])): ?>
    <div class="alert-error">
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach ?>
        </ul>
    </div>
    <?php unset($_SESSION['errors']) ?>
<?php endif ?>