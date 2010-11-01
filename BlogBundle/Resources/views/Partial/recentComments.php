<ul id="recentPosts" class="imagedList">
<?php foreach($comments AS $comment): ?>
    <?php $post = $comment->getPost(); ?>
    <li>
        <a href="<?= $view->get('router')->generate('blog_show_post', array('id' => $post->getId())); ?>#comment-<?= $comment->getId(); ?>">
            <img src="http://www.gravatar.com/avatar/<?= md5(strtolower(trim($comment->getUserEmail()))); ?>.jpg?s=32" alt="" />
            <?= $comment->created()->format('F, d. Y'); ?>, Comments (0)<br /><strong>Re: <?= $post->getHeadline(); ?></strong>
        </a>
    </li>
<?php endforeach; ?>
</ul>