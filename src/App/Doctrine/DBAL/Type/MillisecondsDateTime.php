<?php

namespace App\Doctrine\DBAL\Type;

use DateTime;
use DateTimeInterface;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\Type;

class MillisecondsDateTime extends Type
{
    public const NAME = 'milliseconds_dt';
    public const FORMAT_TIME = 'Y-m-d H:i:s.v';

    /**
     * @param mixed[] $column
     */
    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return 'DATETIME(3)';
    }

    /**
     * @return string|void
     */
    public function getName()
    {
        return static::NAME;
    }

    /**
     * @param mixed $value
     *
     * @throws ConversionException
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        if (null === $value) {
            return $value;
        }

        if ($value instanceof DateTimeInterface) {
            return $value->format(static::FORMAT_TIME);
        }

        throw ConversionException::conversionFailedInvalidType($value, $this->getName(), ['null', 'DateTime']);
    }

    /**
     * @param mixed $value
     *
     * @throws ConversionException
     */
    public function convertToPHPValue($value, AbstractPlatform $platform): ?DateTimeInterface
    {
        if ($value === null || $value instanceof DateTimeInterface) {
            return $value;
        }

        $val = DateTime::createFromFormat(static::FORMAT_TIME, $value);

        if (!$val) {
            throw ConversionException::conversionFailedFormat($value, $this->getName(), static::FORMAT_TIME);
        }

        return $val;
    }
}
