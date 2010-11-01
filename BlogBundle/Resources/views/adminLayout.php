<html>
    <head>
        <title>Whitewashing.de - Backend</title>

        <meta http-equiv="Content-Type" content="text/html;charset=utf-8"/>

        <link rel="stylesheet" href="<?= $view->assets->getUrl('themes/whitewashing-de/blueprint/screen.css'); ?>" type="text/css" media="screen, projection" />
        <link rel="stylesheet" href="<?= $view->assets->getUrl('themes/whitewashing-de/blueprint/print.css'); ?>" type="text/css" media="print" />
        <!--[if lt IE 8]><link rel="stylesheet" href="<?= $view->assets->getUrl('themes/whitewashing-de/blueprint/ie.css'); ?>" type="text/css" media="screen, projection" /><![endif]-->
    </head>
    
    <body>
        <div class="container content">
            <?php $view->slots->output('_content') ?>
        </div>
    </body>

</html>