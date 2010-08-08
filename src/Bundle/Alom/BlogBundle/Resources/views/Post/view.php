<?php $view->extend('PageBundle::layout') ?>
<?php $active = $view->get('slots')->get('menu.active', 'blog'); ?>
<?php $active = $view->get('slots')->get('head.title', $post->getTitle()); ?>

<div class="page-content">
    <h1>«&nbsp;<?php echo $post->getTitle(); ?>&nbsp;»</h1>

    <div class="blog-post-date">
        <?php echo $post->getPublishedAt()->format("F j, Y"); ?>
    </div>

    <div class="blog-post-history no-print">
        <a class="button previous" href="#">&lt; Previous</a>
        <a class="button next"     href="#">Next &gt;</a>
    </div>

    <div class="rich-content">
        <?php echo $post->getBody(); ?>
    </div>
</div>
