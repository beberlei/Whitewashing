<ul>
    <li><a href="<?= $view->router->generate('blog'); ?>">Go to Blog</a></li>
    <li><a href="<?= $view->router->generate('blog_admin_dashboard'); ?>">Dashboard</a></li>
    <li><a href="<?= $view->router->generate('blog_post_admin'); ?>">Posts</a>
        <ul>
            <li><a href="<?= $view->router->generate('blog_post_new'); ?>">Write Post</a></li>
        </ul>
    </li>
</ul>