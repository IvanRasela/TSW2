
<?php
//file: view/users/login.php

require_once(__DIR__."/../../core/ViewManager.php");
$view = ViewManager::getInstance();
$switch = $view->getVariable("switch");
$currentuser = $view->getVariable("currentusername");
$view->setVariable("title", "Switchs");

?>


<h1>IAmOn</h1>
            <p>Busca un Switch</p>
            <form action="index.php?controller=Switchs&amp;action=find" method="GET">
                <label for="uuid">Introduce la UUID:</label>
                <input type="text" id="uuid" name="uuid" required>

                <input type="submit" value="<?= "Buscar" ?>">
            </form>

            <?php $view->moveToFragment("css");?>
            <link rel="stylesheet" type="text/css" src="css/style2.css">
            <?php $view->moveToDefaultFragment(); ?>