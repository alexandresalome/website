<?php $view->extend('PageBundle::layout') ?>
<?php $view->get('slots')->set('head.title', 'Contact form'); ?>
<?php $view->get('slots')->set('menu.active', 'contact'); ?>

<div class="page-content">
    <h1>Contact form</h1>

    <?php echo $view->render('ContactBundle:Contact:edit-form'); ?>
</div>
