<?php

declare(strict_types=1);

/**
 * User: morontt
 * Date: 07.04.2026
 * Time: 18:28
 */

namespace App\DTO;

use DateTime;
use DateTimeInterface;
use Symfony\Component\Serializer\Attribute\Context;
use Symfony\Component\Serializer\Attribute\SerializedName;
use Symfony\Component\Serializer\Attribute\SerializedPath;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;

class SecureCookieDTO
{
    #[SerializedPath('[a][i]')]
    public ?int $userId = null;

    #[SerializedPath('[a][u]')]
    public ?string $userName = null;

    #[SerializedPath('[a][r]')]
    public ?string $userRole = null;

    #[SerializedName('d')]
    #[Context([DateTimeNormalizer::FORMAT_KEY => DateTimeInterface::RFC3339])]
    public ?DateTime $deadLine = null;
}
