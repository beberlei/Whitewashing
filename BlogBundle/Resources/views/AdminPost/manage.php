<?php $view->extend('BlogBundle::adminLayout') ?>

<div class="span-6">
    <?= $view->adminNav->menu(); ?>
</div>

<div class="span-18 last">
    <h1>Posts</h1>

    <table>
        <tr>
            <th>Headline</th>
            <th>Author</th>
            <th>Categories</th>
            <th>Tags</th>
            <th>Date</th>
            <th>#</th>
        </tr>
<?php foreach ($posts AS $post): ?>
        <tr>
            <td><?= $post->getHeadline(); ?></td>
            <td><?= $post->getAuthor()->getName(); ?></td>
            <td>
                <?php foreach ($post->getCategories() AS $category): ?>
                    <?= $category->getName(); ?>
                <?php endforeach; ?>
            </td>
            <td><?= implode(", ", $post->getTagNames()); ?></td>
            <td><?= $post->created()->format('d.m.Y, H:i'); ?></td>
            <th>
                <a href="<?= $view->router->generate("blog_post_edit", array("id" => $post->getId())); ?>">Edit</a>
                <a href="<?= $view->router->generate("blog_post_delete", array("id" => $post->getId())); ?>">Delete</a>
            </th>
        </tr>
<?php endforeach; ?>
    </table>
</div>