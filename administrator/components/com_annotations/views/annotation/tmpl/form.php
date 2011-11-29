<?php defined('KOOWA') or die( 'Restricted access' ); ?>

<?= @helper('behavior.validator') ?>

<script src="media://lib_koowa/js/koowa.js" />
<style src="media://lib_koowa/css/koowa.css" />

<form action="" method="post" class="-koowa-form" id="category-form">
    <div class="grid_8">
        <div class="panel title group">
            <input class="inputbox required" type="text" name="selector" id=""selector"" size="40" maxlength="255" value="<?= $annotation->selector ?>" placeholder="<?= @text('Selector') ?>" />
        </div>
        <?= @editor(array(
            'name'    => 'text',
            'editor'  => null,
            'width'   => '100%',
            'height'  => '300',
            'cols'    => '60',
            'rows'    => '20',
            'buttons' => true,
            'options' => array('theme' => 'simple', 'pagebreak', 'readmore')
        ));
        ?>
    </div>
</form>