# Whitewashing Blog

Whitewashing is my blog and a playground for symfony2 application development.

## Configuration

Add the following submodule to your symfony2 project:

    git submodule add git://github.com/beberlei/Whitewashing.git src/Bundle/Whitewashing
    git submodule update --init

Because i am not very fond of the Bundle prefix requirement I have written the whole bundle in the `Whitewashing`
main namespace. That means you have to make certain additional configurations to your application kernel.

Add the following to the `AppKernel#registerBundleDirs()` method:

    'Whitewashing'       => __DIR__.'/../src/Bundle/Whitewashing',

Register the bundle in your `AppKernel#registerBundles` method:

    new Whitewashing\BlogBundle\BlogBundle(),

Add the following to your app configuration.

    whitewashing.blog:
      default_id:   1
      host_url: http://www.whitewashing.de

You also have to configure the security details to be able to write posts and such:

    security.config:
        providers:
            blog:
                password_encoder: sha1
                entity: { class: Whitewashing\Core\User, property: name }
        firewalls:
            public:
                pattern: /.*
                form-login: true
                anonymous: true
            blog-backend:
                pattern:  /blog/admin.*
                form-login:
                  check_path: /blog/login_check
                  login_path: /blog/login
                  provider:   blog

And the following information to your `routing.yml`:

    blog:
        resource: Whitewashing/BlogBundle/Resources/config/routing.yml

You can then create all the required database tables by using the symfony console doctrine commands
and access the Schema-Tool.

Whitewashing Bundle requires the following bundles:

* DoctrineORMBundle
* ZetaBundle
* ZendBundle

## TODOs

* Rewrite Controllers to use Dependency Injection container
* Pimp the backend
* Add Blog/Article entity
* Add functional tests for controllers