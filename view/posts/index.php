<?php
//file: view/switches/index.php

require_once(__DIR__."/../../core/ViewManager.php");
$view = ViewManager::getInstance();

$switches = $view->getVariable("Switchs");
$switchesSuscritos = $view->getVariable("SwitchsSuscritos");
$currentuser = $view->getVariable("currentusername");


$view->setVariable("title", "Switchs");

$fechaHoraActual = new DateTime(); // Obtiene la fecha y hora actual
$dateFormat = "d-m-Y";
$hourFormat = "H:i";
$cont = 0;

?>
&nbsp;
<a href="index.php?controller=switchs&amp;action=find">Buscar switch</a>

<h1>Mis Switches</h1>
<div class="mis-switchs">
<table border="1">
    <tr>
        <th>Switch Name</th>
        <th>Alias User</th>
        <th>Public_UUID</th>
        <th>Acciones</th>
    </tr>

    <!-- Iteración sobre los switches -->
    <?php if($switches != NULL )
            foreach ($switches as $switchs): ?>
        <tr>
            <td>
                <a href="index.php?controller=switchs&amp;action=view&amp;Public_UUID=<?= $switchs->getPublic_UUID() ?>"><?= htmlentities($switchs->getSwitchsName()) ?></a>
            </td>
            <td>
                <?= $switchs->getAliasUser()->getAlias() ?>
            </td>
            <td>
                <?= $switchs->getPublic_UUID() ?>
            </td>
            <td>
                <?php if (isset($currentuser) && $currentuser == $switchs->getAliasUser()->getAlias()): ?>
                    <!-- 'Delete Button' -->
                    <form method="POST" action="index.php?controller=switchs&amp;action=delete" id="delete_switchs_<?= $switchs->getPrivate_UUID(); ?>" style="display: inline">
                        <input type="hidden" name="Private_UUID" value="<?= $switchs->getPrivate_UUID() ?>">
                        <a href="#" onclick="if (confirm('are you sure?')) { document.getElementById('delete_switchs_<?= $switchs->getPrivate_UUID() ?>').submit() }">Delete</a>
                    </form>
                    &nbsp;
                <?php endif; ?>

                <?php if (isset($currentuser) && $currentuser != $switchs->getAliasUser()->getAlias()): ?>
                    <!-- 'Subscribe Button' -->
                    <form method="POST" action="index.php?controller=switchs&amp;action=suscribe" id="suscribe_switch_<?= $switchs->getPublic_UUID(); ?>" style="display: inline">
                        <input type="hidden" name="Public_UUID" value="<?= $switchs->getPublic_UUID() ?>">
                        <a href="#" onclick="if (confirm('are you sure?')) { document.getElementById('suscribe_switch_<?= $switchs->getPublic_UUID() ?>').submit() }">Suscribe</a>
                    </form>
                <?php endif; ?>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
</div>


<?php if (isset($currentuser)): ?>
	<a href="index.php?controller=switchs&amp;action=add">Create switch</a>
<?php endif; ?>


<!-- HTML para la segunda tabla -->
<h2>Switches Suscritos</h2>
<div class="switchs-suscritos">
<table border="1">
    <tr>
        <th>Switch Name</th>
        <th>Alias User</th>
        <th>Public_UUID</th>
        <th>Acciones</th>
    </tr>

    <!-- Iteración sobre los switches suscritos -->
    <?php if($switchesSuscritos != NULL )
            foreach ($switchesSuscritos as $switches): ?>
        <tr>
            <!-- ... (repetir la estructura para la segunda tabla) ... -->
			<td>
            <a href="index.php?controller=switchs&amp;action=view&amp;Public_UUID=<?= $switches->getPublic_UUID() ?>"><?= htmlentities($switches->getSwitchsName()) ?></a>
			</td>
			<td>
				<?= $switches->getAliasUser()->getAlias() ?>
			</td>
            <td>
				<?= $switches->getPublic_UUID()?>
			</td>
			<td>
				<?php

				//if (isset($currentuser) && $currentuser == $switches->getAliasUser()->getAlias()):?>
					<?php
					// 'Delete Button': show it as a link, but do POST in order to preserve
					// the good semantic of HTTP
					?>
					<form
					method="POST"
					action="index.php?controller=switchs&amp;action=desuscribe"
					id="desuscribe_switchs_<?= $switches->getPublic_UUID(); ?>"
					style="display: inline"
					>

					<input type="hidden" name="Public_UUID" value="<?= $switches->getPublic_UUID() ?>">

					<a href="#" 
					onclick="
					if (confirm('are you sure?')) {
						document.getElementById('desuscribe_switchs_<?= $switches->getPublic_UUID() ?>').submit()
					}"
					>Desuscribe</a>

			</form>

			&nbsp;

			<?php //endif; ?>

			<?php?>
	</td>
        </tr>
    <?php endforeach; ?>
</table>
                </div>
