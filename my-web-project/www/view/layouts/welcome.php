<?php
// file: view/layouts/welcome.php

$view = ViewManager::getInstance();

require_once(__DIR__ . "/../../core/I18n.php");

?><!DOCTYPE html>
<html>
<head>
	<title><?= $view->getVariable("title", "no title") ?></title>
	<meta charset="utf-8">
	<link rel="stylesheet" href="css/style.css" type="text/css">
	<?= $view->getFragment("css") ?>
	<?= $view->getFragment("javascript") ?>
</head>
<body>
	<header>
		<div class="header-content">
            <h1 class="bienvenida">Bienvenido a IAmOn!</h1>
        </div>
	</header>
	<main>
		<!-- flash message -->
		<div id="flash">
			<?= $view->popFlash() ?>
		</div>

		<?= $view->getFragment(ViewManager::DEFAULT_FRAGMENT) ?>
	</main>
	<footer class="footer-content">
		<?php
		include(__DIR__."/language_select_element.php");
		?>
	</footer>
</body>
</html>