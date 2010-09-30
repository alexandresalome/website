<?php $title  = $view->get('slots')->get('head.title',  null); ?>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <?php if (null !== $title): ?>
            <title><?php echo $title; ?></title>
        <?php endif ?>
        <link rel="stylesheet" href="<?php echo $view->get('assets')->getUrl('css/main.css'); ?>" />
    </head>
    <body>
        <?php $view->output('::banner.php'); ?>
        <div id="wrapper">

            <div id="header" class="no-print">
                <?php $view->output('::header.php'); ?>
            </div>

            <div id="content">
                <?php $view->get('slots')->output('_content') ?>
            </div>

            <div id="footer" class="no-print">
                <?php $view->output('::footer.php'); ?>
            </div>

        </div>
    </body>
</html>
