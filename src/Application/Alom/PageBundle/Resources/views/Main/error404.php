<?php $view->extend('PageBundle::layout') ?>
<?php $view->get('slots')->set('head.title', 'Page not found'); ?>

<div class="page-error-404 page-content">
    <h1>404 - This page was not found</h1>
    <div class="button-set">
        <a class="button" href="<?php echo $view->get('router')->generate('page_main_homepage'); ?>">« Back to homepage</a>
    </div>
</div>
