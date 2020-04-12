<?php

namespace Mtt\BlogBundle\Doctrine\DBAL\Type;

use DateTime;
use DateTimeInterface;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\Type;

class MillisecondsDateTime extends Type
{
    const NAME = 'milliseconds_dt';
    const FORMAT_TIME = 'Y-m-d H:i:s.v';

    /**
     * @param array $fieldDeclaration
     * @param AbstractPlatform $platform
     *
     * @return string
     */
    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
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
     * @param AbstractPlatform $platform
     *
     * @throws ConversionException
     *
     * @return mixed|string
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
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
     * @param AbstractPlatform $platform
     *
     * @throws ConversionException
     *
     * @return DateTime
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
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
