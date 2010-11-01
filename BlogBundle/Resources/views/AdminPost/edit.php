<?php $view->extend('BlogBundle::adminLayout') ?>

<div class="span-6">
    <?= $view->adminNav->menu(); ?>
</div>

<div class="span-18 last">
    <?php echo $writeForm->renderFormTag($view->router->generate('blog_post_edit', array('id' => $post->getId()))) ?>
        <?php echo $writeForm->renderErrors() ?>
        <?php echo $writeForm->render() ?>

        <input type="submit" name="submit_thenedit" value="Save"
        <input type="submit" name="submit_thenshow" value="Save and Show" />
    </form>
</div>