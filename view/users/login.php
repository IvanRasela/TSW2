
<?php
//file: view/users/login.php

require_once(__DIR__."/../../core/ViewManager.php");
$view = ViewManager::getInstance();
$view->setVariable("title", "Login");
$errors = $view->getVariable("errors");
?>

<div class="login-container">
    <h1>IAmOn</h1>
    <p>Inicia sesión en tu cuenta</p>
    <?= isset($errors["general"])?$errors["general"]:"" ?>
    <form action="index.php?controller=users&amp;action=login" method="POST">
    <div class="form-group-login">
        <label for="alias">Usuario:</label>
        <input type="text" id="alias" name="alias" required>
    </div>
    <div class="form-group-login">
        <label for="passwd">Contraseña:</label>
        <input type="password" id="passwd" name="passwd" required>
    </div>
        <div class="submit-login">
        <input type="submit" value="<?= "Login" ?>">
        </div>
    </form>
    <p class="accesos-enlace"><a href="index.php?controller=switchs&amp;action=index">Acceder como usuario invitado</a></p>
    <p class="accesos-enlace"><a href="index.php?controller=users&amp;action=register">Registrate</a></p>
</div>
<?php $view->moveToFragment("css");?>
<link rel="stylesheet" type="text/css" src="css/style2.css">
<?php $view->moveToDefaultFragment(); ?>