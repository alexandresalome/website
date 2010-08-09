<?php $title  = $view->get('slots')->get('head.title',  null); ?>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <?php if (null !== $title): ?>
            <title><?php echo $title; ?></title>
        <?php endif ?>
        <?php echo $view->get('stylesheets')->add('css/main.css'); ?>
        <?php echo $view->get('stylesheets')->render(); ?>
    </head>
    <body>
        <div id="wrapper">

            <div id="header" class="no-print">
              <?php $view->output('PageBundle::header'); ?>
            </div>

            <div id="content">
                <?php $view->slots->output('_content') ?>
            </div>

            <div id="footer" class="no-print">
                <p><?php echo date('Y'); ?> - Powered by <a href="http://symfony-reloaded.org">Symfony2</a>, <a href="http://www.doctrine-project.org">Doctrine2</a> and coffee. Sourcecode available on <a href="#">Github</a>.</p>
            </div>

        </div>
    </body>
</html>
