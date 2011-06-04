<?php

namespace Whitewashing\Tests;

use DoctrineExtensions\PHPUnit\OrmTestCase;

abstract class FunctionalTestCase extends \PHPUnit_Extensions_Database_TestCase
{
    /**
     * @var EntityManager
     */
    private $em;

    public function getConnection()
    {
        $this->em = $this->createEntityManager();
        $conn = $this->em->getConnection();
        $pdo = $conn->getWrappedConnection();

        $schemaTool = new \Doctrine\ORM\Tools\SchemaTool($this->em);
        $schemaTool->createSchema($this->em->getMetadataFactory()->getAllMetadata());

        return $this->createDefaultDBConnection($pdo, $conn->getDatabase());
    }

    /**
     * @return EntityManager
     */
    public function getEntityManager()
    {
        return $this->em;
    }

    /**
     * @return Doctrine\ORM\EntityManager
     */
    private function createEntityManager()
    {
        $cache = new \Doctrine\Common\Cache\ArrayCache();

        $xmlDriver = new \Doctrine\ORM\Mapping\Driver\XmlDriver(__DIR__ . "/../BlogBundle/Resources/config/doctrine");

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

        return \Doctrine\ORM\EntityManager::create($connectionOptions, $config);
    }

    public function fakeUser()
    {
        return \Whitewashing\Blog\User::create('beberlei', 'kontakt@beberlei.de', 'asdefg');
    }
}