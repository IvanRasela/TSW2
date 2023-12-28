<?php
//file: view/posts/add.php
require_once(__DIR__."/../../core/ViewManager.php");
$view = ViewManager::getInstance();

$switch = $view->getVariable("switchs");
$errors = $view->getVariable("errors");

$view->setVariable("title", "Edit Post");

?><h1>("Create switch")?</h1>
<form action="index.php?controller=Switchs&amp;action=add" method="POST">
    ("Nombre"): <input type="text" name="SwitchName" value="<?= $switch->getSwitchsName() ?>">
    <?= (isset($errors["title"])) ? ($errors["title"]) : '' ?><br>

    ("Descripción")<input type="text" name="Description" value="<?= $switch->getDescriptionswitchs() ?>">
    <?= (isset($errors["title"])) ? ($errors["title"]) : '' ?><br>

    <input type="submit" name="submit" value="submit">
</form>