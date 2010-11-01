<?php $view->extend('BlogBundle::layout') ?>

<?= $view->render('BlogBundle:Partial:posts', array('post' => $post)); ?>

<div class="references">
    <div class="span-10">
        <?php
            $url = urlencode($view->get('router')->generate('blog_show_post', array('id' => $post->getId())));
            $title = urlencode($post->getHeadline());
        ?>

        <h4>Share This Post</h4>

        <ul class="socialBookmarks">
            <li><a href="http://twitter.com/home?status=<?= $title; ?>+<?= $url; ?>" target="_blank"><img src="<?= $view->get('assets')->getUrl('themes/whitewashing-de/images/icons/twitter-16x16.png'); ?>" width="16" height="16" border="0" hspace="0" alt="Share on Twitter" title="Share on Twitter" /></a></li>
            <li><a href="http://de.facebook.com/sharer.php?u=<?= $url; ?>&amp;t=<?= $title; ?>" target="_blank"><img src="<?= $view->get('assets')->getUrl('themes/whitewashing-de/images/icons/facebook-16x16.png'); ?>" alt="Facebook" title="Share on Facebook" align="left" border="0" height="16" hspace="0" width="16"></a></li>
            <li><a href="http://del.icio.us/post?url=<?= $url; ?>&amp;title=<?= $title; ?>" target="_blank"><img src="<?= $view->get('assets')->getUrl('themes/whitewashing-de/images/icons/delicious-16x16.png'); ?>" alt="Share on deli.cio.us" title="Share on deli.cio.us" align="left" border="0" height="16" hspace="0" width="16"></a></li>
            <li><a href="http://digg.com/submit?phase=2&url=<?= $url; ?>&amp;title=<?= $title; ?>" target="_blank"><img src="<?= $view->get('assets')->getUrl('themes/whitewashing-de/images/icons/digg-16x16.png'); ?>" width="16" height="16" border="0" hspace="0" alt="Share on Digg" title="Share on Digg" align="left" /></a></li>
            <li><a href="http://reddit.com/submit?url=<?= $url; ?>&amp;title=<?= $title; ?>" target="_blank"><img src="<?= $view->get('assets')->getUrl('themes/whitewashing-de/images/icons/reddit-16x16.png'); ?>" width="16" height="16" border="0" hspace="0" alt="Share on reddit" title="Share on reddit" align="left" /></a></li>
            <li><a href="http://www.stumbleupon.com/submit?url=<?= $url; ?>&amp;title=<?= $title; ?>" target="_blank"><img src="<?= $view->get('assets')->getUrl('themes/whitewashing-de/images/icons/stumbleupon-16x16.png'); ?>" width="16" height="16" border="0" hspace="0" alt="Share on StumbleUpon" title="Share on StumbleUpon" align="left" /></a></li>
        </ul>
    </div>

    <div class="span-5">
        <h4>Tags</h4>

        <?php if (count($post->getTags()) > 0): ?>
        <ul>
        <?php foreach ($post->getTags() AS $tag): ?>
            <li><a href="<?= $view->get('router')->generate('blog_show_tag', array('tagName' => $tag->getSlug())); ?>"><?= $tag->getName(); ?></a></li>
        <?php endforeach; ?>
        </ul>
        <?php else: ?>
        <p>This post is untagged.</p>
        <?php endif; ?>
    </div>
</div>

<h4>Comments</h4>

<?php if (count($comments)): ?>
<?php foreach ($comments AS $comment): ?>
<a name="comment-<?= $comment->getId(); ?>"></a>
<div class="comment">
    <div class="author span-3">
        <img src="http://www.gravatar.com/avatar/<?= md5(strtolower(trim($comment->getUserEmail()))); ?>.jpg" alt="" /><br />
        <strong><?= $view->escape($comment->getUsername()); ?></strong><br />
        <?= $view->escape($comment->created()->format('M, d. Y')); ?>
    </div>

    <div class="text span-12 last">
        <p><?= nl2br($view->escape($comment->getText())); ?></p>
    </div><br clear="all">
</div>
<?php endforeach; ?>

<?php else: ?>
    <p>No comments have been written yet.</p>
<?php endif; ?>

<h4>Write a Comment</h4>

<?php /* @var $form Form */ ?>
<?php echo $form->renderFormTag($view->get('router')->generate('blog_write_comment', array('postId' => $post->getId()))); ?>
  <?php echo $form->renderHiddenFields(); ?>

  <?php echo $form->renderErrors() ?>

<div class="span-5">
    <label for="username">Username</label>
</div>
<div class="span-10 last">
    <?= $form->get('comment')->get('username')->render(); ?>
</div>

<div class="span-5">
    <label for="userEmail">Username</label>
</div>
<div class="span-10 last">
    <?= $form->get('comment')->get('userEmail')->render(); ?><br />
</div>

<div class="span-5">
    <label for="text">Comment</label>
</div>
<div class="span-10 last">
    <?= $form->get('comment')->get('text')->render(); ?><br />
</div>

<div class="span-5">
    <label for="riddleResult">Riddle me this: <?= $writeComment->getRiddle(); ?></label>
</div>
<div class="span-10 last">
    <?= $form->get('riddleResult')->render(); ?>
</div>

  <input type="submit" value="Send!" />
</form>