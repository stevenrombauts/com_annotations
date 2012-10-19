<?php defined('KOOWA') or die( 'Restricted access' ); ?>

<div id="sidebar">
	<? foreach(@service('com://admin/annotations.model.annotations')->group('tbl.package')->sort('package')->getList() as $package) : ?>
	<h3><?= @text($package->package) ?></h3>
		
		<ul>
		<? foreach(@service('com://admin/annotations.model.annotations')->package($package->package)->group('tbl.identifier')->sort('identifier')->getList() as $row) : ?>
			<? $identifier = new KServiceIdentifier($row->identifier) ?>
			<li <? if($state->identifier == $row->identifier) echo 'class="active"' ?>>
			    <a href="<?= @route('identifier='.$row->identifier) ?>">
					<?= implode('.', array_merge($identifier->path, array($identifier->name))) ?>
			    </a>
			</li>
		<? endforeach; ?>
		</ul>
	<? endforeach ?>
</div>