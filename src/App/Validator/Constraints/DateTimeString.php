<?php
/**
 * User: morontt
 * Date: 07.05.2025
 * Time: 19:50
 */

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

class DateTimeString extends Constraint
{
    public string $message = 'This value is not a valid time.';
}
