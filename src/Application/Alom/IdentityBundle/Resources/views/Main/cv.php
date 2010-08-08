<?php $view->extend('PageBundle::layout') ?>
<?php $view->get('slots')->set('head.title', 'Alexandre Salomé - Web Developer'); ?>
<?php $view->get('slots')->set('menu.active', 'cv'); ?>

<div class="page-content">
    <h1>Alexandre Salomé - Web Developer</h1>
    <?php echo $view->render('IdentityBundle:Main:cv-card'); ?>
</div>
