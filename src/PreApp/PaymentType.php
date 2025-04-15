<?php

declare(strict_types=1);

namespace BnplPartners\Factoring004\PreApp;

use MyCLabs\Enum\Enum;

/**
 * @method static static BNPL_004()
 * @method static static PAD()
 *
 * @psalm-immutable
 */
final class PaymentType extends Enum
{
    private const BNPL_004 = '0-0-4';
    private const PAD = 'PAD';

    public static function default(): self
    {
        return self::BNPL_004();
    }
}