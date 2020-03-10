<?php declare(strict_types=1);

namespace App\Framework;

use App\Types\MoneyType;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

class Database
{
    const ENTITY_PATH = __DIR__ . '/../Entity';

    /** @var string */
    private $databaseUrl;

    /** @var string */
    private $driver;

    /** @var EntityManagerInterface */
    private $em;

    public function __construct(string $databaseUrl, string $driver)
    {
        $this->databaseUrl = $databaseUrl;
        $this->driver = $driver;
    }

    public function getEntityManager(): EntityManagerInterface
    {
        if (null === $this->em) {
            $paths = [
                static::ENTITY_PATH,
            ];

            $connectionParams = [
                'url' => $this->databaseUrl,
                'driver' => $this->driver,
            ];

            $conn = DriverManager::getConnection($connectionParams);

            $config = Setup::createAnnotationMetadataConfiguration($paths, false, null, null, false);

            $this->em = EntityManager::create($conn, $config);

            if (!Type::hasType('money')) {
                Type::addType('money', MoneyType::class);
                $this->em->getConnection()->getDatabasePlatform()->registerDoctrineTypeMapping('money', 'money');
            }
        }

        return $this->em;
    }

    /**
     * @param string $className
     *
     * @return mixed
     */
    public function getRepository(string $className)
    {
        return $this->getEntityManager()->getRepository($className);
    }
}
