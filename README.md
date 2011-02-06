# Whitewashing Blog

Whitewashing is my blog and a playground for symfony2 application development.

## Required Bundles

* DoctrineBundle (Symfony Core)
* SecurityBundle (Symfony Core)
* Zend Framework 1 (for Zend_Feed_Writer) - Can't use the ZF2 dependency of Symfony here, Feed doesnt work there.

## Optional Bundles

* FOS UserBundle - Integrates well and an user entity is provided if you only want to use the blog bundle and don't have an application bundle.

## Configuration

Add the following submodule to your symfony2 project:

    git submodule add git://github.com/beberlei/Whitewashing.git src/Whitewashing
    git submodule update --init

Add Whitewashing and ZF1 to your app/autoload.php

    $loader = new UniversalClassLoader();
    $loader->registerNamespaces(array(
        // ..
        'Whitewashing'                   => $srcDir,
    ));

    require_once 'Zend/Loader/Autoloader.php';
    Zend_Loader_Autoloader::getInstance();

Register the bundle in your `AppKernel#registerBundles` method:

    new Whitewashing\BlogBundle\WhitewashingBlogBundle(),

Load the whitewashing extension in your app configuration (app/config/config.yml).

    whitewashing.blog:
      default_blog_id:   1
      host_url: http://www.whitewashing.de

Configure doctrine.dbal and doctrine.orm if you haven't done so:

    doctrine.dbal: ~
    doctrine.orm: ~

Add the mapping directory:

    doctrine.orm:
      mappings:
        WhitewashingBlogBundle:
          dir: Resources/config/metadata
          type: xml
          prefix: Whitewashing\Blog

If you already have a firewall and provider defined you only need to restrict the access
to the admin area:

    security.config:
        # ...
        access_control:
            - { path: /blog/admin.*, role: ROLE_USER }

If you haven't done so you also have to configure the security details to secure
the admin area:

    security.config:
        providers:
            fos_user:
                id: fos_user.user_manager
        firewalls:
            public:
                pattern: /.*
                form-login:
                  check_path: /login-check
                  login_path: /login
                  provider:   fos_user
                anonymous: true
        access_control:
            - { path: /blog/admin.*, role: ROLE_USER }

If you don't have an application besides this blog you can also use the security user shipped.
The blog is independent from the FOS UserBundle though, you can integrate it with any security provider.

    fos_user.config:
        db_driver: orm
        class:
            model:
                user: Whitewashing\BlogBundle\Security\User

And the following information to your `routing.yml`:

    blog:
        resource: "@WhitewashingBlogBundle/Resources/config/routing.yml"

You can then create all the required database tables by using the symfony console doctrine commands
and access the Schema-Tool.

## Theming

Whitewashing Blog Bundle defaults to using "::layout.html.twig" as layout. There is an example
layout in Resources/views/layout_example.html.twig that shows all the current widgets and elements
that you can use and render.

## TODOs

* Rewrite Controllers to use Dependency Injection container
* Add Blog/Article entity
* Add functional tests for controllers