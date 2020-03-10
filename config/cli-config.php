<?php declare(strict_types=1);

use App\Framework\Database;
use Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper;
use Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper;
use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Dotenv\Dotenv;

require_once __DIR__ . "/../vendor/autoload.php";

chdir(__DIR__);

$dotenv = new Dotenv();
$dotenv->loadEnv(__DIR__.'/../.env');

$connectionUrl = $_ENV['DATABASE_URL'];
$driver = $_ENV['DATABASE_DRIVER'];
$em = (new Database($connectionUrl, $driver))->getEntityManager();

return new HelperSet([
    'em' => new EntityManagerHelper($em),
    'db' => new ConnectionHelper($em->getConnection()),
]);
