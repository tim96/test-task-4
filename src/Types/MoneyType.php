<?php declare(strict_types=1);

namespace App\Types;

use Doctrine\DBAL\Types\BigIntType;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Money\Currency;
use Money\Money;

class MoneyType extends BigIntType
{
    const NAME = 'money';

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return (null === $value) ? null : new Money(intval($value), new Currency('RUB'));
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if ($value instanceof Money) {
            $value = $value->getAmount();
        }

        return (int) parent::convertToDatabaseValue($value, $platform);
    }

    public function getName()
    {
        return self::NAME;
    }

    public function getBindingType()
    {
        return \PDO::PARAM_INT;
    }
}
