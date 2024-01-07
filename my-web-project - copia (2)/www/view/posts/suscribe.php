
<?php
//file: view/users/login.php

require_once(__DIR__."/../../core/ViewManager.php");
$view = ViewManager::getInstance();
$view->setVariable("title", "Login");
$errors = $view->getVariable("errors");
?>


<h1>IAmOn</h1>
            <p>Suscribete a un Switch</p>
        <?= isset($errors["general"])?$errors["general"]:"" ?>
            <form action="index.php?controller=switchs&amp;action=suscribe" method="POST">
                <label for="Public_UUID">Public_UUID:</label>
                <input type="text" id="Public_UUID" name="Public_UUID" required>

                <input type="submit" value="<?= "Buscar" ?>">
            </form>

            <?php $view->moveToFragment("css");?>
            <link rel="stylesheet" type="text/css" src="css/style2.css">
            <?php $view->moveToDefaultFragment(); ?>