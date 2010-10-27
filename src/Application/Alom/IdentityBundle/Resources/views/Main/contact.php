<?php $view->extend('::layout.php') ?>
<?php $view->get('slots')->set('head.title', 'Contact'); ?>
<?php $view->get('slots')->set('menu.active', 'contact'); ?>

<div class="page-content">

    <h1>Contact</h1>

    <div class="rich-content">
        <h2>Email is good</h2>
        <p>
            Send me e-mails on <a href="mailto:alexandre.salome@gmail.com">alexandre.salome@gmail.com</a>.
        </p>
        <p>
            If you have questions about symfony, go to <a href="#">symfony-users</a> groups. I'm present on it.
        </p>

        <h2>Contact form</h2>

        <p>
            <a href="<?php echo $view->get('router')->generate('ContactBundle_Message_Edit') ?>">Go to</a>
    </div>

</div>
