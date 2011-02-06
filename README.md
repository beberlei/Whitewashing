# Whitewashing Blog

Whitewashing is my blog and a playground for symfony2 application development.

## Required Bundles

* FOS UserBundle
* Zeta Bundle

## Configuration

Add the following submodule to your symfony2 project:

    git submodule add git://github.com/beberlei/Whitewashing.git src/Whitewashing
    git submodule update --init

Add Whitewashing to your app/autoload.php

    $loader = new UniversalClassLoader();
    $loader->registerNamespaces(array(
        // ..
        'Whitewashing'                   => $srcDir,
    ));

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

## TODOs

* Rewrite Controllers to use Dependency Injection container
* Add Blog/Article entity
* Add functional tests for controllers