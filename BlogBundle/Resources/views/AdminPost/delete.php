<?php $view->extend('BlogBundle::adminLayout') ?>

<div class="span-6">
    <?= $view->adminNav->menu(); ?>
</div>

<div class="span-18 last">
    <h1>Post deleted!</h1>

    <p>The post with headline "<strong><?= $post->getHeadline(); ?></strong>" was deleted.</p>
</div>