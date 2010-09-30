<?php $active = $view->get('slots')->get('menu.active', null); ?>
<h1><a href="/dev.php/">Alexandre Salomé</a></h1>
<ul>
    <?php $nodes = array(
        'blog_post_list'        => 'Blog',
        'identity_main_cv'      => 'CV',
        'identity_main_contact' => 'Contact'
    ); ?>
    <?php foreach ($nodes as $route => $title) {

        echo '<li';

        if ($active == strtolower($title)) {
            echo ' class="active"';
        }

        echo '><a href="'.$view->get('router')->generate($route).'">'.$title.'</a></li>';
    } ?>
</ul>
