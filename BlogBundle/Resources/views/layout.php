<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru">
    <head>
        <title>Whitewashing.de :: </title>

        <meta http-equiv="Content-Type" content="text/html;charset=utf-8"/>

        <link rel="stylesheet" href="<?= $view->get('assets')->getUrl('themes/whitewashing-de/blueprint/screen.css'); ?>" type="text/css" media="screen, projection" />
        <link rel="stylesheet" href="<?= $view->get('assets')->getUrl('themes/whitewashing-de/blueprint/print.css'); ?>" type="text/css" media="print" />
        <!--[if lt IE 8]><link rel="stylesheet" href="<?= $view->get('assets')->getUrl('themes/whitewashing-de/blueprint/ie.css'); ?>" type="text/css" media="screen, projection" /><![endif]-->

        <link rel="stylesheet" href="<?= $view->get('assets')->getUrl('themes/whitewashing-de/css/whitewashing.css'); ?>" type="text/css" media="screen, projection" />
        <link rel="stylesheet" href="<?= $view->get('assets')->getUrl('themes/whitewashing-de/css/markup.css'); ?>" type="text/css" media="screen, projection" />
    </head>

    <body>
        <div class="container content">

            <div class="span-16">

                <?php $view->get('slots')->output('_content') ?>
            </div>

            <div id="menu" class="span-8 last">
                <a href="<?= $view->get('router')->generate('blog'); ?>"><img class="logo" src="<?= $view->get('assets')->getUrl('themes/whitewashing-de/images/logo.jpg'); ?>" alt="Whitewashing.de" /></a>

                <h3>About Whitewashing.de</h3>

                <p>Whitewashing is the blog of Benjamin Eberlei and covers topics in computer science, databases
                and web-development and other topics of interest for the author. You can read about me at my
                personal page or write a mail to kontakt at beberlei dot de.</p>

                <h3>Topics</h3>

                <?= $view->get('posts')->renderCloud(); ?>

                <h3>Search</h3>

                <form method="get" action="<?= $view->get('router')->generate('blog_post_search'); ?>">
                    <input type="text" size="30" name="q" value="" />
                    <input type="submit" value="Go" />
                </form>

                <p class="buttons">
                    <a href="http://www.twitter.com/beberlei"><img src="http://twitter-badges.s3.amazonaws.com/follow_me-a.png" alt="Follow beberlei on Twitter"/></a>

                    <a href="<?= $view->get('router')->generate('blog_feed'); ?>"<img src="<?= $view->get('assets')->getUrl('themes/whitewashing-de/images/icons/feed-32x32.png'); ?>" alt="" /></a>
                </p>

            </div>
        </div>

        <div class="pagefooter">
            <div class="container">

                <div class="span-8">
                    <h4>Recent Posts</h4>

                    <?= $view->get('posts')->renderRecent(); ?>
                </div>

                <div class="span-8">
                    <h4>Recent Comments</h4>

                    <?= $view->get('comments')->renderRecent(); ?>
                </div>

                <div class="span-8 last">
                    <h4>Share and RSS</h4>

                    <ul class="imagedList">
                        <li>
                            <a href="mailto:kontakt@beberlei.de"><img src="<?= $view->get('assets')->getUrl('themes/whitewashing-de/images/icons/email-16x16.png'); ?>" alt="" />Write me an E-Mail</a>
                        </li>
                        <li>
                            <a href="<?= $view->get('router')->generate('blog_feed'); ?>"><img src="<?= $view->get('assets')->getUrl('themes/whitewashing-de/images/icons/feed-16x16.png'); ?>" alt="" />Subscribe to RSS Feed</a>
                        </li>
                        <li>
                            <a href="http://www.twitter.com/beberlei"><img src="<?= $view->get('assets')->getUrl('themes/whitewashing-de/images/icons/twitter-16x16.png'); ?>" alt="" />Follow me on Twitter</a>
                        </li>
                        <li>
                            <a href="http://www.slideshare.net/beberlei"><img src="<?= $view->get('assets')->getUrl('themes/whitewashing-de/images/icons/slideshare-16x16.png'); ?>" alt="" />View Conference Slides</a>
                        </li>
                    </ul>
                </div>

                <div class="span-24 copyright">
                    <small>Whitewashing.de is published by Benjamin Eberlei. All contents are copyright &copy;2007-<?= date('Y'); ?> by their respective authors.</small>
                </div>
            </div>
        </div>
    </body>

</html>