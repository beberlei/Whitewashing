<?php

namespace Whitewashing\Tests;

use DoctrineExtensions\PHPUnit\OrmTestCase;

abstract class FunctionalTestCase extends OrmTestCase
{
    public function createEntityManager()
    {
        $cache = new \Doctrine\Common\Cache\ArrayCache();

        $xmlDriver = new \Doctrine\ORM\Mapping\Driver\XmlDriver(__DIR__ . "/../BlogBundle/Resources/config/metadata");

        $config = new \Doctrine\ORM\Configuration();
        $config->setAutoGenerateProxyClasses(true);
        $config->setProxyDir(\sys_get_temp_dir());
        $config->setProxyNamespace('Whitewashing\Proxies');
        $config->setMetadataCacheImpl($cache);
        $config->setMetadataDriverImpl($xmlDriver);
        $config->setQueryCacheImpl($cache);

        $connectionOptions = array(
            'driver' => 'pdo_sqlite',
            'memory' => true,
        );

        $em = \Doctrine\ORM\EntityManager::create($connectionOptions, $config);
        $schemaTool = new \Doctrine\ORM\Tools\SchemaTool($em);

        $cmf = $em->getMetadataFactory();
        $classes = $cmf->getAllMetadata();

        $schemaTool->dropSchema($classes);
        $schemaTool->createSchema($classes);

        return $em;
    }

    public function fakeUser()
    {
        return \Whitewashing\Core\User::create('beberlei', 'kontakt@beberlei.de', 'asdefg');
    }
}