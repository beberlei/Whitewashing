<?php $view->extend('BlogBundle::layout') ?>

<h2>Search result for <?= $view->escape($term); ?></h2>

<p>Found <?= $result->resultCount; ?> results in <?= $result->queryTime; ?> seconds.</p>

<ol>
<?php foreach ($result->documents AS $resDoc): ?>
    <li><strong><a href="<?= $view->router->generate('blog_show_post', array('id' => $resDoc->document->getId())); ?>"><?= $resDoc->document->getHeadline(); ?></a></strong> (<?= number_format($resDoc->score * 100, 4); ?>)</li>
<?php endforeach; ?>
</ol>