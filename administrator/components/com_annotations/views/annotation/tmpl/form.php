<?php defined('KOOWA') or die( 'Restricted access' ); ?>

<?= @helper('behavior.validator') ?>

<script src="media://lib_koowa/js/koowa.js" />
<style src="media://lib_koowa/css/koowa.css" />

<form action="" method="post" class="-koowa-form" id="category-form">
    <div class="grid_8">
        <div class="panel title group">
        	<input class="inputbox required" type="text" name="title" id="title" size="40" maxlength="255" value="<?= $annotation->title ?>" placeholder="<?= @text('Title') ?>" />
            <?= @text('This element\'s CSS selector is'); ?> 
            <input type="text" name="selector" placeholder="<?= @text('Selector') ?>" value="<?= $annotation->selector ?>" size="125" maxlength="255" />
        </div>
        <?= @editor(array(
            'name'    => 'text',
            'editor'  => null,
            'width'   => '100%',
            'height'  => '300',
            'cols'    => '60',
            'rows'    => '20',
            'buttons' => true,
            'options' => array('theme' => 'simple')
        ));
        ?>
    </div>
    <div id="panels" class="grid_4">
        <div class="panel">
            <h3><?= @text('Options') ?></h3>
            <table class="paramlist admintable">
                <tr>
                    <td class="paramlist_key">
                        <label><?= @text('Position') ?></label>
                    </td>
                    <td>
                        <?= @helper('listbox.positions', array('selected' => $annotation->position)) ?>
                    </td>
                </tr>
                <tr>
                    <td class="paramlist_key">
                        <label><?= @text('Identifier') ?></label>
                    </td>
                    <td>
                        <input class="inputbox required" type="text" name="identifier" id="identifier" size="60" maxlength="255" value="<?= $annotation->identifier ?>" placeholder="<?= @text('Identifier') ?>" />
                    </td>
                </tr>
            </table>
        </div>
	</div>
</form>