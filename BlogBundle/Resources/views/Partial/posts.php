<?php
/* @var $post Whitewashing\Blog\Post */
/* @var $view Symfony\Bundle\FrameworkBundle\Templating\Engine */
?>
<div class="post-container">
    <h2><a href="<?= $view->get('router')->generate('blog_show_post', array('id' => $post->getId())); ?>"><?= $post->getHeadline(); ?></a></h2>

    <div class="post">
        <?= $post->getFormattedText(); ?>
    </div>

    <div class="footer">
        <?= $post->getAuthor()->getName(); ?> on <?= $post->created()->format('F, d. Y'); ?>,
        <a href="<?= $view->get('router')->generate('blog_show_post', array('id' => $post->getId())); ?>#comments">Comments (<?= $post->getCommentCount(); ?>)</a>
    </div>
</div>