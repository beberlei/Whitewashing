<ul id="recentPosts" class="imagedList">
<?php foreach ($posts AS $post): ?>
    <li>
        <a href="<?= $view->get('router')->generate('blog_show_post', array('id' => $post->getId())); ?>">
           <img src="<?= $view->get('assets')->getUrl('themes/whitewashing-de/images/icons/website-32x32.png'); ?>" alt="" />
           <?= $post->created()->format('F, d. Y'); ?>, Comments (0)<br /><strong><?= $post->getHeadline(); ?></strong>
        </a>
    </li>
<?php endforeach; ?>
</ul>