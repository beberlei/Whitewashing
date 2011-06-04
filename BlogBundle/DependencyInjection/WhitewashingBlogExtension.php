<?php
/*
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

namespace Whitewashing\BlogBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\Config\FileLocator;

class WhitewashingBlogExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $configuration)
    {
        foreach ($configs AS $config) {
            $this->doLoadBlog($config, $configuration);
        }
    }

    public function doLoadBlog($config, ContainerBuilder $configuration)
    {
        $loader = new XmlFileLoader($configuration, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');

        $params = array('default_blog_id', 'host_url', 'disqus_shortname');
        foreach ($params AS $param) {
            if (isset($config[$param])) {
                $configuration->setParameter('whitewashing.blog.'. $param, $config[$param]);
            }
        }
        
        return $configuration;
    }
}