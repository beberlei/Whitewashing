<?php $view->extend('BlogBundle::layout') ?>

<?php foreach($posts AS $post): ?>

<?= $view->render('BlogBundle:Partial:posts', array('post' => $post)); ?>

<?php endforeach; ?>
