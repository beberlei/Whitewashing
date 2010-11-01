<?php $view->extend('BlogBundle::adminLayout') ?>

<div class="span-6">
    <?= $view->adminNav->menu(); ?>
</div>

<div class="span-18 last">
    <h1>Confirm Delete</h1>

    <p>You are about to delete the post "<strong><?= $post->getHeadline(); ?></strong>".
        Are you sure?</p>

    <form method="post" action="<?= $view->router->generate('blog_post_delete', array('id' => $post->getId())); ?>">
        <input type="submit" name="confirmDelete" value="Yes, delete this post" />
    </form>

</div>