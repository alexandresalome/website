<?php $view->extend('::layout.php') ?>
<?php $view->get('slots')->set('head.title', 'Alexandre Salomé - Web Developer'); ?>
<?php $view->get('slots')->set('menu.active', 'cv'); ?>

<div class="page-content">
    <h1>Alexandre Salomé - Web Developer</h1>
    <?php echo $view->render('IdentityBundle:Main:_cv_card.php'); ?>
</div>
