<?php $view->extend('BlogBundle::adminLayout') ?>

<div class="span-24 last">
    <h1>Login</h1>

    <?php if ($error): ?>
    <font color="red">Fehler beim Login!</font>
    <?php endif; ?>

    <form method="post" action="<?= $view->router->generate('blog_admin_login'); ?>">
        <input type="text" name="username" size="20" />
        <input type="password" name="password" size="20" />
        <input type="submit" value="Login" />
    </form>
</div>