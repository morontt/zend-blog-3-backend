<?php
/**
 * User: morontt
 * Date: 07.05.2025
 * Time: 19:52
 */

namespace App\Validator\Constraints;

use DateTime;
use Exception;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class DateTimeStringValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof DateTimeString) {
            throw new UnexpectedTypeException($constraint, DateTimeString::class);
        }

        if (empty($value)) {
            return;
        }

        if (!is_scalar($value) && !(is_object($value) && method_exists($value, '__toString'))) {
            throw new UnexpectedValueException($value, 'string');
        }

        $value = (string)$value;
        try {
            new DateTime($value);
        } catch (Exception $e) {
            $this->context->addViolation($constraint->message);
        }
    }
}
