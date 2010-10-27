<?php $formattedDate = $post->getPublishedAt()->format("F j, Y"); ?>

<?php $view->extend('::layout.php') ?>
<?php $view->get('slots')->set('menu.active', 'blog'); ?>
<?php $view->get('slots')->set('head.title', $post->getTitle() . ' - ' . $formattedDate); ?>

<div class="page-content">
    <h1>«&nbsp;<?php echo $post->getTitle(); ?>&nbsp;»</h1>

    <div class="blog-post-date">
        <?php echo $formattedDate; ?>
    </div>

    <div class="blog-post-history no-print">
        <a class="button previous" href="#">&lt; Previous</a>
        <a class="button next"     href="#">Next &gt;</a>
    </div>

    <div class="rich-content">
        <?php echo $post->getBody(); ?>
    </div>
</div>
