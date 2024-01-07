
<?php
//file: view/users/register.php

require_once(__DIR__."/../../core/ViewManager.php");
$view = ViewManager::getInstance();
$errors = $view->getVariable("errors");
$user = $view->getVariable("usuario");
$view->setVariable("title", "Register");
?>


<div class="registerContainer">
        <h1 class="registro">Registro en IAmOn</h1>
        <form action="index.php?controller=users&amp;action=register" method="POST">
            <div class="form-group-register">
                <label for="alias" class="aliasNameRegistro">Alias:</label>
                <input type="text" id="alias" name="alias" value="<?= $user->getAlias() ?>">
                <p class="error"><?= isset($errors["alias"]) ? $errors["alias"] : "" ?></p>
            </div>

            <div class="form-group-register">
                <label for="passwd">Contraseña:</label>
                <input type="password" id="passwd" name="passwd" value="">
                <p class="error"><?= isset($errors["passwd"]) ? $errors["passwd"] : "" ?></p>
            </div>

            <div class="form-group-register">
                <label for="email">Correo (Si deseas recibir notificaciones):</label>
                <input type="email" id="email" name="email" value="">
                <p class="error"><?= isset($errors["email"]) ? $errors["email"] : "" ?></p>
            </div>
            <div class="submit-register">
            <input type="submit" value="Registrarse">
            </div>
            
        </form>

        <p class="cuenta-existente">¿Ya tienes una cuenta? <a href="index.php?controller=users&amp;action=login">Inicia sesión aquí.</a></p>
    </div>

