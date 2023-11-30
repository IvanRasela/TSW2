<?php
//file: view/posts/view.php
require_once(__DIR__."/../../core/ViewManager.php");
$view = ViewManager::getInstance();

$switch = $view->getVariable("switch");
$switchPrivate = $view->getVariable("switchPrivate");
$currentuser = $view->getVariable("currentusername");
$errors = $view->getVariable("errors");

$view->setVariable("title", "View Switch");

?><h1>Mis Switches</h1>
<table border="1">
    <tr>
        <th>Switch Name</th>
		<th>Public_UUID</th>
        <th>Alias User</th>
		<th>Descripcion</th>
        <th>LastTimePowerOn</th>
		<th>MaxTimePowerOn</th>
        <th>Acciones</th>
		<th>Estado</th>
    </tr>

    <!-- Iteración sobre los switches -->
	<tr>
		<td>
		<?= $switch->getSwitchsName() ?>
		</td>
		<td>
			<?= $switch->getPublic_UUID() ?>
		</td>
		<td>
			<?= $switch->getAliasUser()->getAlias() ?>
		</td>
		<td>
			<?= $switch->getDescriptionswitchs() ?>
		</td>
		<td>
			<?= $switch->getLastTimePowerOn() ?>
		</td>
		<td>
			<?= $switch->getMaxTimePowerOn() ?>
		</td>
		
		<td>
			<?php if (isset($currentuser) && $currentuser == $switch->getAliasUser()->getAlias()): ?>
				<!-- 'Delete Button' -->
				<form method="POST" action="index.php?controller=switchs&amp;action=delete" id="delete_switchs_<?= $switch->getPrivate_UUID(); ?>" style="display: inline">
					<input type="hidden" name="Private_UUID" value="<?= $switch->getPrivate_UUID() ?>">
					<a href="#" onclick="if (confirm('are you sure?')) { document.getElementById('delete_switchs_<?= $switch->getPrivate_UUID() ?>').submit() }">Delete</a>
				</form>
				&nbsp;
				<form method="POST" action="index.php?controller=switchs&amp;action=changeState" id="onoff_switchs_<?= $switch->getPrivate_UUID(); ?>" style="display: inline">
					<input type="hidden" name="Private_UUID" value="<?= $switch->getPrivate_UUID() ?>">
					<a href="#" onclick="if (confirm('are you sure?')) { document.getElementById('onoff_switchs_<?= $switch->getPrivate_UUID() ?>').submit() }">On/Off</a>
				</form>
			<?php endif; ?>

			<?php if (isset($currentuser) && $currentuser != $switch->getAliasUser()->getAlias()): ?>
				<!-- 'Subscribe Button' -->
				<form method="POST" action="index.php?controller=switchs&amp;action=desuscribe" id="desuscribe_switch_<?= $switch->getPublic_UUID(); ?>" style="display: inline">
					<input type="hidden" name="Public_UUID" value="<?= $switch->getPublic_UUID() ?>">
					<a href="#" onclick="if (confirm('are you sure?')) { document.getElementById('desuscribe_switch_<?= $switch->getPublic_UUID() ?>').submit() }">Desuscribe</a>
				</form>
			<?php endif; ?>

			<?php if ($switchPrivate != NULL): ?>
				<!-- 'Delete Button' -->
				<form method="POST" action="index.php?controller=switchs&amp;action=OnOff" id="onoff_switchs_<?= $switchPrivate->getPrivate_UUID(); ?>" style="display: inline">
					<input type="hidden" name="Private_UUID" value="<?= $switchPrivate->getPrivate_UUID() ?>">
					<a href="#" onclick="if (confirm('are you sure?')) { document.getElementById('onoff_switchs_<?= $switchPrivate->getPrivate_UUID() ?>').submit() }">On/Off</a>
				</form>
				&nbsp;
			<?php endif; ?>

		</td>
		<td>
			<?php echo(($switch->getLastTimePowerOn() < $switch->getMaxTimePowerOn()) ? "on" : "off") ?>
		</td>
	</tr>
</table>

