<?php defined('KOOWA') or die( 'Restricted access' ); ?>

<div id="sidebar">
	<h3><?= @text('Pages')?></h3>
	
	<ul>
	<? foreach(array_unique(@service('com://admin/annotations.model.annotations')->getList()->getColumn('page')) as $page) : ?>
	<li <? if($state->page == $page) echo 'class="active"' ?>>
	    <a href="<?= @route('page='.urlencode($page)) ?>">
			<?= str_replace(JURI::root(), '', $page); ?>
	    </a>
	</li>
	<? endforeach ?>
	</ul>
</div>