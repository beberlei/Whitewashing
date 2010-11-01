<?php
/**
 * Whitewashing
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to kontakt@beberlei.de so I can send you a copy immediately.
 */

namespace Whitewashing\BlogBundle;

use Symfony\Framework\Bundle\Bundle;
use  Symfony\Component\DependencyInjection\ContainerInterface;
use Whitewashing\BlogBundle\DependencyInjection\WhitewashingExtension;
use  Symfony\Component\DependencyInjection\Loader\Loader;

class BlogBundle extends Bundle
{
    /**
     * Customizes the Container instance.
     *
     * @param  Symfony\Component\DependencyInjection\ContainerInterface $container A ContainerInterface instance
     *
     * @return  Symfony\Component\DependencyInjection\BuilderConfiguration A BuilderConfiguration instance
     */
    public function buildContainer(ContainerInterface $container)
    {
        Loader::registerExtension(new WhitewashingExtension());
    }

    /**
     * Boots the Bundle.
     */
    public function boot()
    {
        $xmlDriver = new \Doctrine\ORM\Mapping\Driver\XmlDriver(array(__DIR__."/Resources/config/metadata"));

        $md = $this->container->get('doctrine.orm.metadata_driver');
        $md->addDriver($xmlDriver, 'Whitewashing');

        $em = $this->container->get('doctrine.orm.default_entity_manager');
        $em->getRepository('Whitewashing\Blog\Blog')->setCurrentBlogId($this->container->getParameter('whitewashing.blog.default_id'));

        // Register Zend Autoloader
        require_once "Zend/Loader/Autoloader.php";
        \Zend_Loader_Autoloader::getInstance();
    }

    /**
     * Shutdowns the Bundle.
     */
    public function shutdown()
    {
    }
}