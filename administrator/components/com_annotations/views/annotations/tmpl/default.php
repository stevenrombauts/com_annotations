<?php defined('KOOWA') or die('Restricted access') ?>

<?= @template('default_sidebar'); ?>

<form id="articles-form" action="" method="get" class="-koowa-grid">
    <?= @template('default_filter'); ?>
    <table class="adminlist">
        <thead>
            <tr>
                <th width="10"></th>
                <th>
                    <?= @helper('grid.sort', array('column' => 'selector')) ?>
                </th>
                <th width="7%">
                    <?= @helper('grid.sort', array('column' => 'position')) ?>
                </th>
                <th width="7%">
                    <?= @helper('grid.sort', array('title' => 'Order', 'column' => 'ordering')) ?>
                </th>
            </tr>
            <tr>
                <td align="center">
                    <?= @helper('grid.checkall') ?>
                </td>
                <td>
                    <?= @helper('grid.search') ?>
                </td>
                <td></td>
                <td></td>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <td colspan="8">
                    <?= @helper('paginator.pagination', array('total' => $total)) ?>
                </td>
            </tr>
        </tfoot>
        <tbody>
        <? foreach($annotations as $annotation) : ?>
            <tr>
                <td align="center">
                    <?= @helper('grid.checkbox' , array('row' => $annotation)) ?>
                </td>
                <td>
                	<a href="<?= @route('view=annotation&id='.$annotation->id) ?>">
                    	<?= @escape($annotation->selector); ?>
                    </a>
                </td>
                <td align="center">
                    <?= $annotation->position ?>
                </td>
                <td align="center">
                    <?= @helper('grid.order', array('row' => $annotation, 'total' => $total)) ?>
                </td>
            </tr>
        <? endforeach ?>
        </tbody>
    </table>
</form>