<?php $view->extend('::layout.php') ?>
<?php $view->get('slots')->set('head.title', 'Message sent !'); ?>
<?php $view->get('slots')->set('menu.active', 'contact'); ?>

<div class="page-content">
    <h1>Message sent !</h1>
</div>
