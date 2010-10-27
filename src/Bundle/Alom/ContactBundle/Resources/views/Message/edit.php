<?php
/**
 * @todo Clean this hack
 */
$form = $form->getRawValue();
?>

<?php $view->extend('::layout.php') ?>

<?php $view->get('slots')->set('head.title', 'Contact form'); ?>
<?php $view->get('slots')->set('menu.active', 'contact'); ?>

<div class="page-content">
    <h1>Contact form</h1>
    <?php echo $form->renderFormTag($view->get('router')->generate('ContactBundle_Message_Save')); ?>
        <?php $view->output('ContactBundle:Message:_form.php', array('form' => $form)); ?>
    <?php echo '</form>'; ?>
</div>
