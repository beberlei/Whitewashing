*****************
Whitewashing Blog
*****************

Whitewashing is my blog and a playground for symfony2 application development.

Add the following submodule to your symfony2 project:::

    git submodule add git://github.com/beberlei/Whitewashing.git src/Bundle/Whitewashing
    git submodule update --init

Add the following to your app configuration.::

    whitewashing.blog:
      default_id:   1

And the following information to your `routing.yml`:::

    blog:
        resource: Whitewashing/BlogBundle/Resources/config/routing.yml

Whitewashing Bundle requires the following bundles:

* DoctrineORMBundle
* ZetaBundle
* ZendBundle