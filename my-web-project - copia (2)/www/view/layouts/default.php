<?php
//file: view/layouts/default.php

$view = ViewManager::getInstance();
$currentuser = $view->getVariable("currentusername");

?><!DOCTYPE html>
<html>
<head>
	<title><?= $view->getVariable("title", "no title") ?></title>
	<meta charset="utf-8">
	<link rel="stylesheet" href="css/style.css" type="text/css">
	<!-- enable ji18n() javascript function to translate inside your scripts -->
	<!--<script src="index.php?controller=language&amp;action=i18njs">-->
	</script>
	<?= $view->getFragment("css") ?>
	<?= $view->getFragment("javascript") ?>
</head>
<body>
	<!-- header -->
	<header>
		<div class="header-content-default">
		<h1>DashBoard</h1>
		
		<nav id="menu">
			<ul class="lista-default">

				<?php if (isset($currentuser)): ?>
					<li><?= sprintf($currentuser) ?>
						<a 	class="logout" href="index.php?controller=users&amp;action=logout">Cerrar sesión</a>
					</li>

				<?php else: ?>
					<li><a href="index.php?controller=users&amp;action=login">Login</a></li>
				<?php endif ?>
			</ul>
		</nav>
		</div>
	</header>

	<main>
		<div id="flash">
			<?= $view->popFlash() ?>
		</div>

		<?= $view->getFragment(ViewManager::DEFAULT_FRAGMENT) ?>
	</main>

	<footer>
		<?php
		include(__DIR__."/language_select_element.php");
		?>
	</footer>

</body>
</html>