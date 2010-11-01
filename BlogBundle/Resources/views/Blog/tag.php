<?php $view->extend('BlogBundle::layout') ?>

<h1>Posts by Tag "<?= $tag->getName(); ?>"</h1>

<p><a href="<?= $view->get('router')->generate('blog_feed_tags', array('tagName' => $tag->getSlug())); ?>">
    <img src="<?= $view->get('assets')->getUrl('themes/whitewashing-de/images/icons/feed-16x16.png'); ?>" alt="" />
    Subscribe to posts of this tag</a></p>

<?php foreach($posts AS $post): ?>

<?= $view->render('BlogBundle:Partial:posts', array('post' => $post)); ?>

<?php endforeach; ?>
